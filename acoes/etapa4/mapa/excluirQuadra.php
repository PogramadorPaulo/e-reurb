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
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_NUMBER_INT);
// Validação dos campos obrigatórios
if (
    empty($id)
) {
    $response = array(
        'status' => 'warning',
        'title' => 'Atenção',
        'message' => 'Preencha todos os campos obrigatórios!',
        'icon' => 'warning',
    );

    echo json_encode($response);
    exit;
}

// Verifica se o lote_number já está cadastrado na mesma quadra
$verificaLote = $db->prepare("
    SELECT COUNT(*) AS total FROM tb_lotes 
    WHERE lote_quadra = :lote_quadra AND lote_status = 1
");
$verificaLote->bindValue(":lote_quadra", $id);
$verificaLote->execute();
$resultado = $verificaLote->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $response = array(
        'status' => 'warning',
        'title' => 'Atenção',
        'message' => 'Não é possível excluir está QUADRA, existe lotes cadastrado!',
        'icon' => 'warning',
    );

    echo json_encode($response);
    exit;
}

// Inserir os dados no banco de dados
$sql = $db->prepare("
UPDATE tb_quadras 
SET
quadra_status=:quadra_status,
quadra_update=:quadra_update
WHERE quadra_id=:quadra_id");
$sql->bindValue(":quadra_id", $id);
$sql->bindValue(":quadra_status", $status);
$sql->bindValue(":quadra_update", date('Y-m-d H:i'));
$sql->execute();

if ($sql->rowCount() > 0) {
    $response = array(
        'status' => 'success',
        'title' => 'Sucesso',
        'message' => 'Cadastro efetuado com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
}
