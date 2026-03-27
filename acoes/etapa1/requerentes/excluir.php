<?php

require_once('../../../config.php');
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_NUMBER_INT);
$idProcedimento = filter_input(INPUT_POST, "idProcedimento", FILTER_SANITIZE_NUMBER_INT);
// Verificar se a etapa está concluída
if (isEtapaConcluida($idProcedimento, 1, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}

$sql = $db->prepare("SELECT id_requerente from requerentes WHERE id_requerente=:id");
$sql->bindValue(":id", $id);
$sql->execute();
if ($sql->rowCount() == 0) {
    $response = array(
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Não foi possível realizar está operação! Tente novamente',
        'icon' => 'error',
    );
    echo json_encode($response);
} else {

    $update = $db->prepare("UPDATE requerentes SET status_requente=:status WHERE id_requerente=:id");
    $update->bindValue(":id", $id);
    $update->bindValue(":status", $status);
    $update->execute();
    if ($update) {
        $response = array(
            'status' => 'success',
            'tittle' => 'Sucesso',
            'message' => 'Cadastro excluido com sucesso.',
            'icon' => 'success',
        );
        echo json_encode($response);
    } else {
        $response = array(
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Ops! Não foi possível excluir!',
            'icon' => 'error',
        );

        echo json_encode($response);
    }
}
