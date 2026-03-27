<?php
require_once '../../config.php';
session_start();
// Receber o ID do procedimento
$id_procedimento = filter_input(INPUT_POST, 'id_procedimento', FILTER_SANITIZE_NUMBER_INT);

if (!$id_procedimento) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'ID do procedimento inválido.',
        'icon' => 'error'
    ]);
    exit;
}

try {
    // Verificar o status atual do procedimento
    $stmt = $db->prepare("
        SELECT procedure_situacao 
        FROM procedures 
        WHERE cod_procedimento = :id_procedimento
    ");
    $stmt->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $stmt->execute();
    $status = $stmt->fetchColumn();

    if ($status === false) {
        echo json_encode([
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Procedimento não encontrado.',
            'icon' => 'error'
        ]);
        exit;
    }

    if ($status == 1) {
        echo json_encode([
            'status' => 'warning',
            'tittle' => 'Atenção',
            'message' => 'Este procedimento já está ativo.',
            'icon' => 'warning'
        ]);
        exit;
    }

    // Atualizar o status do procedimento para "ativo" (1)
    $update = $db->prepare("
        UPDATE procedures 
        SET procedure_situacao = 'ativo', status=1
        WHERE cod_procedimento = :id_procedimento
    ");
    $update->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $update->execute();

    // Registra a atividade do usuário
    $atividade = $db->prepare("
        INSERT INTO tb_atividades_usuarios (atividade_user, atividade_name, atividade_data)
        VALUES (:user_id, :atividade_name, :atividade_data)
    ");
    $atividade->bindValue(':user_id', $_SESSION['uid'], PDO::PARAM_INT); // ID do usuário logado
    $atividade->bindValue(':atividade_name', 'Processo : ' . $id_procedimento.' reativado');
    $atividade->bindValue(':atividade_data', date('Y-m-d H:i:s'));
    $atividade->execute();

    // Nome dinâmico para o histórico
    $etapaName = "Processo: {$id_procedimento} reativado.";

    // Inserir no histórico do processo
    $historico = $db->prepare("
        INSERT INTO tb_etapa_historico 
        (h_idProcesso, h_statusID, h_name, h_date, h_user) 
        VALUES (:processo, :h_statusID, :name, :data, :user)
    ");
    $historico->bindValue(':processo', $id_procedimento, PDO::PARAM_STR);
    $historico->bindValue(':h_statusID', 2);
    $historico->bindValue(':user', $_SESSION['uid'], PDO::PARAM_INT);
    $historico->bindValue(':name', "$etapaName");
    $historico->bindValue(':data', date('Y-m-d H:i:s'));
    $historico->execute();

    echo json_encode([
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Procedimento ativado com sucesso!',
        'icon' => 'success'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao ativar o procedimento: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
