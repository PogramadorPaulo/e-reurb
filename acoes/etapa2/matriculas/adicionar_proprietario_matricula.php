<?php
include_once "../../../config.php";

try {
    $matriculaId = filter_input(INPUT_POST, 'matricula_id', FILTER_SANITIZE_NUMBER_INT);
    $proprietarioId = filter_input(INPUT_POST, 'proprietario_id', FILTER_SANITIZE_NUMBER_INT);

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

    if (!$matriculaId || !$proprietarioId) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'ID da matrícula ou proprietário inválido.',
            'icon' => 'error'
        ]);
        exit;
    }

    // Verifica se o proprietário já está associado à matrícula
    $stmtCheck = $db->prepare("SELECT COUNT(*) FROM tb_matriculas_proprietarios_relacionamento WHERE matricula_id = :matricula_id AND proprietario_id = :proprietario_id AND status = 1");
    $stmtCheck->bindParam(':matricula_id', $matriculaId, PDO::PARAM_INT);
    $stmtCheck->bindParam(':proprietario_id', $proprietarioId, PDO::PARAM_INT);
    $stmtCheck->execute();

    if ($stmtCheck->fetchColumn() > 0) {
        echo json_encode([
            'status' => 'warning',
            'title' => 'Atenção',
            'message' => 'Este proprietário já está associado a esta matrícula.',
            'icon' => 'warning'
        ]);
        exit;
    }

    // Insere o proprietário na matrícula
    $stmtInsert = $db->prepare("INSERT INTO tb_matriculas_proprietarios_relacionamento (matricula_id, proprietario_id, status) VALUES (:matricula_id, :proprietario_id, 1)");
    $stmtInsert->bindParam(':matricula_id', $matriculaId, PDO::PARAM_INT);
    $stmtInsert->bindParam(':proprietario_id', $proprietarioId, PDO::PARAM_INT);
    $stmtInsert->execute();

    echo json_encode([
        'status' => 'success',
        'title' => 'Sucesso',
        'message' => 'Proprietário adicionado à matrícula com sucesso.',
        'icon' => 'success'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Erro ao adicionar proprietário: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
