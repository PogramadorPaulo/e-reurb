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
$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);
$novo_lote_quadra = filter_input(INPUT_POST, "novo_lote_quadra", FILTER_SANITIZE_NUMBER_INT);
$identificacao = filter_input(INPUT_POST, "identificacao", FILTER_SANITIZE_SPECIAL_CHARS);
$lote_tipo = filter_input(INPUT_POST, "loteTipo", FILTER_SANITIZE_SPECIAL_CHARS);
$lote_coordenadas = filter_input(INPUT_POST, "lote_coordenadas", FILTER_SANITIZE_SPECIAL_CHARS);
$memorial = filter_input(INPUT_POST, "memorial", FILTER_SANITIZE_SPECIAL_CHARS);
$lote_number = filter_input(INPUT_POST, "lote_number", FILTER_SANITIZE_SPECIAL_CHARS);

// Verifica se já existe um lote com o mesmo número e identificação na mesma quadra
$checkLote = $db->prepare("
    SELECT COUNT(*) AS total FROM tb_lotes 
    WHERE lote_number = :lote_number AND lote_quadra = :lote_quadra AND lote_status = 1
");
$checkLote->bindValue(":lote_number", $lote_number);
$checkLote->bindValue(":lote_quadra", $novo_lote_quadra);
$checkLote->execute();
$result = $checkLote->fetch(PDO::FETCH_ASSOC); // Corrigido para fetch

if ($result['total'] > 0) {
    // Retorna uma resposta de aviso caso já exista um lote com os mesmos dados
    $response = array(
        'status' => 'warning',
        'title' => 'Atenção',
        'message' => 'Este lote com o número <b>' . $lote_number . '</b> e identificação <b>' . $identificacao . '</b> já está cadastrado nesta quadra.',
        'icon' => 'warning',
    );
    echo json_encode($response);
    exit;
}

// Inserir dados do lote
$sql = $db->prepare("
    INSERT INTO tb_lotes 
    (lote_identificado, lote_tipo, lote_quadra, lote_number, lote_memorial, lote_coordenadas, lote_cad, lote_user)
    VALUES (:lote_identificado, :lote_tipo, :lote_quadra, :lote_number, :lote_memorial, :lote_coordenadas, :lote_cad, :lote_user)
");
$sql->bindValue(":lote_quadra", $novo_lote_quadra);
$sql->bindValue(":lote_identificado", $identificacao);
$sql->bindValue(":lote_tipo", $lote_tipo);
$sql->bindValue(":lote_number", $lote_number);
$sql->bindValue(":lote_memorial", $memorial);
$sql->bindValue(":lote_coordenadas", $lote_coordenadas);
$sql->bindValue(":lote_cad", date('Y-m-d H:i'));
$sql->bindValue(":lote_user", $idUser);
$sql->execute();

$novoLoteId = $db->lastInsertId(); // Pega o ID do novo lote

// Verifica se a identificação é "sim" para cadastrar os proprietários
if (strtolower($identificacao) === "sim") {
    $proprietarios = $_POST['selectProprietarios']; // array de IDs de proprietários
    // Inserir cada proprietário para este lote
    foreach ($proprietarios as $proprietarioId) {
        $stmt = $db->prepare("
        INSERT INTO tb_lotes_proprietarios (loteP_idLote, loteP_idProprietario)
        VALUES (:loteId, :proprietarioId)
    ");
        $stmt->bindValue(":loteId", $novoLoteId, PDO::PARAM_INT);
        $stmt->bindValue(":proprietarioId", $proprietarioId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
$response = array(
    'status' => 'success',
    'title' => 'Sucesso',
    'message' => 'Cadastro efetuado com sucesso.',
    'icon' => 'success',
);
echo json_encode($response);
