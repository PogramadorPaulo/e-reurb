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
						<h5 class="card-title">PROCESSOS</h5>
						<input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
						<input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">
						<div class="card-tools float-right">
							<?php if ($viewData['user']->hasPermission('processo_novo')) : ?>
								<button class="btn waves-effect waves-light btn-success btn-square btn-block" id="novoProcedimento"><i class="ti-plus"></i> Novo</button>
							<?php endif; ?>
						</div>
					</div>
					<div class="card-body">
						<button id="toggleFilters" class="btn waves-effect waves-light btn-primary btn-square mb-3 btn-toggle"><i class="fa fa-filter"></i> Mostrar Filtros </button>
						<div id="filters" class="border p-4 mb-3 rounded" style="display: none;">
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label>Pesquisar por:</label>
										<input type="search" id="search_obejto" autocomplete="off" class="form-control mb-1" placeholder="Nome do núcleo (Bairro)" />
									</div>
								</div>

								<div class="col-lg-4">
									<div class="form-group">
										<label>Tipo de Regurarização:</label>
										<select class="form-control" name="select_search_tipos" id="select_search_tipos" required>
											<option value="">--todos--</option>
											<option value="Reurb-E">Reurb-E</option>
											<option value="Reurb-S">Reurb-S</option>
											<option value="Reurb-M">Reurb-M</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-4 mb-2">
												<label for="search_data_inicial">Data Inicial:</label>
												<input type="date" id="search_data_inicial" autocomplete="off" class="form-control" value="" />
											</div>
											<div class="col-md-4 mb-2">
												<label for="search_data_final">Data Final:</label>
												<input type="date" id="search_data_final" autocomplete="off" class="form-control" value="" />
											</div>

											<div class="col-md-4 mb-2 d-flex align-items-end">
												<button id="btn_search" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="dynamic_content">

						</div>


					</div>
				</div>
				<br>
				<!-- /.card -->
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>


<script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/processos/ajax.js"></script>