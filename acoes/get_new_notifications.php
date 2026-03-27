<?php
include_once("../config.php");
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$deferido = $db->prepare("
select not_id, 
not_status, 
not_leitura, 
not_user 
from tb_notifications_painel 

WHERE not_user=$id
and not_status=1
and not_leitura=1
 
 ");
$deferido->execute();
$qtd_deferido = $deferido->rowCount();
if ($qtd_deferido == 0) {
	echo 0;
} else {
	echo  $qtd_deferido;
}
