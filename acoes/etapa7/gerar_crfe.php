<?php
session_start();

// Definir o cabeçalho para JSON antes de qualquer saída
header('Content-Type: application/json');

// Incluir as configurações do banco de dados
include_once "../../config.php";

// Recebe o ID do procedimento
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


// Adicionar Dompdf para geração do PDF
require 'pdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ativar suporte a URLs remotas
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$idMunicipio = $_POST['id_municipio'];

/**
 * Função para validar datas.
 */
function isValidDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Função para validar strings.
 */
function isValidString($string)
{
    return isset($string) && !empty($string);
}

/**
 * Função para validar CPF (básico).
 */
function isValidCPF($cpf)
{
    return preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf);
}

/**
 * Função para validar CEP.
 */
function isValidCEP($cep)
{
    return preg_match('/^\d{5}-\d{3}$/', $cep);
}

/**
 * Gera um número único aleatório para o documento.
 */
function generateUniqueDocNumber($db, $length = 8)
{
    do {
        $doc_numero = '';
        for ($i = 0; $i < $length; $i++) {
            $doc_numero .= mt_rand(0, 9);
        }

        $stmt = $db->prepare("SELECT COUNT(*) FROM tb_etapas_anexos WHERE anexo_id = :anexo_id");
        $stmt->bindParam(':anexo_id', $doc_numero, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    } while ($count > 0);

    return $doc_numero;
}

/**
 * Preenche o template HTML substituindo os placeholders pelos valores correspondentes.
 */
function preencherTemplate($templatePath, $dados)
{
    if (!file_exists($templatePath)) {
        throw new Exception("Template não encontrado: " . $templatePath);
    }

    $template = file_get_contents($templatePath);

    foreach ($dados as $chave => $valor) {
        if (is_array($valor)) {
            $valor = implode(', ', $valor);
        }

        if (strpos($chave, '_html') !== false) {
            $template = str_replace("{{" . $chave . "}}", $valor, $template);
        } else {
            $template = str_replace("{{" . $chave . "}}", htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'), $template);
        }
    }

    return $template;
}

try {
    $id_procedimento = filter_input(INPUT_POST, 'id_procedimento', FILTER_VALIDATE_INT);
    $id_municipio = filter_input(INPUT_POST, 'id_municipio', FILTER_VALIDATE_INT);
    $id_user = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT);
    if (!$id_procedimento) {
        $response = array(
            'status' => 'warning',
            'tittle' => 'Atenção',
            'message' => 'ID do procedimento inválido.',
            'icon' => 'warning',
        );

        echo json_encode($response);
        exit;
    }
    if (!$id_municipio) {

        $response = array(
            'status' => 'warning',
            'tittle' => 'Atenção',
            'message' => 'ID do município inválido.',
            'icon' => 'warning',
        );

        echo json_encode($response);
        exit;
    }

    if (!$id_user) {

        $response = array(
            'status' => 'warning',
            'tittle' => 'Atenção',
            'message' => 'ID do usuário inválido.',
            'icon' => 'warning',
        );

        echo json_encode($response);
        exit;
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

    // 2. Recuperar dados da comissão
    $sql_comissao = $db->prepare("
        SELECT * FROM comissao 
        LEFT JOIN comissao_funcao ON comissao.funcao = comissao_funcao.id_funcao
        WHERE id_municipio = :id
    ");
    $sql_comissao->bindValue(":id", $id_municipio);
    $sql_comissao->execute();
    $comissao = $sql_comissao->fetch(PDO::FETCH_ASSOC);
    if (!$comissao) {

        $response = array(
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Comissão não encontrada.',
            'icon' => 'error',
        );

        echo json_encode($response);
        exit;
    }

    // 3. Recuperar dados do requerentes
    $sql_requerente = $db->prepare("SELECT * FROM requerentes WHERE id_procedimento=:id and status_requente = 1 ");
    $sql_requerente->bindValue(":id", $id_procedimento);
    $sql_requerente->execute();
    $requerentes = $sql_requerente->fetch(PDO::FETCH_ASSOC);
    if (!$requerentes) {

        $response = array(
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Requeremtes não encontrada.',
            'icon' => 'error',
        );

        echo json_encode($response);
        exit;
    }

    // 3. Recuperar dados do procedimento
    $sql_proc = $db->prepare("SELECT * FROM procedures WHERE cod_procedimento = :id");
    $sql_proc->bindParam(':id', $id_procedimento, PDO::PARAM_INT);
    $sql_proc->execute();
    $procedure = $sql_proc->fetch(PDO::FETCH_ASSOC);
    if (!$procedure) {

        $response = array(
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Procedimento não encontrado.',
            'icon' => 'error',
        );

        echo json_encode($response);
        exit;
    }

    // 4. Buscar quadras e lotes
    $queryQuadras = "SELECT quadra_id, quadra_letra FROM tb_quadras WHERE quadra_prc = :id AND quadra_status = 1";
    $stmtQuadras = $db->prepare($queryQuadras);
    $stmtQuadras->bindParam(':id', $id_procedimento, PDO::PARAM_INT);
    $stmtQuadras->execute();

    $quadras = [];
    while ($quadra = $stmtQuadras->fetch(PDO::FETCH_ASSOC)) {
        $quadraId = $quadra['quadra_id'];

        $queryLotes = "
            SELECT 
                l.lote_id,
                l.lote_number,
                l.lote_memorial,
                COALESCE(GROUP_CONCAT(p.nome ORDER BY p.nome SEPARATOR ', '), 'Sem proprietário') AS nomeProprietarios
            FROM tb_lotes l
            LEFT JOIN tb_lotes_proprietarios lp ON l.lote_id = lp.loteP_idLote AND lp.loteP_status = 1
            LEFT JOIN proprietarios_tabulares p ON lp.loteP_idProprietario = p.id_tab
            WHERE l.lote_quadra = :quadraId AND l.lote_status = 1
            GROUP BY l.lote_id
            ORDER BY l.lote_number
        ";
        $stmtLotes = $db->prepare($queryLotes);
        $stmtLotes->bindParam(':quadraId', $quadraId, PDO::PARAM_INT);
        $stmtLotes->execute();

        $lotes = $stmtLotes->fetchAll(PDO::FETCH_ASSOC);
        $quadras[] = [
            'quadra_letra' => $quadra['quadra_letra'],
            'lotes' => $lotes
        ];
    }

    // Gerar lista de lotes em HTML
    $lotes_lista = '';
    foreach ($quadras as $quadra) {
        $lotes_lista .= '<li><b>Quadra: ' . htmlspecialchars($quadra['quadra_letra']) . '</b></li>';
        foreach ($quadra['lotes'] as $lote) {
            $lotes_lista .= '
            <ul>
              <li>
                 <b>Lote: ' . htmlspecialchars($lote['lote_number']) . '</b> - Proprietário(s): <b>' . htmlspecialchars($lote['nomeProprietarios']) . '</b>
                 <br>
                 <b>Memorial descritivo:</b> <i>' . htmlspecialchars($lote['lote_memorial']) . '</i>
              </li>
              <hr>
            </ul>';
        }
    }

    // 5. Recuperar proprietários tabulares
    $sql_tab = $db->prepare("SELECT * FROM proprietarios_tabulares WHERE id_tab_procedimento = :id AND status_tab = 1 ORDER BY nome");
    $sql_tab->bindValue(":id", $id_procedimento);
    $sql_tab->execute();

    $proprietarios_tabulares = '';
    while ($row = $sql_tab->fetch(PDO::FETCH_ASSOC)) {
        // Validar e formatar dados do proprietário
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

        // Calcular maioridade do proprietário
        $dataNascimentoProprietario = DateTime::createFromFormat('Y-m-d', $row['data_nasc']);
        $idadeProprietario = $dataNascimentoProprietario ? $dataNascimentoProprietario->diff(new DateTime())->y : 0;
        $maioridadeProprietario = $idadeProprietario >= 18 ? 'maior de idade' : 'menor de idade';

        // Montar informações do proprietário
        $proprietario = "
        <p style='text-align: justify;'><b>Proprietário:</b> {$nome}, CPF: {$cpf}, RG: {$rg}, 
        Profissão: {$profissao}, Nascido em: {$dataNasc} ({$maioridadeProprietario}), 
        Filho de {$pai} e {$mae}, Estado Civil: {$estadoCivil}, 
        Residente no endereço: {$logradouro}, {$numero} {$complemento}, 
        Bairro: {$bairro}, Cidade: {$cidade}, Estado: {$estado}, 
        CEP: {$cep}, Capacidade: {$capacidade}.</p>";

        // Verificar e formatar dados do cônjuge, se aplicável
        if ($estadoCivil === 'Casado') {
            $nomeConjuge = isValidString($row['conjuge_nome']) ? $row['conjuge_nome'] : '{não informado}';
            $cpfConjuge = isValidCPF($row['conjuge_cpf']) ? $row['conjuge_cpf'] : '{não informado}';
            $rgConjuge = isValidString($row['conjuge_rg']) ? $row['conjuge_rg'] : '{não informado}';
            $profissaoConjuge = isValidString($row['conjuge_profissao']) ? $row['conjuge_profissao'] : '{não informado}';
            $dataNascConjuge = isValidDate($row['conjuge_nasc']) ? date('d/m/Y', strtotime($row['conjuge_nasc'])) : '{não informado}';
            $paiConjuge = isValidString($row['conjuge_pai']) ? $row['conjuge_pai'] : '{não informado}';
            $maeConjuge = isValidString($row['conjuge_mae']) ? $row['conjuge_mae'] : '{não informado}';

            // Calcular maioridade do cônjuge
            $dataNascimentoConjuge = DateTime::createFromFormat('Y-m-d', $row['conjuge_nasc']);
            $idadeConjuge = $dataNascimentoConjuge ? $dataNascimentoConjuge->diff(new DateTime())->y : 0;
            $maioridadeConjuge = $idadeConjuge >= 18 ? 'maior de idade' : 'menor de idade';

            $conjuge = "
            <p style='text-align: justify;'><b>Cônjuge:</b> {$nomeConjuge}, CPF: {$cpfConjuge}, RG: {$rgConjuge}, 
            Profissão: {$profissaoConjuge}, Nascido em: {$dataNascConjuge} ({$maioridadeConjuge}), 
            Filho de {$paiConjuge} e {$maeConjuge}.</p>";
            $proprietario .= $conjuge;
        }

        // Adicionar separador entre registros
        $proprietarios_tabulares .= $proprietario . "<hr>";
    }

    // 6. Gerar data atual formatada
    $meses = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];

    $dataAtual = new DateTime();
    $data['dia'] = $dataAtual->format('d');
    $data['mes'] = $meses[(int)$dataAtual->format('m')];
    $data['ano'] = $dataAtual->format('Y');

    // Combinar dados para o template
    $dados = array_merge($config, $comissao, $procedure, $data, $requerentes);
    $dados['lotes_lista_html'] = $lotes_lista;
    $dados['proprietarios_tabulares_html'] = $proprietarios_tabulares;
    $dados['logo'] = $logoPath; // Adicionando o caminho da logo

    // Preencher o template
    $templatePath = 'templates/CRFe.html';
    $documentoPreenchido = preencherTemplate($templatePath, $dados);

    // Gerar um número único para o documento
    $doc_numero = generateUniqueDocNumber($db);

    // Caminho onde o PDF será salvo
    $caminhoPDF = __DIR__ . "/../../assets/documentos/crfe_{$doc_numero}.pdf";

    $dompdf->loadHtml($documentoPreenchido);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Adicionar números de página ao PDF
    $canvas = $dompdf->getCanvas();
    $font = $dompdf->getFontMetrics()->getFont("Arial", "normal");
    $size = 10;

    // Adiciona a paginação no rodapé
    $canvas->page_text(520, 20, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));

    // Salvar o PDF no servidor
    file_put_contents($caminhoPDF, $dompdf->output());

    // Salvar o documento no banco de dados
    $sql_insert = $db->prepare("
        INSERT INTO tb_etapas_anexos (anexo_prc, anexo_etapa, anexo_titulo, anexo_conteudo, anexo_arquivo, anexo_arquivo_ext, anexo_cadastro, anexo_user) 
        VALUES (:anexo_prc, :anexo_etapa, :anexo_titulo, :anexo_conteudo, :anexo_arquivo, :anexo_arquivo_ext, :anexo_cadastro, :anexo_user)
    ");
    $sql_insert->execute([
        ':anexo_prc' => $id_procedimento,
        ':anexo_etapa' => 7,
        ':anexo_titulo' => 'CRFe',
        ':anexo_conteudo' => $documentoPreenchido,
        ':anexo_arquivo' => 'crfe_' . $doc_numero . '.pdf',
        ':anexo_arquivo_ext' => 'pdf',
        ':anexo_cadastro' => date('Y-m-d H:i:s'),
        ':anexo_user' => $id_user,
    ]);

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

    $atividade->bindValue(":atividade_user", $id_user);
    $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
    $atividade->bindValue(":atividade_name", "Nova CRFe gerada <a href=" . BASE_URL . "assets/documentos/crf_$doc_numero.pdf" . ">Arquivo</a>");
    $atividade->execute();

    $response = array(
        'status' => 'success',
        'tittle' => 'Sucesso',
        'message' => 'Documento gerado com sucesso.',
        'icon' => 'success',
    );
    echo json_encode($response);
} catch (Exception $e) {
    error_log("Erro ao gerar CRF: " . $e->getMessage());
    $response = array(
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao gerar o documento.',
        'icon' => 'error',
    );
    echo json_encode($response);
}
