<?php
include_once "../../../config.php";

try {
    // Captura e sanitiza os dados do formulário
    $matriculaNome = filter_input(INPUT_POST, 'matricula_nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
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
    // Verifica se o nome da matrícula foi fornecido
    if (empty($matriculaNome)) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'O nome da matrícula é obrigatório.',
            'icon' => 'error'
        ]);
        exit;
    }

    // Verifica se já existe uma matrícula com o mesmo nome
    $checkSql = $db->prepare("SELECT COUNT(*) AS total FROM tb_matriculas WHERE matricula_nome = :matricula_nome AND matricula_status = 1");
    $checkSql->bindParam(':matricula_nome', $matriculaNome);
    $checkSql->execute();
    $result = $checkSql->fetch(PDO::FETCH_ASSOC);

    if ($result['total'] > 0) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Já existe uma matrícula com este nome.',
            'icon' => 'error'
        ]);
        exit;
    }


    // Inserir nova matrícula no banco de dados
    $sql = $db->prepare("
        INSERT INTO tb_matriculas (matricula_prc, matricula_nome, matricula_status, matricula_cad)
        VALUES (:matricula_prc, :matricula_nome, 1, NOW())
    ");
    $sql->bindParam(':matricula_nome', $matriculaNome);
    $sql->bindParam(':matricula_prc', $id);
    if ($sql->execute()) {
        echo json_encode([
            'status' => 'success',
            'title' => 'Sucesso',
            'message' => 'Matrícula cadastrada com sucesso!',
            'icon' => 'success'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar a matrícula. Tente novamente.',
            'icon' => 'error'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Erro ao cadastrar a matrícula: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
