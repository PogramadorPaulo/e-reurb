
<?php
require_once('../../config.php');

$limit = '50';
$page = 1;
if ($_POST['page'] > 1) {
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
} else {
  $start = 0;
}

$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

$query = "SELECT * FROM tb_atividades_usuarios WHERE atividade_user = $id ";

if ($_POST['data_ini'] != 0 && $_POST['data_fin'] != 0) {
  $data_inicio = date("Y-m-d H:i", strtotime($_POST['data_ini']));
  $data_fim = date("Y-m-d H:i", strtotime($_POST['data_fin']));

  $query .= " AND atividade_data BETWEEN '$data_inicio' AND '$data_fim'";
}




$query .= ' ORDER BY atividade_data DESC ';

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();
$output = '';
$output .= '

   <div class="card  text-muted">
      ' . $total_data . ' - Atividades
   </div>

';
$status = '';
$icone = '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
if ($total_data > 0) {

  foreach ($result as $row) {

    $output .= '
    <div>
      <label>' . $row['atividade_name'] . '</label>         
      <i> ' . date("d/m/Y H:i", strtotime($row['atividade_data'])) . '</i>      
    </div>
 
    ';
  }

} else {
  $output .= '
  <div>
  <label class="text-secondary card p-2">Nenhuma atividade encontrada</label>
  <div>
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
      <a class="page-link" href="">' . $page_array[$count] . ' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if ($previous_id > 0) {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Anterior</a></li>';
    } else {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="">Anterior</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if ($next_id >= $total_links) {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="">Próximo</a>
      </li>
        ';
    } else {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Próximo</a></li>';
    }
  } else {
    if ($page_array[$count] == '...') {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="">...</a>
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
