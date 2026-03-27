<?php

if (isset($_POST["id"])) {
	require_once('../../config.php');

	$id = addslashes($_POST['id']);

	$resultado = '';

	$sql = $db->prepare("
	SELECT * FROM tb_municipios
    WHERE municipio_id=:id
	");
	$sql->bindValue(":id", $id);
	$sql->execute();

	if ($sql->rowCount() > 0) {
		$row = $sql->fetch(PDO::FETCH_ASSOC);

		$resultado = '
		<input value="' . htmlspecialchars($row['municipio_id']) . '" type="hidden" id="id" name="id">
		<!-- Informações Gerais do Município -->
		<fieldset class="border p-3 rounded">
			<legend class="w-auto px-2 text-primary">Informações Gerais</legend>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="municipio_name">Município</label>
						<input type="text" class="form-control" name="municipio_name" id="municipio_name" placeholder="Digite o nome do município" value="' . htmlspecialchars($row['municipio_name']) . '" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="municipio_uf">UF</label>
						<input type="text" class="form-control" maxlength="2" name="municipio_uf" id="municipio_uf" placeholder="Digite a UF do município (ex: SP)" value="' . htmlspecialchars($row['municipio_uf']) . '" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="municipio_cnpj">CNPJ</label>
						<input type="text" class="form-control" name="municipio_cnpj" id="municipio_cnpj" placeholder="Digite o CNPJ do município" value="' . htmlspecialchars($row['municipio_cnpj']) . '" required>
					</div>
				</div>
			</div>
		</fieldset>

		<!-- Informações do Prefeito -->
		<fieldset class="border p-3 rounded mt-4">
			<legend class="w-auto px-2 text-primary">Informações do Prefeito</legend>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="municipio_prefeito">Nome do Prefeito</label>
						<input type="text" class="form-control" name="municipio_prefeito" id="municipio_prefeito" placeholder="Digite o nome do prefeito" value="' . htmlspecialchars($row['municipio_prefeito']) . '">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="municipio_autoridade">Cargo</label>
						<input type="text" class="form-control" name="municipio_autoridade" id="municipio_autoridade" placeholder="Digite o cargo do prefeito" value="' . htmlspecialchars($row['municipio_autoridade']) . '">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="municipio_prefeito_cpf">CPF</label>
						<input type="text" class="form-control" name="municipio_prefeito_cpf" id="municipio_prefeito_cpf" placeholder="Digite o CPF do prefeito" value="' . htmlspecialchars($row['municipio_prefeito_cpf']) . '">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="municipio_prefeito_rg">RG</label>
						<input type="text" class="form-control" name="municipio_prefeito_rg" id="municipio_prefeito_rg" placeholder="Digite o RG do prefeito" value="' . htmlspecialchars($row['municipio_prefeito_rg']) . '">
					</div>
				</div>
			</div>
		</fieldset>

		<!-- Informações de Normativas -->
		<fieldset class="border p-3 rounded mt-4">
			<legend class="w-auto px-2 text-primary">Normativas</legend>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						  <textarea name="municipio_normativas" placeholder="Leis, Decretos, Portarias" class="form form-control" rows="5">' . htmlspecialchars($row['municipio_normativas']) . '</textarea>
					</div>
				</div>
			</div>
		</fieldset>
		';
	} else {
		$resultado = '<div class="alert alert-warning">Município não encontrado.</div>';
	}

	echo $resultado;
}
