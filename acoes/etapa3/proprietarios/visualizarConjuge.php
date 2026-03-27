<?php

if (isset($_POST["id"])) {
	require_once('../../../config.php');

	$id = addslashes($_POST['id']);

	$resultado = '';

	$sql = $db->prepare("
	SELECT * FROM proprietarios_tabulares
    WHERE id_tab=:id
	");
	$sql->bindValue(":id", $id);
	$sql->execute();
	$cont = $sql->rowCount(); // Se não achar nemhuma noticia dericona para index
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$resultado = '
		<input value="' . $row['id_tab'] . '" type="hidden" id="id" name="id">

		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>CPF</label>
					<input value="' . $row['conjuge_cpf'] . '" type="text" class="form-control" id="cpfProprietarioConjuge" name="cpf" placeholder="000.000.000-00">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Nome</label>
					<input type="text" value="' . $row['conjuge_nome'] . '" class="form-control" name="nome" id="nome" placeholder="Nome">
				</div>
			</div>

			
			<div class="col-md-3">
				<div class="form-group">
					<label>Regime:</label>
					<select class="form-control" name="regime" id="regime">
						<option class="" ' . (($row["conjuge_regime"] == 'União Estável') ? 'selected' : '') . ' value="União Estável">União Estável</option>
						<option class="" ' . (($row["conjuge_regime"] == 'Comunhão parcial de bens') ? 'selected' : '') . ' value="Comunhão parcial de bens">Comunhão parcial de bens</option>
						<option class="" ' . (($row["conjuge_regime"] == 'Comunhão universal de bens') ? 'selected' : '') . ' value="Comunhão universal de bens">Comunhão universal de bens</option>
						<option class="" ' . (($row["conjuge_regime"] == 'Separação total / convencional de bens') ? 'selected' : '') . ' value="Separação total / convencional de bens">Separação total / convencional de bens</option>
						<option class="" ' . (($row["conjuge_regime"] == 'Separação obrigatória de bens') ? 'selected' : '') . ' value="Separação obrigatória de bens">Separação obrigatória de bens</option>
						ption class="" ' . (($row["conjuge_regime"] == 'Participação final dos aquestos') ? 'selected' : '') . ' value="Participação final dos aquestos">Participação final dos aquestos</option>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Data do Casamento/União</label>
					<input value="' . $row['conjuge_data_casamento'] . '" type="date" class="form-control" name="data_casamento" placeholder="">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Data de Nascimento</label>
					<input value="' . $row['conjuge_nasc'] . '" type="date" class="form-control" name="data_nasc" placeholder="">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Capacidade:</label>
					<select class="form-control" name="capaz" id="capaz">
						<option class="" ' . (($row["conjuge_capacidade"] == 'Capaz') ? 'selected' : '') . ' value="Capaz">Capaz</option>
						<option class="" ' . (($row["conjuge_capacidade"] == 'Incapaz interditado') ? 'selected' : '') . ' value="Incapaz interditado">Incapaz interditado</option>
						<option class="" ' . (($row["conjuge_capacidade"] == 'Incapaz parcial') ? 'selected' : '') . ' value="Incapaz parcial">Incapaz parcial</option>
						<option class="" ' . (($row["conjuge_capacidade"] == 'Incapaz total') ? 'selected' : '') . ' value="Incapaz total">Incapaz total</option>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<fieldset class="border p-3 mb-3">
					<legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
					<div class="custom-radio-group mt-2">
						<label class="custom-radio">
							<input type="radio" name="sexo" value="Masculino" id="masculino" ' . (($row["conjuge_sexo"] == 'Masculino') ? 'checked' : '') . '>
							<span class="radio-btn">Masculino</span>
						</label>
						<label class="custom-radio">
							<input type="radio" name="sexo" value="Feminino" id="feminino" ' . (($row["conjuge_sexo"] == 'Feminino') ? 'checked' : '') . '>
							<span class="radio-btn">Feminino</span>
						</label>
					</div>
				</fieldset>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>RG:</label>
					<input value="' . $row['conjuge_rg'] . '" type="text" class="form-control" name="rg" placeholder="RG">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Orgão Emissor</label>
					<input value="' . $row['conjuge_emissor'] . '" type="text" class="form-control" name="emissor" placeholder="Orgão Emissor">
				</div>
			</div>


			<div class="col-md-3">
				<div class="form-group">
					<label>Profissão:</label>
					<input value="' . $row['conjuge_profissao'] . '" type="text" class="form-control" name="profissao" placeholder="Profissão">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Estado Civil:</label>
					<select class="form-control" name="estado_civil" id="estado_civil">
						<option class="" ' . (($row["conjuge_estado_civil"] == 'Solteiro') ? 'selected' : '') . ' value="Solteiro">Solteiro</option>
						<option class="" ' . (($row["conjuge_estado_civil"] == 'Casado') ? 'selected' : '') . ' value="Casado">Casado</option>
						<option class="" ' . (($row["conjuge_estado_civil"] == 'Divorciado') ? 'selected' : '') . ' value="Divorciado">Divorciado</option>
						<option class="" ' . (($row["conjuge_estado_civil"] == 'Viúvo') ? 'selected' : '') . ' value="Viúvo">Viúvo</option>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Telefone:</label>
					<input value="' . $row['conjuge_telefone'] . '" type="text" class="form-control" name="telefone" id="telefoneProprietarioConjuge" placeholder="(00) 0000-0000">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Celular:</label>
					<input value="' . $row['conjuge_celular'] . '" type="text" class="form-control" name="celular" id="celularProprietarioConjuge" placeholder="(00) 00000-0000">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>E-mail:</label>
					<input value="' . $row['conjuge_email'] . '" type="email" class="form-control" autocomplete="on" name="email" id="email" placeholder="E-mail">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>CEP:</label>
					<input value="' . $row['conjuge_cep'] . '" type="text" class="form-control" name="cepProprietarioConjuge" id="cep" placeholder="CEP">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Logradouro:</label>
					<input value="' . $row['conjuge_log'] . '" type="text" class="form-control" name="logradouro" id="logradouro" placeholder="Endereço">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Nº:</label>
					<input value="' . $row['conjuge_numero'] . '" type="text" class="form-control" name="numero" id="numero" placeholder="Número">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Complemente:</label>
					<input value="' . $row['conjuge_complemento'] . '" type="text" class="form-control" name="complemente" id="complemente" placeholder="Complemente">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Bairro:</label>
					<input value="' . $row['conjuge_bairro'] . '" type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Município:</label>
					<input value="' . $row['conjuge_municipio'] . '" type="text" class="form-control" name="municipio" id="municipio" placeholder="Cidade">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Estado:</label>
					<input value="' . $row['conjuge_estado'] . '" type="text" class="form-control" name="estado" id="estado" placeholder="Estado">
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label>Nome do Pai:</label>
					<input value="' . $row['conjuge_pai'] . '" type="text" class="form-control" name="pai" id="pai" placeholder="Nome do Pai">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Nome da Mãe:</label>
					<input value="' . $row['conjuge_mae'] . '" type="text" class="form-control" name="mae" id="mae" placeholder="Nome do Mãe">
				</div>
			</div>
	    </div>
		';
	}

	echo $resultado;
}
?>

<script>
	// Validar cpf
	$('#cpfProprietarioConjuge').on('keyup', function() {
		var strcpf_con = document.getElementById("cpfProprietarioConjuge").value;
		strcpf_con = strcpf_con.replace(/[^\d]+/g, '');
		var qntNumero = strcpf_con.length;
		if (qntNumero <= 11) {
			function Testacpf_con(strcpf_con) {
				var Soma;
				var Resto;
				Soma = 0;


				if (strcpf_con == "00000000000" ||
					strcpf_con == "11111111111" ||
					strcpf_con == "22222222222" ||
					strcpf_con == "33333333333" ||
					strcpf_con == "44444444444" ||
					strcpf_con == "55555555555" ||
					strcpf_con == "66666666666" ||
					strcpf_con == "77777777777" ||
					strcpf_con == "88888888888" ||
					strcpf_con == "99999999999")
					return false;

				for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strcpf_con.substring(i - 1, i)) * (11 - i);
				Resto = (Soma * 10) % 11;

				if ((Resto == 10) || (Resto == 11)) Resto = 0;
				if (Resto != parseInt(strcpf_con.substring(9, 10))) return false;

				Soma = 0;
				for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strcpf_con.substring(i - 1, i)) * (12 - i);
				Resto = (Soma * 10) % 11;

				if ((Resto == 10) || (Resto == 11)) Resto = 0;
				if (Resto != parseInt(strcpf_con.substring(10, 11))) return false;
				return true;
			}
		}

		if (Testacpf_con(strcpf_con) == false) {
			//alert('cpf_con Inválido');
			//document.getElementById("cpf_con").focus();
			document.getElementById("cpfProprietarioConjuge").style.border = "2px solid red";
			document.getElementById("btn-salvar-edit-proprietario-conjuge").disabled = true;


		}
		if (Testacpf_con(strcpf_con) == true) {

			document.getElementById("cpfProprietarioConjuge").style.border = "2px solid green";
			document.getElementById("btn-salvar-edit-proprietario-conjuge").disabled = false;
		}

	});


	$(document).ready(function() {
		$('#cpfProprietarioConjuge').mask('999.999.999-99');
		$('#cepProprietarioConjuge').mask('99999-999');
		$('#celularProprietarioConjuge').mask('(99) 99999-9999');
		$('#telefoneProprietarioConjuge').mask('(99) 9999-9999');
	});
</script>