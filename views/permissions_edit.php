<!-- Page-header end -->
<div class="pcoded-inner-content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">
				<form action="<?php echo BASE_URL; ?>permissions/edit_action/<?php echo $permission_id; ?>" method="POST">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Editar Grupo de Permissões</h5>
							<div class="card-tools float-right">
								<button type="submit" class="btn waves-effect waves-light btn-success btn-square btn-block"><i class="ti-save"></i> Salvar</button>
							</div>
						</div>
						<div class="card-body">
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
							<div class="form-group">
								<label for="exampleInputEmail1">Nome do grupo</label>
								<input type="text" class="form-control" id="name" name="name" value="<?php echo $permission_group_name; ?>" required>
							</div>

							<?php foreach ($permission_items as $item) : ?>
								<div class="chk-option">
									<div class="checkbox-fade fade-in-primary">
										<label class="check-task">
											<input <?php echo (in_array($item['slug'], $permission_group_slugs)) ? 'checked="checked"' : ''; ?>type="checkbox" id="item-<?php echo $item['id']; ?>" name="items[]" value="<?php echo $item['id']; ?>" />

											<span class="cr"><i class="cr-icon fa fa-check txt-default"></i></span>
											<span for="item-<?php echo $item['id']; ?>" class="text-inverse"><?php echo $item['name']; ?></span>
										</label>
									</div>
								</div>

							<?php endforeach; ?>
						</div>

					</div>
				</form>
				<!-- /.card -->
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>
<!-- /.content -->