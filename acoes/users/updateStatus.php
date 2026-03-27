<?php
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $status = $_POST['status'];

    if (empty($userId) || $status === null) {
        $response = array(
            'status' => 'warning',
            'tittle' => 'Atenção',
            'message' => 'ID do usuário ou status ausente.',
            'icon' => 'warning',
        );
        echo json_encode($response);
        exit;
    }

    try {
        // Atualiza o status no banco de dados
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $response = array(
                'status' => 'success',
                'tittle' => 'Sucesso',
                'message' => 'Status atualizado com sucesso!',
                'icon' => 'success',
            );
        } else {
            $response = array(
                'status' => 'info',
                'tittle' => 'Nenhuma alteração',
                'message' => 'O status já estava atualizado.',
                'icon' => 'info',
            );
        }
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Erro ao atualizar o status: ' . $e->getMessage(),
            'icon' => 'error',
        );
    }

    echo json_encode($response);
    exit;
} else {
    $response = array(
        'status' => 'error',
        'tittle' => 'Método não permitido',
        'message' => 'Apenas requisições POST são permitidas.',
        'icon' => 'error',
    );
    echo json_encode($response);
    exit;
}
