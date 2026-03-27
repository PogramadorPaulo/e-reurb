<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_FILES['arquivo'])) {
	require_once('../../../config.php');

	$idProcedimento = filter_input(INPUT_POST, "idProcesso", FILTER_SANITIZE_SPECIAL_CHARS);
	$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
	$titulo = filter_input(INPUT_POST, "titulo", FILTER_SANITIZE_SPECIAL_CHARS);
	$idEtapa = 3;
	$diretorio = "../../../assets/documentos/";

	if (isEtapaConcluida($idProcedimento, $idEtapa, $db)) {
		echo json_encode([
			'status' => 'error',
			'title' => 'Etapa Concluída',
			'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
			'icon' => 'warning',
		]);
		exit;
	}

	if (empty($id) || empty($idEtapa)) {
		echo json_encode([
			'status' => 'error',
			'title' => 'Erro',
			'message' => 'IDs ausentes. Não foi possível processar a requisição.',
			'icon' => 'error',
		]);
		exit;
	}

	$nome_arquivo = $_FILES['arquivo']['name'];
	$tamanho_arquivo = $_FILES['arquivo']['size'];
	$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

	$permitidos = ['.pdf', '.docx', '.xlsx', '.jpeg', '.jpg', '.png', '.bmp', '.zip', '.rar'];
	$dot = strrchr($nome_arquivo, '.');
	$ext = $dot ? strtolower($dot) : '';

	if ($ext === '' || !in_array($ext, $permitidos, true)) {
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

	$tituloLimpo = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $titulo);
	$nome_atual = crc32(uniqid(time())) . '-' . $tituloLimpo . $ext;

	$insert = $db->prepare("
        INSERT INTO tb_proprietarios_anexos 
        (anexo_proprietario, anexo_etapa, anexo_titulo, anexo_arquivo, anexo_ext, anexo_cadastro, anexo_user) 
        VALUES (:anexo_proprietario, :anexo_etapa, :anexo_titulo, :anexo_arquivo, :anexo_ext, :anexo_cadastro, :anexo_user)
    ");

	$insert->bindValue(":anexo_proprietario", $id);
	$insert->bindValue(":anexo_etapa", $idEtapa);
	$insert->bindValue(":anexo_titulo", $titulo);
	$insert->bindValue(":anexo_arquivo", $nome_atual);
	$insert->bindValue(":anexo_cadastro", date('Y-m-d H:i:s'));
	$insert->bindValue(":anexo_ext", str_replace(".", "", $ext));
	$insert->bindValue(":anexo_user", $_SESSION['uid']);
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
} else {
	echo json_encode([
		'status' => 'error',
		'title' => 'Erro',
		'message' => 'Nenhum arquivo foi selecionado.',
		'icon' => 'error',
	]);
}
