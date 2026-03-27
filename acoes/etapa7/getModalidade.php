<?php
require_once('../../config.php');

// Receber o ID do procedimento
$procedimentoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$procedimentoId) {
    echo json_encode(['status' => 'error', 'message' => 'ID do procedimento inválido.']);
    exit;
}

try {
    // Consultar a modalidade na tabela procedures
    $stmt = $db->prepare("SELECT modalidade FROM procedures WHERE cod_procedimento = :id");
    $stmt->bindParam(':id', $procedimentoId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['status' => 'success', 'modalidade' => $result['modalidade']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Procedimento não encontrado.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar modalidade: ' . $e->getMessage()]);
}
