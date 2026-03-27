
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

$limit = '30'; // Limite de resultados por página
$page = isset($_POST['page']) && $_POST['page'] > 1 ? $_POST['page'] : 1;

$start = ($page > 1) ? (($page - 1) * $limit) : 0;

$query = "
    SELECT u.id, u.name, u.email, u.status, u.admin, p.name AS permission_name
    FROM users u
    LEFT JOIN permission_groups p ON u.id_permission = p.id
    WHERE u.status = 1";

// Filtros
if (!empty($_POST['query'])) {
    $query .= ' AND u.name LIKE "%' . str_replace(' ', '%', $_POST['query']) . '%" ';
}


$query .= ' ORDER BY u.name DESC';

$filter_query = $query . ' LIMIT ' . $start . ', ' . $limit;

$statement = $db->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $db->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output = '<div class="text-muted mb-3">' . $total_data . ' - Processos encontrados</div>';

if ($total_data > 0) {


    foreach ($result as $row) {
        $prcNumber = !empty($row['numero_procedimento']) ? $row['numero_procedimento'] : 'N/A';
        $prcTipo = !empty($row['modalidade']) ? $row['modalidade'] : 'N/A';
        $nucleoNome = !empty($row['nucleo_nome']) ? htmlspecialchars($row['nucleo_nome'], ENT_QUOTES, 'UTF-8') : 'N/A';
        $prcDate = !empty($row['data_cad']) ? date("d/m/Y H:i", strtotime($row['data_cad'])) : 'N/A';
        $situacao = (int)$row['status']; // Verifica o status como inteiro
      
        $output .= '
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                    <h6 class="mb-0">Procedimento Nº ' . htmlspecialchars($prcNumber) . '</h6>
                    <span class="badge">' . htmlspecialchars($prcTipo) . '</span>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nome do Núcleo:</strong> ' . $nucleoNome . '</p>
                    <p class="mb-0"><strong>Data de Abertura:</strong> ' . $prcDate . '</p>
                    <hr class="my-3">
                    <div class="p-3 rounded bg-light">
                        <h6 class="text-primary"><i class="fa fa-history"></i> Último Histórico</h6>
                        <p>' . $historicoTexto . '</p>
                        ' . $justificativaTexto . '
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                      <span class="badge ' . $badgeClass . '">' . $statusTexto . '</span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <div class="btn-group" role="group" aria-label="Ações">';

        // Botões com base no status e permissões
        if ($situacao === 1 && $canCancel) {
            $output .= '
                            <a href="' . BASE_URL . 'processos/view/' . $row['cod_procedimento'] . '" class="btn btn-info btn-sm">
                                <i class="ti-pencil-alt"></i> Abrir
                            </a>
                            <button class="btn btn-danger btn-sm cancelarProcedimento" data-id="' . htmlspecialchars($row['cod_procedimento']) . '">
                                <i class="fa fa-ban" aria-hidden="true"></i> Cancelar
                            </button>';
        } elseif ($situacao === 0 && $canActivate) {
            $output .= '
                            <button class="btn btn-success btn-sm ativarProcedimento" data-id="' . htmlspecialchars($row['cod_procedimento']) . '">
                                <i class="fa fa-check-circle" aria-hidden="true"></i> Ativar
                            </button>';
        }

        $output .= '
                        </div>
                    </div>
                </div>
            </div>';
    }
} else {
    $output .= '<div class="alert alert-warning text-center">Nenhum processo encontrado</div>';
}

// Paginação
$total_links = ceil($total_data / $limit);
$page_array = [];
$previous_link = $next_link = '';
$page_link = '';

$output .= '<nav><ul class="pagination justify-content-center pagination-sm">';

if ($total_links > 4) {
    if ($page < 5) {
        for ($count = 1; $count <= 5; $count++) $page_array[] = $count;
        $page_array[] = '...';
        $page_array[] = $total_links;
    } else {
        $end_limit = $total_links - 5;
        if ($page > $end_limit) {
            $page_array[] = 1;
            $page_array[] = '...';
            for ($count = $end_limit; $count <= $total_links; $count++) $page_array[] = $count;
        } else {
            $page_array[] = 1;
            $page_array[] = '...';
            for ($count = $page - 1; $count <= $page + 1; $count++) $page_array[] = $count;
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
} else {
    for ($count = 1; $count <= $total_links; $count++) $page_array[] = $count;
}

foreach ($page_array as $count) {
    if ($page == $count) {
        $page_link .= '<li class="page-item active"><a class="page-link" href="#">' . $count . '</a></li>';
        $previous_id = $count - 1;
        $previous_link = $previous_id > 0
            ? '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Anterior</a></li>'
            : '<li class="page-item disabled"><a class="page-link">Anterior</a></li>';
        $next_id = $count + 1;
        $next_link = $next_id > $total_links
            ? '<li class="page-item disabled"><a class="page-link">Próximo</a></li>'
            : '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Próximo</a></li>';
    } else {
        $page_link .= $count === '...'
            ? '<li class="page-item disabled"><a class="page-link">...</a></li>'
            : '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $count . '">' . $count . '</a></li>';
    }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '</ul></nav>';

echo $output;
