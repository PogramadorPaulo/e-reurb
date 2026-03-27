<?php
require_once('../../../config.php');

$limit = 20; // Limite de resultados por página
$page = isset($_POST['page']) && $_POST['page'] > 1 ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM proprietarios_tabulares WHERE status_tab = 1";

// Filtro por ID de procedimento
if (!empty($_POST['id'])) {
  $query .= " AND id_tab_procedimento = :id";
} else {
  echo '<div><label class="text-secondary">Ops! Nada encontrado</label></div>';
  exit;
}

// Filtro por busca (query)
if (!empty($_POST['query'])) {
  $search = '%' . str_replace(' ', '%', $_POST['query']) . '%';
  $query .= " AND nome LIKE :search";
}

$query .= ' ORDER BY nome';

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
    // Verificar se existem documentos anexados para o proprietário
    $doc_query = "SELECT COUNT(*) FROM tb_proprietarios_anexos WHERE anexo_proprietario = :id_proprietario AND anexo_status = 1";
    $doc_statement = $db->prepare($doc_query);
    $doc_statement->bindValue(':id_proprietario', $row['id_tab'], PDO::PARAM_INT);
    $doc_statement->execute();
    $has_documents = $doc_statement->fetchColumn() > 0; // Retorna true se existirem documentos anexados

    $isMarried = strtolower($row['estado_civil']) === 'casado';
    $estadoCivil = !empty($row['estado_civil']) ? '<b>' . $row['estado_civil'] . '</b><br>' : '';

    // Exibir mensagem obrigatória se estado_civil for "Casado" e conjuge_nome estiver vazio
    $conjugeNome = '';
    if ($isMarried && empty($row['conjuge_nome']) && empty($row['conjuge_cpf'])) {
      $conjugeNome = '<span class="text-danger"><b>Obrigatório:</b> Cadastre os dados do cônjuge</span><br>';
    } else if (!empty($row['conjuge_nome'])) {
      $conjugeNome = '<b>Cônjuge: </b>' . $row['conjuge_nome'] . '<br>';
    }

    $output .= '
  <div class="card p-3 mb-3 shadow-sm">
    <div class="row align-items-center">
      <div class="col-md-4 col-xl-4">
        <b>' . $row['nome'] . '</b><br>
        <b>CPF/CNPJ: </b>' . $row['cpf'] . ' ' . $row['cnpj'] . '<br>
      </div>
      <div class="col-md-6 col-xl-5">
        ' . $estadoCivil . $conjugeNome . '
      </div>
      <div class="col-md-3 col-xl-3 text-right">
        <div class="btn-group btn-group-sm" role="group" aria-label="Ações">
          <button id="' . $row['id_tab'] . '" type="button" title="Editar"
            class="btn btn-outline view_data_proprietarios">
            <i class="fa fa-pencil-square-o text-secondary" aria-hidden="true"></i>
          </button>
          <button type="button" title="Excluir" 
            class="btn btn-outline" 
            onclick="deletarProprietario(' . $row['id_tab'] . ',0)">
            <i class="fa fa-window-close text-danger" aria-hidden="true"></i>
          </button>';

    // Alterar o ícone e a cor do botão de upload dependendo da presença de documentos
    $uploadIconClass = $has_documents ? 'fa fa-file text-primary' : 'fa fa-cloud-upload text-primary';
    $output .= '<button id="' . $row['id_tab'] . '" type="button" title="Anexar documentos"
                  class="btn btn-outline modalUpload">
                  <i class="' . $uploadIconClass . '" aria-hidden="true"></i>
                </button>';

    if ($isMarried) {
      $output .= '
      <button id="' . $row['id_tab'] . '" type="button" title="Cônjuge"
        class="btn btn-outline view_data_proprietarios_conjuge">
        <i class="fa fa-user-plus text-success" aria-hidden="true"></i>
      </button>';
    }

    $output .= '
        </div>
      </div>
    </div>
  </div>';
  }
  $output .= '<div class="text-muted small"><i>' . $total_data . '</i> proprietário(s)</div>';
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
