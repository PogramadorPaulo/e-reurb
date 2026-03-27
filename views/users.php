<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 40px;
		/* Diminua a largura do switch */
		height: 20px;
		/* Diminua a altura do switch */
	}

	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		transition: .4s;
		border-radius: 20px;
		/* Mantenha as bordas arredondadas */
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 16px;
		/* Diminua a altura do círculo */
		width: 16px;
		/* Diminua a largura do círculo */
		left: 2px;
		/* Ajuste a posição */
		bottom: 2px;
		/* Ajuste a posição */
		background-color: white;
		transition: .4s;
		border-radius: 50%;
	}

	input:checked+.slider {
		background-color: #4CAF50;
	}

	input:checked+.slider:before {
		transform: translateX(20px);
		/* Ajuste a distância de deslocamento do círculo */
	}

	.toggle-label {
		margin-left: 5px;
		/* Diminua a margem ao lado do switch */
		font-weight: bold;
	}

	input:checked~.toggle-label {
		color: #4CAF50;
	}

	input:not(:checked)~.toggle-label {
		color: #ff4d4d;
	}
</style>
<!-- Page-header end -->
<div class="pcoded-inner-content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">

				<?php if (isset($_SESSION['msgErro'])) { ?>
					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
						<?php echo $_SESSION['msgErro']; ?>
						<?php unset($_SESSION['msgErro']); ?>
					</div>
				<?php } ?>
				<!--Msg de sucesso -->
				<?php if (isset($_SESSION['msgSuccess'])) { ?>
					<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
						<?php echo $_SESSION['msgSuccess']; ?>
						<?php unset($_SESSION['msgSuccess']); ?>
					</div>
				<?php } ?>

				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Usuários</h5>
						<div class="card-tools float-right">
							<a href="<?php echo BASE_URL; ?>users/add" class="btn btn-success"><i class="ti-plus"></i> Adicionar</a>
						</div>
					</div>


					<div class="card-body">
						<div class="mb-3">
							<label for="filtro_municipio" class="form-label">Filtrar por Município</label>
							<select id="filtro_municipio" class="form-control">
								<option value="">Carregando municípios...</option>
							</select>
						</div>


						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Nome do Usuário</th>
										<th>E-mail</th>
										<th>Nível de permissão</th>
										<th>Status</th>
										<th width="150">Ações</th>
									</tr>
								</thead>
								<tbody id="tabela_usuarios">
									<?php foreach ($list as $item): ?>
										<!-- conteúdo mantido -->
									<?php endforeach; ?>
								</tbody>

							</table>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$.ajax({
			url: 'acoes/users/carrega_select_municipios.php',
			method: 'GET',
			success: function(data) {
				$('#filtro_municipio').html(data);
			},
			error: function() {
				$('#filtro_municipio').html('<option value="">Erro ao carregar</option>');
			}
		});

		// Filtro funcionando após carregar
		$('#filtro_municipio').on('change', function() {
			const municipio_id = $(this).val();
			$.post('acoes/users/fetch_usuarios.php', {
				municipio_id
			}, function(html) {
				$('#tabela_usuarios').html(html);
			});
		});
	});
</script>