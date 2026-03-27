<?php
include_once("../../../config.php");

// Inicializa o array de saída
$saida = [];

// Valida se 'id' foi enviado no POST
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID não fornecido']);
    exit;
}

$id = $_POST['id'];

// Consulta para buscar os proprietários
$sql = "
    SELECT id_tab, nome, cpf, cnpj 
    FROM proprietarios_tabulares 
    WHERE id_tab_procedimento = :id
    ORDER BY nome
";

$result = $db->prepare($sql);
$result->bindParam(":id", $id, PDO::PARAM_INT);
$result->execute();

// Preenche o array $saida com os resultados da consulta
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    // Define qual identificação será exibida (CPF ou CNPJ)
    $identificacao = !empty($row["cpf"]) ? $row["cpf"] : (!empty($row["cnpj"]) ? $row["cnpj"] : 'Não informado');

    $saida[] = [
        'id' => $row["id_tab"],
        'nome' => $row["nome"],
        'identificacao' => $identificacao, // Adiciona o CPF/CNPJ separadamente
    ];
}

// Define o cabeçalho para JSON e imprime a saída codificada como JSON
header('Content-Type: application/json');
echo json_encode($saida);
