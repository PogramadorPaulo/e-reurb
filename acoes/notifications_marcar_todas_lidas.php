<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../config.php');
/** @var PDO $db */

header('Content-Type: application/json; charset=utf-8');

$token = !empty($_SESSION['token']) ? $_SESSION['token'] : null;

if (!$token) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Sessão expirada ou inválida.'
    ));
    exit;
}

$user = $db->prepare("SELECT id FROM users WHERE token = :token");
$user->bindValue(":token", $token);
$user->execute();
$id = $user->fetchColumn();

if (!$id) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Usuário não localizado.'
    ));
    exit;
}

$update = $db->prepare("
    UPDATE tb_notifications_painel
    SET not_leitura = :status, not_leitura_date = :date
    WHERE not_user = :id
      AND not_status = 1
      AND not_leitura = 1
");
$update->bindValue(":id", $id, PDO::PARAM_INT);
$update->bindValue(":status", 0, PDO::PARAM_INT);
$update->bindValue(":date", date('Y-m-d H:i:s'));
$update->execute();

echo json_encode(array(
    'status' => 'success',
    'updated' => $update->rowCount()
));
