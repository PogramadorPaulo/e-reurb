<?php
session_start();
require_once('../../../config.php');

// Definição de variáveis
$limit = 30;
$page = filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT) ?: 1;
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$idEtapa = 1; // Alterado para etapa1
$queryParam = filter_input(INPUT_POST, 'query', FILTER_DEFAULT) ?? '';

$start = ($page - 1) * $limit;

// Query base
$query = "
    SELECT * FROM tb_etapas_anexos 
      LEFT JOIN users ON tb_etapas_anexos.anexo_user = users.id
    WHERE anexo_prc = :id 
    AND anexo_etapa = :etapa
    AND anexo_status = 1
";

if (!empty($queryParam)) {
  $query .= ' AND anexo_titulo LIKE :query';
}

$query .= ' ORDER BY anexo_cadastro DESC';
$filter_query = $query . ' LIMIT :start, :limit';

// Obter total de registros
$totalStatement = $db->prepare($query);
$totalStatement->bindValue(':id', $id, PDO::PARAM_INT);
$totalStatement->bindValue(':etapa', $idEtapa, PDO::PARAM_INT);
if (!empty($queryParam)) {
  $totalStatement->bindValue(':query', "%$queryParam%", PDO::PARAM_STR);
}
$totalStatement->execute();
$total_data = $totalStatement->rowCount();

// Executar consulta paginada
$statement = $db->prepare($filter_query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->bindValue(':etapa', $idEtapa, PDO::PARAM_INT);
$statement->bindValue(':start', $start, PDO::PARAM_INT);
$statement->bindValue(':limit', $limit, PDO::PARAM_INT);
if (!empty($queryParam)) {
  $statement->bindValue(':query', "%$queryParam%", PDO::PARAM_STR);
}
$statement->execute();
$result = $statement->fetchAll();

// HTML para exibição
$output = '';

if ($total_data > 0) {
  $canDelete = hasPermission($_SESSION['uid'], 'processo_1etapa_excluir_documento', $db);
  $output .= '<div class="row g-3">'; // Usando g-3 para espaçamento entre colunas
  foreach ($result as $row) {
    $icone = getIcone($row['anexo_arquivo_ext']);
    $arquivoUrl = BASE_URL . 'assets/documentos/' . htmlspecialchars($row['anexo_arquivo']);

    // Valida dados
    $titulo = !empty($row['anexo_titulo']) ? htmlspecialchars($row['anexo_titulo']) : 'Sem título';
    $formato = !empty($row['anexo_arquivo_ext']) ? htmlspecialchars(strtoupper($row['anexo_arquivo_ext'])) : 'Indefinido';
    $dataCadastro = !empty($row['anexo_cadastro']) ? date("d/m/Y H:i", strtotime($row['anexo_cadastro'])) : 'Data não disponível';
    $usuario = !empty($row['name']) ? htmlspecialchars($row['name']) : 'Usuário não disponível';

    // Conteúdo do Tooltip em HTML
    $tooltipContent = "Data: $dataCadastro Usuário: $usuario";

    $output .= '
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="cardDocumento">
            <a href="' . $arquivoUrl . '" target="_blank" aria-label="Abrir ' . $titulo . '">
                <div class="mb-1">' . $icone . '</div>
                <label>' . $titulo . '</label>
            </a>
            <div class="text-muted small d-flex align-items-center justify-content-center mt-2">
                Formato: ' . $formato . '
                <i class="fa fa-info-circle text-muted ml-1" 
                   data-toggle="tooltip" 
                   data-html="true" 
                   title="' . htmlspecialchars($tooltipContent) . '"></i>
            </div>';
    if ($canDelete) {
      $output .= '
                              <button class="btn delete-document-etapa1" id="delete-document" 
                    title="Excluir" 
                    data-id="' . $row['anexo_id'] . '"
                    aria-label="Excluir ' . $titulo . '">X</button>';
    }
    $output .= '       
           
        </div>
    </div>';
  }
  $output .= '</div>';
  $output .= '<div class="text-muted small p-2 mb-1">' . $total_data . ' documento(s) encontrado(s)</div>';
} else {
  $output .= '<div class="text-center my-4"><label class="text-secondary">Nenhum documento encontrado</label></div>';
}

function getIcone($extensao)
{
  $icones = [
    'docx' => '<i class="fa fa-file-word-o text-primary fa-2x"></i>',
    'pdf'  => '<i class="fa fa-file-pdf-o text-danger fa-2x"></i>',
    'xlsx' => '<i class="fa fa-file-excel-o text-success fa-2x"></i>',
    'jpeg' => '<i class="fa fa-file-image-o text-warning fa-2x"></i>',
    'jpg'  => '<i class="fa fa-file-image-o text-warning fa-2x"></i>',
    'png'  => '<i class="fa fa-file-image-o text-warning fa-2x"></i>',
    'zip'  => '<i class="fa fa-file-archive-o text-secondary fa-2x"></i>',
    'rar'  => '<i class="fa fa-file-archive-o text-secondary fa-2x"></i>',
  ];
  return $icones[strtolower($extensao)] ?? '<i class="fa fa-file-text-o text-secondary fa-2x"></i>';
}

$output .= '<br /><div class="d-flex justify-content-center"><ul class="pagination pagination-sm">';

// Função para gerar array de páginas com pontos de suspensão
function generatePageArray($current_page, $total_pages)
{
  $page_array = [];

  if ($total_pages <= 4) {
    for ($i = 1; $i <= $total_pages; $i++) {
      $page_array[] = $i;
    }
  } else {
    if ($current_page <= 4) {
      for ($i = 1; $i <= 5; $i++) {
        $page_array[] = $i;
      }
      $page_array[] = '...';
      $page_array[] = $total_pages;
    } elseif ($current_page > $total_pages - 4) {
      $page_array[] = 1;
      $page_array[] = '...';
      for ($i = $total_pages - 4; $i <= $total_pages; $i++) {
        $page_array[] = $i;
      }
    } else {
      $page_array[] = 1;
      $page_array[] = '...';
      for ($i = $current_page - 1; $i <= $current_page + 1; $i++) {
        $page_array[] = $i;
      }
      $page_array[] = '...';
      $page_array[] = $total_pages;
    }
  }

  return $page_array;
}

$total_pages = ceil($total_data / $limit);
$page_array = generatePageArray($page, $total_pages);

foreach ($page_array as $page_number) {
  if ($page_number == '...') {
    $output .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
  } elseif ($page_number == $page) {
    $output .= '<li class="page-item active"><a class="page-link" href="#">' . $page_number . '</a></li>';
  } else {
    $output .= '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $page_number . '">' . $page_number . '</a></li>';
  }
}

$output .= '</ul></div>';

echo $output;
?>
<!-- Tooltips Bootstrap 4 (jQuery) -->
<script>
$(function () {
  $('#conteudo_anexos [data-toggle="tooltip"]').tooltip({ html: true, placement: 'top' });
});
</script>