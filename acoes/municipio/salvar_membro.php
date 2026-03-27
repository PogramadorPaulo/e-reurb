<?php
include_once('../../config.php');

$id            = $_POST['id'] ?? null;
$municipio_id  = $_POST['municipio_id'] ?? null;
$nome          = trim($_POST['nome'] ?? '');
$funcao        = $_POST['funcao'] ?? null;

if (empty($municipio_id) || empty($nome) || empty($funcao)) {
    exit('Campos obrigatórios');
}

if ($id) {
    // Atualiza
    $stmt = $db->prepare("UPDATE comissao SET nome = :nome, funcao = :funcao WHERE id = :id");
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':funcao', $funcao);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    echo 'ok';
} else {
    // Insere novo
    $stmt = $db->prepare("INSERT INTO comissao (id_municipio, nome, funcao, cadastro, status) 
        VALUES (:id_municipio, :nome, :funcao, NOW(), 1)");
    $stmt->bindValue(':id_municipio', $municipio_id);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':funcao', $funcao);
    $stmt->execute();
    echo 'ok';
}
