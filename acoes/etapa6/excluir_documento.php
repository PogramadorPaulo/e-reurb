<?php
require_once('../../config.php');

header('Content-Type: application/json');

// Recuperar o ID do documento a ser excluído
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode([
        'success' => false,
        'message' => 'ID inválido.',
        'code' => 400  // Código de erro HTTP
    ]);
    exit;
}

try {
    // Atualizando o status do anexo para "excluído" (status = 2)
    $stmt = $db->prepare("
        UPDATE tb_etapas_anexos 
        SET anexo_status = :anexo_status
        WHERE anexo_id = :id
    ");

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':anexo_status', 2); // Considerando o status 2 como "excluído"

    $stmt->execute();

    // Checa se nenhuma linha foi afetada, o que indicaria que o ID não foi encontrado
    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Documento não encontrado ou já foi excluído.',
            'code' => 404  // Documento não encontrado
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'O documento foi excluído com sucesso.',
        'code' => 200  // Sucesso
    ]); // Mensagem de sucesso
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao excluir: ' . $e->getMessage(),
        'code' => 500  // Erro do servidor
    ]);
}
