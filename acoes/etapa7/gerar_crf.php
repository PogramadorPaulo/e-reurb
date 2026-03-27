<?php
session_start();
header('Content-Type: application/json');
include_once "../../config.php";

$id_procedimento = filter_input(INPUT_POST, "id_procedimento", FILTER_SANITIZE_NUMBER_INT);
// Verificar se a etapa está concluída
if (isEtapaConcluida($id_procedimento, 7, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}


$id_municipio = filter_input(INPUT_POST, 'id_municipio', FILTER_VALIDATE_INT);
$id_user = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT);

if (!$id_procedimento || !$id_municipio || !$id_user) {
    echo json_encode(['status' => 'warning', 'tittle' => 'Atenção', 'message' => 'Dados inválidos.', 'icon' => 'warning']);
    exit;
}


require 'pdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ativar suporte a URLs remotas
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/../../')); // Garante que as imagens locais sejam lidas
$dompdf = new Dompdf($options);


function isValidDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
function isValidString($string)
{
    return isset($string) && !empty($string);
}
function isValidCPF($cpf)
{
    return preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf);
}
function isValidCEP($cep)
{
    return preg_match('/^\d{5}-\d{3}$/', $cep);
}
function generateUniqueDocNumber($db, $length = 8)
{
    do {
        $doc_numero = '';
        for ($i = 0; $i < $length; $i++) {
            $doc_numero .= mt_rand(0, 9);
        }
        $stmt = $db->prepare("SELECT COUNT(*) FROM tb_etapas_anexos WHERE anexo_id = :anexo_id");
        $stmt->bindParam(':anexo_id', $doc_numero);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    } while ($count > 0);
    return $doc_numero;
}
function preencherTemplate($templatePath, $dados)
{
    if (!file_exists($templatePath)) throw new Exception("Template não encontrado: " . $templatePath);
    $template = file_get_contents($templatePath);
    foreach ($dados as $chave => $valor) {
        $valor = is_array($valor) ? implode(', ', $valor) : $valor;
        $template = str_replace("{{" . $chave . "}}", strpos($chave, '_html') !== false ? $valor : htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'), $template);
    }
    return $template;
}
function buscarLotesPorTipo($tipo, $id_procedimento, $db)
{
    $stmtQuadras = $db->prepare("SELECT quadra_id, quadra_letra FROM tb_quadras WHERE quadra_prc = :id AND quadra_status = 1");
    $stmtQuadras->bindParam(':id', $id_procedimento);
    $stmtQuadras->execute();
    $resultado = [];
    while ($quadra = $stmtQuadras->fetch(PDO::FETCH_ASSOC)) {
        $stmtLotes = $db->prepare("
            SELECT l.lote_id, l.lote_number, l.lote_memorial, l.lote_tipo,
                COALESCE(GROUP_CONCAT(p.nome ORDER BY p.nome SEPARATOR ', '), 'Sem proprietário') AS nomeProprietarios
            FROM tb_lotes l
            LEFT JOIN tb_lotes_proprietarios lp ON l.lote_id = lp.loteP_idLote AND lp.loteP_status = 1
            LEFT JOIN proprietarios_tabulares p ON lp.loteP_idProprietario = p.id_tab
            WHERE l.lote_quadra = :quadraId AND l.lote_status = 1 AND l.lote_tipo = :tipo
            GROUP BY l.lote_id
            ORDER BY l.lote_number");
        $stmtLotes->bindParam(':quadraId', $quadra['quadra_id']);
        $stmtLotes->bindParam(':tipo', $tipo);
        $stmtLotes->execute();
        $lotes = $stmtLotes->fetchAll(PDO::FETCH_ASSOC);
        if ($lotes) $resultado[] = ['quadra_letra' => $quadra['quadra_letra'], 'lotes' => $lotes];
    }
    return $resultado;
}
function gerarHTMLLotes($quadras)
{
    $html = '';
    foreach ($quadras as $quadra) {
        $html .= '<li><b>Quadra: ' . htmlspecialchars($quadra['quadra_letra']) . '</b></li>';
        foreach ($quadra['lotes'] as $lote) {
            $html .= '<ul><li><b>Lote: ' . htmlspecialchars($lote['lote_number']) . '</b> - Proprietário(s): <b>' .
                htmlspecialchars($lote['nomeProprietarios']) . '</b><br><b>Memorial descritivo:</b> <i>' .
                htmlspecialchars($lote['lote_memorial']) . '</i></li><hr></ul>';
        }
    }
    return $html;
}

// 1. Recuperar dados de configuração do município
$sql_config = $db->prepare("
        SELECT * FROM tb_municipios 
        WHERE municipio_id = :id and municipio_status = 1
    ");
$sql_config->bindValue(":id", $id_municipio);
$sql_config->execute();
$config = $sql_config->fetch(PDO::FETCH_ASSOC);
$logoPath = BASE_URL  . 'assets/tema/images/' . $config['municipio_logo_municipal'];

if (file_exists($logoPath)) {
    $dados['logo'] = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
} else {
    $dados['logo'] = ''; // Ou uma imagem padrão/base64 de erro
}


if (!$config) {
    $response = array(
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Configuração do município não encontrada.',
        'icon' => 'error',
    );

    echo json_encode($response);
    exit;
}


$sql_comissao = $db->prepare("SELECT * FROM comissao LEFT JOIN comissao_funcao ON comissao.funcao = comissao_funcao.id_funcao WHERE id_municipio = :id");
$sql_comissao->bindValue(":id", $id_municipio);
$sql_comissao->execute();
$comissao = $sql_comissao->fetch(PDO::FETCH_ASSOC);

$sql_proc = $db->prepare("SELECT * FROM procedures WHERE cod_procedimento = :id");
$sql_proc->bindParam(':id', $id_procedimento);
$sql_proc->execute();
$procedure = $sql_proc->fetch(PDO::FETCH_ASSOC);

$quadras_E = buscarLotesPorTipo('E', $id_procedimento, $db);
$quadras_S = buscarLotesPorTipo('S', $id_procedimento, $db);

$dados = array_merge($config, $comissao, $procedure);
$dados['lotes_E_html'] = gerarHTMLLotes($quadras_E);
$dados['lotes_S_html'] = gerarHTMLLotes($quadras_S);

// Buscar proprietários tabulares e seus cônjuges
$sql_tab = $db->prepare("SELECT * FROM proprietarios_tabulares WHERE id_tab_procedimento = :id AND status_tab = 1 ORDER BY nome");
$sql_tab->bindValue(":id", $id_procedimento);
$sql_tab->execute();
$proprietarios_tabulares = '';
while ($row = $sql_tab->fetch(PDO::FETCH_ASSOC)) {
    $nome = isValidString($row['nome']) ? $row['nome'] : '{não informado}';
    $cpf = isValidCPF($row['cpf']) ? $row['cpf'] : '{não informado}';
    $rg = isValidString($row['rg']) ? $row['rg'] : '{não informado}';
    $profissao = isValidString($row['profissao']) ? $row['profissao'] : '{não informado}';
    $dataNasc = isValidDate($row['data_nasc']) ? date('d/m/Y', strtotime($row['data_nasc'])) : '{não informado}';
    $pai = isValidString($row['pai']) ? $row['pai'] : '{não informado}';
    $mae = isValidString($row['mae']) ? $row['mae'] : '{não informado}';
    $estadoCivil = isValidString($row['estado_civil']) ? $row['estado_civil'] : '{não informado}';
    $logradouro = isValidString($row['logradouro']) ? $row['logradouro'] : '{não informado}';
    $numero = isValidString($row['numero']) ? $row['numero'] : '{não informado}';
    $complemento = isValidString($row['complemento']) ? $row['complemento'] : '{não informado}';
    $bairro = isValidString($row['bairro']) ? $row['bairro'] : '{não informado}';
    $cidade = isValidString($row['cidade']) ? $row['cidade'] : '{não informado}';
    $estado = isValidString($row['estado']) ? $row['estado'] : '{não informado}';
    $cep = isValidCEP($row['cep']) ? $row['cep'] : '{não informado}';
    $capacidade = isValidString($row['capacidade']) ? $row['capacidade'] : '{não informado}';

    $dataNascimentoProprietario = DateTime::createFromFormat('Y-m-d', $row['data_nasc']);
    $idadeProprietario = $dataNascimentoProprietario ? $dataNascimentoProprietario->diff(new DateTime())->y : 0;
    $maioridadeProprietario = $idadeProprietario >= 18 ? 'maior de idade' : 'menor de idade';

    $proprietario = "<p style='text-align: justify;'><b>Proprietário:</b> {$nome}, CPF: {$cpf}, RG: {$rg}, Profissão: {$profissao}, Nascido em: {$dataNasc} ({$maioridadeProprietario}), Filho de {$pai} e {$mae}, Estado Civil: {$estadoCivil}, Residente no endereço: {$logradouro}, {$numero} {$complemento}, Bairro: {$bairro}, Cidade: {$cidade}, Estado: {$estado}, CEP: {$cep}, Capacidade: {$capacidade}.</p>";

    if ($estadoCivil === 'Casado') {
        $nomeConjuge = isValidString($row['conjuge_nome']) ? $row['conjuge_nome'] : '{não informado}';
        $cpfConjuge = isValidCPF($row['conjuge_cpf']) ? $row['conjuge_cpf'] : '{não informado}';
        $rgConjuge = isValidString($row['conjuge_rg']) ? $row['conjuge_rg'] : '{não informado}';
        $profissaoConjuge = isValidString($row['conjuge_profissao']) ? $row['conjuge_profissao'] : '{não informado}';
        $dataNascConjuge = isValidDate($row['conjuge_nasc']) ? date('d/m/Y', strtotime($row['conjuge_nasc'])) : '{não informado}';
        $paiConjuge = isValidString($row['conjuge_pai']) ? $row['conjuge_pai'] : '{não informado}';
        $maeConjuge = isValidString($row['conjuge_mae']) ? $row['conjuge_mae'] : '{não informado}';

        $dataNascimentoConjuge = DateTime::createFromFormat('Y-m-d', $row['conjuge_nasc']);
        $idadeConjuge = $dataNascimentoConjuge ? $dataNascimentoConjuge->diff(new DateTime())->y : 0;
        $maioridadeConjuge = $idadeConjuge >= 18 ? 'maior de idade' : 'menor de idade';

        $conjuge = "<p style='text-align: justify;'><b>Cônjuge:</b> {$nomeConjuge}, CPF: {$cpfConjuge}, RG: {$rgConjuge}, Profissão: {$profissaoConjuge}, Nascido em: {$dataNascConjuge} ({$maioridadeConjuge}), Filho de {$paiConjuge} e {$maeConjuge}.</p>";
        $proprietario .= $conjuge;
    }

    $proprietarios_tabulares .= $proprietario . "<hr>";
}
$dados['proprietarios_tabulares_html'] = $proprietarios_tabulares;

$dataAtual = new DateTime();
$meses = [1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril', 5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto', 9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'];
$dados['dia'] = $dataAtual->format('d');
$dados['mes'] = $meses[(int)$dataAtual->format('m')];
$dados['ano'] = $dataAtual->format('Y');

$dados['logo'] = $logoPath; // Adicionando o caminho da logo

$templatePath = 'templates/CRF.html';
$documentoPreenchido = preencherTemplate($templatePath, $dados);
$doc_numero = generateUniqueDocNumber($db);
$caminhoPDF = __DIR__ . "/../../assets/documentos/crf_{$doc_numero}.pdf";

$dompdf->loadHtml($documentoPreenchido);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$canvas = $dompdf->getCanvas();
$font = $dompdf->getFontMetrics()->getFont("Arial", "normal");
$canvas->page_text(520, 20, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, [0, 0, 0]);
file_put_contents($caminhoPDF, $dompdf->output());

$sql_insert = $db->prepare("INSERT INTO tb_etapas_anexos (anexo_prc, anexo_etapa, anexo_titulo, anexo_conteudo, anexo_arquivo, anexo_arquivo_ext, anexo_cadastro, anexo_user) VALUES (:anexo_prc, 7, 'CRF', :anexo_conteudo, :anexo_arquivo, 'pdf', :anexo_cadastro, :anexo_user)");
$sql_insert->execute([
    ':anexo_prc' => $id_procedimento,
    ':anexo_conteudo' => $documentoPreenchido,
    ':anexo_arquivo' => 'crf_' . $doc_numero . '.pdf',
    ':anexo_cadastro' => date('Y-m-d H:i:s'),
    ':anexo_user' => $id_user
]);

$atividade = $db->prepare("INSERT INTO tb_atividades_usuarios (atividade_user, atividade_name, atividade_data) VALUES (:atividade_user, :atividade_name, :atividade_data)");
$atividade->execute([
    ':atividade_user' => $id_user,
    ':atividade_name' => "Nova CRF gerada <a href=" . BASE_URL . "assets/documentos/crf_$doc_numero.pdf" > "Arquivo</a>",
    ':atividade_data' => date('Y-m-d H:i:s')
]);

echo json_encode(['status' => 'success', 'tittle' => 'Sucesso', 'message' => 'Documento gerado com sucesso.', 'icon' => 'success']);
