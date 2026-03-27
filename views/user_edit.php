<!-- Page-header end -->
<div class="pcoded-inner-content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">
				<?php foreach ($list as $item) : ?>
					<form action="<?php echo BASE_URL; ?>users/edit_action/<?php echo $id_user ?>" method="POST">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Editar Usuário</h5>
								<div class="card-tools float-right">
									<button type="submit" class="btn btn-success"><i class="ti-save"></i> Salvar</button>
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
									<label for="exampleInputEmail1">Nome</label><span class="text-danger"> *</span>
									<input type="text" class="form-control" id="name" name="name" value="<?php echo $item['name'] ?>" placeholder="Nome do usuário" required>
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1">E-mail</label><span class="text-danger"> *</span>
									<input type="email" class="form-control" id="email" name="email" value="<?php echo $item['email'] ?>" placeholder="E-mail" required>
								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1">Grupo</label><span class="text-danger"> *</span>
									<select class="form-control" name="grupo" id="grupo" required>
										<option value="" disabled>--selecionar--</option>
										<?php foreach ($list_permission as $p) : ?>
											<option <?php echo ($item['id_permission'] == $p['id']) ? 'selected' : ''; ?> value="<?php echo $p['id'] ?>"><?php echo $p['name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							
								<div class="form-group">
									<label for="status">Cadastro: <?php echo date("d/m/Y H:i", strtotime($item['data'])) ?></label>
								</div>
							</div>

						</div>
					</form>
				<?php endforeach; ?>
				<!-- /.card -->
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>
<!-- /.content -->