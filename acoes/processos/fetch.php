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
include_once('../../config.php');

// Definição de limite de resultados e página
$limit = 20;
$page = isset($_POST['page']) && $_POST['page'] > 1 ? intval($_POST['page']) : 1;
$start = ($page - 1) * $limit;

// Verifica o perfil e o município vinculados ao usuário autenticado
$userQuery = $db->prepare("SELECT admin, user_municipio FROM users WHERE id = :userId");
$userQuery->bindValue(':userId', $userId, PDO::PARAM_INT);
$userQuery->execute();
$userData = $userQuery->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Usuário inválido.'
  ]);
  exit;
}

$isAdmin = (int)$userData['admin'] === 1;
$municipioFiltro = null;
$etapaAtualFiltro = null;
$percentualFiltro = isset($_POST['percentual']) ? $_POST['percentual'] : '';
$faixasPercentuais = [
  '0-25' => [0, 25],
  '26-50' => [26, 50],
  '51-70' => [51, 70],
  '71-99' => [71, 99],
  '100' => [100, 100]
];

if (isset($_POST['etapa_atual']) && $_POST['etapa_atual'] !== '') {
  $etapaAtualFiltro = filter_var($_POST['etapa_atual'], FILTER_VALIDATE_INT);
  if ($etapaAtualFiltro === false || $etapaAtualFiltro <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Etapa inválida.']);
    exit;
  }
}

if ($percentualFiltro !== '' && !isset($faixasPercentuais[$percentualFiltro])) {
  echo json_encode(['status' => 'error', 'message' => 'Faixa de porcentagem inválida.']);
  exit;
}

// Query base para buscar processos
$estatisticasSql = "
  SELECT
    ep.processo_id,
    COUNT(*) AS total_etapas,
    SUM(CASE WHEN ep.procedimento_status = 4 THEN 1 ELSE 0 END) AS etapas_concluidas,
    (
      SELECT ep_atual.etapa_id
      FROM etapas_procedimentos ep_atual
      INNER JOIN etapas_processo etapa_atual
        ON etapa_atual.etapa_processo_id = ep_atual.etapa_id
      WHERE ep_atual.processo_id = ep.processo_id
        AND ep_atual.procedimento_status <> 4
      ORDER BY etapa_atual.etapa_ordem ASC
      LIMIT 1
    ) AS etapa_atual_id
  FROM etapas_procedimentos ep
  GROUP BY ep.processo_id
";

$query = "
  SELECT
    procedures.*,
    tb_municipios.*,
    COALESCE(estatisticas.total_etapas, 0) AS total_etapas,
    COALESCE(estatisticas.etapas_concluidas, 0) AS etapas_concluidas,
    estatisticas.etapa_atual_id,
    CASE
      WHEN COALESCE(estatisticas.total_etapas, 0) = 0 THEN 0
      ELSE ROUND((estatisticas.etapas_concluidas / estatisticas.total_etapas) * 100, 2)
    END AS percentual_concluido
  FROM procedures
  INNER JOIN tb_municipios ON procedures.municipio = tb_municipios.municipio_id
  LEFT JOIN ($estatisticasSql) estatisticas ON estatisticas.processo_id = procedures.cod_procedimento
  WHERE 1=1
";

// Administradores podem escolher qualquer município; os demais ficam restritos ao vínculo da conta
if ($isAdmin) {
  if (isset($_POST['municipio_filtro']) && $_POST['municipio_filtro'] !== '') {
    $municipioFiltro = filter_var($_POST['municipio_filtro'], FILTER_VALIDATE_INT);
    if ($municipioFiltro === false || $municipioFiltro <= 0) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Município inválido.'
      ]);
      exit;
    }
  }
} else {
  $municipioFiltro = filter_var($userData['user_municipio'], FILTER_VALIDATE_INT);
  if ($municipioFiltro === false || $municipioFiltro <= 0) {
    echo json_encode([
      'status' => 'error',
      'message' => 'Usuário sem município válido associado.'
    ]);
    exit;
  }
}

if ($municipioFiltro !== null) {
  $query .= " AND procedures.municipio = :id_municipio";
}

// Adiciona filtros opcionais
if (!empty($_POST['query'])) {
  $query .= " AND nucleo_nome LIKE :query";
}
if (!empty($_POST['tipo'])) {
  $query .= " AND modalidade = :tipo";
}
if (!empty($_POST['data_inicial'])) {
  $query .= " AND data_cad >= :data_inicial";
}
if (!empty($_POST['data_final'])) {
  $query .= " AND data_cad <= :data_final";
}
if ($etapaAtualFiltro !== null) {
  $query .= " AND estatisticas.etapa_atual_id = :etapa_atual";
}
if ($percentualFiltro !== '') {
  $query .= " AND (CASE WHEN COALESCE(estatisticas.total_etapas, 0) = 0 THEN 0 ELSE ROUND((estatisticas.etapas_concluidas / estatisticas.total_etapas) * 100, 2) END) BETWEEN :percentual_minimo AND :percentual_maximo";
}

// Ordenação e Paginação
$query .= " ORDER BY procedures.id DESC";
$filter_query = $query . " LIMIT :start, :limit";

// Conta o total de registros sem paginação
$total_statement = $db->prepare($query);
if ($municipioFiltro !== null) {
  $total_statement->bindValue(':id_municipio', $municipioFiltro, PDO::PARAM_INT);
}
if (!empty($_POST['query'])) {
  $total_statement->bindValue(':query', '%' . str_replace(' ', '%', $_POST['query']) . '%', PDO::PARAM_STR);
}
if (!empty($_POST['tipo'])) {
  $total_statement->bindValue(':tipo', $_POST['tipo'], PDO::PARAM_STR);
}
if (!empty($_POST['data_inicial'])) {
  $total_statement->bindValue(':data_inicial', $_POST['data_inicial'], PDO::PARAM_STR);
}
if (!empty($_POST['data_final'])) {
  $total_statement->bindValue(':data_final', $_POST['data_final'], PDO::PARAM_STR);
}
if ($etapaAtualFiltro !== null) {
  $total_statement->bindValue(':etapa_atual', $etapaAtualFiltro, PDO::PARAM_INT);
}
if ($percentualFiltro !== '') {
  $total_statement->bindValue(':percentual_minimo', $faixasPercentuais[$percentualFiltro][0], PDO::PARAM_INT);
  $total_statement->bindValue(':percentual_maximo', $faixasPercentuais[$percentualFiltro][1], PDO::PARAM_INT);
}
$total_statement->execute();
$total_data = $total_statement->rowCount();

// Executa a query com paginação
$filter_statement = $db->prepare($filter_query);
$filter_statement->bindValue(':start', $start, PDO::PARAM_INT);
$filter_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
if ($municipioFiltro !== null) {
  $filter_statement->bindValue(':id_municipio', $municipioFiltro, PDO::PARAM_INT);
}
if (!empty($_POST['query'])) {
  $filter_statement->bindValue(':query', '%' . str_replace(' ', '%', $_POST['query']) . '%', PDO::PARAM_STR);
}
if (!empty($_POST['tipo'])) {
  $filter_statement->bindValue(':tipo', $_POST['tipo'], PDO::PARAM_STR);
}
if (!empty($_POST['data_inicial'])) {
  $filter_statement->bindValue(':data_inicial', $_POST['data_inicial'], PDO::PARAM_STR);
}
if (!empty($_POST['data_final'])) {
  $filter_statement->bindValue(':data_final', $_POST['data_final'], PDO::PARAM_STR);
}
if ($etapaAtualFiltro !== null) {
  $filter_statement->bindValue(':etapa_atual', $etapaAtualFiltro, PDO::PARAM_INT);
}
if ($percentualFiltro !== '') {
  $filter_statement->bindValue(':percentual_minimo', $faixasPercentuais[$percentualFiltro][0], PDO::PARAM_INT);
  $filter_statement->bindValue(':percentual_maximo', $faixasPercentuais[$percentualFiltro][1], PDO::PARAM_INT);
}
$filter_statement->execute();
$result = $filter_statement->fetchAll(PDO::FETCH_ASSOC);

// Gera o conteúdo da página
$output = '<div class="text-muted mb-3">' . $total_data . ' - Processos encontrados</div>';

if ($total_data > 0) {
  // Verifica permissões do usuário
  $canCancel = hasPermission($userId, 'processso_cancelar', $db);
  $canActivate = hasPermission($userId, 'processso_ativar', $db);

  foreach ($result as $row) {
    $prcMunicipio = $row['municipio_name'] ?: 'N/A';
    $prcMunicipioUF = $row['municipio_uf'] ?: 'N/A';
    $prcNumber = $row['numero_procedimento'] ?: 'N/A';
    $prcTipo = $row['modalidade'] ?: 'N/A';
    $nucleoNome = htmlspecialchars($row['nucleo_nome'], ENT_QUOTES, 'UTF-8') ?: 'N/A';
    $prcDate = $row['data_cad'] ? date("d/m/Y H:i", strtotime($row['data_cad'])) : 'N/A';
    $situacao = (int)$row['status'];

    $statusTexto = $situacao === 1 ? 'Ativo' : 'Cancelado';
    $badgeClass = $situacao === 1 ? 'badge-success' : 'badge-danger';

    // Buscar o último histórico
    $stmtHistorico = $db->prepare("SELECT h.h_date, h.h_name, h.h_justificativa FROM tb_etapa_historico h WHERE h.h_idProcesso = :cod_procedimento ORDER BY h.h_date DESC LIMIT 1");
    $stmtHistorico->bindValue(':cod_procedimento', $row['cod_procedimento'], PDO::PARAM_INT);
    $stmtHistorico->execute();
    $ultimoHistorico = $stmtHistorico->fetch(PDO::FETCH_ASSOC);

    $historicoTexto = $ultimoHistorico
      ? $ultimoHistorico['h_name'] . ' em ' . date("d/m/Y H:i", strtotime($ultimoHistorico['h_date']))
      : 'Sem histórico registrado';

    $justificativaTexto = !empty($ultimoHistorico['h_justificativa'])
      ? '<p class="mb-0"><strong>Justificativa:</strong> ' . htmlspecialchars($ultimoHistorico['h_justificativa'], ENT_QUOTES, 'UTF-8') . '</p>'
      : '';

    $totalEtapas = (int)$row['total_etapas'];
    $etapasConcluidas = (int)$row['etapas_concluidas'];
    $porcentagemConcluida = (float)$row['percentual_concluido'];

    // Calculando a cor com base na porcentagem de conclusão
    if ($porcentagemConcluida <= 25) {
      $colorClass = 'badge badge-danger'; // Vermelho para 0 a 50%
    } elseif ($porcentagemConcluida > 25 && $porcentagemConcluida < 50) {
      $colorClass = 'badge badge-warning'; // Laranja para 51 a 69%
    } elseif ($porcentagemConcluida > 50 && $porcentagemConcluida < 70) {
      $colorClass = 'badge badge-info'; // Laranja para 51 a 69%
    } elseif ($porcentagemConcluida >= 70 && $porcentagemConcluida < 100) {
      $colorClass = 'badge badge-primary'; // Laranja para 70% até 99%
    } elseif ($porcentagemConcluida == 100) {
      $colorClass = 'badge badge-success'; // Verde para 100%
      $statusTexto = 'Concluído';
      $badgeClass = 'badge-success';
    }


    $output .= '
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                <h6 class="mb-0">Procedimento Nº ' . htmlspecialchars($prcNumber) . '</h6>
                <span class="badge ' . $badgeClass . '">' . $statusTexto . '</span>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Município: ' . $prcMunicipio . ' - ' . $prcMunicipioUF . '</h6>
                <p><strong>Nome do Núcleo:</strong> ' . $nucleoNome . '</p>
                <p><strong>Data de Abertura:</strong> ' . $prcDate . '</p>
                <div class="float-right m-4"><strong>Conclusão:</strong> <span class="' . $colorClass . '">' . $porcentagemConcluida . '%</span> (' . $etapasConcluidas . ' de ' . $totalEtapas . ' etapas concluídas)</div>


                <hr class="my-3">
                <div class="p-3 rounded bg-light">
                    <h6 class="text-primary"><i class="fa fa-history"></i> Último Histórico</h6>
                    <p>' . $historicoTexto . '</p>
                    ' . $justificativaTexto . '
                </div>
            </div>
            <div class="card-footer">
                <div class="btn-group" role="group" aria-label="Ações">';
    if ($situacao === 1 && $canCancel) {
      $output .= '
                <a href="' . BASE_URL . 'processos/view/' . $row['cod_procedimento'] . '" class="btn btn-info btn-sm">
                    <i class="ti-pencil-alt"></i> Abrir
                </a>
                <button class="btn btn-danger btn-sm cancelarProcedimento" data-id="' . $row['cod_procedimento'] . '">
                    <i class="fa fa-ban"></i> Cancelar
                </button>';
    } elseif ($situacao === 0 && $canActivate) {
      $output .= '
                <button class="btn btn-success btn-sm ativarProcedimento" data-id="' . $row['cod_procedimento'] . '">
                    <i class="fa fa-check-circle"></i> Ativar
                </button>';
    }
    $output .= '</div></div></div></div>';
  }
} else {
  $output .= '<div class="alert alert-info text-center">Nenhum processo encontrado.</div>';
}
// Paginação
$total_pages = ceil($total_data / $limit);
$output .= '
<div align="center">
    <ul class="pagination justify-content-center pagination-sm">';
for ($i = 1; $i <= $total_pages; $i++) {
  $active = ($page == $i) ? ' active' : '';
  $output .= '
        <li class="page-item' . $active . '">
            <a class="page-link" href="javascript:void(0)" data-page_number="' . $i . '">' . $i . '</a>
        </li>';
}
$output .= '
    </ul>
</div>';

// Retorna o HTML gerado
echo $output;
