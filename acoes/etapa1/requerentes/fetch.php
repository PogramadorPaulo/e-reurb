
<?php
require_once('../../../config.php');

$limit = '10'; // Limite de resultados por página
$page = isset($_POST['page']) && $_POST['page'] > 1 ? $_POST['page'] : 1;

if ($page > 1) {
  $start = (($page - 1) * $limit);
} else {
  $start = 0;
}


$query = "SELECT * FROM requerentes WHERE 1=1 AND status_requente=1";
if ($_POST['id'] != '') {
  $query .= " AND id_procedimento = " . $_POST['id'] . " ";
} else {
  $output .= '
  <div>
   <label class="text-secondary">Ops!Nada encontrado</label>
  <div>
  ';
  exit;
}

if ($_POST['query'] != '') {
  $query .= ' AND nome LIKE "%' . str_replace(' ', '%', $_POST['query']) . '%"  ';
}


$query .= ' ORDER BY nome ';

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();
$output = '';
$status = '';
$icone = '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
if ($total_data > 0) {

  foreach ($result as $row) {

    $output .= '
         <div class="card p-2">
                <div class="row">
                    <div class="col-md-9">
                        <b>' . $row['nome'] . '</b><br>
                        <b>CPF/CNPJ: </b>' . $row['cpf'] . '' . $row['cnpj'] . '<br>
                    </div>
                    <div class="col-md-3">
                        <button id="' . $row['id_requerente'] . '" type="submit" class="btn btn-outline-info btn-sm view_data_requerente">
                          <i class="fa     fa-pencil-square-o" aria-hidden="true"></i> Editar</button>
                         <button class="btn btn-outline-danger btn-sm" onclick="deletarRequerente(' . $row['id_requerente'] . ',0)">
                          <i class="fa fa-window-close" aria-hidden="true"></i> Excluir
                        </button>
                    </div>
                </div>
        </div>
    ';
  }
  $output .= '<div class="text-muted"><span class="border rounded p-1">' . $total_data . '</span> requerente(s)</div>';
} else {
  $output .= '
  <div>
  <label class="text-secondary card p-2">Nenhum registro encontrado</label>
  <div>
  ';
}

echo $output;
