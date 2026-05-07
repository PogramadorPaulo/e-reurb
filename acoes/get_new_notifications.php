<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once("../config.php");
/** @var PDO $db */

$token = !empty($_SESSION['token']) ? $_SESSION['token'] : null;

if (!$token) {
	echo 0;
	exit;
}

$user = $db->prepare("SELECT id FROM users WHERE token = :token");
$user->bindValue(":token", $token);
$user->execute();
$id = $user->fetchColumn();

if (!$id) {
	echo 0;
	exit;
}

$deferido = $db->prepare("
select not_id, 
not_status, 
not_leitura, 
not_user 
from tb_notifications_painel 

WHERE not_user = :id
and not_status=1
and not_leitura=1
 
 ");
$deferido->bindValue(":id", $id, PDO::PARAM_INT);
$deferido->execute();
$qtd_deferido = $deferido->rowCount();
if ($qtd_deferido == 0) {
	echo 0;
} else {
	echo  $qtd_deferido;
}
