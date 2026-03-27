<!-- Page-header end -->
<div class="pcoded-inner-content" id="content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">

				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Notificações</h5>
					</div>
					<div class="card-body">
						<div id="resposta"></div>
						<div class="d-flex justify-content-center mb-3">
							<div class="spinner-border" role="status" id="loader">
								<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
								<span class="sr-only">Loading...</span>
							</div>
						</div>

						<div id="dynamic_content">

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Tabela 
	$(document).ready(function() {
		load_data(1);
		$('#dynamic_content').html('<p>Carregando...</p>');

		function load_data(page, query = '') {
			$.ajax({
				url: "<?php echo BASE_URL; ?>acoes/fetch-notifications.php",
				method: "POST",
				data: {
					page: page,
					query: query,
					id: <?php echo $viewData['user']->getId(); ?>
				},
				success: function(data) {
					$('#dynamic_content').html(data);
				}
			});
		}

		$(document).on('click', '.page-link', function() {
			var page = $(this).data('page_number');
			var query = $('#search_nome').val();
			load_data(page, query);
		});

		$('#search_nome').keyup(function() {
			var query = $('#search_nome').val();
			load_data(1, query);
		});


	});
</script>