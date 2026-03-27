<?php

if (isset($_POST["user_id"])) {
	require_once('../../../config.php');

	$id = addslashes($_POST['user_id']);

	$resultado = '';

	$sql = $db->prepare("
	SELECT * FROM requerentes
    WHERE id_requerente=:id
	");
	$sql->bindValue(":id", $id);
	$sql->execute();
	$cont = $sql->rowCount(); // Se não achar nemhuma noticia dericona para index
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$resultado = '
		<input value="' . $row['id_requerente'] . '" type="hidden" id="id" name="id">
		<div class="row">
		   <div class="col-md-12">
		   
			<fieldset class="border p-3 mb-3">
				<legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
				<div class="custom-radio-group mt-2">
					<label class="custom-radio">
						<input type="radio" name="tipo" value="Física" id="fisica" ' . (($row["tipo_pessoa"] == 'Física') ? 'checked' : '') . '>
						<span class="radio-btn">Física</span>
					</label>
					<label class="custom-radio">
						<input type="radio" name="tipo" value="Jurídica" id="juridica" ' . (($row["tipo_pessoa"] == 'Jurídica') ? 'checked' : '') . '>
						<span class="radio-btn">Jurídica</span>
					</label>
				</div>
			</fieldset>
			
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label>CPF</label>
				<input value="' . $row['cpf'] . '" type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label>CNPJ</label>
				<input value="' . $row['cnpj'] . '" type="text" class="form-control" name="cnpj" id="cnpj" placeholder="00.000.000/0000-00">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Inscrição Estadual:</label>
				<input type="text" value="' . $row['i_estadual'] . '" class="form-control" name="i_estadual" id="i_estadual" placeholder="Inscrição Estadual">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Inscrição Municipal:</label>
				<input type="text" value="' . $row['i_municipal'] . '" class="form-control" name="i_municipal" id="i_municipal" placeholder="Inscrição Municipal">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Representante Legal:</label>
				<input type="text" value="' . $row['representante'] . '" class="form-control" name="representante" id="representante" placeholder="Representante Legal">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Cargo:</label>
				<input type="text" value="' . $row['cargo'] . '" class="form-control" name="cargo" id="cargo" placeholder="Cargo">
			</div>
		</div>


		<div class="col-md-12">
			<div class="form-group">
				<label>Nome</label>
				<input type="text" value="' . $row['nome'] . '" class="form-control" name="nome" id="nome" placeholder="Nome">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Data de Nascimento</label>
				<input value="' . $row['data_nasc'] . '" type="date" class="form-control" name="data_nasc" placeholder="">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Sexo:</label>
				<div class="form-check">
					<input class="form-check-input" type="radio" ' . (($row["sexo"] == 'Masculino') ? 'checked' : '') . ' name="sexo" id="masculino" value="Masculino">
					<label class="form-check-label" for="masculino">Masculino</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" ' . (($row["sexo"] == 'Feminino') ? 'checked' : '') . ' name="sexo" id="feminino" value="Feminino">
					<label class="form-check-label" for="feminino">Feminino</label>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>RG:</label>
				<input value="' . $row['rg'] . '" type="text" class="form-control" name="rg" placeholder="RG">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Orgão Emissor</label>
				<input value="' . $row['emissor'] . '" type="text" class="form-control" name="emissor" placeholder="Orgão Emissor">
			</div>
		</div>


		<div class="col-md-6">
			<div class="form-group">
				<label>Profissão:</label>
				<input value="' . $row['profissao'] . '" type="text" class="form-control" name="profissao" placeholder="Profissão">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Estado Civil:</label>
				<select class="form-control" name="estado_civil" id="estado_civil">
					<option value="' . $row['estado_civil'] . '">' . $row['estado_civil'] . '</option>
					<option class="" value="Solteiro">Solteiro</option>
					<option class="" value="Casado">Casado</option>
					<option class="" value="Divorciado">Divorciado</option>
					<option class="" value="Viúvo">Viúvo</option>
				</select>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>União Estável:</label>
				<select class="form-control" name="uniao" id="uniao">
					<option " value="' . $row['uniao_estavel'] . '">' . $row['uniao_estavel'] . '</option>
					<option class="" value="Sim">Sim</option>
					<option class="" value="Não">Não</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Telefone:</label>
				<input value="' . $row['telefone'] . '" type="text" class="form-control" name="telefone" id="telefone" placeholder="(00) 0000-0000">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Celular:</label>
				<input value="' . $row['celular'] . '" type="text" class="form-control" name="celular" id="celular_" placeholder="(00) 00000-0000">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>E-mail:</label>
				<input value="' . $row['email'] . '" type="email" class="form-control" autocomplete="on" name="email" id="email" placeholder="E-mail">
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>CEP:</label>
				<input value="' . $row['cep'] . '" type="text" class="form-control" name="cep" id="cep" placeholder="CEP">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Logradouro:</label>
				<input value="' . $row['logradouro'] . '" type="text" class="form-control" name="logradouro" id="logradouro" placeholder="Endereço">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Nº:</label>
				<input value="' . $row['numero'] . '" type="text" class="form-control" name="numero" id="numero" placeholder="Número">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Complemente:</label>
				<input value="' . $row['complemento'] . '" type="text" class="form-control" name="complemente" id="complemente" placeholder="Complemente">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Bairro:</label>
				<input value="' . $row['bairro'] . '" type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Município:</label>
				<input value="' . $row['cidade'] . '" type="text" class="form-control" name="municipio" id="municipio" placeholder="Cidade">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label>Estado:</label>
				<input value="' . $row['estado'] . '" type="text" class="form-control" name="estado" id="estado" placeholder="Estado">
			</div>
		</div>

		<hr>
		<div class="col-md-6">
			<div class="form-group">
				<label>Nome do Pai:</label>
				<input value="' . $row['pai'] . '" type="text" class="form-control" name="pai" id="pai" placeholder="Nome do Pai">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Nome da Mãe:</label>
				<input value="' . $row['mae'] . '" type="text" class="form-control" name="mae" id="mae" placeholder="Nome do Mãe">
			</div>
		</div>

	</div>
		';
	}

	echo $resultado;
}
?>