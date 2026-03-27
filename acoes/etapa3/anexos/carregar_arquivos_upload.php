<?php
session_start();
if (isset($_GET["id"])) {
	include_once "../../../config.php";
	$id = addslashes($_GET['id']);

	// Preparar a consulta
	$sql = $db->prepare("
        SELECT * FROM tb_proprietarios_anexos
        LEFT JOIN users ON tb_proprietarios_anexos.anexo_user = users.id
        WHERE anexo_proprietario = :id
        AND anexo_status = 1
        ORDER BY anexo_cadastro DESC
    ");
	$sql->bindValue(":id", $id, PDO::PARAM_INT);
	$sql->execute();
	$cont = $sql->rowCount();

	if ($cont == 0) {
		$resultado = 'Nenhum documento anexado!';
	} else {
		$resultado = '';
		$resultado .= '<div class="row">';

		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$canDelete = hasPermission($_SESSION['uid'], 'processo_3etapa_excluir_documento', $db);
			$icone = getIcone($row['anexo_ext']);
			$arquivoUrl = BASE_URL . 'assets/documentos/' . htmlspecialchars($row['anexo_arquivo']);

			// Validação dos dados
			$titulo = !empty($row['anexo_titulo']) ? htmlspecialchars($row['anexo_titulo']) : 'Sem título';
			$formato = !empty($row['anexo_ext']) ? htmlspecialchars(strtoupper($row['anexo_ext'])) : 'Indefinido';
			$dataCadastro = !empty($row['anexo_cadastro']) ? date("d/m/Y H:i", strtotime($row['anexo_cadastro'])) : 'Data não disponível';
			$usuario = !empty($row['name']) ? htmlspecialchars($row['name']) : 'Usuário não disponível';

			// Conteúdo do Tooltip em HTML
			$tooltipContent = "Data: $dataCadastro  Usuário: $usuario";

			// HTML do card do documento
			$resultado .= '
            <div class="col-12 col-md-6 col-lg-3 col-xl-2 mb-3 mb-4">
                <div class="cardDocumento">
                    <a href="' . $arquivoUrl . '" target="_blank" aria-label="Abrir ' . $titulo . '">
                        <div class="mb-1">' . $icone . '</div>
                        <label>' . $titulo . '</label>
                    </a>
                    <div class="text-muted small d-flex align-items-center justify-content-center">
                        Formato: ' . $formato . '
                        <i class="fa fa-info-circle text-muted ml-1"
                           data-bs-toggle="tooltip"
                           data-bs-html="true"
                           title="' . htmlspecialchars($tooltipContent) . '"></i>
                    </div>';
			if ($canDelete) {
				$resultado .= '
                              <button class="btn delete-document-proprietario" id="delete-document" 
                    title="Excluir" 
                    data-id="' . $row['anexo_id'] . '"
                    aria-label="Excluir ' . $titulo . '">X</button>';
			}
			$resultado .= '         
                </div>
            </div>
            ';
		}

		$resultado .= '</div>';
		$resultado .= '<div class="text-muted small p-2 mb-1">' . $cont . ' documento(s)</div>';
	}

	echo $resultado;
}

function getIcone($extensao)
{
	$icones = [
		'docx' => '<i class="fa fa-file-word-o text-primary fa-2x"></i>',
		'pdf'  => '<i class="fa fa-file-pdf-o text-danger fa-2x"></i>',
		'xlsx' => '<i class="fa fa-file-excel-o text-success fa-2x"></i>',
		'jpeg' => '<i class="fa fa-file-image-o text-warning fa-2x"></i>',
		'jpg'  => '<i class="fa fa-file-image-o text-warning fa-2x"></i>',
		'png'  => '<i class="fa fa-file-image-o text-warning fa-2x"></i>',
		'zip'  => '<i class="fa fa-file-archive-o text-secondary fa-2x"></i>',
		'rar'  => '<i class="fa fa-file-archive-o text-secondary fa-2x"></i>',
	];
	return $icones[strtolower($extensao)] ?? '<i class="fa fa-file-text-o"></i>';
}
