<?php
require_once "../config.php";
session_start();

if (empty($_POST["password"]) && empty($_POST["password"]) && empty($_POST["chave"])) {
	echo "Digite sua nova senha";
	exit;
}
$chave = trim(addslashes($_POST["chave"]));
$password = trim(addslashes($_POST["password"]));
$password_ = trim(addslashes($_POST["password_"]));

if ($password != $password_) {
	echo "Senhas não confere";
	exit;
}

$sql_inserir = $db->prepare("
UPDATE users SET password=:password, recuperar_senha=:recuperar_senha WHERE recuperar_senha=:chave");
$sql_inserir->bindValue(":password", md5($password));
$sql_inserir->bindValue(":chave", $chave);
$sql_inserir->bindValue(":recuperar_senha", '');
$sql_inserir->execute();

if ($sql_inserir->rowCount() > 0) {
	echo 'success';
} else {
	echo 'Erro!';
}
