<?php
include_once('../../../config.php');
header('Content-Type: application/json');

try {
    $id_procedimento = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Consulta para buscar proprietários
    $query = $db->prepare("SELECT * FROM proprietarios_tabulares WHERE id_tab_procedimento = :id AND status_tab = 1 ORDER BY nome");
    $query->bindParam(':id', $id_procedimento, PDO::PARAM_INT);
    $query->execute();
    $proprietarios = $query->fetchAll(PDO::FETCH_ASSOC);

    // Para cada proprietário, busca os imóveis
    foreach ($proprietarios as &$proprietario) {
        $imoveisQuery = $db->prepare("SELECT * FROM imoveis WHERE id_proprietario_imovel = :id AND status_imovel = 1");
        $imoveisQuery->bindValue(":id", $proprietario['id_tab']);
        $imoveisQuery->execute();
        $proprietario['imoveis'] = $imoveisQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($proprietarios); // Retorna os dados no formato JSON
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

