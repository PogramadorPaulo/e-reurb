<?php
require_once('../../config.php');

// ID do procedimento recebido por GET
$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

if (!$id) {
    echo '<li class="list-group-item text-danger">ID do procedimento inválido.</li>';
    exit;
}

try {
    // Consultar documentos ordenados por etapa, ordem e ID
    $stmt = $db->prepare("
        SELECT *
        FROM tb_etapas_anexos 
        WHERE anexo_prc = :id 
        AND anexo_status = 1
        ORDER BY anexo_ordem ASC
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$documents) {
        echo '<li class="list-group-item text-secondary">Nenhum documento encontrado.</li>';
        exit;
    }

    // Função para formatar a data no padrão brasileiro
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

    // Exibir os documentos em ordem
    foreach ($documents as $doc) {
        // Criar o link para visualizar o arquivo
        $fileUrl = BASE_URL . 'assets/documentos/' . htmlspecialchars($doc['anexo_arquivo'], ENT_QUOTES, 'UTF-8');
        $fileExtension = strtoupper(htmlspecialchars($doc['anexo_arquivo_ext'], ENT_QUOTES, 'UTF-8'));
        $cadastro = formatarDataBrasileira($doc['anexo_cadastro']);
        $title = htmlspecialchars($doc['anexo_titulo']);
        $anexoId = htmlspecialchars($doc['anexo_id'], ENT_QUOTES, 'UTF-8');

        echo '<li class="list-group-item d-flex justify-content-between align-items-center draggable" id="' . $anexoId . '" draggable="true">
                <span>
                    <i class="fa fa-arrows-alt handle" style="cursor: grab;"></i> 
                    ' . htmlspecialchars($doc['anexo_titulo'], ENT_QUOTES, 'UTF-8') . '
                    <span class="text-muted">
                    (Etapa: ' . htmlspecialchars($doc['anexo_etapa'], ENT_QUOTES, 'UTF-8') . ' | ' . $fileExtension . ')</span>
                    <small class="text-secondary">' . $title . '</small>
                    <br>
                    <small class="text-secondary">Cadastro: ' . $cadastro . '</small>
                </span>
                <div>
                    <a href="' . $fileUrl . '" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-eye"></i> Visualizar
                    </a>
                    <button class="btn btn-outline-danger btn-sm" onclick="excluirDocumentoEtapa6(' . $anexoId . ')">
                        <i class="fa fa-trash"></i> Excluir
                    </button>
                </div>
              </li>';
    }
} catch (Exception $e) {
    echo '<li class="list-group-item text-danger">Erro ao buscar documentos: ' . $e->getMessage() . '</li>';
}
?>

