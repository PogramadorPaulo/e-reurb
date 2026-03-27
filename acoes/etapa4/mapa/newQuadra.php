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
$processo = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);
$nova_quadra_nome = filter_input(INPUT_POST, "nova_quadra_nome", FILTER_SANITIZE_SPECIAL_CHARS);


// Validação dos campos obrigatórios
if (
    empty($idUser) ||
    empty($processo) ||
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


// Verifica se o lote_number já está cadastrado na mesma quadra
$verificaLote = $db->prepare("
    SELECT COUNT(*) AS total FROM tb_quadras 
    WHERE quadra_letra = :quadra_letra AND quadra_prc = :quadra_prc
");
$verificaLote->bindValue(":quadra_letra", strtoupper($nova_quadra_nome));
$verificaLote->bindValue(":quadra_prc", $processo);
$verificaLote->execute();
$resultado = $verificaLote->fetch(PDO::FETCH_ASSOC);

if ($resultado['total'] > 0) {
    $response = array(
        'status' => 'warning',
        'title' => 'Atenção',
        'message' => 'Quadra <h2 class="border m-2">' . strtoupper($nova_quadra_nome) . '</h2> já está cadastrado.</b>',
        'icon' => 'warning',
    );

    echo json_encode($response);
    exit;
}

// Inserir os dados no banco de dados
$sql = $db->prepare(
    "insert into tb_quadras 
	(   
        quadra_prc,
        quadra_letra,
        quadra_user,
        quadra_cad
    ) 

	 values(
        :quadra_prc,
        :quadra_letra,
        :quadra_user,
        :quadra_cad
     )"
);
$sql->bindValue(":quadra_prc", $processo);
$sql->bindValue(":quadra_letra", strtoupper($nova_quadra_nome));
$sql->bindValue(":quadra_user", $idUser);
$sql->bindValue(":quadra_cad", date('Y-m-d H:i'));
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
    $atividade->bindValue(":atividade_name", 'Nova QUADRA cadastrado: Quadra:' . $nova_quadra_nome . ' Processo: ' . $processo);
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'title' => 'Sucesso',
        'message' => 'Cadastro efetuado com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
}
