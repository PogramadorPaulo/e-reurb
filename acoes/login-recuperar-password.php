<?php
require_once('../config.php');
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
// verifica se foi enviado o email e senha
if (empty($email)) {
    echo 'Preencha com email';
    exit;
}
// verifica se existe o email cadastrado
$sql = "SELECT id, email, name from users WHERE email=:email";
$sql = $db->prepare($sql);
$sql->bindValue(':email', $email);
$sql->execute();
if ($sql->rowCount() < 1) {
    echo 'E-mail não cadastrado';
    exit;
} else {
    $data = $sql->fetch();

    $chave_recuperar_senha = password_hash($data['id'], PASSWORD_DEFAULT);
    $sql = "UPDATE users SET recuperar_senha =:recuperar_senha WHERE id =:id";
    $sql = $db->prepare($sql);
    $sql->bindValue(':recuperar_senha', $chave_recuperar_senha);
    $sql->bindValue(':id', $data['id']);
    $sql->execute();

    // envia email com link 
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
    $mail->AddAddress('' . $email . '', 'Recuperação de senha');

    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = "Recuperar senha - " . NAME;

    $mail->Body = '
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f9f9f9;
                            color: #333333;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            width: 80%;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                        }
                        h2 {
                            color: #2E6FA7;
                        }
                        p {
                            line-height: 1.5;
                        }
                        a.button {
                            display: inline-block;
                            padding: 10px 20px;
                            font-size: 16px;
                            color: #ffffff;
                            background-color: #2E6FA7;
                            text-decoration: none;
                            border-radius: 5px;
                            margin-top: 20px;
                        }
                        ul {
                            list-style-type: disc;
                            margin-left: 20px;
                        }
                        .footer {
                            margin-top: 30px;
                            font-size: 12px;
                            color: #777777;
                            text-align: center;
                        }
                        .warning {
                            color: #FF0000;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Recuperação de Senha</h2>
                        <p>Olá, <strong>' . $data['name'] . '</strong></p>
                        <p>Recebemos uma solicitação para redefinir sua senha de acesso ao sistema. Para garantir a segurança da sua conta, por favor, siga as instruções abaixo para criar uma nova senha.</p>
                        
                        <p><strong>Informações da conta:</strong></p>
                        <ul>
                            <li><strong>Nome:</strong> ' . $data['name'] . '</li>
                            <li><strong>E-mail:</strong> ' . $email . '</li>
                            <li><strong>Data da solicitação:</strong> ' . date("d/m/Y H:i") . '</li>
                        </ul>
                        
                        <p>Para criar uma nova senha, clique no botão abaixo:</p>
                        <p><a href="' . BASE_URL . 'views/new-password.php?chave=' . $chave_recuperar_senha . '" target="_blank" class="button">Criar Nova Senha</a></p>

                        <p>Se você não solicitou a redefinição de senha, por favor, ignore este e-mail. Sua senha atual permanecerá inalterada.</p>
                        
                        <h3>Dicas de Segurança</h3>
                        <ul>
                            <li>Crie uma senha segura, utilizando uma combinação de letras maiúsculas, minúsculas, números e símbolos.</li>
                            <li>Evite reutilizar senhas antigas ou utilizar informações pessoais fáceis de adivinhar, como datas de nascimento ou nomes.</li>
                            <li>Troque sua senha regularmente e não a compartilhe com outras pessoas.</li>
                        </ul>
                        
                        <p class="warning">Atenção: Este é um e-mail automático. Não responda.</p>
                        
                        <div class="footer">
                            <p>&copy; ' . date("Y") . ' ' . NAME . '. Todos os direitos reservados.</p>
                        </div>
                    </div>
                </body>
                </html>
             ';


    if (!$mail->send()) {
        echo 'Erro ao enviar o email!';
        exit;
    } else {
        echo 'Email enviado com sucesso!';
        exit;
    }
}
echo 'Erro!';
