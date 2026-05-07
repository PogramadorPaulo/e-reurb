<?php
require_once('../../../config.php');

function somenteNumerosRequerente($valor)
{
    return preg_replace('/\D+/', '', (string) $valor);
}

function validarCpfRequerente($cpf)
{
    $cpf = somenteNumerosRequerente($cpf);

    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    for ($digito = 9; $digito < 11; $digito++) {
        $soma = 0;
        for ($i = 0; $i < $digito; $i++) {
            $soma += (int) $cpf[$i] * (($digito + 1) - $i);
        }

        $resto = ($soma * 10) % 11;
        if ($resto === 10) {
            $resto = 0;
        }

        if ($resto !== (int) $cpf[$digito]) {
            return false;
        }
    }

    return true;
}

function validarCnpjRequerente($cnpj)
{
    $cnpj = somenteNumerosRequerente($cnpj);

    if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
        return false;
    }

    $tamanho = strlen($cnpj) - 2;
    $numeros = substr($cnpj, 0, $tamanho);
    $digitos = substr($cnpj, $tamanho);
    $soma = 0;
    $pos = $tamanho - 7;

    for ($i = $tamanho; $i >= 1; $i--) {
        $soma += (int) $numeros[$tamanho - $i] * $pos--;
        if ($pos < 2) {
            $pos = 9;
        }
    }

    $resultado = $soma % 11 < 2 ? 0 : 11 - ($soma % 11);
    if ($resultado !== (int) $digitos[0]) {
        return false;
    }

    $tamanho++;
    $numeros = substr($cnpj, 0, $tamanho);
    $soma = 0;
    $pos = $tamanho - 7;

    for ($i = $tamanho; $i >= 1; $i--) {
        $soma += (int) $numeros[$tamanho - $i] * $pos--;
        if ($pos < 2) {
            $pos = 9;
        }
    }

    $resultado = $soma % 11 < 2 ? 0 : 11 - ($soma % 11);
    return $resultado === (int) $digitos[1];
}

/** @var PDO $db */

$idProcedimento = filter_input(INPUT_POST, "idProcedimento", FILTER_SANITIZE_NUMBER_INT);
$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

$tipo =  filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_SPECIAL_CHARS);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_SPECIAL_CHARS);
$cnpj = filter_input(INPUT_POST, "cnpj", FILTER_SANITIZE_SPECIAL_CHARS);
$i_estadual = filter_input(INPUT_POST, "i_estadual", FILTER_SANITIZE_SPECIAL_CHARS);
$i_municipal = filter_input(INPUT_POST, "i_municipal", FILTER_SANITIZE_SPECIAL_CHARS);
$representante = filter_input(INPUT_POST, "representante", FILTER_SANITIZE_SPECIAL_CHARS);
$cargo =  filter_input(INPUT_POST, "cargo", FILTER_SANITIZE_SPECIAL_CHARS);
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

// Verificar se a etapa está concluída
if (isEtapaConcluida($idProcedimento, 1, $db)) {
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
    empty($tipo) ||
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

if (!in_array($tipo, ['Física', 'Jurídica'], true)) {
    echo json_encode([
        'status' => 'warning',
        'title' => 'Atenção',
        'message' => 'Selecione o tipo de pessoa.',
        'icon' => 'warning',
    ]);
    exit;
}

if ($tipo === 'Física') {
    $cnpj = '';
    $i_estadual = '';
    $i_municipal = '';
    $representante = '';
    $cargo = '';

    if (!validarCpfRequerente($cpf)) {
        echo json_encode([
            'status' => 'warning',
            'title' => 'Atenção',
            'message' => 'Informe um CPF válido.',
            'icon' => 'warning',
        ]);
        exit;
    }
} elseif ($tipo === 'Jurídica') {
    $cpf = '';
    $pai = '';
    $mae = '';

    if (!validarCnpjRequerente($cnpj)) {
        echo json_encode([
            'status' => 'warning',
            'title' => 'Atenção',
            'message' => 'Informe um CNPJ válido.',
            'icon' => 'warning',
        ]);
        exit;
    }
}

// Verificar se o CPF ou CNPJ já está cadastrado em outro requerente do mesmo procedimento
$campoDocumento = $tipo === 'Jurídica' ? 'cnpj' : 'cpf';
$valorDocumento = $tipo === 'Jurídica' ? $cnpj : $cpf;
$check = $db->prepare("
    SELECT id_requerente
    FROM requerentes
    WHERE {$campoDocumento} = :documento
      AND id_procedimento = :id_procedimento
      AND id_requerente <> :id
      AND status_requente = 1
    LIMIT 1
");
$check->bindValue(":documento", $valorDocumento);
$check->bindValue(":id_procedimento", $idProcedimento);
$check->bindValue(":id", $id);
$check->execute();

if ($check->rowCount() > 0) {
    echo json_encode([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Já existe outro requerente cadastrado com este CPF ou CNPJ.',
        'icon' => 'error',
    ]);
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
    "UPDATE requerentes SET 
	 tipo_pessoa=:tipo_pessoa, cpf=:cpf, cnpj=:cnpj, i_estadual=:i_estadual, i_municipal=:i_municipal, 
	 representante=:representante, cargo=:cargo, nome=:nome, data_nasc=:data_nasc, sexo=:sexo, rg=:rg, emissor=:emissor, profissao=:profissao,
	 estado_civil=:estado_civil, uniao_estavel=:uniao_estavel, telefone=:telefone, celular=:celular, email=:email, cep=:cep, 
	 logradouro=:logradouro, numero=:numero, complemento=:complemento, bairro=:bairro, cidade=:cidade, estado=:estado, pai=:pai, mae=:mae, 
	 data_update=:data_update
     WHERE id_requerente=:id
	 "
);

$sql->bindValue(":tipo_pessoa", $tipo);
$sql->bindValue(":cpf", $cpf);
$sql->bindValue(":cnpj", $cnpj);
$sql->bindValue(":i_estadual", $i_estadual);
$sql->bindValue(":i_municipal", $i_municipal);
$sql->bindValue(":representante", $representante);
$sql->bindValue(":cargo", $cargo);
$sql->bindValue(":nome", $nome);
$sql->bindValue(":data_nasc", $data_nascimento);
$sql->bindValue(":sexo", $sexo);
$sql->bindValue(":rg", $rg);
$sql->bindValue(":emissor", $emissor);
$sql->bindValue(":profissao", $profissao);
$sql->bindValue(":estado_civil", $estado_civil);
$sql->bindValue(":uniao_estavel", $uniao);
$sql->bindValue(":telefone", $telefone);
$sql->bindValue(":celular", $celular);
$sql->bindValue(":email", $email);
$sql->bindValue(":cep", $cep);
$sql->bindValue(":logradouro", $logradouro);
$sql->bindValue(":numero", $numero);
$sql->bindValue(":complemento", $complemente);
$sql->bindValue(":bairro", $bairro);
$sql->bindValue(":cidade", $municipio);
$sql->bindValue(":estado", $estado);
$sql->bindValue(":pai", $pai);
$sql->bindValue(":mae", $mae);
$sql->bindValue(":data_update", date('Y-m-d H:i'));
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
    $atividade->bindValue(":atividade_name", 'Editado requerente: ' . $nome);
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Cadastro salvo com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
} else {
    $response = array(
        'status' => 'info',
        'tittle' => 'Sucesso',
        'message' => 'Não houve nenhuma alteração nos dados para salvar.',
        'icon' => 'info',
    );
    echo json_encode($response);
}
