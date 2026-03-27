<div class="pcoded-inner-content">
	<div class="main-body">
		<div class="page-wrapper">
			<div class="content">

				<div class="card">
					<div class="card-header">
						<h5 class="card-title">CONTRATOS</h5>
						<div class="card-tools float-right">
							<button class="btn waves-effect waves-light btn-success btn-square btn-block btn_modalNew" id="btn_modalNew"><i class="ti-plus"></i> Novo</button>
						</div>
					</div>
					<div class="card-body">
						<button id="toggleFilters" class="btn waves-effect waves-light btn-primary btn-square mb-3 btn-toggle"><i class="fa fa-filter"></i> Mostrar Filtros </button>
						<div id="filters" class="border p-4 mb-3 rounded" style="display: none;">
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label>Pesquisar por:</label>
										<input type="search" id="search_obejto" autocomplete="off" class="form-control mb-1" placeholder="Digite aqui" />
									</div>
								</div>
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

<!-- modal novo -->
<div id="modalNew" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" data-bs-focus="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-personalizado" role="document">
		<div class="modal-content">
			<form id="form_new" method="POST" action="<?php echo BASE_URL ?>acoes/municipio/add.php" class="form_new" enctype="multipart/form-data" autocomplete="off">
				<div class="modal-header bg-info">
					<h5 class="modal-title">Novo Municipio</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="card-body">
					<!-- Informações Gerais do Município -->
					<fieldset class="border p-3 rounded">
						<legend class="w-auto px-2 text-primary">Informações Gerais</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="municipio_name">Município</label>
									<input type="text" class="form-control" name="municipio_name" id="municipio_name" placeholder="Digite o nome do município" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="municipio_uf">UF</label>
									<input type="text" class="form-control" maxlength="2" name="municipio_uf" id="municipio_uf" placeholder="Digite a UF do município (ex: SP)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="municipio_cnpj">CNPJ</label>
									<input type="text" class="form-control" name="municipio_cnpj" id="cnpj" placeholder="Digite o CNPJ do município" required>
								</div>
							</div>
						</div>
					</fieldset>

					<!-- Informações do Prefeito -->
					<fieldset class="border p-3 rounded mt-4">
						<legend class="w-auto px-2 text-primary">Informações do Prefeito</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="municipio_prefeito">Nome do Prefeito</label>
									<input type="text" class="form-control" name="municipio_prefeito" id="municipio_prefeito" placeholder="Digite o nome do prefeito">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="municipio_autoridade">Cargo</label>
									<input type="text" class="form-control" name="municipio_autoridade" id="municipio_autoridade" placeholder="Digite o cargo do prefeito">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="municipio_prefeito_cpf">CPF</label>
									<input type="text" class="form-control" name="municipio_prefeito_cpf" id="cpf" placeholder="Digite o CPF do prefeito">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="municipio_prefeito_rg">RG</label>
									<input type="text" class="form-control" name="municipio_prefeito_rg" id="municipio_prefeito_rg" placeholder="Digite o RG do prefeito">
								</div>
							</div>
						</div>
					</fieldset>

					<!-- Informações de normativas -->
					<fieldset class="border p-3 rounded mt-4">
						<legend class="w-auto px-2 text-primary">Normativas</legend>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="municipio_lei_municipal">Lei Municipal que Regulariza a Reurb</label>
									<div class="form-group">
										<textarea name="municipio_normativas" placeholder="Leis, Decretos, Portarias" class="form form-control" rows="5"></textarea>
									</div>
								</div>
							</div>


						</div>
					</fieldset>

				</div>


				<div class="modal-footer">
					<input type="hidden" id="idUser" value="<?php echo $viewData['user']->getId(); ?>" name="idUser" />
					<button type="submit" class="btn btn-success" id="btn_new_send"><i class="ti-save"></i> Cadastrar</button>
					<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="ti-close"></i> Fechar</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- modal edit -->
<div id="modalEdit" data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<form id="form_edit" method="POST" action="<?php echo BASE_URL ?>acoes/municipio/update.php" class="form_edit" enctype="multipart/form-data" autocomplete="off">
		<div class="modal-dialog modal-personalizado" role="document">
			<div class="modal-content">
				<div class="modal-header bg-info">
					<h5 class="modal-title" id="">Editar Município</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="resposta"></div>
					<div class="d-flex justify-content-center mb-3">
						<div class="spinner-border" role="status" id="loader">
							<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
							<span class="sr-only">Loading...</span>
						</div>
					</div>
					<div id="view_dados"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="btn_sendEdit"><i class="ti-save"></i> Salvar</button>
					<input type="hidden" id="idUser" value="<?php echo $viewData['user']->getId(); ?>" name="idUser" />
					<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="ti-close"></i> Fechar</button>
				</div>
			</div>
		</div>
	</form>
</div>


<!-- Modal Comissão -->
<div class="modal fade" id="modalComissao" data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Comissão do Município</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="conteudo_comissao">
				<!-- Conteúdo será carregado via AJAX -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="ti-close"></i> Fechar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Membro Comissão (formulário separado) -->
<div class="modal fade" id="modalMembroComissao" data-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Membro da Comissão</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="conteudo_membro_comissao">
				<!-- Formulário será carregado aqui -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="ti-close"></i> Fechar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Funções da Comissão -->
<div class="modal fade" id="modalFuncoesComissao" tabindex="-1" aria-labelledby="modalFuncoesComissaoLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Funções da Comissão</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="conteudo_funcoes_comissao">
				<!-- Conteúdo será carregado via AJAX -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="ti-close"></i> Fechar</button>
			</div>
		</div>
	</div>
</div>



<!-- Modal para Upload do Logo -->
<div class="modal fade" id="uploadLogoModal" tabindex="-1" role="dialog" aria-labelledby="uploadLogoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h5 class="modal-title" id="uploadLogoModalLabel">Upload do Logo do Município</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="uploadLogoForm" action="<?php echo BASE_URL ?>acoes/municipio/upload_logo.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" name="municipio_id" id="municipioId">
					<div class="form-group">
						<label for="logoFile">Selecione a imagem do logo:</label>
						<input type="file" class="form-control-file" id="logoFile" name="logo" accept=".jpg,.jpeg,.png" required>
						<small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG.</small>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-success">Enviar</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/municipio/js.js"></script>

<script>
	$(document).on('click', '.view_data_comissao', function() {
		var municipio_id = $(this).attr('id');

		$.ajax({
			url: 'acoes/municipio/carregar_comissao.php',
			method: 'POST',
			data: {
				municipio_id: municipio_id
			},
			success: function(data) {
				$('#conteudo_comissao').html(data);
				$('#modalComissao').modal('show');
			}
		});
	});
</script>