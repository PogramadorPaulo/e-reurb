<?php
require_once '../../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        // Captura e sanitiza o CPF enviado via POST
        $cpf = filter_input(INPUT_POST, 'cpf', FILTER_DEFAULT);
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        // Verificar se o CPF já existe no banco de dados
        $sql = "SELECT COUNT(*) FROM proprietarios_tabulares WHERE cpf = :cpf AND id_tab_procedimento = :id AND status_tab = 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Se o CPF já existir, retornar uma resposta JSON informando
        if ($count > 0) {
            $response = array("status" => "exists");
        } else {
            $response = array("status" => "available");
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        // Caso ocorra um erro
        $response = array("status" => "error", "message" => $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
