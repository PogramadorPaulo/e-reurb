<?php
require_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
			$allowedExtensions = ['jpg', 'jpeg', 'png'];
			$file = $_FILES['logo'];
			$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

			if (!in_array($extension, $allowedExtensions)) {
				echo json_encode([
					'status' => 'error',
					'tittle' => 'Erro',
					'message' => 'Formato inválido. Apenas JPG, JPEG e PNG são permitidos.',
					'icon' => 'error',
				]);
				exit;
			}

			$newFileName = 'logo_' . time() . '.' . $extension;
			$uploadPath = '../../assets/tema/images/' . $newFileName;

			if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
				$municipioId = filter_input(INPUT_POST, 'municipio_id', FILTER_SANITIZE_NUMBER_INT);
				$sql = $db->prepare("
                    UPDATE tb_municipios 
                    SET municipio_logo_municipal = :logo, municipio_update = NOW()
                    WHERE municipio_id = :id
                ");
				$sql->bindValue(':logo', $newFileName);
				$sql->bindValue(':id', $municipioId);
				$sql->execute();

				echo json_encode([
					'status' => 'success',
					'tittle' => 'Sucesso',
					'message' => 'Logo enviado com sucesso!',
					'icon' => 'success',
				]);
			} else {
				throw new Exception('Erro ao salvar o arquivo.');
			}
		} else {
			throw new Exception('Nenhum arquivo enviado.');
		}
	} catch (Exception $e) {
		echo json_encode([
			'status' => 'error',
			'tittle' => 'Erro',
			'message' => 'Erro no upload: ' . $e->getMessage(),
			'icon' => 'error',
		]);
	}
} else {
	echo json_encode([
		'status' => 'error',
		'tittle' => 'Erro',
		'message' => 'Requisição inválida.',
		'icon' => 'error',
	]);
}
