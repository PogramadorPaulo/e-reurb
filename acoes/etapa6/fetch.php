<?php
session_start();

// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['uid'])) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Sessão expirada ou inválida. Por favor, faça login novamente.'
  ]);
  exit;
}

$userId = $_SESSION['uid'];
require_once('../../config.php');

/**
 * Função para obter parâmetros do POST com sanitização básica.
 *
 * @param string $key     Nome do parâmetro.
 * @param mixed  $default Valor padrão se o parâmetro não existir.
 *
 * @return mixed Valor do parâmetro ou valor padrão.
 */

function getPostParam($key, $default = null)
{
  return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

// Obter parâmetros do POST com validação
$page = filter_var(getPostParam('page', 1), FILTER_VALIDATE_INT, [
  'options' => [
    'default' => 1,
    'min_range' => 1
  ]
]);

$limit = 20; // Limite de resultados por página
$start = ($page - 1) * $limit;

$id = getPostParam('id');
$queryParam = getPostParam('query');

// Se 'id' estiver vazio, retornar mensagem e encerrar
if (empty($id)) {
  echo '<div><label class="text-secondary">Ops! Nada encontrado</label></div>';
  exit;
}

// Construir a consulta base
$baseQuery = "FROM tb_etapas_anexos WHERE anexo_etapa = 6 AND anexo_status =1 AND anexo_prc=:anexo_prc";

// Inicializar array de parâmetros
$params = [
  ':anexo_prc' => $id
];

// Se 'query' estiver presente, adicionar filtro de busca
if (!empty($queryParam)) {
  $baseQuery .= " AND anexo_titulo LIKE :search";
  // Substituir espaços por '%' para buscas mais flexíveis
  $search = '%' . str_replace(' ', '%', $queryParam) . '%';
  $params[':search'] = $search;
}

try {
  // Consulta para contar o total de registros
  $countQuery = "SELECT COUNT(*) AS total " . $baseQuery;
  $stmt = $db->prepare($countQuery);
  foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
  }
  $stmt->execute();
  $countResult = $stmt->fetch();
  $total_data = $countResult['total'];

  // Se não houver dados, retornar mensagem e encerrar
  if ($total_data == 0) {
    echo '<div><label class="text-secondary">Nenhum documento encontrado</label></div>';
    exit;
  }

  // Consulta para obter os dados com paginação
  // Nota: LIMIT e OFFSET são convertidos para inteiros para evitar injeção de SQL
  $dataQuery = "SELECT * " . $baseQuery . " ORDER BY anexo_id DESC LIMIT :start, :limit";
  $stmt = $db->prepare($dataQuery);

  foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
  }

  // Bind dos parâmetros de paginação
  $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
  $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetchAll();

  // Inicializar variável de saída
  $output = '';

  // Verifica e formata a data para o padrão brasileiro
  function formatarDataBrasileira($data)
  {
    if (empty($data)) {
      return '';
    }
    try {
      $dateTime = new DateTime($data);
      return $dateTime->format('d/m/Y H:i');
    } catch (Exception $e) {
      return 'Data inválida';
    }
  }

  // Verificar permissões do usuário
  $canEdit = hasPermission($userId, 'processso_6etapaEditarDoc', $db);
  $canDelete = hasPermission($userId, 'processso_6etapaExcluirDoc', $db);

  // Loop para construir o HTML dos documentos
  foreach ($result as $row) {
    $doc_tipo = htmlspecialchars($row['anexo_titulo'], ENT_QUOTES, 'UTF-8');
    $doc_criacao = formatarDataBrasileira($row['anexo_cadastro']);
    $doc_atualizacao = formatarDataBrasileira($row['anexo_update']);

    $doc_id = (int)$row['anexo_id'];
    $pdf_url = BASE_URL . 'assets/documentos/' . htmlspecialchars($row['anexo_arquivo'], ENT_QUOTES, 'UTF-8');

    $output .= '
            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h6 class="mb-0">' . $doc_tipo . '</h6>
                    <div>
                        <a href="' . $pdf_url . '" target="_blank" class="btn btn-sm btn-light">
                            <i class="fa fa-file-pdf"></i> Abrir PDF
                        </a>';

    if ($canDelete) {
      $output .= '
                        <button class="btn btn-light btn-sm delete-document-etapa6" id="" 
                            title="Excluir documento" 
                            data-id="' . $doc_id . '"
                            aria-label="Excluir">
                            <i class="fa fa-trash"></i>
                        </button>';
    }
    $output .= '
                    </div>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1"><strong>Criação:</strong> ' . $doc_criacao . '</p>
                        <p class="mb-1"><strong>Última Atualização:</strong> ' . $doc_atualizacao . '</p>
                    </div>';

    if ($canEdit) {
      $output .= '
                    <div>
                        <button data-id="' . $doc_id . '" type="button" class="btn btn-outline-info btn-sm view_data_doc_etapa6">
                            <i class="fa fa-pencil-square-o"></i> Editar
                        </button>
                    </div>';
    }
    $output .= '
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-none d-md-block mt-3">
                            <iframe src="' . $pdf_url . '" width="100%" height="400px" frameborder="0" class="border rounded"></iframe>
                        </div>
                    </div>
                </div>
            </div>';
  }


  // Cálculo do total de páginas
  $total_pages = ceil($total_data / $limit);

  // Construção da paginação com links limitados
  $output .= '<nav><ul class="pagination justify-content-center">';

  // Limitar o número de links de paginação exibidos
  $max_links = 5;
  $start_link = max(1, $page - floor($max_links / 2));
  $end_link = min($total_pages, $start_link + $max_links - 1);

  // Ajustar o início caso esteja próximo do final
  $start_link = max(1, min($start_link, $total_pages - $max_links + 1));

  for ($i = $start_link; $i <= $end_link; $i++) {
    $active = ($i == $page) ? 'active' : '';
    $output .= '<li class="page-item ' . $active . '">
                        <a class="page-link" href="#" data-page_number="' . $i . '">' . $i . '</a>
                    </li>';
  }

  $output .= '</ul></nav>';
} catch (PDOException $e) {
  // Log do erro para análise posterior
  error_log("Database error: " . $e->getMessage());
  echo '<div><label class="text-secondary">Erro ao carregar documentos.</label></div>';
  exit;
}

// Exibir a saída final
echo $output;
