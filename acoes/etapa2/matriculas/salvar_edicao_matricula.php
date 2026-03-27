<?php
include_once "../../../config.php";

try {
    // Captura e sanitiza os dados do formulário
    $matriculaId = filter_input(INPUT_POST, 'matricula_id', FILTER_SANITIZE_NUMBER_INT);
    $matriculaNome = filter_input(INPUT_POST, 'matricula_nome', FILTER_SANITIZE_SPECIAL_CHARS);

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

    // Verifica se o ID da matrícula e o nome foram fornecidos
    if (empty($matriculaId) || empty($matriculaNome)) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'ID da matrícula ou nome não fornecido.',
            'icon' => 'error'
        ]);
        exit;
    }

    // Atualiza a matrícula no banco de dados
    $sql = $db->prepare("
        UPDATE tb_matriculas 
        SET matricula_nome = :matricula_nome,
            matricula_update = :matricula_update
        WHERE matricula_id = :matricula_id
    ");
    $sql->bindParam(':matricula_nome', $matriculaNome);
    $sql->bindValue(':matricula_update', date('Y-m-d H:i:s'));
    $sql->bindParam(':matricula_id', $matriculaId);

    if ($sql->execute()) {
        echo json_encode([
            'status' => 'success',
            'title' => 'Sucesso',
            'message' => 'Matrícula atualizada com sucesso!',
            'icon' => 'success'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Não foi possível atualizar a matrícula. Tente novamente.',
            'icon' => 'error'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Erro ao atualizar a matrícula: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
