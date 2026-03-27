<?php
include_once('../../config.php');

$id = $_POST['id'] ?? null;
if (!$id) {
    exit('ID inválido');
}

$stmt = $db->prepare("DELETE FROM comissao_funcao WHERE id_funcao = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();

echo 'ok';
