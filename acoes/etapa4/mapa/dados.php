<?php
include_once "../../../config.php";

// Captura o ID do lote a ser editado
$loteId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

// Consultar os dados do lote
$loteQuery = $db->prepare("SELECT * FROM tb_lotes WHERE lote_id = :id");
$loteQuery->bindValue(":id", $loteId, PDO::PARAM_INT);
$loteQuery->execute();
$lote = $loteQuery->fetch(PDO::FETCH_ASSOC);

// Consultar os proprietários do lote
$proprietariosQuery = $db->prepare("
    SELECT p.id_tab AS id, p.nome FROM proprietarios_tabulares p
    INNER JOIN tb_lotes_proprietarios lp ON lp.loteP_idProprietario = p.id
    WHERE lp.loteP_idLote = :loteId
");
$proprietariosQuery->bindValue(":loteId", $loteId, PDO::PARAM_INT);
$proprietariosQuery->execute();
$proprietarios = $proprietariosQuery->fetchAll(PDO::FETCH_ASSOC);

// Consultar todos os proprietários disponíveis
$todosProprietariosQuery = $db->query("SELECT id_tab, nome FROM proprietarios_tabulares");
$todosProprietarios = $todosProprietariosQuery->fetchAll(PDO::FETCH_ASSOC);

// Retornar os dados
echo json_encode([
    'status' => 'success',
    'lote' => $lote,
    'proprietarios' => $proprietarios,
    'todosProprietarios' => $todosProprietarios,
]);
