<?php
require_once '../../../config.php';

header('Content-Type: application/json');

$idMunicipio = filter_input(INPUT_GET, 'idMunicipio', FILTER_VALIDATE_INT);

if (!$idMunicipio) {
    echo json_encode(['error' => 'ID de município inválido']);
    exit;
}

try {
    $sql = $db->prepare("
        SELECT modelo_titulo, modelo_documento
        FROM tb_modelos_doc
        WHERE modelo_idMunicipio = :idMunicipio AND modelo_status = 1
        ORDER BY modelo_titulo ASC
    ");
    $sql->bindValue(':idMunicipio', $idMunicipio, PDO::PARAM_INT);
    $sql->execute();

    $modelos = $sql->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($modelos);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar os modelos: ' . $e->getMessage()]);
}
