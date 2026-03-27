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

// Buscar o número atual do procedimento
$stmtNumero = $db->prepare("SELECT numero_procedimento FROM procedures WHERE id = :id");
$stmtNumero->bindValue(':id', $dados['id'], PDO::PARAM_INT);
$stmtNumero->execute();
$registro = $stmtNumero->fetch(PDO::FETCH_ASSOC);

// Extrair parte numérica (últimos 3 dígitos) e ano
$numeroAtual = $registro['numero_procedimento']; // ex: M2025-009
$numeroPartes = explode('-', $numeroAtual);      // ['M2025', '009']
$sequencial = $numeroPartes[1] ?? '001';
$ano = substr($numeroPartes[0], 1);              // remove o prefixo (M) e mantém o ano

// Definir novo prefixo conforme modalidade
switch ($dados['modalidade']) {
    case 'Reurb-E': $prefixo = 'E'; break;
    case 'Reurb-S': $prefixo = 'S'; break;
    case 'Reurb-M': $prefixo = 'M'; break;
    default: $prefixo = 'X';
}

$novoNumeroProcedimento = sprintf('%s%s-%s', $prefixo, $ano, $sequencial);


// Atualizar os dados no banco de dados
$sql = $db->prepare("
    UPDATE procedures SET 
        modalidade = :modalidade,
        forma_organizacao = :forma_organizacao,
        tipo = :tipo,
        n_portaria = :n_portaria,
        presidente_comissao = :presidente_comissao,
        rito = :rito,
        numero_procedimento = :numero_procedimento,
        data_update = :data_update 
    WHERE id = :id
");

$sql->bindValue(":modalidade", $dados['modalidade']);
$sql->bindValue(":forma_organizacao", $dados['forma']);
$sql->bindValue(":tipo", $dados['tipo']);
$sql->bindValue(":n_portaria", $dados['n_portaria']);
$sql->bindValue(":presidente_comissao", $dados['presidente_comissao']);
$sql->bindValue(":rito", $dados['rito']);
$sql->bindValue(":numero_procedimento", $novoNumeroProcedimento);
$sql->bindValue(":data_update", date('Y-m-d H:i:s'));
$sql->bindValue(":id", $dados['id'], PDO::PARAM_INT);

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
    $atividade->bindValue(":atividade_name", 'Processo atualizado - Aba Tipo: ' . $dados_array);
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
