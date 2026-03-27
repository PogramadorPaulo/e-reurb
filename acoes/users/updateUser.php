<?php
require_once '../../config.php';

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$permission = filter_input(INPUT_POST, 'permission', FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

if (!$id || !$name || !$email || !$permission || $status === null) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Dados inválidos fornecidos.',
        'icon' => 'error'
    ]);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE users SET name = :name, email = :email, permission_id = :permission, status = :status WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':permission', $permission, PDO::PARAM_INT);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Usuário atualizado com sucesso!',
        'icon' => 'success'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao atualizar usuário: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
