<!-- Page-header end -->
<div class="pcoded-inner-content" id="content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">
				<div id="resposta"></div>
				<div class="d-flex justify-content-center mb-3">
					<div class="spinner-border" role="status" id="loader">
						<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Histórico de atividades</h5>

					</div>
					<div class="card-body">
						<button id="toggleFilters" class="btn waves-effect waves-light btn-primary btn-square mb-3 btn-toggle"><i class="fa fa-filter"></i> Mostrar Filtros </button>
						<div id="filters" class="border p-4 mb-3 rounded" style="display: none;">
							<div class="row">
								<div class="col-lg-3">
									<div class="form-group">
										<label>Filtrar por: Data inicial</label>
										<input type="datetime-local" id="data_ini" onkeydown="return false" class="form-control" value="<?php echo date('Y-m-d H:i', strtotime(date('Y-m-d H:i') . '-1 month')); ?>" />
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<label>Data final</label>
										<input type="datetime-local" id="data_fin" onkeydown="return false" class="form-control" value="<?php echo date('Y-m-d H:i', strtotime(date('Y-m-d H:i'))); ?>" />
									</div>
								</div>
							</div>
						</div>
						<div id="dynamic_content" class="border p-3 rounded">

						</div>


					</div>
				</div>
				<br>
				<!-- /.card -->
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>



<!-- carregamento -->
<script>
	$(document).ready(function() {
		// Quando o botão de alternar filtros é clicado
		$('#toggleFilters').on('click', function() {
			// Alternar a visibilidade dos filtros
			$('#filters').toggle();

			// Alterar o texto e o ícone do botão com base na visibilidade dos filtros
			var isVisible = $('#filters').is(':visible');
			if (isVisible) {
				$(this).html('<i class="fa fa-times"></i> Esconder Filtros');
			} else {
				$(this).html('<i class="fa fa-filter"></i> Mostrar Filtros');
			}
		});
		carregar();
	});

	// Variáveis globais para armazenar o estado atual
	var currentPage = 1;
	var data_ini = $('#data_ini').val();
	var data_fin = $('#data_fin').val();

	// Função para carregar os dados
	function loadData(page, query = '') {
		$.ajax({
			url: "<?php echo BASE_URL; ?>acoes/minhas-atividades/fetch.php",
			method: "POST",
			data: {
				page: page,
				query: query,
				data_fin: data_fin,
				data_ini: data_ini, 
				id: <?php echo $viewData['user']->getId(); ?>
			},
			success: function(data) {
				$('#dynamic_content').html(data);
				// Atualizar a página atual após carregar os dados
				currentPage = page;
			}
		});
	}

	// Função para recarregar os dados
	function recarregarDados() {
		loadData(currentPage); // Carregar os dados na página atual
		$('#dynamic_content').html('<p>Carregando...</p>'); // Exibir mensagem de carregamento
	}

	function carregar() {
		// Evento de mudança do ano e do mês
		$('#data_ini, #data_fin').change(function() {
			data_ini = $('#data_ini').val(); // Atualizar o ano atual
			data_fin = $('#data_fin').val(); // Atualizar o mês atual
			recarregarDados(); // Recarregar os dados na página atual
		});

		// Carregar dados na inicialização
		recarregarDados();

		// Evento de clique na paginação
		$(document).on('click', '.page-link', function() {
			var page = $(this).data('page_number');
			var query = $('#search_nome').val();
			loadData(page, query);
		});

	};
</script>