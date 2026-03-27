<?php
require_once '../config.php';

$id_procedimento = filter_input(INPUT_POST, "id_procedimento", FILTER_SANITIZE_NUMBER_INT);
$etapa = filter_input(INPUT_POST, "etapa", FILTER_SANITIZE_NUMBER_INT);

if (!$id_procedimento || !$etapa) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Dados inválidos para verificação.'
    ]);
    exit;
}

try {
    $stmt = $db->prepare("
        SELECT procedimento_status 
        FROM etapas_procedimentos 
        WHERE processo_id = :id_procedimento AND etapa_id = :etapa
    ");
    $stmt->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $stmt->bindValue(':etapa', $etapa, PDO::PARAM_INT);
    $stmt->execute();
    $etapa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($etapa) {
        echo json_encode([
            'status' => 'success',
            'etapa_status' => $etapa['procedimento_status']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Etapa não encontrada.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao consultar o status da etapa: ' . $e->getMessage()
    ]);
}
