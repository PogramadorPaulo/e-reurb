<?php
require_once('../config.php');

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (
    $dados['id'] == ''
     or $dados['name'] == ''
) {
    echo 'Informe os campos marcados com * vermelho!';
    exit;
}
$sql = $db->prepare("
UPDATE users SET
name=:name, 
data_update=:data_update
WHERE id=:id");

$sql->bindValue(":id", $dados['id']);
$sql->bindValue(":name", $dados['name']);
$sql->bindValue(":data_update", date('Y-m-d H:i'));
$sql->execute();

if ($sql) {
    echo 'success';
}else{
    echo 'error';
}
