<?php
include_once "../../../config.php";

try {
    // Valida se 'id' foi enviado no POST
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'ID não fornecido']);
        exit;
    }
    $idProcedimento = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    // Consulta para buscar todos os proprietários
    $queryProprietarios = "
        SELECT 
            id, 
            nome, 
            cpf 
        FROM tb_matriculasproprietarios
        WHERE id_procedimento=:id and pro_status = 1
        ORDER BY nome
    ";

    $stmtProprietarios = $db->prepare($queryProprietarios);
    $stmtProprietarios->bindParam(":id", $idProcedimento, PDO::PARAM_INT);
    $stmtProprietarios->execute();

    $proprietarios = [];

    // Preenche o array de proprietários
    while ($proprietario = $stmtProprietarios->fetch(PDO::FETCH_ASSOC)) {
        $proprietarios[] = [
            'id' => $proprietario['id'],
            'nome' => $proprietario['nome'],
            'cpf' => $proprietario['cpf']
        ];
    }

    // Retorna os proprietários em formato JSON
    header('Content-Type: application/json');
    echo json_encode($proprietarios);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao carregar proprietários: ' . $e->getMessage()]);
}
