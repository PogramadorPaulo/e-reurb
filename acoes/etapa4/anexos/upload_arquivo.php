<?php
header('Content-Type: application/json; charset=utf-8');

require_once('../../../config.php');
require_once '../../libs/validar_pdf_fpdi.php';

if (!isset($_FILES['arquivo'])) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Selecione o arquivo.',
		'icon' => 'error',
	]);
	exit;
}

$id = addslashes($_POST['idProcedimento']);
$idEtapa = 4;
$idUser = addslashes($_POST['idUser']);
$diretorio = "../../../assets/documentos/";
$titulo = addslashes($_POST['titulo']);
$nome_arquivo = $_FILES['arquivo']['name'];
$tamanho_arquivo = $_FILES['arquivo']['size'];
$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

if (isEtapaConcluida($_POST['idProcedimento'], 4, $db)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Etapa concluída',
		'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
		'icon' => 'warning',
	]);
	exit;
}

if (empty($id) || empty($idEtapa) || empty($idUser)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Ops! Algo deu errado!',
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

if ($tamanho_arquivo > (1024 * 1024 * TAMANHO_UPLOAD)) {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'O arquivo excedeu o tamanho máximo permitido.',
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

$tituloLimpo = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $titulo);
$nome_atual = crc32(uniqid(time())) . '-' . $tituloLimpo . $ext;

$insert = $db->prepare("
                INSERT INTO tb_etapas_anexos 
                (
                    anexo_prc,
                    anexo_etapa,
                    anexo_titulo,
                    anexo_arquivo,
                    anexo_arquivo_ext,
                    anexo_cadastro,
                    anexo_user
                ) 
                VALUES (
                    :anexo_prc,
                    :anexo_etapa,
                    :anexo_titulo,
                    :anexo_arquivo,
                    :anexo_arquivo_ext,
                    :anexo_cadastro,
                    :anexo_user
                )
            ");
$insert->bindValue(":anexo_prc", $id);
$insert->bindValue(":anexo_etapa", $idEtapa);
$insert->bindValue(":anexo_titulo", $titulo);
$insert->bindValue(":anexo_arquivo", $nome_atual);
$insert->bindValue(":anexo_arquivo_ext", str_replace(".", "", $ext));
$insert->bindValue(":anexo_cadastro", date('Y-m-d H:i:s'));
$insert->bindValue(":anexo_user", $idUser);
$insert->execute();

if ($insert->rowCount() > 0) {
	if (move_uploaded_file($arquivo_tmp, $diretorio . $nome_atual)) {
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
			'message' => 'Falha ao enviar o arquivo! Tente novamente.',
			'icon' => 'error',
		]);
	}
} else {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Não foi possível salvar o arquivo! Tente novamente.',
		'icon' => 'error',
	]);
}
