<?php
header('Content-Type: application/json; charset=utf-8');

require_once('../../../config.php');
require_once '../../libs/validar_pdf_fpdi.php';

if (!isset($_FILES['arquivo'])) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Selecione um arquivo.',
		'icon' => 'error',
	]);
	exit;
}

$id = $_POST['idProcedimento'] ?? '';
$idEtapa = 1;
$idUser = $_POST['idUser'] ?? '';
$titulo = $_POST['titulo'] ?? '';
$diretorio = "../../../assets/documentos/";
$nome_arquivo = $_FILES['arquivo']['name'];
$tamanho_arquivo = $_FILES['arquivo']['size'];
$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

if (isEtapaConcluida($id, 1, $db)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Etapa concluída',
		'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
		'icon' => 'warning',
	]);
	exit;
}

if (empty($id) || empty($idUser) || empty($titulo)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Ops! Algo deu errado. Dados incompletos.',
		'icon' => 'error',
	]);
	exit;
}

$permitidos = [".pdf"];
$ext = strtolower(strrchr($nome_arquivo, "."));

if (!in_array($ext, $permitidos)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Arquivo com extensão não permitida.',
		'icon' => 'error',
	]);
	exit;
}

if ($ext === ".pdf") {
	$validacao = validarPdfParaUpload($arquivo_tmp);
	if (!$validacao['ok']) {
		echo json_encode([
			'status' => 'error',
			'title' => 'Erro no PDF',
			'message' => $validacao['mensagem'],
			'icon' => 'error',
		]);
		exit;
	}
}

if ($tamanho_arquivo > (1024 * 1024 * TAMANHO_UPLOAD)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'O arquivo excedeu o tamanho máximo permitido.',
		'icon' => 'error',
	]);
	exit;
}

$tituloLimpo = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $titulo);
$nome_atual = crc32(uniqid(time())) . '-' . $tituloLimpo . $ext;

$insert = $db->prepare("
    INSERT INTO tb_etapas_anexos 
    (anexo_prc, anexo_etapa, anexo_titulo, anexo_arquivo, anexo_arquivo_ext, anexo_cadastro, anexo_user) 
    VALUES(:anexo_prc, :anexo_etapa, :anexo_titulo, :anexo_arquivo, :anexo_arquivo_ext, NOW(), :anexo_user)
");

$insert->execute([
	":anexo_prc" => $id,
	":anexo_etapa" => $idEtapa,
	":anexo_titulo" => $titulo,
	":anexo_arquivo" => $nome_atual,
	":anexo_arquivo_ext" => str_replace(".", "", $ext),
	":anexo_user" => $idUser,
]);

if ($insert->rowCount() > 0 && move_uploaded_file($arquivo_tmp, $diretorio . $nome_atual)) {
	echo json_encode([
		'status' => 'success',
		'title' => 'Sucesso',
		'message' => 'Upload efetuado com sucesso!',
		'icon' => 'success',
	]);
} else {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Erro ao salvar o arquivo.',
		'icon' => 'error',
	]);
}
