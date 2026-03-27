<?php
require_once '../../config.php';

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'ID inválido.',
        'icon' => 'error'
    ]);
    exit;
}

try {
    $stmt = $db->prepare("SELECT id, name, email, permission_id, status FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success',
            'data' => $user
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Usuário não encontrado.',
            'icon' => 'error'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao buscar os dados: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
