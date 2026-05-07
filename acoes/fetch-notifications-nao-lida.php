<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once('../config.php');
/** @var PDO $db */

$token = !empty($_SESSION['token']) ? $_SESSION['token'] : null;

if (!$token) {
  echo '<div class="label label-danger m-3">Sessão expirada ou inválida.</div>';
  exit;
}

$user = $db->prepare("SELECT id FROM users WHERE token = :token");
$user->bindValue(":token", $token);
$user->execute();
$id = $user->fetchColumn();

if (!$id) {
  echo '<div class="label label-danger m-3">Usuário não localizado.</div>';
  exit;
}

$query = "
SELECT * FROM tb_notifications_painel
WHERE not_user = :id
and not_status = 1
and not_leitura = 1
";

$query .= ' ORDER BY not_date DESC ';

$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$total_data = $statement->rowCount();
$result = $statement->fetchAll();

$output = '<div class="label label-danger m-3">' . $total_data . ' - Notificações novas</div>';
$status = '';
if ($total_data > 0) {
  $output .= '<ul class="notification-list">';
  foreach ($result as $row) {
    $titulo = htmlspecialchars($row['not_titulo'], ENT_QUOTES, 'UTF-8');
    $codigo = rawurlencode($row['not_codigo']);

    if ($row['not_leitura'] == 0) {
      $i = '<div class="label label-inverse-success float-right">Lida em ' . date("d/m/Y H:i:s", strtotime($row['not_leitura_date'])) . '</div>';
    } else {
      $i = 'Não lida';
    }
    $output .= '
    <li class="waves-effect waves-light">
    <div class="media">
      <div class="media-body">
             <a href="' . BASE_URL . 'notifications/view/' . $codigo . '">
                <h5 class="notification-user">' . $titulo . '</h5>
                <span class="notification-time">' . date("d/m/Y H:i:s", strtotime($row['not_date'])) . '</span>
                ' . $i . '
            </a>
      </div>
    </div>
   </li>
    ';
  }
  $output .= '</ul>';
} else {

  $output .= '
        <div class="label label-default m-3">Nenhuma notificação!</div>

  ';
}
echo $output;
