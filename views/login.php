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

    .olho {
      position: absolute;
      top: 37%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;


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
          <div class="text-center">
              <img src="<?php echo BASE_URL; ?>assets/tema/icones/logo.png" alt="logo.png" width="260">
            </div>
          <!-- Authentication card start -->
          <form class="md-float-material form-material" method="POST" id="form_login" name="form_login" enctype="multipart/form-data">
            <div class="auth-box card">
              <div class="card-block">
                <div class="row m-b-20">
                  <div class="col-md-12">
                    <h3 class="text-center">Login</h3>
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
                  <label class="float-label">Seu e-mail</label>
                </div>
                <div class="form-group form-primary">
                  <input type="password" name="password" id="password" class="form-control" required="">
                  <span class="form-bar"></span>
                  <span class="olho" id="olhoSenha">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                  </span>
                  <label class="float-label">Senha</label>
                </div>
                <div class="row m-t-25 text-left">
                  <div class="col-12">

                    <div class="forgot-phone text-right f-right">
                      <a href="<?php echo BASE_URL ?>login/recuperar" class="text-right f-w-600"> Esqueceu a senha?</a>
                    </div>
                  </div>
                </div>
                <div class="row m-t-30">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-info btn-md btn-block waves-effect waves-light text-center m-b-20" id="btnSend">Entrar</button>
                  </div>
                </div>
                <hr />
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-inverse text-left m-b-0"></p>
                    <p class="text-inverse text-left"><a href="https://inovaresites.com.br" target="_blank"><b>Inovare Soluções em Tecnologia</b></a></p>
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
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/script.js "></script>
  <!-- notification js -->
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/bootstrap-growl.min.js"></script>
  <script src="<?php echo BASE_URL ?>assets/tema/js/jquery.mask.min.js"></script>
  <script src="<?php echo BASE_URL ?>assets/tema/js/mascaras.js"></script>
  <!-- Inclua os arquivos JS do SweetAlert2 -->
  <script src="<?php echo BASE_URL ?>assets/tema/js/sweetalert2.all.min.js "></script>
  <script src="<?php echo BASE_URL ?>assets/tema/js/login.js "></script>
</body>

</html>