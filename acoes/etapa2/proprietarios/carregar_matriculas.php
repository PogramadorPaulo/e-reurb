<?php
include_once "../../../config.php";

try {
    // Consulta para buscar matrículas ativas
    $query = "SELECT matricula_id, matricula_nome FROM tb_matriculas WHERE matricula_status = 1 ORDER BY matricula_nome";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados em formato JSON
    header('Content-Type: application/json');
    echo json_encode($matriculas);

} catch (PDOException $e) {
    // Retorna uma mensagem de erro caso ocorra algum problema na consulta
    echo json_encode(['error' => 'Erro ao buscar matrículas: ' . $e->getMessage()]);
}
