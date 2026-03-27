<?php
include_once "../../../config.php";

// Captura e sanitiza os dados recebidos via POST
$matriculaId = filter_input(INPUT_POST, "matricula_id", FILTER_SANITIZE_NUMBER_INT);
$proprietarioId = filter_input(INPUT_POST, "proprietario_id", FILTER_SANITIZE_NUMBER_INT);

$idProcedimento = filter_input(INPUT_POST, "idProcedimento", FILTER_SANITIZE_NUMBER_INT);
// Verificar se a etapa está concluída
if (isEtapaConcluida($idProcedimento, 2, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}


// Verifica se ambos os IDs foram fornecidos
if (empty($matriculaId) || empty($proprietarioId)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'IDs da matrícula e do proprietário são necessários.'
    ]);
    exit;
}

try {
    // Atualiza o status do vínculo para inativo (0)
    $updateQuery = "
        UPDATE tb_matriculas_proprietarios_relacionamento
        SET status = 0, data_update = :data_update
        WHERE matricula_id = :matricula_id AND proprietario_id = :proprietario_id AND status = 1
    ";
    $stmt = $db->prepare($updateQuery);
    $currentDateTime = date('Y-m-d H:i:s');
    $stmt->bindParam(':data_update', $currentDateTime);
    $stmt->bindParam(':matricula_id', $matriculaId, PDO::PARAM_INT);
    $stmt->bindParam(':proprietario_id', $proprietarioId, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se a atualização foi bem-sucedida
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Proprietário excluído com sucesso da matrícula.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Proprietário não encontrado ou já está excluído.'
        ]);
    }
} catch (PDOException $e) {
    // Retorna uma mensagem de erro caso ocorra alguma exceção
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao excluir proprietário: ' . $e->getMessage()
    ]);
}
