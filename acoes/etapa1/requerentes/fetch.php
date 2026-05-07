
<?php
require_once('../../../config.php');
/** @var PDO $db */

$limit = 10; // Limite de resultados por página
$page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT);
$idProcedimento = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$busca = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_SPECIAL_CHARS);
$output = '';

if (empty($page) || $page < 1) {
  $page = 1;
}

$start = ($page - 1) * $limit;

$query = "SELECT * FROM requerentes WHERE 1=1 AND status_requente=1";
$params = array();

if (!empty($idProcedimento)) {
  $query .= " AND id_procedimento = :id ";
  $params[':id'] = $idProcedimento;
} else {
  $output .= '
  <div>
   <label class="text-secondary">Ops!Nada encontrado</label>
  <div>
  ';
  echo $output;
  exit;
}

if (!empty($busca)) {
  $query .= ' AND nome LIKE :busca ';
  $params[':busca'] = '%' . str_replace(' ', '%', $busca) . '%';
}


$query .= ' ORDER BY nome ';

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$statement = $db->prepare($query);
foreach ($params as $campo => $valor) {
  $statement->bindValue($campo, $valor);
}
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
foreach ($params as $campo => $valor) {
  $statement->bindValue($campo, $valor);
}
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();
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
