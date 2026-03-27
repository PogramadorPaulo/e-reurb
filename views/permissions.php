<!-- Page-header end -->
<div class="pcoded-inner-content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Grupos de Permissões</h5>
						<div class="card-tools float-right">
							<a href="<?php echo BASE_URL; ?>permissions/items" class="btn waves-effect waves-light btn-success btn-square">Itens de Permissão</a>
							<a href="<?php echo BASE_URL; ?>permissions/add" class="btn waves-effect waves-light btn-success btn-square"><i class="ti-plus"></i> Adicionar</a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="300">Nome da permissão</th>
										<th width="150">Qtd. de ativos</th>
										<th width="150">Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($list as $item) : ?>
										<tr>
											<td><?php echo $item['name']; ?></td>
											<td><?php echo $item['total_users']; ?></td>
											<td>
												<div class="btn-group" role="group">
													<a href="<?php echo BASE_URL . 'permissions/edit/' . $item['id']; ?>" class="btn btn-primary btn-sm"><i class="ti-pencil-alt"></i> Editar</a>
													<a href="<?php echo BASE_URL . 'permissions/del/' . $item['id']; ?>" class="btn btn-danger btn-sm 
																			   <?php echo ($item['total_users'] != '0') ? 'disabled' : ''; ?>"><i class="ti-trash"></i> Excluir
													</a>
												</div>
											</td>
										</tr>
								</tbody>
							<?php endforeach; ?>
							</table>
						</div>
					</div>

				</div>
				<!-- /.card -->
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>
<!-- /.content -->