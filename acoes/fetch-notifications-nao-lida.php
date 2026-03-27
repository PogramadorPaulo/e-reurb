<?php

include_once('../config.php');
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$query = "
SELECT * FROM tb_notifications_painel
WHERE not_user=$id 
and not_status = 1 
and not_leitura = 1
";

$query .= ' ORDER BY not_date DESC ';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output = '<label class="label label-danger ml-3">' . $total_data . ' - Notificações nova</label>';
$status = '';
if ($total_data > 0) {
  foreach ($result as $row) {
    if ($row['not_leitura'] == 0) {
      $i = '<div class="label label-inverse-success float-right">Lida em ' . date("d/m/Y H:i:s", strtotime($row['not_leitura_date'])) . '</div>';
    } else {
      $i = 'Não lida';
    }
    $output .= '
    <li class="waves-effect waves-light">
    <div class="media">
      <div class="media-body">
             <a href="' . BASE_URL . 'notifications/view/' . $row['not_codigo'] . '">
                <h5 class="notification-user">' . $row['not_titulo'] . '</h5>
                <span class="notification-time">' . date("d/m/Y H:i:s", strtotime($row['not_date'])) . '</span>
                ' . $i . '
            </a>
      </div>
    </div>
   </li>
    ';
  }
} else {

  $output .= '
   <br>
        <div class="label label-default float-left m-3">Nenhuma notificação!</div>

  ';
}
echo $output;
