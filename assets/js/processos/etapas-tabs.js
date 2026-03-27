/**
 * Etapas do processo (Bootstrap 4)
 *
 * Desenvolvedores:
 * - Cada aba é um painel #etapa_<etapa_id> com data-etapa-id = etapa_processo_id (banco).
 * - Lazy load: nos JS das etapas (ex.: anexos), chamar EtapasTabs.registerInit(etapaId, fn).
 *   Use EtapasTabs.resolveTabIdFromPane('#seletorDentroDaEtapa', fallbackNum) para alinhar ao data-tab-num.
 * - Após salvar dados que mudem o estado da etapa, usar EtapasTabs.reloadPreservingTab(tabOpcional).
 * - HTML parcial por etapa (carregar só o markup ao abrir) fica fora do escopo até decisão de produto (Fase 3).
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

	function notifyInitFailure(message) {
		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: 'Não foi possível carregar a etapa',
				text: message || 'Tente atualizar a página ou entre em contato com o suporte.',
				icon: 'error',
			});
		} else {
			window.alert(message || 'Erro ao carregar a etapa.');
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
					ret.then(finish, function (err) {
						console.error('EtapasTabs init (Promise):', err);
						notifyInitFailure(err && err.message ? String(err.message) : null);
						finish();
					});
					return;
				}
			} catch (err) {
				console.error('EtapasTabs init:', err);
				notifyInitFailure(err && err.message ? String(err.message) : null);
				finish();
				return;
			}
		}
		finish();
	}

	function syncAriaTablist($activeLink) {
		var $tabs = $('#etapasTabs');
		if (!$tabs.length) {
			return;
		}
		$tabs.find('a[role="tab"]').each(function () {
			var $a = $(this);
			var sel = $activeLink && $a[0] === $activeLink[0];
			$a.attr('aria-selected', sel ? 'true' : 'false');
			$a.attr('tabindex', sel ? '0' : '-1');
		});
	}

	function focusTabPanel($pane) {
		if (!$pane || !$pane.length) {
			return;
		}
		var $body = $pane.find('.etapa-tab-body').first();
		if (!$body.length) {
			return;
		}
		var el = $body[0];
		$body.attr('tabindex', '-1');
		try {
			if (el && typeof el.focus === 'function') {
				/* focus() sem preventScroll rola a página até o painel; leitores de tela ainda recebem o foco */
				el.focus({ preventScroll: true });
			}
		} catch (e) {
			try {
				el.focus();
			} catch (e2) {
				/* ignore */
			}
		}
	}

	function cleanupOrphanBackdrop() {
		if (!$('.modal.show').length && $('.modal-backdrop').length) {
			$('.modal-backdrop').remove();
			$('body').removeClass('modal-open').css('padding-right', '');
		}
	}

	window.EtapasTabs = {
		/**
		 * Obtém etapa_id do painel (.etapa-tab-pane[data-etapa-id]) a partir de um nó interno.
		 * @param {string} anchorSelector - ex.: '#etapa2_anexos', '#etapa1-inner'
		 * @param {string|number} fallback - se o painel não for encontrado
		 */
		resolveTabIdFromPane: function (anchorSelector, fallback) {
			var el = typeof anchorSelector === 'string' ? document.querySelector(anchorSelector) : anchorSelector;
			if (el) {
				var pane = el.closest('.etapa-tab-pane');
				if (pane && pane.getAttribute('data-etapa-id')) {
					return String(pane.getAttribute('data-etapa-id'));
				}
			}
			return String(fallback);
		},

		registerInit: function (etapaId, fn) {
			inits[String(etapaId)] = fn;
		},

		/** Limpa cache de init para uma etapa (ex.: após mutação sem reload completo). */
		resetInit: function (etapaId) {
			delete initialized[String(etapaId)];
			delete running[String(etapaId)];
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

		/**
		 * Recarrega a página mantendo a aba na URL.
		 * @param {string|number|null|undefined} optionalTab - se informado, usa como ?tab= (ex.: next_etapa_id após concluir).
		 */
		reloadPreservingTab: function (optionalTab) {
			var tab =
				optionalTab !== undefined && optionalTab !== null && String(optionalTab) !== ''
					? String(optionalTab)
					: this.getCurrentTabNum();
			sessionStorage.setItem('processoActiveTab', tab);
			var url = window.location.pathname + '?tab=' + encodeURIComponent(tab);
			window.location.href = url;
		},
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

		var $initialActive = $tabs.find('a.nav-link.active:not(.disabled)').first();
		if ($initialActive.length) {
			syncAriaTablist($initialActive);
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

		$tabs.on('hide.bs.tab', 'a[data-toggle="tab"]', function () {
			$('.modal.show').modal('hide');
		});

		function initPaneForLink($link) {
			if (!$link || !$link.length) {
				return;
			}
			var href = $link.attr('href');
			if (!href) {
				return;
			}
			var $pane = $(href);
			var tabNum = String($link.data('tab-num'));
			runInitOnce(tabNum, $pane);
			focusTabPanel($pane);
		}

		$tabs.on('shown.bs.tab', 'a[data-toggle="tab"]', function () {
			var $target = $(this);
			if ($target.hasClass('disabled')) {
				return;
			}
			syncAriaTablist($target);
			var tabNum = String($target.data('tab-num'));
			var href = $target.attr('href');
			if (!href) {
				return;
			}
			var $pane = $(href);
			/* Garante um único painel ativo (evita sobreposição após transições/modais). */
			$('#etapasContent > .tab-pane').not($pane).removeClass('active show').attr('aria-hidden', 'true');
			$pane.attr('aria-hidden', 'false');
			syncUrl(tabNum);
			initPaneForLink($target);
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
				syncAriaTablist($linkToShow);
				$('#etapasContent > .tab-pane').not($paneTarget).removeClass('active show').attr('aria-hidden', 'true');
				$paneTarget.attr('aria-hidden', 'false');
				initPaneForLink($linkToShow);
			}
		}

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
			if ($m.data('etapasReparented')) {
				var $p = $m.data('etapasModalParent');
				if ($p && $p.length) {
					$m.appendTo($p);
				}
				$m.removeData('etapasModalParent');
				$m.removeData('etapasReparented');
			}
			setTimeout(cleanupOrphanBackdrop, 0);
		});
	});
})(jQuery);
