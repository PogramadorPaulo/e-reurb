<?php

if (isset($_POST["id"])) {
	include_once "../../../config.php";
	$id = addslashes($_POST['id']);
	$idProcesso = addslashes($_POST['idProcesso']);
	$resultado = '';
	$sql = $db->prepare("
	SELECT * FROM proprietarios_tabulares
	WHERE id_tab=:id
	");
	$sql->bindValue(":id", $id);
	$sql->execute();
	$row = $sql->fetch();
	$nome = !empty($row['nome']) ? $row['nome'] : '';
	$resultado .= '
	            Nome: <i>' . $nome . '</i> - Processo: <i>' . $idProcesso . '</i>
				<input type="hidden" value="' . $row['id_tab'] . '" name="id" id="idProprietario"/>
			    <br><br>
			    <div class="row">	
					<div class="col-md-3">
					  <div class="card">
							<label for="arquivo_anexo_proprietario" class="custom-file-upload"><i class="fa fa-cloud-upload fa-2x" aria-hidden="true"></i>
							<br>
							Enviar arquivo
							</label>
							<input type="file" id="arquivo_anexo_proprietario" class="customFile" accept=".jpeg,.jpg,.pdf,.png,.rar,.zip,.xlsx,.docx,.bmp" required />
							<div id="fileInfoAnexo"></div>
					  </div>
				    </div>

					<div class="col-md-9 text-muted">
						Formatos permitidos: <b>(pdf, word, xlsx, png, jpeg, jpeg, bmp, zip, rar )</b><br>
						Tamanho máximo do aquivo: <b>' . TAMANHO_UPLOAD . 'MB</b>
					</div>
			   </div>

			 <button type="button" id="botaoUploadProprietario" style="display: none;" class="btn btn-primary">
			   <i class="fa fa-upload"></i>Fazer Upload</button>
			 <hr>	
	';
	$resultado .= '<div id="visualiza_arquivos"></div>';
	echo $resultado;
}

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#arquivo_anexo_proprietario').on('change', function() {
			var fileName = $(this).val().split('\\').pop(); // Obtém o nome do arquivo selecionado
			var fileSize = this.files[0].size; // Obtém o tamanho do arquivo em bytes
			var fileSizeFormatted = formatBytes(fileSize);

			$('#fileInfoAnexo').text('Arquivo selecionado: ' + fileName + ' (Tamanho: ' + fileSizeFormatted + ')');
			// Exibe o botão de upload
			$('#botaoUploadProprietario').show();
		});

		// Função para formatar o tamanho do arquivo em bytes para uma string legível
		function formatBytes(bytes, decimals = 2) {
			if (bytes === 0) return '0 Bytes';

			const k = 1024;
			const dm = decimals < 0 ? 0 : decimals;
			const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

			const i = Math.floor(Math.log(bytes) / Math.log(k));

			return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
		}

	});


	$(document).ready(function() {
		carregaArquivos();
	});

	var spinner = $('#loader');

	function carregaArquivos() {
		$.ajax({
			url: '<?php echo BASE_URL ?>acoes/etapa3/anexos/carregar_arquivos_upload.php?id=<?php echo $id ?>',
			method: 'GET',
			beforeSend: function() {
				spinner.show();
				$('#visualiza_arquivos').html('Carregando...');
			},
			success: function(response) {

				$('#visualiza_arquivos').html(response);

				spinner.hide();
			},
			error: function(xhr, status, error) {
				console.error('Erro ao buscar imagens:', error);
				spinner.hide();
			}
		});
	}

	/* Upload */
	$(document).ready(function() {
		var spinner = $('#loader');
		$('#botaoUploadProprietario').on('click', function() {
			$('#modalProprietarioDocumentos').modal('hide');

			Swal.fire({
				title: 'Insira o título do arquivo:',
				input: 'text',
				showCancelButton: true,
				confirmButtonText: 'Enviar',
				cancelButtonText: 'Cancelar',
				showLoaderOnConfirm: true,
				preConfirm: (titulo) => {
					if (!titulo) {
						Swal.showValidationMessage('Por favor, insira um título!');
					}
					return titulo; // Retorna o título
				},
				allowOutsideClick: () => !Swal.isLoading(),
			}).then((result) => {
				if (result.isConfirmed) {
					$('#modalProprietarioDocumentos').modal('show');
					spinner.show();

					var formData = new FormData();
					formData.append('arquivo', $('#arquivo_anexo_proprietario')[0].files[0]);
					formData.append('titulo', result.value); // Adiciona o título
					formData.append('idProcesso', $('#idProcesso').val()); // Captura e envia o ID do processo
					formData.append('id', $('#idProprietario').val()); // Captura e envia o ID do proprietário

					$.ajax({
						url: '<?php echo BASE_URL ?>acoes/etapa3/anexos/upload_arquivo.php',
						data: formData,
						processData: false,
						contentType: false,
						type: 'POST',
						dataType: 'json',
						beforeSend: function() {
							$('#view_conteudo_upload').css("opacity", ".5");
							$('#arquivo_anexo_proprietario').attr("disabled", "disabled");
							$('#botaoUploadProprietario').hide();
						},
						success: function(response) {
							spinner.hide();
							$('#view_conteudo_upload').css("opacity", "");
							$("#arquivo_anexo_proprietario").removeAttr("disabled");
							$('#botaoUploadProprietario').show();

							Swal.fire({
								title: response.title || response.tittle,
								text: response.message,
								icon: response.icon // 'success', 'error', 'warning', 'info', 'question'
							});

							if (response.status === 'success') {
								carregaArquivos(); // Atualiza a lista de arquivos
							}
						},
						error: function(xhr, response, error) {
							spinner.hide();
							$('#view_conteudo_upload').css("opacity", "");
							$('#botaoUploadProprietario').show();

							Swal.fire({
								title: 'Erro',
								text: 'Tente novamente!',
								icon: 'error'
							});
						}
					});
				} else if (result.dismiss === Swal.DismissReason.cancel) {
					$('#modalProprietarioDocumentos').modal('show');
					$('#botaoUploadProprietario').show();

					Swal.fire({
						title: 'Cancelado',
						text: 'Ação cancelada pelo usuário.',
						icon: 'info'
					});
				}
			});
		});
	});


	// Deletar arquivo documento
	$(document).on('click', '.delete-document-proprietario', function() {
		const docId = $(this).data('id');
		Swal.fire({
			title: 'Tem certeza?',
			text: 'Você não poderá desfazer essa ação!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sim, excluir!',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '../../acoes/etapa3/anexos/del_arquivo.php',
					method: 'POST',
					data: {
						id: docId,
						idProcesso: <?php echo $idProcesso ?>
					},
					dataType: 'json', // Espera a resposta em formato JSON
					success: function(response) {
						// Verifica se a resposta contém um status e uma mensagem
						if (response.status === 'success') {
							Swal.fire('Excluído!', response.message, 'success');
							carregaArquivos();
						} else {
							// Se não for sucesso, mostra a mensagem de erro
							Swal.fire('Erro!', response.message, 'error');
						}
					},
					error: function() {
						Swal.fire('Erro!', 'Não foi possível excluir o documento.', 'error');
					}
				});
			}
		});
	});
</script>