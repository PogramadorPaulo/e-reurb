<!DOCTYPE html>
<html>
<?php require_once '../config.php' ?>

<head>
    <meta charset="utf-8">
    <title><?php echo NAME; ?></title>
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/tema/icones/icone.ico">
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

</head>

<body>
    <section class="login-block">

        <div class="container">
            <?php
            if (isset($_GET['token']) && $_GET['token'] !== '') {
                $token = addslashes($_GET['token']);
                $sql = "SELECT token_ativacao from users WHERE token_ativacao=:token_ativacao";
                $sql = $db->prepare($sql);
                $sql->bindValue(':token_ativacao', $token);
                $sql->execute();
                if ($sql->rowCount() == 0) {
                    echo '<h4 class="text-danger">Ops! Este link não é válido!<br> Solicite um novo link</h4>';
                } else {

                    $update = "UPDATE users SET token_ativacao='', status=1 WHERE token_ativacao=:token_ativacao";
                    $update = $db->prepare($update);;
                    $update->bindValue(':token_ativacao', $token);
                    $update->execute();
                    echo '<h4 class="text-success">Usuário foi ativado com sucesso! <a href="'.BASE_URL.'painel">Fazer o login agora</a></h4>';
                }
            } else {
                echo '<h4 class="text-danger">Erro! Token não localizado!</h4>';
            }


            ?>
        </div>
    </section>


</body>

</html>