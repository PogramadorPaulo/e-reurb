<?php
require_once('../../../config.php');


// Receber os dados do formulário
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Verificar se a etapa está concluída
if (isEtapaConcluida($dados['idProcedimento'], 1, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}
// Atualizar os dados no banco de dados
$sql = $db->prepare("
    UPDATE procedures SET 
    abertura_oficio=:abertura_oficio,
    data_update=:data_update 
    WHERE id=:id
");

$sql->bindValue(":abertura_oficio", $dados['abertura']);
$sql->bindValue(":data_update", date('Y-m-d H:i'));
$sql->bindValue(":id", $dados['id']);
$sql->execute();

if ($sql->rowCount() > 0) {
    $dados_array = implode(' ', $dados);


    $atividade = $db->prepare("
        INSERT INTO tb_atividades_usuarios 
        (
            atividade_user,
            atividade_name,
            atividade_data
        ) 
        VALUES (
            :atividade_user,
            :atividade_name,
            :atividade_data
        )
    ");

    $atividade->bindValue(":atividade_user", $dados['idUser']);
    $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
    $atividade->bindValue(":atividade_name", 'Processo atualizado - Aba Requerente: ' . $dados_array);
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'title' => 'Sucesso',
        'message' => 'Atualização efetuada com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
} else {
    $response = array(
        'status' => 'info',
        'title' => 'Sucesso',
        'message' => 'Não houve nenhuma alteração nos dados para salvar',
        'icon' => 'info',
    );
    echo json_encode($response);
}
