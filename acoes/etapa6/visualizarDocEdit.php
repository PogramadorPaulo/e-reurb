<?php

if (isset($_POST["id"])) {
	require_once('../../config.php');

	$id = addslashes($_POST['id']);

	$resultado = '';

	$sql = $db->prepare("
    SELECT * FROM tb_etapas_anexos
    WHERE anexo_id=:id
    ");
	$sql->bindValue(":id", $id);
	$sql->execute();
	$cont = $sql->rowCount();
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$resultado = '
        <input value="' . $row['anexo_id'] . '" type="hidden" id="id" name="id">
        <div class="form-group">
          <label class="control-label">Texto Conteúdo</label>
          <textarea id="conteudoDocEtapa6" name="conteudo">' . $row['anexo_conteudo'] . '</textarea>
        </div>
        ';
	}

	echo $resultado;
}
?>

<script type="text/javascript">
	// Destroi o TinyMCE se ele já estiver inicializado
	if (tinymce.get("conteudoDocEtapa6")) {
		tinymce.get("conteudoDocEtapa6").remove();
	}


	// Inicializa o TinyMCE
	tinymce.init({
		selector: '#conteudoDocEtapa6',
		height: 600,
		plugins: [
			'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
			'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
			'insertdatetime', 'media', 'table', 'help', 'wordcount'
		],
		toolbar: 'undo redo | blocks | media image | ' +
			'bold italic backcolor | alignleft aligncenter ' +
			'alignright alignjustify | bullist numlist outdent indent | ' +
			'removeformat | help',
		content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',

		// Configurações para URLs absolutas
		relative_urls: false,
		remove_script_host: false,
		document_base_url: '<?php echo BASE_URL; ?>',

		// Configurações de Upload de Imagens
		automatic_uploads: true,
		file_picker_types: 'image',
		images_upload_url: '<?php echo BASE_URL; ?>processos/upload',
	});
</script>