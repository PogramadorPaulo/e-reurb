<?php
include_once "../../../config.php";


// Função para retornar resposta JSON
function sendResponse($status, $title, $message, $icon)
{
    $response = array(
        'status' => $status,
        'title' => $title,
        'message' => $message,
        'icon' => $icon,
    );
    echo json_encode($response);
    exit;
}

// Verificar se os dados do POST foram recebidos
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$dados) {
    sendResponse('error', 'Erro', 'Dados inválidos.', 'error');
}


// Verificar se a etapa está concluída
if (isEtapaConcluida($dados['idProcedimento'], 2, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}

// Validar apenas os campos que são essenciais
if (empty($dados['id']) || empty($dados['idUser'])) {
    sendResponse('error', 'Erro', 'Campos obrigatórios não preenchidos.', 'error');
}

// Processar o array de natureza_caracteristica, se existir
$v = isset($dados['natureza_caracteristica']) ? implode(', ', $dados['natureza_caracteristica']) : '';

// Preparar a query de atualização
$sql = $db->prepare(
    "UPDATE procedures SET 
        nucleo_caracteristica=:nucleo_caracteristica, 
        nucleo_data_implantacao=:nucleo_data_implantacao, 
        nucleo_nome=:nucleo_nome, 
        nucleo_rural=:nucleo_rural, 
        nucleo_d_rural=:nucleo_d_rural, 
        nucleo_cdr=:nucleo_cdr, 
        nucleo_profissional=:nucleo_profissional,
        nucleo_profissional_registro=:nucleo_profissional_registro,
        nucleo_profissional_rg_data=:nucleo_profissional_rg_data,
        nucleo_cep=:nucleo_cep, 
        nucleo_endereco=:nucleo_endereco, 
        nucleo_numero_inical=:nucleo_numero_inical, 
        nucleo_numero_final=:nucleo_numero_final, 
        nucleo_memorial=:nucleo_memorial, 
        nucleo_coordenadas=:nucleo_coordenadas,
        nucleo_aprovacao_amb=:nucleo_aprovacao_amb, 
        nucleo_equipamentos=:nucleo_equipamentos, 
        nucleo_natureza=:nucleo_natureza, 
        nucleo_natureza_caract=:nucleo_natureza_caract, 
        nucleo_agua=:nucleo_agua, 
        nucleo_energia=:nucleo_energia, 
        nucleo_saneamento=:nucleo_saneamento, 
        nucleo_drenagem=:nucleo_drenagem, 
        nucleo_benfeitorias=:nucleo_benfeitorias,
        nucleo_cpf_res=:nucleo_cpf_res, 
        nucleo_nome_res=:nucleo_nome_res, 
        nucleo_comp_urbanistica=:nucleo_comp_urbanistica, 
        nucleo_comp_ambiental=:nucleo_comp_ambiental, 
        nucleo_notif=:nucleo_notif, 
        nucleo_update_data=:nucleo_update_data
    WHERE id=:id"
);

// Bind dos valores usando o array $dados
try {
    // Verificar e associar apenas os campos que foram preenchidos
    $sql->bindValue(":nucleo_caracteristica", $dados['carac'] ?? null);
    $sql->bindValue(":nucleo_data_implantacao", $dados['data_implantacao'] ?? null);
    $sql->bindValue(":nucleo_nome", $dados['nome'] ?? null);
    $sql->bindValue(":nucleo_rural", $dados['rural'] ?? null);
    $sql->bindValue(":nucleo_d_rural", $dados['denominacao_rural'] ?? null);
    $sql->bindValue(":nucleo_cdr", $dados['cdr'] ?? null);
    $sql->bindValue(":nucleo_profissional", $dados['nucleo_profissional'] ?? null);
    $sql->bindValue(":nucleo_profissional_registro", $dados['nucleo_profissional_registro'] ?? null);
    $sql->bindValue(":nucleo_profissional_rg_data", $dados['nucleo_profissional_rg_data'] ?? null);
    $sql->bindValue(":nucleo_cep", $dados['cep'] ?? null);
    $sql->bindValue(":nucleo_endereco", $dados['endereco'] ?? null);
    $sql->bindValue(":nucleo_numero_inical", $dados['n_inicial'] ?? null);
    $sql->bindValue(":nucleo_numero_final", $dados['n_final'] ?? null);
    $sql->bindValue(":nucleo_memorial", $dados['memorial'] ?? null);
    $sql->bindValue(":nucleo_coordenadas", $dados['coordenadas'] ?? null);
    $sql->bindValue(":nucleo_aprovacao_amb", $dados['ap_ambiental'] ?? null);
    $sql->bindValue(":nucleo_equipamentos", $dados['equipamentos'] ?? null);
    $sql->bindValue(":nucleo_natureza", $dados['natureza'] ?? null);
    $sql->bindValue(":nucleo_natureza_caract", $v ?? null); // Natureza característica processada acima
    $sql->bindValue(":nucleo_agua", $dados['agua'] ?? null);
    $sql->bindValue(":nucleo_energia", $dados['energia'] ?? null);
    $sql->bindValue(":nucleo_saneamento", $dados['saneamento'] ?? null);
    $sql->bindValue(":nucleo_drenagem", $dados['drenagem'] ?? null);
    $sql->bindValue(":nucleo_benfeitorias", $dados['benfeitorias'] ?? null);
    $sql->bindValue(":nucleo_cpf_res", $dados['cpf_responsavel'] ?? null);
    $sql->bindValue(":nucleo_nome_res", $dados['nome_responsavel'] ?? null);
    $sql->bindValue(":nucleo_comp_urbanistica", $dados['urbanisticas'] ?? null);
    $sql->bindValue(":nucleo_comp_ambiental", $dados['ambientais'] ?? null);
    $sql->bindValue(":nucleo_notif", $dados['notificar'] ?? null);
    $sql->bindValue(":nucleo_update_data", date('Y-m-d H:i'));
    $sql->bindValue(":id", $dados['id']);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        // Função para converter arrays em strings legíveis
        /* function arrayToString($dados)
        {
            $result = [];
            foreach ($dados as $key => $value) {
                // Verificar se o valor é um array e convertê-lo para uma string
                if (is_array($value)) {
                    $result[] = $key . ': ' . implode(', ', $value);
                } else {
                    $result[] = $key . ': ' . $value;
                }
            }
            return implode(' | ', $result); // Usando ' | ' para separar os campos
        }

        // Utilizar a função ao criar o log
        $dados_array = arrayToString($dados);

        $atividade = $db->prepare("
            INSERT INTO tb_atividades_usuarios 
            (atividade_user, atividade_name, atividade_data) 
            VALUES (:atividade_user, :atividade_name, :atividade_data)
        ");
        $atividade->bindValue(":atividade_user", $dados['idUser']);
        $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
        $atividade->bindValue(":atividade_name", 'Processo atualizado - Descrição do núcleo: ' . $dados_array);
        $atividade->execute();
        */
        sendResponse('success', 'Sucesso', 'Atualização efetuada com sucesso.', 'success');
    } else {
        sendResponse('info', 'Sucesso', 'Não houve nenhuma alteração nos dados para salvar.', 'info');
    }
} catch (PDOException $e) {
    sendResponse('error', 'Erro', 'Erro na atualização: ' . $e->getMessage(), 'error');
}
