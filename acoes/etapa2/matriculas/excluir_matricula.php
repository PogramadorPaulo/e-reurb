<?php
include_once "../../../config.php";

try {
    // Captura e sanitiza os dados recebidos via POST
    $matriculaId = filter_input(INPUT_POST, "matricula_id", FILTER_SANITIZE_NUMBER_INT);

    $idProcedimento = filter_input(INPUT_POST, "idProcedimento", FILTER_SANITIZE_NUMBER_INT);
    // Verificar se a etapa está concluída
    if (isEtapaConcluida($idProcedimento, 2, $db)) {
        echo json_encode([
            'status' => 'error',
            'tittle' => 'Etapa Concluída',
            'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
            'icon' => 'warning'
        ]);
        exit;
    }


    // Verifica se o ID foi fornecido
    if (empty($matriculaId)) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'ID da matrícula é necessário.',
            'icon' => 'error'
        ]);
        exit;
    }

    // Verifica se a matrícula existe e está ativa
    $checkSql = $db->prepare("SELECT COUNT(*) AS total FROM tb_matriculas WHERE matricula_id = :matricula_id AND matricula_status = 1");
    $checkSql->bindParam(':matricula_id', $matriculaId, PDO::PARAM_INT);
    $checkSql->execute();
    $result = $checkSql->fetch(PDO::FETCH_ASSOC);

    if ($result['total'] == 0) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Matrícula não encontrada ou já está excluída.',
            'icon' => 'error'
        ]);
        exit;
    }

    // Verifica se existem proprietários associados à matrícula
    $checkProprietariosSql = $db->prepare("
        SELECT COUNT(*) AS total 
        FROM tb_matriculas_proprietarios_relacionamento 
        WHERE matricula_id = :matricula_id AND status = 1
    ");
    $checkProprietariosSql->bindParam(':matricula_id', $matriculaId, PDO::PARAM_INT);
    $checkProprietariosSql->execute();
    $proprietariosResult = $checkProprietariosSql->fetch(PDO::FETCH_ASSOC);

    if ($proprietariosResult['total'] > 0) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Não é possível excluir esta matrícula pois ela possui proprietários associados. Remova os proprietários antes de excluir a matrícula.',
            'icon' => 'error'
        ]);
        exit;
    }

    // Atualiza o status da matrícula para inativo (0)
    $updateSql = $db->prepare("
        UPDATE tb_matriculas 
        SET matricula_status = 0, 
            matricula_update = :matricula_update 
        WHERE matricula_id = :matricula_id
    ");
    $currentDateTime = date('Y-m-d H:i:s');
    $updateSql->bindParam(':matricula_update', $currentDateTime);
    $updateSql->bindParam(':matricula_id', $matriculaId, PDO::PARAM_INT);

    if ($updateSql->execute()) {
        echo json_encode([
            'status' => 'success',
            'title' => 'Sucesso',
            'message' => 'Matrícula excluída com sucesso.',
            'icon' => 'success'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Não foi possível excluir a matrícula. Tente novamente.',
            'icon' => 'error'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Erro ao excluir a matrícula: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
