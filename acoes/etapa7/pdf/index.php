<?php
// gerar_pdf.php

require_once('../../../config.php'); // Ajuste o caminho conforme a estrutura do seu projeto
require_once('vendor/autoload.php'); // Carrega as dependências do Composer

use Dompdf\Dompdf;
use Dompdf\Options;

function getGetParam($key, $default = null)
{
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}

// Obter o ID do documento via GET
$doc_id = filter_var(getGetParam('id'), FILTER_VALIDATE_INT);

if (!$doc_id) {
    die("ID do documento inválido.");
}

try {
    // Conectar ao banco de dados
    $stmt = $db->prepare("SELECT * FROM tb_documentos WHERE doc_numero = :doc_numero AND doc_status = 1");
    $stmt->bindValue(':doc_numero', $doc_id, PDO::PARAM_INT);
    $stmt->execute();
    $documento = $stmt->fetch();

    if (!$documento) {
        die("Documento não encontrado.");
    }

    // Obter o conteúdo do documento
    $conteudo_html = $documento['doc_conteudo']; // Substitua 'doc_conteudo' pelo nome correto da coluna que armazena o HTML

    // Configurar o DOMPDF
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true); // Permitir acesso a URLs externas
    $dompdf = new Dompdf($options);

    // Carregar o HTML
    $dompdf->loadHtml($conteudo_html);

    // Definir o tamanho do papel e a orientação
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar o HTML como PDF
    $dompdf->render();

    // Adicionar números de página ao PDF
    $canvas = $dompdf->getCanvas();
    $font = $dompdf->getFontMetrics()->getFont("Arial", "normal");
    $size = 10;

    // Adiciona a paginação no rodapé
    $canvas->page_text(485, 20, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));

    // Output do PDF para o navegador
    $dompdf->stream("Documento_" . $documento['doc_tipo'] . "-$doc_id.pdf", ["Attachment" => false]);

    exit;
} catch (PDOException $e) {
    // Log do erro para análise posterior
    error_log("Database error: " . $e->getMessage());
    die("Erro ao gerar o PDF.");
} catch (Exception $e) {
    // Log do erro para análise posterior
    error_log("DOMPDF error: " . $e->getMessage());
    die("Erro ao gerar o PDF.");
}
