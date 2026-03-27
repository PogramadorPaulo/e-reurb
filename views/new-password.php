<!DOCTYPE html>
<html>
<?php
require_once('../config.php');
session_start();
?>

<head>
    <meta charset="utf-8">
    <title><?php echo NAME; ?></title>
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/tema/icones/favicon.ico">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/bootstrap/css/bootstrap.min.css">
    <!-- themify icon -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/icon/themify-icons/themify-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/icon/font-awesome/css/font-awesome.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/animate.css/css/animate.css">
    <!-- scrollbar.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/jquery.mCustomScrollbar.css">
    <!-- waves.css -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/tema/pages/waves/css/waves.min.css" type="text/css" media="all">/>
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/style.css">

</head>

<body themebg-pattern="theme6">
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
    <?php
    $chave = addslashes($_GET['chave']);
    $sql = "SELECT recuperar_senha from users WHERE recuperar_senha=:recuperar_senha";
    $sql = $db->prepare($sql);
    $sql->bindValue(':recuperar_senha', $chave);
    $sql->execute();
    if ($sql->rowCount() <= 0) {
        $_SESSION['msgErro'] = "Ops! Este link não é válido!<br> Solicite um novo link";
        header("Location: " . BASE_URL . "login/recuperar");
        exit;
    }

    ?>

    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>

                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>

                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->

                    <form class="md-float-material form-material" name="form" id="form" enctype="multipart/form-data" method="POST">
                        <div class="text-center">
                            <img src="<?php echo BASE_URL; ?>assets/tema/icones/logo1.png" alt="logo.png" width="260">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Nova senha</h3>
                                    </div>
                                </div>
                                <div id="resposta"></div>

                                <div class="d-flex justify-content-center mb-3">
                                    <div class="spinner-border" role="status" id="loader">
                                        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <small id="password-status"></small>
                                    <input type="password" name="password" id="password" class="form-control" onKeyUp="verificaForcaSenha();" minlength="6" required="">
                                    <span class="form-bar"></span>
                                    <span class="olho" id="olhoSenha">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                    <label class="float-label">Nova Senha</label>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="password" name="password_" id="password_" class="form-control" required="">
                                    <span class="form-bar"></span>
                                    <label class="float-label">Repita a Senha</label>
                                </div>

                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <input type="hidden" name="chave" id="chave" value="<?php echo $chave ?>" class="form-control" required="">

                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20" id="btnSend" onclick="send()" name="enviar">Enviar</button>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-inverse text-left m-b-0"></p>
                                        <p class="text-inverse text-left"><a href="<?php echo BASE_URL ?>"><b>Fazer login</b></a></p>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo VERSAO ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </form>

                    <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>

    <!-- Required Jquery -->
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/jquery-ui/jquery-ui.min.js "></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/popper.js/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/bootstrap/js/bootstrap.min.js "></script>
    <!-- custom js -->
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/pages/dashboard/custom-dashboard.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/script.js "></script>
    <!-- notification js -->
    <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/bootstrap-growl.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/tema/js/jquery.mask.min.js"></script>
    <script src="<?php echo BASE_URL ?>assets/tema/js/mascaras.js"></script>

</body>

</html>

<!-- input password -->
<script>
    const senhaInput = document.getElementById('password');
    const olhoSenha = document.getElementById('olhoSenha');
    olhoSenha.addEventListener('click', function() {
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            olhoSenha.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
        } else {
            senhaInput.type = 'password';
            olhoSenha.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
        }
    });
</script>



<script>
    var spinner = $('#loader');

    function send() {
        spinner.show();
        // validar campos
        var password = document.getElementById("password").value;
        var password_ = document.getElementById("password_").value;
        if (password == '') {
            $("#resposta").append("<div class='alert alert-warning' role='alert'>Informe sua nova senha de acesso!</div>");
            $('.alert').fadeIn(500).delay(3000).fadeOut(500);
            $(".alert").fadeOut(400, function() {
                $(this).remove();
            });
            document.getElementById("password").focus();
            document.getElementById("password").style.borderColor = "red";
            spinner.hide();
            exit;
        }
        if (password_ == '') {
            $("#resposta").append("<div class='alert alert-warning' role='alert'>Repita novamente sua nova senha</div>");
            $('.alert').fadeIn(500).delay(3000).fadeOut(500);
            $(".alert").fadeOut(400, function() {
                $(this).remove();
            });
            document.getElementById("password_").focus();
            document.getElementById("password_").style.borderColor = "red";
            spinner.hide();
            exit;
        }


        // fazer requesição

        var formData = new FormData(document.getElementById("form"));
        $.ajax({
            type: 'POST',
            url: '<?php echo BASE_URL ?>acoes/new-password.php',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {

                $('#btnSend').attr("disabled", "disabled");
                $('#form1').css("opacity", ".5");
            },
            success: function(status) {
                console.log(status);
                if (status == 'Digite sua nova senha') {
                    $("#resposta").append("<div class='alert alert-warning' role='alert'>Digite sua nova senha</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('#form1').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();
                } else if (status == 'Senhas não confere') {
                    $("#resposta").append("<div class='alert alert-warning' role='alert'>Senhas não confere! Repita novamente</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    document.getElementById("password").style.borderColor = "red";
                    document.getElementById("password_").style.borderColor = "red";
                    $('#form1').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();
                } else if (status == 'Erro!') {
                    $("#resposta").append("<div class='alert alert-success' role='alert'>Ops! Algo deu errado, Tente novamente</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('#form1').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();

                } else if (status == 'success') {
                    $("#resposta").append("<div class='alert alert-success' role='alert'>Nova senha foi criado com sucesso! Faça o login</div>");
                    $('#form1').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();
                }

            }

        });
    }

    function verificaForcaSenha() {
        var numeros = /([0-9])/;
        var alfabetoa = /([a-z])/;
        var alfabetoA = /([A-Z])/;
        var chEspeciais = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;


        if ($('#password').val().length < 6) {
            $('#password-status').html("<span style='color:red'>Senha Fraca, insira no mínimo 6 caracteres</span>");
            $('#btnSend').attr("disabled", "disabled");
        } else {
            if ($('#password').val().match(numeros) && $('#password').val().match(alfabetoa) && $('#password').val().match(alfabetoA) && $('#password').val().match(chEspeciais)) {
                $('#password-status').html("<span style='color:green'><b>Senha Forte</b></span>");
                $("#btnSend").removeAttr("disabled");
            } else {
                $('#password-status').html("<span style='color:orange'>Insira um caracter especial, letra Maiúscula e Minúscula </span>");
                $('#btnSend').attr("disabled", "disabled");
            }
        }
    }
</script>