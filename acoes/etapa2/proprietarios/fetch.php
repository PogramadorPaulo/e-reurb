<?php
require_once('../../../config.php');

$limit = 20; // Limite de resultados por página
$page = isset($_POST['page']) && $_POST['page'] > 1 ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;

// Filtro por ID de procedimento
if (empty($_POST['id'])) {
  echo '<div><label class="text-secondary">Ops! Nada encontrado</label></div>';
  exit;
}


$query = "SELECT * FROM tb_matriculasproprietarios 
WHERE id_procedimento =" . $_POST['id'] . " AND pro_status = 1 ";


// Filtro por busca (query)
if (!empty($_POST['query'])) {
  $search = '%' . str_replace(' ', '%', $_POST['query']) . '%';
  $query .= " AND nome LIKE :search";
}

$query .= ' ORDER BY nome';

// Executando consulta para obter o total de registros
$statement = $db->prepare($query);
if (!empty($_POST['query'])) $statement->bindValue(':search', $search, PDO::PARAM_STR);
$statement->execute();
$total_data = $statement->rowCount();

// Consulta com paginação aplicada
$filter_query = $query . " LIMIT $start, $limit";
$statement = $db->prepare($filter_query);
if (!empty($_POST['query'])) $statement->bindValue(':search', $search, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll();

$output = '';
if ($total_data > 0) {
  foreach ($result as $row) {
    $output .= '
            <div class="card p-2">
                <div class="row">
                    <div class="col-md-9">
                        <b>' . $row['nome'] . '</b><br>
                        <b>CPF/CNPJ: </b>' . $row['cpf'] . ' ' . $row['cnpj'] . '<br>
                    </div>
                    <div class="col-md-3">
                        <button id="' . $row['id'] . '" type="submit" class="btn btn-outline-info btn-sm view_data_ProprietarioMatricula">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deletarProprietarioMatricula(' . $row['id'] . ',0)">
                          <i class="fa fa-window-close" aria-hidden="true"></i> Excluir
                        </button>
                    </div>
                </div>
            </div>';
  }

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
  $output .= '<div><label class="text-secondary card p-2">Nenhum proprietário encontrado</label></div>';
}

echo $output;
