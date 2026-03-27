<?php
require_once '../config.php';

// Receber o ID do procedimento
$id_procedimento = filter_input(INPUT_POST, 'id_procedimento', FILTER_SANITIZE_NUMBER_INT);

if (!$id_procedimento) {
    json_response_send([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'ID do processo inválido.',
        'icon' => 'error'
    ]);
}

try {
    // Consultar o histórico do processo
    $stmt = $db->prepare("
        SELECT h.h_date, h.h_name, h.h_statusID, h.h_justificativa, u.name AS user_name
        FROM tb_etapa_historico h
        LEFT JOIN users u ON h.h_user = u.id
        WHERE h.h_idProcesso = :id_procedimento
        ORDER BY h.h_date DESC
    ");
    $stmt->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($historico)) {
        echo json_encode([
            'status' => 'success',
            'data' => []
        ]);
        exit;
    }

    // Identificar o último histórico e adicionar classe de status
    foreach ($historico as $index => &$item) {
        switch ($item['h_statusID']) {
            case 4: // Concluído
                $item['status_class'] = 'concluida';
                break;
            case 1: // Pendente
                $item['status_class'] = 'pendente';
                break;
            case 5: // Em análise
                $item['status_class'] = 'analise';
                break;
            case 6: // Cancelado
                $item['status_class'] = 'cancelado';
                break;
            case 2: // Andamento
                $item['status_class'] = 'andamento';
                break;
            case 7: // Aberto
                $item['status_class'] = 'aberto';
                break;
            default: // Outros (se necessário)
                $item['status_class'] = '';
                break;
        }
        // Marcar o último histórico
        $item['is_last'] = ($index === 0) ? true : false;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $historico
    ]);
} catch (Exception $e) {
    json_response_send([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Erro ao buscar histórico: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}
