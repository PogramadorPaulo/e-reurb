<?php
include_once('../../config.php');

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');

if (!$id || $nome == '') {
    exit('Campos obrigatórios');
}

$stmt = $db->prepare("UPDATE comissao_funcao SET funcao_nome = :nome WHERE id_funcao = :id");
$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':id', $id);
$stmt->execute();

echo 'ok';
