<!DOCTYPE html>
<html>

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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/tema/pages/waves/css/waves.min.css" type="text/css" media="all">

    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/style.css">
    <!-- Inclua os arquivos CSS do SweetAlert2 -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/sweetalert2.min.css">

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
    </style>

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

                    <form class="md-float-material form-material" id="form" name="form" method="POST" enctype="multipart/form-data">
                        <div class="text-center">
                            <img src="<?php echo BASE_URL; ?>assets/tema/icones/logo.png" alt="logo.png" width="260">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Recuperar senha</h3>
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
                                    <input type="email" name="email" id="email" class="form-control" required="">
                                    <span class="form-bar"></span>
                                    <label class="float-label">Digite seu e-mail de acesso</label>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-info btn-md btn-block waves-effect waves-light text-center m-b-20" id="btnSend" onclick="send()">Enviar</button>
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


<script>
    var spinner = $('#loader');

    function send() {
        // validar campos
        var email = document.getElementById("email").value;
        if (email == '') {
            $("#resposta").append("<div class='alert alert-warning' role='alert'>Informe seu E-mail de acesso!</div>");
            $('.alert').fadeIn(500).delay(3000).fadeOut(500);
            $(".alert").fadeOut(400, function() {
                $(this).remove();
            });
            document.getElementById("email").focus();
            document.getElementById("email").style.borderColor = "red";
            exit;
        }

        // fazer requesição
        spinner.show();
        var formData = new FormData(document.getElementById("form"));
        $.ajax({
            type: 'POST',
            url: '<?php echo BASE_URL ?>acoes/login-recuperar-password.php',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {

                $('#btnSend').attr("disabled", "disabled");
                $('#form').css("opacity", ".5");
            },
            success: function(status) {
                console.log(status);
                if (status == 'Preencha com email') {
                    $("#resposta").append("<div class='alert alert-warning' role='alert'>Infome seu e-mail cadastrado</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    // alert('Preencha com email e senha');
                    $('#form').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();
                } else if (status == 'E-mail não cadastrado') {
                    $("#resposta").append("<div class='alert alert-warning' role='alert'>Este e-mail não está cadastrado!</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('#form').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();
                } else if (status == 'Erro!') {
                    $("#resposta").append("<div class='alert alert-success' role='alert'>Ops! Algo deu errado, Tente novamente</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('#form').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();

                } else if (status == 'Erro ao enviar o email!') {
                    $("#resposta").append("<div class='alert alert-success' role='alert'>Ops! Não foi possível enviar o e-mail! Tente novamente</div>");
                    $('.alert').fadeIn(500).delay(3000).fadeOut(500);
                    $(".alert").fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('#form').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();

                } else if (status == 'Email enviado com sucesso!') {
                    $("#resposta").append("<div class='alert alert-success' role='alert'>Foi enviado um link de recuperação de senha no email " + email + "</div>");
                    $('#form').css("opacity", "");
                    $("#btnSend").removeAttr("disabled");
                    spinner.hide();
                }

            }

        });
    }
</script>