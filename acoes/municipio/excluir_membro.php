<?php
include_once('../../config.php');

$id = $_POST['id'] ?? null;

if (!$id) {
    exit('ID inválido');
}

$stmt = $db->prepare("UPDATE comissao SET status = 0 WHERE id = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();

echo 'ok';
