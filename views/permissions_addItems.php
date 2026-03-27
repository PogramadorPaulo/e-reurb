<!-- Page-header end -->
<div class="pcoded-inner-content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">
				<form action="<?php echo BASE_URL; ?>permissions/addItem_action" method="POST">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Novo Item de Permissões</h5>
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
								<label for="exampleInputEmail1">Nome do item</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="" required>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Nome slug</label>
								<input type="text" class="form-control" id="slug" name="slug" placeholder="" required>
							</div>

						</div>

					</div>
				</form>
				<!-- /.card -->
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>
<!-- /.content -->