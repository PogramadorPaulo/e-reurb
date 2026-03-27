<?php

if (isset($_POST["id"])) {
	require_once('../../../config.php');

	$id = addslashes($_POST['id']);

	$resultado = '';

	$sql = $db->prepare("
	SELECT * FROM confrontantes
    WHERE id_con=:id
	");
	$sql->bindValue(":id", $id);
	$sql->execute();
	$cont = $sql->rowCount(); // Se não achar nemhuma noticia dericona para index
	while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
		$resultado = '
		<input value="' . $row['id_con'] . '" type="hidden" id="id" name="id">
		
		<div class="row">
		    <div class="col-md-6">
				<fieldset class="border p-3 mb-3">
					<legend class="w-auto">Pessoa: <span class="text-danger">*</span></legend>
					<div class="custom-radio-group mt-2">
						<label class="custom-radio">
							<input type="radio" name="identificacao" value="Identificado" id="fisicaConfrontante" ' . (($row["con_identificado"] == 'Identificado') ? 'checked' : '') . '>
							<span class="radio-btn">Identificado</span>
						</label>
						<label class="custom-radio">
							<input type="radio" name="identificacao" value="Não Identificado" id="naoIdentificado" ' . (($row["con_identificado"] == 'Não Identificado') ? 'checked' : '') . '>
							<span class="radio-btn">Não Identificado</span>
						</label>
					</div>
				</fieldset>
		    </div>
		    <div class="col-md-6">
				<fieldset class="border p-3 mb-3">
					<legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
					<div class="custom-radio-group mt-2">
						<label class="custom-radio">
							<input type="radio" name="tipo" value="Física" id="fisicaConfrontante" ' . (($row["tipo_pessoa"] == 'Física') ? 'checked' : '') . '>
							<span class="radio-btn">Física</span>
						</label>
						<label class="custom-radio">
							<input type="radio" name="tipo" value="Jurídica" id="juridicaConfrontante" ' . (($row["tipo_pessoa"] == 'Jurídica') ? 'checked' : '') . '>
							<span class="radio-btn">Jurídica</span>
						</label>
					</div>
				</fieldset>
		    </div>
        </div>

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>CPF</label>
					<input value="' . $row['cpf'] . '" type="text" class="form-control" id="cpfConfrontante" name="cpf" placeholder="000.000.000-00">
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>CNPJ</label>
					<input value="' . $row['cnpj'] . '" type="text" class="form-control" name="cnpj" id="cnpjConfrontante" placeholder="00.000.000/0000-00">
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
				<fieldset class="border p-3 mb-3">
					<legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
					<div class="custom-radio-group mt-2">
						<label class="custom-radio">
							<input type="radio" name="sexo" value="Masculino" id="masculino" ' . (($row["sexo"] == 'Masculino') ? 'checked' : '') . '>
							<span class="radio-btn">Masculino</span>
						</label>
						<label class="custom-radio">
							<input type="radio" name="sexo" value="Feminino" id="feminino" ' . (($row["sexo"] == 'Feminino') ? 'checked' : '') . '>
							<span class="radio-btn">Feminino</span>
						</label>
					</div>
				</fieldset>
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
					<input value="' . $row['telefone'] . '" type="text" class="form-control" name="telefone" id="telefoneConfrontante" placeholder="(00) 0000-0000">
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Celular:</label>
					<input value="' . $row['celular'] . '" type="text" class="form-control" name="celular" id="celularConfrontante" placeholder="(00) 00000-0000">
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
					<input value="' . $row['cep'] . '" type="text" class="form-control" name="cepConfrontante" id="cep" placeholder="CEP">
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

<script>
	// Validar cpf
	$('#cpfConfrontante').on('keyup', function() {
		var strcpf_con = document.getElementById("cpfConfrontante").value;
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
			document.getElementById("cpfConfrontante").style.border = "2px solid red";
			document.getElementById("btn-salvar-edit-confrontante").disabled = true;


		}
		if (Testacpf_con(strcpf_con) == true) {

			document.getElementById("cpfConfrontante").style.border = "2px solid green";
			document.getElementById("btn-salvar-edit-confrontante").disabled = false;
		}

	});

	// Validar CNPJ
	$('#cnpjConfrontante').on('keyup', function() {
		var cnpj_con = document.getElementById("cnpjConfrontante").value;
		cnpj_con = cnpj_con.replace(/[^\d]+/g, '');
		var qntNumero = cnpj_con.length;

		if (qntNumero <= 14) {
			function validarcnpj_con(cnpj_con) {

				if (cnpj_con == '') return false;

				if (cnpj_con.length != 14)
					return false;

				// Elimina cnpj_cons invalidos conhecidos
				if (cnpj_con == "00000000000000" ||
					cnpj_con == "11111111111111" ||
					cnpj_con == "22222222222222" ||
					cnpj_con == "33333333333333" ||
					cnpj_con == "44444444444444" ||
					cnpj_con == "55555555555555" ||
					cnpj_con == "66666666666666" ||
					cnpj_con == "77777777777777" ||
					cnpj_con == "88888888888888" ||
					cnpj_con == "99999999999999")
					return false;

				// Valida DVs
				tamanho = cnpj_con.length - 2
				numeros = cnpj_con.substring(0, tamanho);
				digitos = cnpj_con.substring(tamanho);
				soma = 0;
				pos = tamanho - 7;
				for (i = tamanho; i >= 1; i--) {
					soma += numeros.charAt(tamanho - i) * pos--;
					if (pos < 2)
						pos = 9;
				}
				resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
				if (resultado != digitos.charAt(0))
					return false;

				tamanho = tamanho + 1;
				numeros = cnpj_con.substring(0, tamanho);
				soma = 0;
				pos = tamanho - 7;
				for (i = tamanho; i >= 1; i--) {
					soma += numeros.charAt(tamanho - i) * pos--;
					if (pos < 2)
						pos = 9;
				}
				resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
				if (resultado != digitos.charAt(1))
					return false;

				return true;

			}
		}
		if (validarcnpj_con(cnpj_con) == false) {
			//document.getElementById("cnpj_con").focus();
			document.getElementById("cnpjConfrontante").style.border = "2px solid red";
			document.getElementById("btn-salvar-edit-confrontante").disabled = true;

		}
		if (validarcnpj_con(cnpj_con) == true) {
			document.getElementById("cnpjConfrontante").style.border = "2px solid green";
			document.getElementById("btn-salvar-edit-confrontante").disabled = false;
		}


	});


	$(document).ready(function() {
		$('#cnpjConfrontante').mask('99.999.999/9999-99');
		$('#cpfConfrontante').mask('999.999.999-99');
		$('#cepConfrontante').mask('99999-999');
		$('#celularConfrontante').mask('(99) 99999-9999');
		$('#telefoneConfrontante').mask('(99) 9999-9999');
	});
</script>