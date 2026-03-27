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
						<h5 class="card-title">Itens de Permissões</h5>
						<div class="card-tools float-right">
							<a href="<?php echo BASE_URL; ?>permissions/items_add" class="btn waves-effect waves-light btn-success btn-square btn-block"><i class="ti-plus"></i> Adicionar</a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="300">Nome do item de permissão</th>
										<th width="150">Slug</th>
										<th width="150">Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($list as $item) : ?>
										<tr>
											<td><?php echo $item['name']; ?></td>
											<td><?php echo $item['slug']; ?></td>
											<td>
												<div class="btn-group" role="group">
													<a href="<?php echo BASE_URL . 'permissions/items_edit/' . $item['id']; ?>" class="btn btn-primary btn-sm"><i class="ti-pencil-alt"></i> Editar</a>
													<a href="<?php echo BASE_URL . 'permissions/items_del/' . $item['id']; ?>" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Excluir
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