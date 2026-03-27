<?php
include_once "../../../config.php";
$idProcedimento = filter_input(INPUT_POST, "idProcedimento", FILTER_SANITIZE_NUMBER_INT);
// Verificar se a etapa está concluída
if (isEtapaConcluida($idProcedimento, 4, $db)) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}

// Captura e sanitiza os dados do formulário
$idQuadra = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);
$identificacao = filter_input(INPUT_POST, "identificacaoEdit", FILTER_SANITIZE_SPECIAL_CHARS);
$lote_tipo = filter_input(INPUT_POST, "loteTipoEdit", FILTER_SANITIZE_SPECIAL_CHARS);
$lote_coordenadas = filter_input(INPUT_POST, "lote_coordenadas", FILTER_SANITIZE_SPECIAL_CHARS);
$memorial = filter_input(INPUT_POST, "memorial", FILTER_SANITIZE_SPECIAL_CHARS);
$lote_number = filter_input(INPUT_POST, "lote_number", FILTER_SANITIZE_NUMBER_INT);

// Atualizar dados do lote
$sql = $db->prepare("
    UPDATE tb_lotes 
    SET 
     lote_identificado = :lote_identificado,
     lote_number = :lote_number, 
     lote_tipo = :lote_tipo,
     lote_memorial = :lote_memorial, 
     lote_coordenadas = :lote_coordenadas,
     lote_update = :lote_update, 
     lote_user = :lote_user
     WHERE lote_id = :lote_id
");
$sql->bindValue(":lote_identificado", $identificacao);
$sql->bindValue(":lote_number", $lote_number);
$sql->bindValue(":lote_tipo", $lote_tipo);
$sql->bindValue(":lote_memorial", $memorial);
$sql->bindValue(":lote_coordenadas", $lote_coordenadas);
$sql->bindValue(":lote_update", date('Y-m-d H:i'));
$sql->bindValue(":lote_user", $idUser);
$sql->bindValue(":lote_id", $idQuadra);
$sql->execute();

// Verifica se a identificação é "sim" para cadastrar os proprietários
if (strtolower($identificacao) === "sim") {
    // Verifica se o array de proprietários foi enviado e não está vazio
    if (!isset($_POST['selectProprietarios']) || !is_array($_POST['selectProprietarios']) || empty($_POST['selectProprietarios'])) {
        echo json_encode([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Nenhum Beneficiário foi selecionado para cadastro.',
            'icon' => 'error',
        ]);
        exit;
    }

    $proprietarios = $_POST['selectProprietarios']; // array de IDs de proprietários

    // Inserir cada proprietário para este lote, verificando duplicatas
    foreach ($proprietarios as $proprietarioId) {
        // Verifica se o proprietário já está associado ao lote
        $checkProprietario = $db->prepare("
            SELECT COUNT(*) AS total FROM tb_lotes_proprietarios 
            WHERE loteP_idLote = :loteId AND loteP_idProprietario = :proprietarioId
        ");
        $checkProprietario->bindValue(":loteId", $idQuadra, PDO::PARAM_INT);
        $checkProprietario->bindValue(":proprietarioId", $proprietarioId, PDO::PARAM_INT);
        $checkProprietario->execute();
        $proprietarioExistente = $checkProprietario->fetch(PDO::FETCH_ASSOC);

        if ($proprietarioExistente['total'] == 0) {
            // Insere o proprietário se não estiver associado
            $stmt = $db->prepare("
                INSERT INTO tb_lotes_proprietarios (loteP_idLote, loteP_idProprietario)
                VALUES (:loteId, :proprietarioId)
            ");
            $stmt->bindValue(":loteId", $idQuadra, PDO::PARAM_INT);
            $stmt->bindValue(":proprietarioId", $proprietarioId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

$response = array(
    'status' => 'success',
    'title' => 'Sucesso',
    'message' => 'Cadastro atualizado com sucesso.',
    'icon' => 'success',
);
echo json_encode($response);
