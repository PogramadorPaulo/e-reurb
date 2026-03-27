<?php

header('Content-Type: application/json');
require_once('../../config.php');
try {
    // Recebe a ordem via JSON
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['ordem']) || !is_array($input['ordem'])) {
        echo json_encode([
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Dados inválidos fornecidos.',
            'icon' => 'error'
        ]);
        exit;
    }

    $ordem = $input['ordem'];

    // Atualiza a ordem no banco de dados (exemplo)
    foreach ($ordem as $posicao => $anexo_id) {
        $sql = $db->prepare("UPDATE tb_etapas_anexos SET anexo_ordem = :ordem WHERE anexo_id = :id");
        $sql->bindValue(':ordem', $posicao + 1, PDO::PARAM_INT); // Posição começa em 1
        $sql->bindValue(':id', $anexo_id, PDO::PARAM_INT);
        $sql->execute();
    }

    echo json_encode([
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Ordem salva com sucesso!',
        'icon' => 'success'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao salvar ordem: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
