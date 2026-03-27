<?php

include_once('../config.php');

$limit = '25';
$page = 1;
if ($_POST['page'] > 1) {
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
} else {
  $start = 0;
}

$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

$query = "
SELECT * FROM tb_notifications_painel
WHERE not_user=" . $id . "
and not_status = 1 
";

$query .= ' ORDER BY not_date DESC ';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';
$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output = '<label class="badge badge-inverse-info">' . $total_data . ' - Notificações</label>';
$status = '';
if ($total_data > 0) {
  $output .= '
  <div class="card-block table-border-style">
  <div class="table-responsive">
  <table class="table table-hover">
    <thead class="thead-light">
      <tr>
        <th class="text-left">Notificação</th>
        <th class="text-left">Data/Hora</th>
        <th class="text-left">Ações</th>
      </tr>
    </thead>';

  foreach ($result as $row) {
    if ($row['not_leitura'] == 0) {
      $i = '<span class="label label-inverse-success">Lida em ' . date("d/m/Y H:i:s", strtotime($row['not_leitura_date'])) . '</span>';
      $c = 'text-muted';
    } else {
      $i = '';
      $c = 'h6';
    }
    $output .= '
    <tbody> 
    <tr class="' . $c . '">
       <td class="text-left"><b>'.$row['not_tipo'].'</b> - ' . $row['not_titulo'] . ' <br> ' . $i . '</td>
       <td class="text-left">' . date("d/m/Y H:i:s", strtotime($row['not_date'])) . '</td>
       <td class="text-left">
         <a href="' . BASE_URL . 'notifications/view/' . $row['not_codigo'] . '" class="btn waves-effect waves-light btn-info btn-square"><i class="ti-eye"></i></a>
       </td> 
    </tr>
    </tbody>
   ';
  }
  $output .= '
  </table>
  </div>
</div>
';
} else {

  $output .= '
   <br>
        <div class="label label-default float-left m-3">Nenhuma notificação!</div>

  ';
}
$output .= '

<br />
<div align="center">
  <ul class="pagination pagination-sm">
';

$total_links = ceil($total_data / $limit);
$previous_link = '';
$next_link = '';
$page_link = '';

$page_array = array();

if ($total_links > 4) {
  if ($page < 5) {
    for ($count = 1; $count <= 5; $count++) {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  } else {
    $end_limit = $total_links - 5;
    if ($page > $end_limit) {
      $page_array[] = 1;
      $page_array[] = '...';
      for ($count = $end_limit; $count <= $total_links; $count++) {
        $page_array[] = $count;
      }
    } else {
      $page_array[] = 1;
      $page_array[] = '...';
      for ($count = $page - 1; $count <= $page + 1; $count++) {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
} else {
  for ($count = 1; $count <= $total_links; $count++) {
    $page_array[] = $count;
  }
}

for ($count = 0; $count < count($page_array); $count++) {
  if ($page == $page_array[$count]) {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">' . $page_array[$count] . ' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if ($previous_id > 0) {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Anterior</a></li>';
    } else {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Anterior</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if ($next_id >= $total_links) {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Próximo</a>
      </li>
        ';
    } else {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Próximo</a></li>';
    }
  } else {
    if ($page_array[$count] == '...') {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    } else {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $page_array[$count] . '">' . $page_array[$count] . '</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;
