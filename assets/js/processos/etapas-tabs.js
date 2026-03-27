/**
 * Navegação por etapas do processo: URL ?tab=, loaders por aba, init sob demanda.
 */
(function ($) {
	'use strict';

	var inits = {};
	var initialized = {};
	var running = {};

	function showPaneLoader($pane, show) {
		var $loader = $pane.find('.etapa-tab-loader');
		if (show) {
			$loader.addClass('is-visible').attr('aria-busy', 'true');
		} else {
			$loader.removeClass('is-visible').attr('aria-busy', 'false');
		}
	}

	function runInitOnce(tabNum, $pane) {
		if (!$pane || !$pane.length) {
			return;
		}
		if (initialized[tabNum]) {
			showPaneLoader($pane, false);
			return;
		}
		if (running[tabNum]) {
			return;
		}
		running[tabNum] = true;
		showPaneLoader($pane, true);
		var fn = inits[String(tabNum)];
		var finish = function () {
			showPaneLoader($pane, false);
			initialized[tabNum] = true;
			running[tabNum] = false;
		};
		if (typeof fn === 'function') {
			try {
				var ret = fn($pane);
				if (ret && typeof ret.then === 'function') {
					ret.then(finish, finish);
					return;
				}
			} catch (err) {
				console.error('EtapasTabs init:', err);
				finish();
				return;
			}
		}
		finish();
	}

	window.EtapasTabs = {
		registerInit: function (etapaId, fn) {
			inits[String(etapaId)] = fn;
		},

		getCurrentTabNum: function () {
			var p = new URLSearchParams(window.location.search);
			var t = p.get('tab');
			if (t) {
				return String(t);
			}
			var $a = $('#etapasTabs a.nav-link.active:not(.disabled)');
			if ($a.length) {
				return String($a.data('tab-num') || '');
			}
			var ss = sessionStorage.getItem('processoActiveTab');
			return ss || '1';
		},

		/** Recarrega a página mantendo a aba atual na URL. */
		reloadPreservingTab: function () {
			var tab = this.getCurrentTabNum();
			sessionStorage.setItem('processoActiveTab', tab);
			var url = window.location.pathname + '?tab=' + encodeURIComponent(tab);
			window.location.href = url;
		}
	};

	$(function () {
		var $tabs = $('#etapasTabs');
		if (!$tabs.length) {
			return;
		}

		function syncUrl(tabNum) {
			sessionStorage.setItem('processoActiveTab', tabNum);
			var u = new URL(window.location.href);
			u.searchParams.set('tab', tabNum);
			window.history.pushState({ tab: tabNum }, '', u.pathname + u.search);
		}

		$tabs.on('click', 'a[data-toggle="tab"]', function (e) {
			if ($(this).hasClass('disabled')) {
				e.preventDefault();
				e.stopImmediatePropagation();
				if (typeof Swal !== 'undefined') {
					Swal.fire('Etapa Bloqueada', 'Aguardando concluir etapa anterior', 'warning');
				}
				return false;
			}
		});

		// Ao sair de uma etapa, fecha modais abertas (evita backdrop/conteúdo da etapa anterior sobreposto).
		$tabs.on('hide.bs.tab', 'a[data-toggle="tab"]', function () {
			$('.modal.show').modal('hide');
		});

		// Usar $(this) e não $(e.target): cliques nos spans internos do <a> deixam target sem href/data-tab-num.
		$tabs.on('shown.bs.tab', 'a[data-toggle="tab"]', function () {
			var $target = $(this);
			if ($target.hasClass('disabled')) {
				return;
			}
			var tabNum = String($target.data('tab-num'));
			var href = $target.attr('href');
			if (!href) {
				return;
			}
			var $pane = $(href);
			syncUrl(tabNum);
			runInitOnce(tabNum, $pane);

			setTimeout(function () {
				if (!$('.modal.show').length && $('.modal-backdrop').length) {
					$('.modal-backdrop').remove();
					$('body').removeClass('modal-open').css('padding-right', '');
				}
			}, 400);
		});

		window.addEventListener('popstate', function () {
			var tab = new URLSearchParams(window.location.search).get('tab');
			if (!tab) {
				tab = sessionStorage.getItem('processoActiveTab') || '1';
			}
			var $link = $tabs.find('a[data-tab-num="' + tab + '"]').not('.disabled').first();
			if ($link.length) {
				$link.tab('show');
			}
		});

		// Abre a aba da URL (ou primeira liberada)
		var tabParam = new URLSearchParams(window.location.search).get('tab');
		if (!tabParam) {
			tabParam = sessionStorage.getItem('processoActiveTab');
		}
		var $linkToShow = null;
		if (tabParam) {
			$linkToShow = $tabs.find('a[data-tab-num="' + tabParam + '"]').not('.disabled').first();
		}
		if (!$linkToShow || !$linkToShow.length) {
			$linkToShow = $tabs.find('a[data-toggle="tab"]').not('.disabled').first();
		}
		// Não chamar tab('show') se a aba alvo já está ativa — no BS4 isso pode quebrar .fade/.show na 1ª etapa.
		if ($linkToShow && $linkToShow.length) {
			var hrefOpen = $linkToShow.attr('href') || '';
			var $paneTarget = hrefOpen ? $(hrefOpen) : $();
			var alreadyActive =
				$linkToShow.hasClass('active') &&
				$paneTarget.length &&
				$paneTarget.hasClass('active') &&
				$paneTarget.hasClass('show');
			if (!alreadyActive) {
				$linkToShow.tab('show');
			} else {
				syncUrl(String($linkToShow.data('tab-num')));
			}
		}

		// Se a aba já estava ativa e o Bootstrap não disparou shown (ex.: primeiro paint)
		setTimeout(function () {
			var $pane = $('.tab-content .tab-pane.active');
			if (!$pane.length) {
				return;
			}
			var id = $pane.attr('id') || '';
			var m = id.match(/^etapa_(\d+)$/);
			if (!m) {
				return;
			}
			var n = m[1];
			if (!initialized[n]) {
				runInitOnce(n, $pane);
			}
		}, 0);

		/**
		 * BS4 coloca o backdrop no document.body, mas o .modal continua dentro do painel (#content).
		 * Isso separa modal e backdrop em contextos de empilhamento diferentes e costuma gerar
		 * fundo estranho / aparência "desfocada" no conteúdo. Reanexa o .modal ao body só enquanto
		 * estiver aberto e devolve ao placeholder ao fechar.
		 */
		$(document).on('show.bs.modal', '#content .modal', function () {
			var $m = $(this);
			if ($m.data('etapasReparented')) {
				return;
			}
			$m.data('etapasModalParent', $m.parent());
			$m.appendTo(document.body);
			$m.data('etapasReparented', true);
		});

		$(document).on('hidden.bs.modal', '.modal', function () {
			var $m = $(this);
			if (!$m.data('etapasReparented')) {
				return;
			}
			var $p = $m.data('etapasModalParent');
			if ($p && $p.length) {
				$m.appendTo($p);
			}
			$m.removeData('etapasModalParent');
			$m.removeData('etapasReparented');
		});
	});
})(jQuery);
