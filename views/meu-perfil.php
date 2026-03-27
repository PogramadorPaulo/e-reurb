<style>
	#cpf_invalido {
		display: none;
		position: absolute;
		font-size: 10px;
		color: #EB5F61;
		margin-left: 8px;
		margin-top: 2px;
	}

	.olho {
		position: absolute;
		top: 37%;
		right: 10px;
		transform: translateY(-50%);
		cursor: pointer;
	}
</style>
<!-- Page-header end -->
<div class="pcoded-inner-content" id="content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">
				<div class="d-flex justify-content-center mb-3">
					<div class="spinner-border" role="status" id="loader">
						<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<div id="resposta"></div>
				<div class="row">
					<div class="col-md-6">
						<?php foreach ($list as $item) : ?>
							<form id="form_dados_gerais" method="POST" class="form_dados_gerais" autocomplete="off">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Dados Gerais</h5>
										<div class="card-tools float-right">
											<button type="submit" id="btn_edit_dados_gerais" onclick="editDadosGerais()" class="btn waves-effect waves-light btn-success btn-square btn-block"><i class="ti-save"></i> Salvar</button>
											<input type="hidden" name="id" id="id" value="<?php echo $item['id'] ?>">
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="exampleInputEmail1">Nome</label><span class="text-danger"> *</span>
													<input type="text" class="form-control" id="name" name="name" value="<?php echo $item['name'] ?>" placeholder="Nome completo" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="exampleInputEmail1">E-mail</label>
													<input type="email" class="form-control" id="email" name="email" value="<?php echo $item['email'] ?>" placeholder="E-mail" disabled>
												</div>
											</div>

										</div>

										<p style="font-size: 10px;">
											Cadastrado: <?php echo date("d/m/Y H:i", strtotime($item['data'])) ?>
											<?php if ($item['data_update'] != '') {
												echo 'Atualizado: ' . date("d/m/Y H:i", strtotime($item['data_update']));
											} ?>
										</p>
									</div>
								</div>
							</form>
						<?php endforeach; ?>
					</div>
					<div class="col-md-6">
						<div class="auth-box card">
							<form class="md-float-material form-material" id="form_senha" name="form" enctype="multipart/form-data">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Atualização de Senha</h5>
										<div class="card-tools float-right">
											<button type="submit" id="btn_edit_senha" onclick="editSenha()" class="btn waves-effect waves-light btn-success btn-square btn-block"><i class="ti-save"></i> Alterar senha</button>
											<input type="hidden" name="id" value="<?php echo $item['id'] ?>">
										</div>
									</div>
									<div class="card-body">
										<div class="form-group form-primary">

											<input type="password" name="password_atual" id="password_atual" class="form-control" minlength="6" required="">
											<span class="form-bar"></span>
											<span class="olho" id="olhoSenhapassword_atual">
												<i class="fa fa-eye" aria-hidden="true"></i>
											</span>
											<label class="float-label">Senha atual</label>
										</div>

										<div class="form-group form-primary">
											<small id="password-status"></small>
											<input type="password" name="password" id="password" class="form-control" onKeyUp="verificaForcaSenha();" minlength="6" required="">
											<span class="form-bar"></span>
											<span class="olho" id="olhoSenhapassword">
												<i class="fa fa-eye" aria-hidden="true"></i>
											</span>
											<label class="float-label">Senha Nova</label>
										</div>
										<div class="form-group form-primary">

											<input type="password" name="password_" id="password_" class="form-control" minlength="6" required="">
											<span class="form-bar"></span>
											<span class="olho" id="olhoSenhapassword_">
												<i class="fa fa-eye" aria-hidden="true"></i>
											</span>
											<label class="float-label">Repita a Senha</label>
										</div>
									</div>
								</div>
							</form>
						</div>

					</div>
				</div>
			</div><!-- /.container-fluid -->
		</div>
	</div>
</div>
<!-- /.content -->
<script>
	$('#cpf').on('keyup', function() {
		var strCPF = document.getElementById("cpf").value;
		strCPF = strCPF.replace(/[^\d]+/g, '');
		var qntNumero = strCPF.length;
		if (qntNumero <= 11) {
			function TestaCPF(strCPF) {
				var Soma;
				var Resto;
				Soma = 0;


				if (strCPF == "00000000000" ||
					strCPF == "11111111111" ||
					strCPF == "22222222222" ||
					strCPF == "33333333333" ||
					strCPF == "44444444444" ||
					strCPF == "55555555555" ||
					strCPF == "66666666666" ||
					strCPF == "77777777777" ||
					strCPF == "88888888888" ||
					strCPF == "99999999999")
					return false;

				for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
				Resto = (Soma * 10) % 11;

				if ((Resto == 10) || (Resto == 11)) Resto = 0;
				if (Resto != parseInt(strCPF.substring(9, 10))) return false;

				Soma = 0;
				for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
				Resto = (Soma * 10) % 11;

				if ((Resto == 10) || (Resto == 11)) Resto = 0;
				if (Resto != parseInt(strCPF.substring(10, 11))) return false;
				return true;
			}
		}

		if (TestaCPF(strCPF) == false) {
			//	alert('CPF Inválido');
			//document.getElementById("cpf").focus();

			document.getElementById("cpf").style.border = "2px solid red";
			document.getElementById("cpf_invalido").style.display = "block";
			document.getElementById("cpf_invalido").style.color = "red";
			document.getElementById("btn_edit_dados_gerais").disabled = true;

		}
		if (TestaCPF(strCPF) == true) {
			document.getElementById("cpf_invalido").style.display = "none";
			document.getElementById("btn_edit_dados_gerais").disabled = false;
			document.getElementById("cpf").style.border = "2px solid green";
		}

	});
</script>

<script>
	// update dados notificação
	var spinner = $('#loader');

	function editNotification() {
		spinner.show();
		var formData = new FormData(document.getElementById("form_notification"));
		$.ajax({
			type: 'POST',
			url: '<?php echo BASE_URL ?>acoes/meu-perfil-notification-update.php',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function() {

				$('#btn_edit_notification').attr("disabled", "disabled");
				$('#form_notification').css("opacity", ".5");
			},
			success: function(status) {
				console.log(status);
				if (status == 'error') {
					$("#resposta").append("<div class='alert alerta-danger' role='alert'>Ops! Algo deu errado</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});
					$('#form_notification').css("opacity", "");
					$("#btn_edit_notification").removeAttr("disabled");
					spinner.hide();
				} else if (status == 'success') {
					$("#resposta").append("<div class='alert alerta-success' role='alert'>Salvo com sucesso</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});


					$('#form_notification').css("opacity", "");
					$("#btn_edit_notification").removeAttr("disabled");
					spinner.hide();

				}
			}

		});
	}
</script>

<script>
	// update dados gerais
	var spinner = $('#loader');

	function editDadosGerais() {
		spinner.show();
		var formData = new FormData(document.getElementById("form_dados_gerais"));
		$.ajax({
			type: 'POST',
			url: '<?php echo BASE_URL ?>acoes/meu-perfil-dados-gerais-update.php',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function() {

				$('#btn_edit_dados_gerais').attr("disabled", "disabled");
				$('#form_dados_gerais').css("opacity", ".5");
			},
			success: function(status) {
				console.log(status);
				if (status == 'Informe os campos marcados com * vermelho!') {
					$("#resposta").append("<div class='alert alerta-warning' role='alert'>" + status + "</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});
					$('#form_dados_gerais').css("opacity", "");
					$("#btn_edit_dados_gerais").removeAttr("disabled");
					spinner.hide();
				} else if (status == 'success') {
					$("#resposta").append("<div class='alert alerta-success' role='alert'>Dados editado com sucesso</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});


					$('#form_dados_gerais').css("opacity", "");
					$("#btn_edit_dados_gerais").removeAttr("disabled");
					spinner.hide();

				} else if (status == 'error') {
					$("#resposta").append("<div class='alert alerta-danger' role='alert'>Ops! Não fopi possível atualizar! Tente novamente</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});


					$('#form_dados_gerais').css("opacity", "");
					$("#btn_edit_dados_gerais").removeAttr("disabled");
					spinner.hide();

				}
			}

		});
	}
</script>

<script>
	// update senha
	var spinner = $('#loader');

	function editSenha() {
		spinner.show();
		var formData = new FormData(document.getElementById("form_senha"));
		$.ajax({
			type: 'POST',
			url: '<?php echo BASE_URL ?>acoes/meu-perfil-senha-update.php',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function() {

				$('#btn_edit_senha').attr("disabled", "disabled");
				$('#form_senha').css("opacity", ".5");
			},
			success: function(status) {
				console.log(status);
				if (status == 'Preencha todos os campos obrigatórios!') {
					$("#resposta").append("<div class='alert alerta-warning' role='alert'>" + status + "</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});
					$('#form_senha').css("opacity", "");
					$("#btn_edit_senha").removeAttr("disabled");
					spinner.hide();
				}
				if (status == 'Senha atual não confere!') {
					$("#resposta").append("<div class='alert alerta-warning' role='alert'>" + status + "</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});
					$('#form_senha').css("opacity", "");
					$("#btn_edit_senha").removeAttr("disabled");
					spinner.hide();
				}
				if (status == 'Senhas não confere!') {
					$("#resposta").append("<div class='alert alerta-warning' role='alert'>" + status + "</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});
					$('#form_senha').css("opacity", "");
					$("#btn_edit_senha").removeAttr("disabled");
					spinner.hide();
				} else if (status == 'success') {
					$("#resposta").append("<div class='alert alerta-success' role='alert'>Senha alterada com sucesso</div>");
					$('.alert').fadeIn(500).delay(3000).fadeOut(500);
					$(".alert").fadeOut(400, function() {
						$(this).remove();
					});


					$('#form_senha').css("opacity", "");
					$("#btn_edit_senha").removeAttr("disabled");
					spinner.hide();
					document.getElementById("form_senha").reset();

				}
			}

		});
	}
</script>

<!-- input password -->
<script>
	const password_atual = document.getElementById('password_atual');
	const olhoSenhapassword_atual = document.getElementById('olhoSenhapassword_atual');
	olhoSenhapassword_atual.addEventListener('click', function() {
		if (password_atual.type === 'password') {
			password_atual.type = 'text';
			olhoSenhapassword_atual.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
		} else {
			password_atual.type = 'password';
			olhoSenhapassword_atual.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
		}
	});

	/* */
	const password = document.getElementById('password');
	const olhoSenhapassword = document.getElementById('olhoSenhapassword');
	olhoSenhapassword.addEventListener('click', function() {
		if (password.type === 'password') {
			password.type = 'text';
			olhoSenhapassword.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
		} else {
			password.type = 'password';
			olhoSenhapassword.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
		}
	});

	/* */
	const password_ = document.getElementById('password_');
	const olhoSenhapassword_ = document.getElementById('olhoSenhapassword_');
	olhoSenhapassword_.addEventListener('click', function() {
		if (password_.type === 'password') {
			password_.type = 'text';
			olhoSenhapassword_.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
		} else {
			password_.type = 'password';
			olhoSenhapassword_.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
		}
	});
</script>

<script>
	function verificaForcaSenha() {
		var numeros = /([0-9])/;
		var alfabetoa = /([a-z])/;
		var alfabetoA = /([A-Z])/;
		var chEspeciais = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;

		if ($('#password').val().length < 6) {
			$('#password-status').html("<span style='color:red'>Senha Fraca, insira no mínimo 6 caracteres</span>");
			$('#btn_edit_senha').attr("disabled", "disabled");
		} else {
			if ($('#password').val().match(numeros) && $('#password').val().match(alfabetoa) && $('#password').val().match(alfabetoA) && $('#password').val().match(chEspeciais)) {
				$('#password-status').html("<span style='color:green'><b>Senha Forte</b></span>");
				$("#btn_edit_senha").removeAttr("disabled");
			} else {
				$('#password-status').html("<span style='color:orange'>Insira um caracter especial, letra Maiúscula e Minúscula </span>");
				$('#btn_edit_senha').attr("disabled", "disabled");
			}
		}
	}
</script>