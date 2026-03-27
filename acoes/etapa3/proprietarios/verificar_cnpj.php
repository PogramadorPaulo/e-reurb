<?php
require_once '../../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        $cnpj = filter_input(INPUT_POST, 'cnpj', FILTER_DEFAULT);
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $sql = "SELECT COUNT(*) FROM proprietarios_tabulares WHERE cnpj = :cnpj AND id_tab_procedimento = :id AND status_tab = 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

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
