<?php
require_once('../config.php');
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$sql = $db->prepare("SELECT * from tb_notifications_painel WHERE not_id=:id");
$sql->bindValue(":id", $id);
$sql->execute();
if ($sql->rowCount() == 0) {
    echo 'Ops!Notificação não localizada!';
} else {
    $update = $db->prepare("UPDATE tb_notifications_painel SET not_leitura=:status, not_leitura_date=:date WHERE not_id=:id");
    $update->bindValue(":id", $id);
    $update->bindValue(":status", 0);
    $update->bindValue(":date", date('Y-m-d H:i:s'));
    $update->execute();
    if ($update) {
        echo 'success';
    }
}
