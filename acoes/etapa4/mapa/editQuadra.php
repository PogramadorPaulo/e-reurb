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
$quadra_id = filter_input(INPUT_POST, "quadra_id", FILTER_SANITIZE_NUMBER_INT);
$nova_quadra_nome = filter_input(INPUT_POST, "nova_quadra_nome", FILTER_SANITIZE_SPECIAL_CHARS);
$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);

// Validação dos campos obrigatórios
if (
    empty($quadra_id) ||
    empty($nova_quadra_nome)
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

// Inserir os dados no banco de dados
$sql = $db->prepare("UPDATE tb_quadras 
SET
quadra_letra=:quadra_letra, 
quadra_user=:quadra_user, 
quadra_update=:quadra_update 
WHERE quadra_id=:quadra_id");
$sql->bindValue(":quadra_id", $quadra_id);
$sql->bindValue(":quadra_letra", strtoupper($nova_quadra_nome));
$sql->bindValue(":quadra_update", date('Y-m-d H:i'));
$sql->bindValue(":quadra_user", $idUser);
$sql->execute();

if ($sql->rowCount() > 0) {

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

    $atividade->bindValue(":atividade_user", $idUser);
    $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
    $atividade->bindValue(":atividade_name", 'Quadra:' . $nova_quadra_nome . ' alterado');
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'title' => 'Sucesso',
        'message' => 'Cadastro efetuado com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
}
