<?php
include_once "../../../config.php";

try {
    // Consulta para buscar todos os proprietários
    $queryProprietarios = "
        SELECT *       
        FROM tb_notificacoes_tipos
        WHERE tipo_status = 1
        ORDER BY tipo_name
    ";

    $stmtProprietarios = $db->prepare($queryProprietarios);
    $stmtProprietarios->bindParam(":id", $idProcedimento, PDO::PARAM_INT);
    $stmtProprietarios->execute();

    $proprietarios = [];

    // Preenche o array de proprietários
    while ($proprietario = $stmtProprietarios->fetch(PDO::FETCH_ASSOC)) {
        $proprietarios[] = [
            'id' => $proprietario['tipo_id'],
            'nome' => $proprietario['tipo_name'],
        ];
    }

    // Retorna os proprietários em formato JSON
    header('Content-Type: application/json');
    echo json_encode($proprietarios);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao carregar proprietários: ' . $e->getMessage()]);
}
