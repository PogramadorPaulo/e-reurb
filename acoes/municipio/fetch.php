<?php
include_once('../../config.php');
session_start();

// Definição de limite e página
$limit = 30;
$page = isset($_POST['page']) && $_POST['page'] > 1 ? intval($_POST['page']) : 1;
$start = ($page - 1) * $limit;

// Base da query
$query = "SELECT * FROM tb_municipios WHERE municipio_status = 1";

// Aplicação de filtro, se fornecido
if (!empty($_POST['query'])) {
    $query .= " AND municipio_name LIKE :query";
}

// Ordenação
$query .= " ORDER BY municipio_name ASC";

// Total de registros
$total_statement = $db->prepare($query);
if (!empty($_POST['query'])) {
    $total_statement->bindValue(':query', '%' . str_replace(' ', '%', $_POST['query']) . '%');
}
$total_statement->execute();
$total_data = $total_statement->rowCount();

// Paginação
$filter_query = $query . " LIMIT :start, :limit";
$filter_statement = $db->prepare($filter_query);

// Bind dos parâmetros
if (!empty($_POST['query'])) {
    $filter_statement->bindValue(':query', '%' . str_replace(' ', '%', $_POST['query']) . '%');
}
$filter_statement->bindValue(':start', $start, PDO::PARAM_INT);
$filter_statement->bindValue(':limit', $limit, PDO::PARAM_INT);

$filter_statement->execute();
$result = $filter_statement->fetchAll(PDO::FETCH_ASSOC);

// Início do output
$output = '
    <div class="card p-2 text-muted">
        ' . $total_data . ' - Cadastro(s) encontrados
    </div>
';

if ($total_data > 0) {
    foreach ($result as $row) {
        $logoUrl = 'assets/tema/images/' . htmlspecialchars($row['municipio_logo_municipal']);

        $output .= '
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row">
                        <!-- Informação do Município -->
                        <div class="col-md-8 d-flex flex-column">
                           <img src="' . $logoUrl . '" alt="Logo do Município" data-name="' . htmlspecialchars($row['municipio_name']) . '" data-id="' . htmlspecialchars($row['municipio_id']) . '" class="rounded mb-2 upload_logo" width="120" height="100" style="cursor: pointer;">
                            <h5 class="text-dark">' . htmlspecialchars($row["municipio_name"]) . ' - ' . htmlspecialchars($row["municipio_uf"]) . '</h5>
                            <p class="text-muted">
                                <strong>CNPJ:</strong> ' . htmlspecialchars($row["municipio_cnpj"]) . '<br>
                            </p>
                        </div>

                        <!-- Ações -->
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <div class="btn-group">
                                <button class="btn btn-dark btn-sm view_data" id="' . htmlspecialchars($row['municipio_id']) . '" title="Editar">
                                    <i class="ti-pencil-alt"></i> Editar
                                </button>
                                <button class="btn btn-primary btn-sm view_data_comissao" id="' . htmlspecialchars($row['municipio_id']) . '" title="Comissão">
                                    <i class="ti-user"></i> Comissão
                                </button>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
} else {
    $output .= '
        <div class="alert alert-warning text-center">
            Nenhum cadastro encontrado.
        </div>';
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
