<?php
require_once('../../../config.php');

$limit = 20; // Limite de resultados por página
$page = isset($_POST['page']) && $_POST['page'] > 1 ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;

$query = "
SELECT * FROM logradouros 
LEFT JOIN proprietarios_tabulares ON logradouros.log_tabulado = proprietarios_tabulares.id_tab
WHERE log_status = 1";

// Filtro por ID de procedimento
if (!empty($_POST['id'])) {
  $query .= " AND log_procedimento_id = :id";
} else {
  echo '<div><label class="text-secondary">Ops! Nada encontrado</label></div>';
  exit;
}

// Filtro por busca (query)
if (!empty($_POST['query'])) {
  $search = '%' . str_replace(' ', '%', $_POST['query']) . '%';
  $query .= " AND log_logradouro LIKE :search";
}

$query .= ' ORDER BY log_logradouro ASC';

// Executando consulta para obter o total de registros
$statement = $db->prepare($query);
if (!empty($_POST['id'])) $statement->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
if (!empty($_POST['query'])) $statement->bindValue(':search', $search, PDO::PARAM_STR);
$statement->execute();
$total_data = $statement->rowCount();

// Consulta com paginação aplicada
$filter_query = $query . " LIMIT $start, $limit";
$statement = $db->prepare($filter_query);
if (!empty($_POST['id'])) $statement->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
if (!empty($_POST['query'])) $statement->bindValue(':search', $search, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll();

$output = '';
if ($total_data > 0) {
  foreach ($result as $row) {


    $output .= '
  <div class="card p-3 mb-3 shadow-sm">
    <div class="row align-items-center">
      <div class="col-md-4 col-xl-5">
        ' . $row['log_logradouro'] . '<br>
        ' . $row['log_numero_ini'] . ' ' . $row['log_numero_ini'] .'<br>
      </div>
      <div class="col-md-6 col-xl-5">
        ' . $row['nome'] . '
      </div>
      <div class="col-md-3 col-xl-2 text-right">
        <div class="btn-group btn-group-sm" role="group" aria-label="Ações">
          <button id="' . $row['log_id'] . '" type="button" title="Editar"
            class="btn btn-outline view_data_proprietarios">
            <i class="fa fa-pencil-square-o text-secondary" aria-hidden="true"></i>
          </button>
          <button type="button" title="Excluir" 
            class="btn btn-outline" 
            onclick="deletarProprietario(' . $row['log_id'] . ',0)">
            <i class="fa fa-window-close text-danger" aria-hidden="true"></i>
          </button>';
    $output .= '
        </div>
      </div>
    </div>
  </div>';
  }
  $output .= '<div class="text-muted small"><i>' . $total_data . '</i> imóveis(s)</div>';
  // Paginação
  $total_pages = ceil($total_data / $limit);
  $output .= '<nav><ul class="pagination justify-content-center">';
  for ($i = 1; $i <= $total_pages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    $output .= '<li class="page-item ' . $active . '">
                        <a class="page-link" href="#" data-page_number="' . $i . '">' . $i . '</a>
                    </li>';
  }
  $output .= '</ul></nav>';
} else {
  $output .= '<div><label class="text-secondary card p-2">Nenhum imóvel encontrado</label></div>';
}

echo $output;
