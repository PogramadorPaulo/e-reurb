<?php
require_once('../config.php');
session_start();
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$not_email = filter_input(INPUT_POST, "not_email", FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($not_email)) {
    $not_email = 1;
} else {
    $not_email = 0;
}

if (
    $id == ''
) {
    echo 'error';
    exit;
}
$sql = $db->prepare("UPDATE users SET user_notification_email=:user_notification_email, data_update=:data_update WHERE id=:id");
$sql->bindValue(":id", $id);
$sql->bindValue(":user_notification_email", $not_email);
$sql->bindValue(":data_update", date('Y-m-d H:i'));
$sql->execute();

if ($sql) {
    echo 'success';
}
