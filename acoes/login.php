<?php
require_once('../config.php');
session_start();

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
// verifica se foi enviado o email e senha
if (empty($email) && empty($password)) {
    $response = array(
        'status' => 'info',
        'tittle' => 'Atenção!',
        'message' => 'Informe seu e-mail e senha para fazer o login.',
        'icon' => 'info',
    );
    echo json_encode($response);
    exit;
}
// verifica se existe o email cadastrado
$sql_consulta = "SELECT email FROM users WHERE email=:email";
$sql_consulta = $db->prepare($sql_consulta);
$sql_consulta->bindValue(':email', trim($email));
$sql_consulta->execute();
if ($sql_consulta->rowCount() < 1) {
    $response = array(
        'status' => 'info',
        'tittle' => 'Atenção!',
        'message' => 'E-mail: <span class="text-info">' . $email . '</span> não está cadastrado!<br> Clique em Cadastrar-se',
        'icon' => 'info',
    );
    echo json_encode($response);
    exit;
}

// verifica se está inativo
$sql_inativo = "SELECT * FROM users WHERE email=:email AND password=:password";
$sql_inativo = $db->prepare($sql_inativo);
$sql_inativo->bindValue(':email', trim($email));
$sql_inativo->bindValue(':password', md5($password));
$sql_inativo->execute();
if ($sql_inativo->rowCount() > 0) {
    $data = $sql_inativo->fetch();

    if ($data['status'] == 0) {
        $response = array(
            'status' => 'warning',
            'tittle' => 'Usuário Inativo',
            'message' => 'Seu usuário foi <b class="text-danger">inativado!</b>',
            'icon' => 'warning',
        );
        echo json_encode($response);
        exit;
    } else if ($data['status'] == 2) {
        /* Gera o token de ativação  */
        $token_ativacao = password_hash($data['id'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET token_ativacao=:token_ativacao WHERE id =:id";
        $sql = $db->prepare($sql);
        $sql->bindValue(':token_ativacao', $token_ativacao);
        $sql->bindValue(':id', $data['id']);
        $sql->execute();

        /* Envia o token de ativação no e-mail do usuário */
        require_once '../PHPMailer-master/PHPMailerAutoload.php';
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
        $mail->FromName = DESCRICAO;
        $mail->AddAddress('' . $data['email'] . '', 'Ativar seu usuário');

        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Ativar seu usuário - " . DESCRICAO;

        $mail->Body =
            '
             Segue as informações de usuário:<br>
             Nome : <b>' . $data['name'] . '</b><br>
             E-mail : <b>' . $data['email'] . '</b><br>
             Data : ' . date("d/m/Y H:i") . '<br>	
             <h2>
                 Para ativar seu usuário acesse o link clicando 
                 <a href="' . BASE_URL . 'views/active-user.php?token=' . $token_ativacao . '" target="_blank">aqui</a>
             </h2>
             <br>
             <br>
             Atenção ! Este e-mail é enviado automaticamente não responder.<br>
     ';

        if (!$mail->send()) {
            $response = array(
                'status' => 'warning',
                'tittle' => 'Ativar Usuário',
                'message' => '
                    Não foi possível enviar o link de ativação no seu e-mail<br>
                    <span class="text-info">' . $data['email'] . '</span><br>
                    <p>Tente novamente mais tarde!</p>
                    
             ',
                'icon' => 'warning',
            );
            echo json_encode($response);
            exit;
        } else {
            $response = array(
                'status' => 'info',
                'tittle' => 'Ativar Usuário',
                'message' => '
                   Acesse seu e-mail <span class="text-info">' . $data['email'] . '</span> e clique no link para ativar seu usuário.',
                'icon' => 'info',
            );
            echo json_encode($response);
            exit;
        }
    }
}

// verifica o email e senha 
$sql = "SELECT * FROM users WHERE email=:email AND password=:password AND status = 1";
$sql = $db->prepare($sql);
$sql->bindValue(':email', trim($email));
$sql->bindValue(':password', md5($password));
$sql->execute();

if ($sql->rowCount() > 0) {
    $data = $sql->fetch();

    $token = md5(time() . rand(0, 999) . $data['id'] . time());
    // atualiza o token 
    $sql = "UPDATE users SET token =:token WHERE id =:id";
    $sql = $db->prepare($sql);
    $sql->bindValue(':token', $token);
    $sql->bindValue(':id', $data['id']);
    $sql->execute();
    $_SESSION['token'] = $token;
    $_SESSION['uid'] = $data['id'];

    /// logs

    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }


    $ip = get_client_ip();
    $ipdat_json = @file_get_contents("http://www.geoplugin.net/json.gp?ip=" . urlencode($ip));
    $ipdat = $ipdat_json ? @json_decode($ipdat_json) : null;

    $country = '';
    $city = '';
    $continent = '';
    $latitude = '';
    $longitude = '';

    if (is_object($ipdat)) {
        $country = isset($ipdat->geoplugin_countryName) ? $ipdat->geoplugin_countryName : '';
        $city = isset($ipdat->geoplugin_city) ? $ipdat->geoplugin_city : '';
        $continent = isset($ipdat->geoplugin_continentName) ? $ipdat->geoplugin_continentName : '';
        $latitude = isset($ipdat->geoplugin_latitude) ? $ipdat->geoplugin_latitude : '';
        $longitude = isset($ipdat->geoplugin_longitude) ? $ipdat->geoplugin_longitude : '';
    }

    $local = '';
    $local .= ($country !== '' ? $country : 'Desconhecido') . '/';
    $local .= ($city !== '' ? $city : 'Desconhecido') . '/';
    $local .= ($continent !== '' ? $continent : 'Desconhecido') . '/';
    $local .= 'Latitude:' . ($latitude !== '' ? $latitude : 'N/A');
    $local .= 'Longitude:' . ($longitude !== '' ? $longitude : 'N/A');

    $sql = $db->prepare("insert into tb_users_logs (id_user, date_login, ip, local) values(:id_user, :date_login, :ip, :local)");
    $sql->bindValue(":id_user", $data['id']);
    $sql->bindValue(":date_login", date('Y-m-d H:i:s'));
    $sql->bindValue(":ip", get_client_ip());
    $sql->bindValue(":local", $local);
    $sql->execute();

    $atividade = $db->prepare(
        "insert into 
    tb_atividades_usuarios 
    (
    atividade_user,
    atividade_name,
    atividade_data
    ) 

    values(
    :atividade_user,
    :atividade_name,
    :atividade_data)
    
    "
    );

    $atividade->bindValue(":atividade_user", $data['id']);
    $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
    $atividade->bindValue(":atividade_name", 'Fez o login. Local:' . $local);
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Login efetuado com sucesso. Aguarde...<br',
        'icon' => 'success',
    );
} else {

    $response = array(
        'status' => 'warning',
        'tittle' => 'Atenção!',
        'message' => 'Usuário e senha não confere',
        'icon' => 'warning',
    );
}
echo json_encode($response);
