<?php
require_once('../../../config.php');


$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$identificacao =  filter_input(INPUT_POST, "identificacao", FILTER_SANITIZE_SPECIAL_CHARS);
$regime =  filter_input(INPUT_POST, "regime", FILTER_SANITIZE_SPECIAL_CHARS);
$data_casamento =  filter_input(INPUT_POST, "data_casamento", FILTER_SANITIZE_SPECIAL_CHARS);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_SPECIAL_CHARS);
$capacidade = filter_input(INPUT_POST, "capaz", FILTER_SANITIZE_SPECIAL_CHARS);
$nome =  filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
$data_nasc =  filter_input(INPUT_POST, "data_nasc", FILTER_SANITIZE_SPECIAL_CHARS);
$sexo =  filter_input(INPUT_POST, "sexo", FILTER_SANITIZE_SPECIAL_CHARS);
$rg =  filter_input(INPUT_POST, "rg", FILTER_SANITIZE_SPECIAL_CHARS);
$emissor =  filter_input(INPUT_POST, "emissor", FILTER_SANITIZE_SPECIAL_CHARS);
$profissao =  filter_input(INPUT_POST, "profissao", FILTER_SANITIZE_SPECIAL_CHARS);
$estado_civil = filter_input(INPUT_POST, "estado_civil", FILTER_SANITIZE_SPECIAL_CHARS);
$uniao = filter_input(INPUT_POST, "uniao", FILTER_SANITIZE_SPECIAL_CHARS);
$telefone = filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_SPECIAL_CHARS);
$celular =  filter_input(INPUT_POST, "celular", FILTER_SANITIZE_SPECIAL_CHARS);
$email =  filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
$cep =  filter_input(INPUT_POST, "cep", FILTER_SANITIZE_SPECIAL_CHARS);
$logradouro =  filter_input(INPUT_POST, "logradouro", FILTER_SANITIZE_SPECIAL_CHARS);
$numero =  filter_input(INPUT_POST, "numero", FILTER_SANITIZE_SPECIAL_CHARS);
$complemente =  filter_input(INPUT_POST, "complemente", FILTER_SANITIZE_SPECIAL_CHARS);
$bairro =  filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_SPECIAL_CHARS);
$municipio =  filter_input(INPUT_POST, "municipio", FILTER_SANITIZE_SPECIAL_CHARS);
$estado =  filter_input(INPUT_POST, "estado", FILTER_SANITIZE_SPECIAL_CHARS);
$pai =  filter_input(INPUT_POST, "pai", FILTER_SANITIZE_SPECIAL_CHARS);
$mae =  filter_input(INPUT_POST, "mae", FILTER_SANITIZE_SPECIAL_CHARS);

$idProcedimento = filter_input(INPUT_POST, "idProcedimento", FILTER_SANITIZE_NUMBER_INT);
// Verificar se a etapa está concluída
if (isEtapaConcluida($idProcedimento, 3, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}

// Validação dos campos obrigatórios
if (
    empty($nome) ||
    empty($id)

) {
    $response = array(
        'status' => 'warning',
        'tittle' => 'Atenção',
        'message' => 'Preencha todos os campos obrigatórios!',
        'icon' => 'warning',
    );

    echo json_encode($response);
    exit;
}

if (isset($data_nasc) && !empty($data_nasc)) {
    $data_nascimento = $data_nasc;

    if (!validarData($data_nascimento)) {
        $response = array(
            'status' => 'warning',
            'tittle' => 'Atenção',
            'message' => 'Data de nascimento inválida. Verifique o formato da data.',
            'icon' => 'warning',
        );
        echo json_encode($response);
        exit;
    }
} else {
    $data_nascimento = $data_nasc;
}

$sql = $db->prepare(
    "UPDATE proprietarios_tabulares SET 
	 conjuge_nome=:conjuge_nome, 
	 conjuge_regime=:conjuge_regime, 
	 conjuge_data_casamento=:conjuge_data_casamento, 
	 conjuge_cpf=:conjuge_cpf,
	 conjuge_nasc=:conjuge_nasc, 
	 conjuge_sexo=:conjuge_sexo, 
	 conjuge_rg=:conjuge_rg, 
	 conjuge_emissor=:conjuge_emissor, 
	 conjuge_profissao=:conjuge_profissao, 
	 conjuge_estado_civil=:conjuge_estado_civil, 
	 conjuge_telefone=:conjuge_telefone, 
	 conjuge_celular=:conjuge_celular,
	 conjuge_email=:conjuge_email,
 	 conjuge_cep=:conjuge_cep,
	 conjuge_log=:conjuge_log, 
	 conjuge_numero=:conjuge_numero, 
	 conjuge_complemento=:conjuge_complemento, 
	 conjuge_bairro=:conjuge_bairro, 
	 conjuge_municipio=:conjuge_municipio, 
	 conjuge_estado=:conjuge_estado, 
	 conjuge_pai=:conjuge_pai, 
	 conjuge_mae=:conjuge_mae, 
	 conjuge_update=:conjuge_update,
	 conjuge_capacidade=:capacidade
     WHERE id_tab=:id
	 "
);

$sql->bindValue(":conjuge_nome", $nome);
$sql->bindValue(":conjuge_regime", $regime);
$sql->bindValue(":conjuge_data_casamento", $data_casamento);
$sql->bindValue(":conjuge_cpf", $cpf);
$sql->bindValue(":conjuge_nasc", $data_nasc);
$sql->bindValue(":conjuge_sexo", $sexo);
$sql->bindValue(":conjuge_rg", $rg);
$sql->bindValue(":conjuge_emissor", $emissor);
$sql->bindValue(":conjuge_profissao", $profissao);
$sql->bindValue(":conjuge_estado_civil", $estado_civil);
$sql->bindValue(":conjuge_telefone", $telefone);
$sql->bindValue(":conjuge_celular", $celular);
$sql->bindValue(":conjuge_email", $email);
$sql->bindValue(":conjuge_cep", $cep);
$sql->bindValue(":conjuge_log", $logradouro);
$sql->bindValue(":conjuge_numero", $numero);
$sql->bindValue(":conjuge_complemento", $complemente);
$sql->bindValue(":conjuge_bairro", $bairro);
$sql->bindValue(":conjuge_municipio", $municipio);
$sql->bindValue(":conjuge_estado", $estado);
$sql->bindValue(":conjuge_pai", $pai);
$sql->bindValue(":conjuge_mae", $mae);
$sql->bindValue(":capacidade", $capacidade);
$sql->bindValue(":conjuge_update", date('Y-m-d H:i'));
$sql->bindValue(":id", $id);
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
    $atividade->bindValue(":atividade_name", 'Editado proprietário cônjuge: ' . $nome);
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Cadastro salvo com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
}
