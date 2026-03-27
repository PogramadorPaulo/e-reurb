<?php
require_once('../../../config.php');


header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

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


$response = ['status' => 'error', 'message' => 'Nenhum item foi selecionado para exclusão.']; // Resposta padrão de erro

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $ItemId = $_POST['id'];


    // Deleta o registro no banco de dados
    $deletar = $db->prepare("DELETE FROM tb_lotes_proprietarios WHERE loteP_id = :id");
    $deletar->bindValue(":id", $ItemId, PDO::PARAM_INT); // Bind do ID do item
    $deletar->execute();
    if ($deletar) {
        $response['status'] = 'success'; // Atualiza o status para sucesso
        $response['message'] = 'O proprietário foi excluído com sucesso.'; // Mensagem de sucesso
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Não foi possível excluir o proprietário'; // Mensagem de erro ao mover
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Não foi possível realizar esta operação!'; // Mensagem de erro ao mover
}

// Envia a resposta JSON
echo json_encode($response);
