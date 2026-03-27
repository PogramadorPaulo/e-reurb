// Função para carregar anexos via AJAX
function carregarAnexos(page = 1, id, query = '') {
    $.ajax({
        url: '../../acoes/etapa1/tipo/fetch_anexos.php',
        method: 'POST',
        data: { page, id, query },
        success: function (data) {
            $('#conteudo_anexos').html(data); // Atualiza o conteúdo dos anexos
        },
        error: function () {
            $('#conteudo_anexos').html('<p>Erro ao carregar anexos.</p>'); // Exibe erro
        }
    });
}

// Evento para troca de página na paginação
$(document).on('click', '.page-link', function () {
    const page = $(this).data('page_number'); // Obtém o número da página clicada
    const id = $('#idProcedimento').val();

    const query = $('#search_query').val(); // Pega valor de pesquisa (se houver)
    carregarAnexos(page, id, query); // Recarrega anexos com a nova página
});

// Evento de pesquisa dinâmica no input de pesquisa
$('#search_query').on('input', function () {
    const query = $(this).val().trim(); // Pega o valor digitado
    const id = $('#idProcedimento').val();

    carregarAnexos(1, id, query); // Recarrega anexos na página 1 com a pesquisa
});



// Carregar anexos assim que a página é inicializada
$(document).ready(function () {
    const id = $('#idProcedimento').val();

    carregarAnexos(1, id); // Carrega a primeira página dos anexos
});

// Arrasta e solta o arquivo de upload // 
document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('arquivo_anexo');
    const fileInfoAnexo = document.getElementById('fileInfoAnexo');

    // Evento para abrir o seletor de arquivo ao clicar na área de drop
    dropzone.addEventListener('click', () => fileInput.click());

    // Captura o arquivo selecionado no input
    fileInput.addEventListener('change', handleFiles);

    // Eventos Drag and Drop
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('bg-primary', 'text-white');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('bg-primary', 'text-white');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('bg-primary', 'text-white');
        const files = e.dataTransfer.files;
        fileInput.files = files;
        handleFiles(); // Processa o arquivo imediatamente
    });

    // Função para manipular o arquivo selecionado ou arrastado
    function handleFiles() {
        const file = fileInput.files[0];
        if (file) {
            fileInfoAnexo.textContent = `Arquivo selecionado: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            document.getElementById('botaoUpload').style.display = 'inline-block';
        }
    }
});


// Detecta quando um arquivo é selecionado no input
$('#arquivo_anexo').on('change', function () {
    $('#botaoUpload').show(); // Exibe o botão de upload
});

// Evento de clique no botão de upload
$('#botaoUpload').on('click', function () {
    $('#modalUpload').modal('hide'); // Fecha o modal

    // Exibe um modal do SweetAlert para inserir o título
    Swal.fire({
        title: 'Insira o título do arquivo:',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (titulo) => {
            if (!titulo) Swal.showValidationMessage('Por favor, insira um título!');
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) realizarUpload(result.value); // Realiza upload se confirmado
        else cancelarUpload(); // Cancela a operação
    });
});

var spinner = $('#loader'); // Elemento de carregamento
// Função para realizar o upload do arquivo via AJAX
function realizarUpload(titulo) {
    spinner.show(); // Exibe o loader

    let formData = new FormData();
    formData.append('arquivo', $('#arquivo_anexo')[0].files[0]); // Adiciona o arquivo
    formData.append('titulo', titulo); // Adiciona o título
    formData.append('idUser', $('#idUser').val()); // Adiciona o ID Ussuário
    formData.append('idProcedimento', $('#idProcedimento').val()); // Adiciona o ID
    $.ajax({
        url: '../../acoes/etapa1/tipo/upload_arquivo.php',
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json',
        beforeSend: () => ajustarEstado(true), // Ajusta o estado antes de enviar
        success: (response) => tratarResposta(response), // Trata a resposta do servidor
        error: () => exibirMensagem('Erro', 'Tente novamente!', 'error'), // Exibe erro
        complete: () => ajustarEstado(false) // Ajusta estado após completar
    });
}

// Função para tratar a resposta do servidor após o upload (JSON: status, title, message, icon)
function tratarResposta(response) {
    const r = typeof response === 'object' && response !== null ? response : null;
    if (!r || !r.status) {
        exibirMensagem('Atenção', typeof response === 'string' ? response : 'Resposta inválida do servidor.', 'warning');
        return;
    }
    const icon = r.icon || (r.status === 'success' ? 'success' : 'warning');
    if (r.status === 'success') {
        atualizarListaArquivos();
        $('#arquivo_anexo').val('');
        $('#fileInfoAnexo').text('');
        $('#botaoUpload').hide();
    }
    exibirMensagem(r.title || 'Atenção', r.message || '', icon);
}
// Função para recarregar a lista de anexos após upload
function atualizarListaArquivos() {
    const id = $('#id').val();

    carregarAnexos(1, id); // Carrega a primeira página novamente
}

// Função para ajustar o estado da interface durante operações
function ajustarEstado(desativar) {
    $('#content').css('opacity', desativar ? '.5' : ''); // Ajusta opacidade
    $('#arquivo_anexo').prop('disabled', desativar); // Habilita/desabilita input
    $('#botaoUpload').toggle(!desativar); // Exibe/esconde botão de upload
    if (!desativar) spinner.hide(); // Esconde loader ao concluir
}

// Função para exibir mensagens usando SweetAlert
function exibirMensagem(titulo, texto, tipo) {
    Swal.fire({ title: titulo, text: texto, icon: tipo });
}

// Função para cancelar o upload e exibir modal de cancelamento
function cancelarUpload() {
    $('#modalUpload').modal('show'); // Reabre o modal de upload
    Swal.fire('Cancelado', 'Ação cancelada pelo usuário', 'info'); // Exibe aviso
}

// Deletar arquivo documento
$(document).on('click', '.delete-document-etapa1', function () {
    const docId = $(this).data('id');
    var idProcedimento = $('#idProcedimento').val();
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
                url: '../../acoes/etapa1/tipo/del_arquivo.php',
                method: 'POST',
                data: { id: docId, idProcedimento: idProcedimento },
                dataType: 'json', // Espera a resposta em formato JSON
                success: function (response) {
                    // Verifica se a resposta contém um status e uma mensagem
                    if (response.status === 'success') {
                        Swal.fire('Excluído!', response.message, 'success');
                        atualizarListaArquivos() // Atualiza a lista de arquivos
                    } else {
                        // Se não for sucesso, mostra a mensagem de erro
                        Swal.fire('Erro!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Erro!', 'Não foi possível excluir o documento.', 'error');
                }
            });
        }
    });
});


// Gerar zip dos documentos da etapa
$(document).ready(function () {
    var spinner = $('#loader'); // Elemento de carregamento

    $('#btnGerarZip').on('click', function () {
        var processoId = $('#idProcedimento').val(); // Obtém o ID do processo dinamicamente
        if (!processoId) {
            Swal.fire('Atenção!', 'Por favor, selecione um processo válido.', 'info'); // Validação simples
            return;
        }

        spinner.show(); // Mostra o spinner de carregamento

        $.ajax({
            url: '../../acoes/etapa1/tipo/gerar_zip.php',
            type: 'GET',
            data: {
                processo_id: processoId,

            },
            xhrFields: {
                responseType: 'blob' // Configuração para receber um blob
            },
            beforeSend: function () {
                $('#content').css("opacity", ".5"); // Reduz a opacidade do conteúdo
            },
            success: function (response) {
                // Verifica se a resposta é um Blob (ZIP)
                if (response instanceof Blob) {
                    // Criação do link para download
                    var url = window.URL.createObjectURL(response);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'processo_eatapa1_' + processoId + '.zip'; // Nome do arquivo para download
                    document.body.appendChild(a); // Adiciona o link ao DOM
                    a.click(); // Simula um clique no link
                    a.remove(); // Remove o link do DOM
                    window.URL.revokeObjectURL(url); // Libera a URL criada
                } else {
                    // Se a resposta não é um blob, deve ser um JSON com status e mensagem
                    if (response.status === 'error') {
                        Swal.fire('Atenção!', response.message, 'info'); // Mensagem de erro
                    }
                }
            },
            error: function (xhr) {
                // Tratamento de erro mais específico
                if (xhr.status === 404) {
                    Swal.fire('Erro!', 'Arquivo não encontrado.', 'error');
                } else if (xhr.status === 500) {
                    Swal.fire('Erro!', 'Erro interno no servidor.', 'error');
                } else {
                    Swal.fire('Erro!', 'Erro ao gerar o arquivo ZIP.', 'error');
                }
            },
            complete: function () {
                spinner.hide(); // Esconde o spinner após a requisição
                $('#content').css("opacity", ""); // Restaura a opacidade do conteúdo
            }
        });
    });
});


