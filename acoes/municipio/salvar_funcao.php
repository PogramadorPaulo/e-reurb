<?php
include_once('../../config.php');

$municipio_id = $_POST['municipio_id'] ?? null;
$nome = trim($_POST['nova_funcao'] ?? '');

if (!$municipio_id || $nome == '') {
    exit('Campos obrigatórios');
}

$stmt = $db->prepare("INSERT INTO comissao_funcao (funcao_nome, id_funcao_municipio) VALUES (:nome, :municipio)");
$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':municipio', $municipio_id);
$stmt->execute();

echo 'ok';
