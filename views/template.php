<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo NAME; ?></title>
  <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/tema/icones/favicon.ico">
 
  <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet"> -->

  <!-- Required Fremwork -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/bootstrap/css/bootstrap-select.min.css">

  <!--  icofont  -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/icon/icofont/css/icofont.css">
  <!-- themify icon -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/icon/themify-icons/themify-icons.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/icon/font-awesome/css/font-awesome.min.css">
  <!-- scrollbar.css -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/jquery.mCustomScrollbar.css">
  <!-- Style.css -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/style.css">
  <!-- Select2 CSS -->
  
  <link href="<?php echo BASE_URL; ?>assets/tema/css/select2.css" rel="stylesheet">





  <!-- Inclua os arquivos CSS do SweetAlert2 -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/sweetalert2.min.css">
  <!-- Modais acima do header/sidebar/loader do tema (z-index) -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/tema/css/modal-zfix.css">
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/tinymce/tinymce.min.js"></script>
  <!-- Required Jquery -->
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/jquery/jquery.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/processos/json-response.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/jquery-ui/jquery-ui.min.js "></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/popper.js/popper.min.js"></script>


</head>

<body>
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

  <div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">
      <nav class="navbar header-navbar pcoded-header">
        <div class="navbar-wrapper">
          <div class="navbar-logo">
            <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
              <i class="ti-menu"></i>
            </a>
            <div class="mobile-search waves-effect waves-light">
              <div class="header-search">
                <div class="main-search morphsearch-search">
                  <div class="input-group">
                    <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                    <input type="text" class="form-control" placeholder="Enter Keyword">
                    <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <a href="<?php echo BASE_URL; ?>">
              <img class="" src="<?php echo BASE_URL; ?>assets/tema/icones/logo1.png" alt="Theme-Logo" width="120"  />
            </a>
            <a class="mobile-options waves-effect waves-light">
              <i class="ti-more"></i>
            </a>
          </div>

          <div class="navbar-container container-fluid">
            <ul class="nav-left">
              <li>
                <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
              </li>
              <li class="header-search">
                <div class="main-search morphsearch-search">
                  <div class="input-group">
                    <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                    <input type="text" class="form-control">
                    <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                  </div>
                </div>
              </li>
              <li>
                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                  <i class="ti-fullscreen"></i>
                </a>
              </li>
            </ul>
            <ul class="nav-right">
              <li class="header-notification">
                <a href="#" class="waves-effect waves-light">
                  <i class="ti-bell"></i>
                  <span id="notif"></span>
                </a>
                <ul class="show-notification">
                  <li>
                    <a href="<?php echo BASE_URL ?>notifications"><i class="ti-bell"></i> Ver todas notificações</a>

                  </li>
                  <div id="content_notifications"></div>

                </ul>
              </li>
              <li class="user-profile header-notification">
                <a href="#!" class="waves-effect waves-light">
                  <img src="<?php echo BASE_URL; ?>assets/tema/images/avatar.png" class="" alt="User-Profile-Image">
                  <span><?php echo $viewData['user']->getName(); ?></span>
                  <i class="ti-angle-down"></i>
                </a>
                <ul class="show-notification profile-notification">
                  <li class="waves-effect waves-light">
                    <a href="<?php echo BASE_URL; ?>minhas_atividades">
                      <i class="fa fa fa-history"></i> Histórico de atividades
                    </a>
                  </li>
                  <li class="waves-effect waves-light">
                    <a href="<?php echo BASE_URL; ?>meu_perfil">
                      <i class="ti-user"></i> Perfil
                    </a>
                  </li>
                  <li class="waves-effect waves-light">
                    <a href="<?php echo BASE_URL; ?>login/logout">
                      <i class="ti-layout-sidebar-left"></i> Sair
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
          <nav class="pcoded-navbar">
            <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
            <div class="pcoded-inner-navbar main-menu">
              <div class="">
                <div class="main-menu-header">
                  <img class="img-50 img-radius" src="<?php echo BASE_URL; ?>assets/tema/images/avatar.png" alt="User-Profile-Image">
                  <div class="user-details">
                    <span id="more-details"><?php echo $viewData['user']->getName(); ?><i class="fa fa-caret-down"></i></span>
                  </div>
                </div>
                <div class="main-menu-content">
                  <ul>
                    <li class="more-details">
                      <a href="<?php echo BASE_URL; ?>minhas_atividades"><i class="fa fa fa-history"></i>Histórico de atividades</a>
                      <a href="<?php echo BASE_URL; ?>meu_perfil"><i class="ti-user"></i>Meu Perfil</a>
                      <a href="<?php echo BASE_URL; ?>login/logout"><i class="ti-layout-sidebar-left"></i>Sair</a>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="pcoded-navigation-label" data-i18n="nav.category.navigation">Menu</div>
              <ul class="pcoded-item pcoded-left-item">

                <li class="<?php echo ($viewData['menuActive'] == 'home') ? 'active' : ''; ?>">
                  <a href="<?php echo BASE_URL; ?>" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Home</span>
                    <span class="pcoded-mcaret"></span>
                  </a>
                </li>
                <?php if ($viewData['user']->hasPermission('processos_view')) : ?>
                  <li class="<?php echo ($viewData['menuActive'] == 'prc') ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>processos" class="waves-effect waves-dark">
                      <span class="pcoded-micon"><i class="fa fa-check-circle"></i><b>D</b></span>
                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Processos</span>
                      <span class="pcoded-mcaret"></span>
                    </a>
                  </li>
                <?php endif; ?>

              </ul>


              <div class="pcoded-navigation-label">Administrar</div>
              <ul class="pcoded-item pcoded-left-item">
                <?php if ($viewData['user']->hasPermission('municipio_view')) : ?>
                  <li class="<?php echo ($viewData['menuActive'] == 'municipio') ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>municipio" class="waves-effect waves-dark">
                      <span class="pcoded-micon"><i class="fa fa-check-circle"></i><b>Municípios</b></span>
                      <span class="pcoded-mtext" data-i18n="nav.form-components.main">Municípios</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($viewData['user']->hasPermission('users_view')) : ?>
                  <li class="<?php echo ($viewData['menuActive'] == 'users') ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>users" class="waves-effect waves-dark">
                      <span class="pcoded-micon"><i class="ti-user"></i><b>Usuário</b></span>
                      <span class="pcoded-mtext" data-i18n="nav.form-components.main">Usuários</span>
                      <span class="pcoded-mcaret"></span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if ($viewData['user']->hasPermission('permissions_view')) : ?>
                  <li class="<?php echo ($viewData['menuActive'] == 'permissions') ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>permissions" class="waves-effect waves-dark">
                      <span class="pcoded-micon"><i class="fa fa-eye"></i><b>Permissão</b></span>
                      <span class="pcoded-mtext" data-i18n="nav.form-components.main">Permissões</span>
                      <span class="pcoded-mcaret"></span>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>





            </div>

          </nav>


          <div class="pcoded-content">
            <?php $this->loadViewInTemplate($viewName, $viewData); ?>
          </div>

        </div>

      </div>
    </div>
  </div>


  <!-- <a href="https://api.whatsapp.com/send?phone=5535998826865&text=Olá, Meu nome é <?php echo $viewData['user']->getName(); ?>, preciso de suporte no site <?php echo DESCRICAO ?>" target="_blank" style="position:fixed;bottom:50px;right:10px;">
    <svg enable-background="new 0 0 512 512" width="40" height="40" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
      <path d="M256.064,0h-0.128l0,0C114.784,0,0,114.816,0,256c0,56,18.048,107.904,48.736,150.048l-31.904,95.104  l98.4-31.456C155.712,496.512,204,512,256.064,512C397.216,512,512,397.152,512,256S397.216,0,256.064,0z" fill="#4CAF50" />
      <path d="m405.02 361.5c-6.176 17.44-30.688 31.904-50.24 36.128-13.376 2.848-30.848 5.12-89.664-19.264-75.232-31.168-123.68-107.62-127.46-112.58-3.616-4.96-30.4-40.48-30.4-77.216s18.656-54.624 26.176-62.304c6.176-6.304 16.384-9.184 26.176-9.184 3.168 0 6.016 0.16 8.576 0.288 7.52 0.32 11.296 0.768 16.256 12.64 6.176 14.88 21.216 51.616 23.008 55.392 1.824 3.776 3.648 8.896 1.088 13.856-2.4 5.12-4.512 7.392-8.288 11.744s-7.36 7.68-11.136 12.352c-3.456 4.064-7.36 8.416-3.008 15.936 4.352 7.36 19.392 31.904 41.536 51.616 28.576 25.44 51.744 33.568 60.032 37.024 6.176 2.56 13.536 1.952 18.048-2.848 5.728-6.176 12.8-16.416 20-26.496 5.12-7.232 11.584-8.128 18.368-5.568 6.912 2.4 43.488 20.48 51.008 24.224 7.52 3.776 12.48 5.568 14.304 8.736 1.792 3.168 1.792 18.048-4.384 35.52z" fill="#FAFAFA" />
    </svg> <span class="btn btn-primary btn-sm">Suporte</span>
  </a>

                -->



  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/bootstrap/js/bootstrap.min.js "></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/bootstrap/js/bootstrap-select.min.js"></script>

  <!-- custom js -->
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/script.js "></script>
  <!-- notification js -->
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/tema/js/bootstrap-growl.min.js"></script>

  <!-- jquery slimscroll js -->
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/jquery-slimscroll/jquery.slimscroll.js "></script>
  <!-- modernizr js -->
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/modernizr/modernizr.js "></script>
  <!-- slimscroll js -->
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/SmoothScroll.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/jquery.mCustomScrollbar.concat.min.js "></script>

  <!-- menu js -->
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/pcoded.min.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/vertical-layout.min.js "></script>
  <!-- Inclua os arquivos JS do SweetAlert2 -->
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/sweetalert2.all.min.js "></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/accordion.js "></script>
  <!-- modernizr js -->
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/SmoothScroll.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/jquery-maskmoney.js "></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/jquery.mask.min.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL ?>assets/tema/js/mascaras.js"></script>

  <!-- Select2 JS -->
  <script src="<?php echo BASE_URL; ?>assets/tema/js/select2.min.js"></script>

</body>


</html>

<!-- Tabela de notificações -->
<script>
  $(document).ready(function() {
    get_notificacao(); // chama funcation notificação 
    load_data(); // chama função 
  });

  $('#content_notifications').html('<p>Carregando...</p>');

  function load_data() {
    $.ajax({
      url: "<?php echo BASE_URL; ?>acoes/fetch-notifications-nao-lida.php?id=<?php echo $viewData['user']->getId(); ?>",
      method: "POST",
      success: function(data) {
        $('#content_notifications').html(data);
      }
    });
  }

  function get_notificacao() {
    $.ajax({
      type: "GET",
      url: "<?php echo BASE_URL ?>acoes/get_new_notifications.php?id=<?php echo $viewData['user']->getId(); ?>",
      success: function(data) {
        if (data != 0) {
          $("#notif").show();
          $("#notif").html('<span class="badge bg-c-red"></span>');
        } else {
          $("#notif").hide();
        }
      }
    });
  }

  // Tempo de atualização 2 minutos=  * 2 * 60 *1000 = 120000 
  setInterval("get_notificacao()", 120000);
  setInterval(" load_data();", 120000);
</script>