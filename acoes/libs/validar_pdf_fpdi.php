<?php

/**
 * Valida PDF para upload: cabeçalho %PDF e leitura via FPDI (compatível com junção na etapa 6).
 *
 * @return array{ok: bool, mensagem: string}
 */
function validarPdfParaUpload(string $caminhoTmp): array
{
	if (!is_string($caminhoTmp) || $caminhoTmp === '' || !is_readable($caminhoTmp)) {
		return ['ok' => false, 'mensagem' => 'O arquivo não pôde ser lido.'];
	}

	$head = @file_get_contents($caminhoTmp, false, null, 0, 8);
	if ($head === false || strlen($head) < 4 || substr($head, 0, 4) !== '%PDF') {
		return [
			'ok' => false,
			'mensagem' => 'O arquivo não é um PDF válido (cabeçalho ausente). Verifique se não renomeou outro tipo de arquivo.',
		];
	}

	require_once __DIR__ . '/tcpdf/tcpdf.php';
	require_once __DIR__ . '/fpdi/src/autoload.php';

	$pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
	try {
		$pageCount = $pdf->setSourceFile($caminhoTmp);
	} catch (\setasign\Fpdi\PdfParser\PdfParserException $e) {
		return [
			'ok' => false,
			'mensagem' => 'O PDF pode estar corrompido ou compactado de forma incompatível. Tente converter ou imprimir como PDF antes de enviar.',
		];
	} catch (\Throwable $e) {
		return [
			'ok' => false,
			'mensagem' => 'Não foi possível validar o PDF. Tente converter o arquivo.',
		];
	}

	if ($pageCount < 1) {
		return ['ok' => false, 'mensagem' => 'O PDF não contém páginas legíveis.'];
	}

	return ['ok' => true, 'mensagem' => ''];
}
