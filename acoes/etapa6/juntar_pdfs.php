<?php

require_once '../../config.php';
require_once 'libs/tcpdf/tcpdf.php';
require_once 'libs/fpdi/src/autoload.php';

use setasign\Fpdi\Tcpdf\Fpdi;

header('Content-Type: application/json');

$id_procedimento = filter_input(INPUT_POST, "id_procedimento", FILTER_DEFAULT);

if (isEtapaConcluida($id_procedimento, 6, $db)) {
	echo json_encode([
		'status' => 'error',
		'tittle' => 'Etapa Concluída',
		'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
		'icon' => 'warning',
	]);
	exit;
}

try {
	$stmt = $db->prepare("
        SELECT * 
        FROM tb_etapas_anexos 
        WHERE anexo_prc = :id_procedimento
        AND anexo_status = 1 
        ORDER BY anexo_ordem ASC
    ");
	$stmt->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_STR);
	$stmt->execute();
	$arquivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (empty($arquivos)) {
		echo json_encode([
			'status' => 'error',
			'tittle' => 'Erro',
			'message' => 'Nenhum arquivo foi encontrado para gerar o projeto.',
			'icon' => 'error',
		]);
		exit;
	}

	$pdfItems = [];
	foreach ($arquivos as $arquivo) {
		if (strtolower($arquivo['anexo_arquivo_ext']) !== 'pdf') {
			continue;
		}
		$filePath = __DIR__ . '/../../assets/documentos/' . $arquivo['anexo_arquivo'];
		if (!file_exists($filePath)) {
			echo json_encode([
				'status' => 'error',
				'tittle' => 'Erro',
				'message' => 'Arquivo não encontrado no servidor para o anexo «' . $arquivo['anexo_titulo'] . '» (arquivo: ' . $arquivo['anexo_arquivo'] . ', etapa ' . $arquivo['anexo_etapa'] . '). Substitua o anexo na etapa correspondente.',
				'icon' => 'error',
			]);
			exit;
		}
		$pdfItems[] = [
			'path' => $filePath,
			'titulo' => $arquivo['anexo_titulo'],
			'nome' => $arquivo['anexo_arquivo'],
			'etapa' => $arquivo['anexo_etapa'],
		];
	}

	if (empty($pdfItems)) {
		echo json_encode([
			'status' => 'error',
			'tittle' => 'Erro',
			'message' => 'Nenhum arquivo PDF válido foi encontrado.',
			'icon' => 'error',
		]);
		exit;
	}

	$pdf = new Fpdi();

	$pdf->SetMargins(0, 0, 0);
	$pdf->SetAutoPageBreak(false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetFont('Helvetica', '', 8);
	$pdf->SetTextColor(0, 0, 0);

	$totalPages = 0;

	foreach ($pdfItems as $item) {
		try {
			$pageCount = $pdf->setSourceFile($item['path']);
			for ($i = 1; $i <= $pageCount; $i++) {
				$templateId = $pdf->importPage($i);
				$size = $pdf->getTemplateSize($templateId);

				$pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
				$pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);

				$pdf->SetY(-10);
				$pdf->Cell(0, 10, "Página " . (++$totalPages), 0, 0, 'C');
			}
		} catch (\Throwable $e) {
			$msg = sprintf(
				'Não foi possível processar o PDF do anexo «%s» (arquivo: %s, etapa %s). O arquivo pode estar corrompido ou em formato não suportado para união. Substitua o anexo na etapa correspondente ou converta o PDF (ex.: imprimir como PDF).',
				$item['titulo'],
				$item['nome'],
				$item['etapa']
			);
			echo json_encode([
				'status' => 'error',
				'tittle' => 'Erro ao unir PDFs',
				'message' => $msg,
				'icon' => 'error',
			]);
			exit;
		}
	}

	$finalFile = __DIR__ . '/../../assets/documentos/projeto_' . $id_procedimento . '.pdf';
	$pdf->Output($finalFile, 'F');

	echo json_encode([
		'status' => 'success',
		'tittle' => 'Sucesso',
		'message' => 'Projeto gerado com sucesso!',
		'file' => BASE_URL . 'assets/documentos/projeto_' . $id_procedimento . '.pdf',
		'icon' => 'success',
	]);
} catch (Exception $e) {
	echo json_encode([
		'status' => 'error',
		'tittle' => 'Erro',
		'message' => 'Erro ao gerar o projeto: ' . $e->getMessage(),
		'icon' => 'error',
	]);
}
