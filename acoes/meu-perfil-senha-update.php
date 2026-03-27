<?php
require_once('../config.php');
session_start();
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$password_atual = filter_input(INPUT_POST, "password_atual", FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
$password_ = filter_input(INPUT_POST, "password_", FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($id) or empty($password_atual) or empty($password) or empty($password_)) {
    echo 'Preencha todos os campos obrigatórios!';
    exit;
}

// verifica a senha atual do usuário está correta
$sql = "SELECT *  from users WHERE password=:password and id=:id";
$sql = $db->prepare($sql);
$sql->bindValue(':id', $id);
$sql->bindValue(':password', md5($password_atual));
$sql->execute();

if ($sql->rowCount() <= 0) {
    echo 'Senha atual não confere!';
    exit;
}
if ($password != $password_) {
    echo 'Senhas não confere!';
    exit;
}
$sql_update = $db->prepare("UPDATE users SET password=:password, data_update=:data_update WHERE id=:id");
$sql_update->bindValue(":id", $id);
$sql_update->bindValue(":password", md5($password_));
$sql_update->bindValue(":data_update", date('Y-m-d H:i'));
$sql_update->execute();

if ($sql_update) {
    $data = $sql->fetch();
    // envia email ao usuário
    require '../PHPMailer-master/PHPMailerAutoload.php';
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = "" . EMAIL_HOST . "";
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = "" . EMAIL . "";
    $mail->Password = "" . EMAIL_PASSWORD . "";
    $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
    $mail->From = "" . EMAIL . "";
    $mail->FromName = NAME;
    $mail->AddAddress('' . $data['email'] . '', 'Alteração de senha');

    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = "Sua senha foi alterada - " . NAME;

    $mail->Body =
        '<div style="background-color: #f0f0f0; color: #7F7F7F; border-radius: 6px; margin: 3px; padding:15px">   
        <h3 style="text-align: center; font-size:18px;">SUA SENHA DE ACESSO FOI ALTERADA</h3>
        <br>
        Usuário : <b>' . $data['name'] . '</b><br>
        Email : <b>' . $data['email'] . '</b><br>
        Data : <b>' . date("d/m/Y H:i") . '</b><br>	
        <br>
            <h4>
              <a href="' . BASE_URL . '" target="_blank" 
                style="background-color: #4CAF50;
                border-radius: 2px;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;">
              Acessar o sistema</a>
            </h4>
        <br>
     </div>
     Atenção ! Este e-mail é enviado automaticamente não responder.<br>
';

    $mail->send();

    echo 'success';
}
