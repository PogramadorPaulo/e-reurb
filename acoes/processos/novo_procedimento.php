<?php
require_once '../../config.php';

// Receber os dados enviados pelo AJAX
$modalidade = filter_input(INPUT_POST, 'modalidade', FILTER_DEFAULT);
$nucleo_nome = filter_input(INPUT_POST, 'nucleo_nome', FILTER_DEFAULT);
$id_user = filter_input(INPUT_POST, 'id_user', FILTER_SANITIZE_NUMBER_INT);
$id_municipio = filter_input(INPUT_POST, 'id_municipio', FILTER_SANITIZE_NUMBER_INT);

// Validar os campos obrigatórios
if (!$modalidade || !$nucleo_nome || !$id_user || !$id_municipio) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Todos os campos são obrigatórios!',
        'icon' => 'error'
    ]);
    exit;
}

try {
    // Gerar o número do procedimento automaticamente
    $stmt = $db->prepare("SELECT MAX(id) AS max_id FROM procedures WHERE modalidade = :modalidade");
    $stmt->bindValue(':modalidade', $modalidade);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $ultimoId = $result['max_id'] ? $result['max_id'] + 1 : 1;

    // Definir o prefixo com base na modalidade
    $prefixo = $modalidade === 'Reurb-E' ? 'E' : ($modalidade === 'Reurb-S' ? 'S' : ($modalidade === 'Reurb-M' ? 'M' : 'X'));


    // Número do procedimento no formato desejado (ex: E2024-001 ou S2024-001)
    $anoAtual = date('Y');
    $numero_procedimento = sprintf("%s%s-%03d", $prefixo, $anoAtual, $ultimoId);

    // Gerar o cod_procedimento (10 dígitos únicos)
    $cod_procedimento = generateUniqueCode($db);

    // Inserir os dados no banco de dados
    $stmt = $db->prepare("
        INSERT INTO procedures (cod_procedimento, numero_procedimento, modalidade, nucleo_nome, data_cad, status, id_user, municipio)
        VALUES (:cod_procedimento, :numero_procedimento, :modalidade, :nucleo_nome, NOW(), 1, :id_user, :id_municipio)
    ");
    $stmt->bindValue(':cod_procedimento', $cod_procedimento);
    $stmt->bindValue(':numero_procedimento', $numero_procedimento);
    $stmt->bindValue(':modalidade', $modalidade);
    $stmt->bindValue(':nucleo_nome', $nucleo_nome);
    $stmt->bindValue(':id_user', $id_user);
    $stmt->bindValue(':id_municipio', $id_municipio);
    $stmt->execute();

    // Inserir as etapas do processo usando o valor correto de $cod_procedimento
    $etapas = [
        ['etapa_id' => 1, 'status' => 7], // Etapa 1 Status em Aberto
        ['etapa_id' => 2, 'status' => 2], // Etapa 2
        ['etapa_id' => 3, 'status' => 2], // Etapa 3
        ['etapa_id' => 4, 'status' => 2], // Etapa 4
        ['etapa_id' => 5, 'status' => 2], // Etapa 5
        ['etapa_id' => 6, 'status' => 2], // Etapa 6
        ['etapa_id' => 7, 'status' => 2], // Etapa 7
    ];

    $stmt = $db->prepare("
        INSERT INTO etapas_procedimentos (processo_id, etapa_id, procedimento_status)
        VALUES (:processo_id, :etapa_id, :status)
    ");

    foreach ($etapas as $etapa) {
        $stmt->bindValue(':processo_id', $cod_procedimento, PDO::PARAM_STR); // Usando cod_procedimento diretamente
        $stmt->bindValue(':etapa_id', $etapa['etapa_id'], PDO::PARAM_INT);
        $stmt->bindValue(':status', $etapa['status'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // Nome dinâmico para o histórico
    $etapaName = "Processo: {$cod_procedimento} aberto.";

    // Inserir no histórico do processo
    $historico = $db->prepare("
        INSERT INTO tb_etapa_historico 
        (h_idProcesso, h_statusID, h_name, h_date, h_user) 
        VALUES (:processo, :h_statusID, :name, :data, :user)
    ");
    $historico->bindValue(':processo', $cod_procedimento, PDO::PARAM_STR);
    $historico->bindValue(':h_statusID', 7);
    $historico->bindValue(':user', $id_user, PDO::PARAM_INT);
    $historico->bindValue(':name', "$etapaName");
    $historico->bindValue(':data', date('Y-m-d H:i:s'));
    $historico->execute();

    // Inserir no histórico do usuário
    $atividade = $db->prepare("
        INSERT INTO tb_atividades_usuarios 
        (atividade_user, atividade_name, atividade_data) 
        VALUES (:id_user, :atividade_name, :atividade_data)
    ");
    $atividade->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $atividade->bindValue(':atividade_name', "$etapaName");
    $atividade->bindValue(':atividade_data', date('Y-m-d H:i:s'));
    $atividade->execute();

    echo json_encode([
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Procedimento criado com sucesso! Número: ' . $numero_procedimento,
        'icon' => 'success',
        'codigo' => $cod_procedimento // Adicione o código do novo procedimento
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao criar o procedimento: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}

/**
 * Gera um código único de 10 dígitos para `cod_procedimento`
 */
function generateUniqueCode($db)
{
    do {
        $code = random_int(1000000000, 9999999999); // Gera um número aleatório de 10 dígitos
        $stmt = $db->prepare("SELECT COUNT(*) FROM procedures WHERE cod_procedimento = :code");
        $stmt->bindValue(':code', $code, PDO::PARAM_INT);
        $stmt->execute();
        $exists = $stmt->fetchColumn() > 0; // Verifica se o código já existe
    } while ($exists);

    return $code;
}
