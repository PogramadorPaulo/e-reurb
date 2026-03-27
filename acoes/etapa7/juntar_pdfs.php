<?php

require_once '../../config.php';
require_once 'libs/tcpdf/tcpdf.php';
require_once 'libs/fpdi/src/autoload.php';

use setasign\Fpdi\Tcpdf\Fpdi;

// Consulta os dados
$stmt = $db->prepare("SELECT * FROM tb_etapas_anexos WHERE anexo_status = 1");
$stmt->execute();
$arquivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($arquivos)) {
    die("Nenhum arquivo foi encontrado no banco de dados.");
}

// Lista de arquivos
$pdfFiles = [];
foreach ($arquivos as $arquivo) {
    // Verifica se a extensão é PDF
    if (strtolower($arquivo['anexo_arquivo_ext']) === 'pdf') {
        $filePath = __DIR__ . '/../../assets/documentos/' . $arquivo['anexo_arquivo'];
        if (file_exists($filePath)) {
            $pdfFiles[] = $filePath;
        } else {
            echo "Arquivo não encontrado: {$filePath}<br>";
        }
    } else {
        echo "Ignorando arquivo não PDF: {$arquivo['anexo_arquivo']} ({$arquivo['anexo_arquivo_ext']})<br>";
    }
}

if (empty($pdfFiles)) {
    die("Nenhum arquivo PDF válido foi encontrado.");
}

// Cria o objeto FPDI
$pdf = new Fpdi();

// Configuração das margens e página
$pdf->SetMargins(0, 0, 0); // Remove margens para evitar linhas extras
$pdf->SetAutoPageBreak(false); // Desativa quebra automática de página
$pdf->setPrintHeader(false); // Remove cabeçalho padrão do TCPDF
$pdf->setPrintFooter(false); // Remove rodapé padrão do TCPDF

// Configura fonte para numeração
$pdf->SetFont('Helvetica', '', 8);
$pdf->SetTextColor(0, 0, 0); // Cor preta

$totalPages = 0; // Contador de páginas

foreach ($pdfFiles as $file) {
    $pageCount = $pdf->setSourceFile($file);
    for ($i = 1; $i <= $pageCount; $i++) {
        $templateId = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($templateId);

        // Adiciona página com o tamanho exato do template
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);

        // Adiciona numeração de página no rodapé
        $pdf->SetY(-10); // Posiciona o cursor 15 unidades acima do rodapé
        $pdf->Cell(0, 10, "Página " . (++$totalPages), 0, 0, 'C'); // Centralizado
    }
}

// Define o caminho absoluto para o arquivo combinado
$finalFile = __DIR__ . '/projeto.pdf';

// Salva o arquivo final no servidor
$pdf->Output($finalFile, 'F');

// Retorna o PDF combinado para download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="projeto.pdf"');
readfile($finalFile);

// Remove o arquivo temporário após o download (opcional)
unlink($finalFile);
