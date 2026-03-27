
// Carregar quadras e lotes no mapa
function carrega_mapaLotes() {
    var id = $('#id').val();

    // Limpar o conteúdo do contêiner antes de renderizar novamente
    $('#mapaQuadras').empty();
    $.ajax({
        url: '../../acoes/etapa4/mapa/carregar_mapa.php',
        type: 'GET',
        dataType: 'json',
        data: { id: id },
        success: function (data) {
            console.log(data); // Para depuração

            data.forEach(quadra => {
                let quadraDiv = $('<div>').addClass('col-sm-12 col-lg-12 quadra').attr('data-quadra-id', quadra.quadra_id);

                // Título da quadra
                quadraDiv.append(`<div class="quadra-title">Quadra ${quadra.quadra_letra}</div>`);

                // Contagem de lotes
                quadraDiv.append(`<div class="lote-count">Total de Lotes: ${quadra.lotes.length}</div>`);

                // Botão para editar a quadra
                let btnEditarQuadra = $('<button title="Editar quadra">')
                    .addClass('btn-editar-quadra text-primary')
                    .html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>')
                    .on('click', () => abrirModalEditarQuadra(quadra));
                quadraDiv.append(btnEditarQuadra);

                // Botão para excluir a quadra
                let btnExcluirQuadra = $('<button title="Excluir quadra">')
                    .addClass('btn-excluir-quadra text-danger')
                    .html('<i class="fa fa-times" aria-hidden="true"></i>')
                    .on('click', () => deletarQuadra(quadra.quadra_id, 0));
                quadraDiv.append(btnExcluirQuadra);

                // Adicionar lotes na quadra
                quadra.lotes.forEach(lote => {
                    let proprietarioNomes = "Sem proprietário";
                    let proprietarioIds = [];

                    if (lote.proprietarios && lote.proprietarios.nomes) {
                        // Divide os nomes e IDs em arrays se houver proprietários
                        let nomesArray = lote.proprietarios.nomes.split(", ");
                        let idsArray = lote.proprietarios.ids.split(", ");
                        let primeirosNomes = nomesArray.map(nome => nome.split(" ")[0]);
                        proprietarioNomes = primeirosNomes.join(", ");
                        proprietarioIds = idsArray;
                    }

                    console.log(`Lote ${lote.lote_number}: Proprietários: ${proprietarioNomes}`);
                    let loteDiv = $('<div>')
                        .addClass('lote')
                        .attr('data-lote-id', lote.lote_id)
                        .html(`<strong>Lote ${lote.lote_number}</strong><br><div class="proprietario-nome">${proprietarioNomes}</div>`)
                        .on('click', function (event) {
                            event.stopPropagation();
                            buscarProprietarios(lote.lote_id);
                            $('#proprietariosModal').modal('show');
                        });

                    // Adicionar a classe especial 'sem-proprietario' se não houver proprietários
                    if (proprietarioNomes === "Sem proprietário") {
                        loteDiv.addClass('sem-proprietario');
                    }

                    quadraDiv.append(loteDiv);
                });

                // Botão para adicionar novo lote
                let btnNovoLote = $('<button title="Adicionar lote">')
                    .addClass('btn-novo-lote')
                    .html('<i class="fa fa-plus" aria-hidden="true"></i> Novo Lote')
                    .on('click', function () {
                        carrega_select_proprietarios();
                        $('#novo_lote_quadra').val(quadra.quadra_id);
                        $('#novoLoteModal').modal('show');

                    });
                quadraDiv.append(btnNovoLote);

                $('#mapaQuadras').append(quadraDiv);
            });
        },
        error: function () {
            alert('Erro ao carregar o mapa de lotes.');
        }
    });
}


function buscarProprietarios(loteId) {
    $.ajax({
        url: '../../acoes/etapa4/mapa/buscar_proprietarios.php',
        type: 'GET',
        data: { id: loteId },
        success: function (data) {
            // Exibir os dados no modal
            $('#viewDataProprietarios').html(data);
            $('#modalProprietarios').modal('show'); // Assume que você tenha um modal com esse ID

        },
        error: function () {
            alert('Erro ao carregar os proprietários.');
        }
    });
}


// deletar quadra // 
function deletarQuadra(id, status) {
    var spinner = $('#loader');
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja prosseguir?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            var idProcedimento = $('#id').val();
            spinner.show();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    id: id,
                    status: status,
                    idProcedimento: idProcedimento,
                },
                url: "../../acoes/etapa4/mapa/excluirQuadra.php",
                beforeSend: function () {
                    $('#content').css("opacity", ".5");
                },
                success: function (response) {
                    if (response.status == 'success') {
                        // Limpa o mapa e atualiza o conteúdo
                        $('#mapaQuadras').empty();
                        carrega_mapaLotes();
                        $('#content').css("opacity", "");
                        spinner.hide();
                    }
                    spinner.hide();
                    $('#content').css("opacity", "");
                    Swal.fire({
                        title: response.tittle,
                        html: response.message,
                        icon: response.icon
                    });
                },
                error: function (error) {
                    $('#content').css("opacity", "");
                    spinner.hide();
                    Swal.fire({
                        title: 'Erro',
                        text: 'Tente novamente',
                        icon: 'error' // Pode ser 'success', 'error', 'warning', 'info' ou 'question'
                    });

                }
            });

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Ação cancelada.');
        }
    });
}

// New quadra//
$(document).ready(function () {
    const spinner = $('#loader');

    // Submissão do formulário usando AJAX
    $('.form-new-quadra').on('submit', function (e) {
        e.preventDefault(); // Previne o envio padrão do formulário
        spinner.show(); // Exibe o spinner

        const form = $(this);
        const formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                $('#content').css("opacity", ".5");
            },
            success: function (response) {
                spinner.hide();
                $('#content').css("opacity", "");

                if (response.status === 'success') {
                    // Limpa o mapa e atualiza o conteúdo
                    $('#mapaQuadras').empty();
                    carrega_mapaLotes();
                    $('#novaQuadraModal').modal('hide');
                }

                // Exibe mensagem de retorno
                Swal.fire({
                    title: response.title || 'Sucesso',
                    html: response.message || 'Operação concluída com sucesso!',
                    icon: response.icon || 'success'
                });
            },
            error: function () {
                spinner.hide();
                $('#content').css("opacity", "");

                Swal.fire({
                    title: 'Erro',
                    text: 'Ocorreu um erro ao tentar realizar a operação. Tente novamente.',
                    icon: 'error'
                });
            }
        });
    });
})
// New lote//
$(document).ready(function () {
    const spinner = $('#loader');

    // Submissão do formulário usando AJAX
    $('.form-new-lote').on('submit', function (e) {
        e.preventDefault(); // Previne o envio padrão do formulário
        spinner.show(); // Exibe o spinner

        const form = $(this);
        const formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                $('#content').css("opacity", ".5");
            },
            success: function (response) {
                spinner.hide();
                $('#content').css("opacity", "");

                if (response.status === 'success') {
                    // Limpa o mapa e atualiza o conteúdo
                    $('#mapaQuadras').empty();
                    carrega_mapaLotes();
                    $('#novoLoteModal').modal('hide');
                }

                // Exibe mensagem de retorno
                Swal.fire({
                    title: response.title || 'Sucesso',
                    html: response.message || 'Operação concluída com sucesso!',
                    icon: response.icon || 'success'
                });
            },
            error: function () {
                spinner.hide();
                $('#content').css("opacity", "");

                Swal.fire({
                    title: 'Erro',
                    text: 'Ocorreu um erro ao tentar realizar a operação. Tente novamente.',
                    icon: 'error'
                });
            }
        });
    });
});

// Update // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-proprietario').on('submit', function (e) {
        spinner.show();
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);
        let formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                $('#content').css("opacity", ".5");
            },
            success: function (response) {
                if (response.status == 'success') {
                    initImoveisModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalEditProprietario').modal('hide');
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: response.tittle,
                    html: response.message,
                    icon: response.icon
                });
            },
            error: function (error) {
                $('#content').css("opacity", "");
                spinner.hide();
                Swal.fire({
                    title: 'Erro',
                    text: 'Tente novamente',
                    icon: 'error' // Pode ser 'success', 'error', 'warning', 'info' ou 'question'
                });

            }
        });
    });
});

// Update quadra // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-quadra').on('submit', function (e) {
        spinner.show();
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);
        let formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                $('#content').css("opacity", ".5");
            },
            success: function (response) {
                if (response.status == 'success') {
                    // Limpa o mapa e atualiza o conteúdo
                    $('#mapaQuadras').empty();
                    carrega_mapaLotes();
                    $('#content').css("opacity", "");
                    $('#editarQuadraModal').modal('hide');
                    spinner.hide();
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: response.tittle,
                    html: response.message,
                    icon: response.icon
                });
            },
            error: function (error) {
                $('#content').css("opacity", "");
                spinner.hide();
                Swal.fire({
                    title: 'Erro',
                    text: 'Tente novamente',
                    icon: 'error' // Pode ser 'success', 'error', 'warning', 'info' ou 'question'
                });

            }
        });
    });
});


// Update lote // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-lote').on('submit', function (e) {
        spinner.show();
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);
        let formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                $('#content').css("opacity", ".5");
            },
            success: function (response) {
                if (response.status == 'success') {
                    // Limpa o mapa e atualiza o conteúdo
                    $('#mapaQuadras').empty();
                    carrega_mapaLotes();
                    $('#content').css("opacity", "");
                    $('#modalProprietarios').modal('hide');
                    spinner.hide();
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: response.tittle,
                    html: response.message,
                    icon: response.icon
                });
            },
            error: function (error) {
                $('#content').css("opacity", "");
                spinner.hide();
                Swal.fire({
                    title: 'Erro',
                    text: 'Tente novamente',
                    icon: 'error' // Pode ser 'success', 'error', 'warning', 'info' ou 'question'
                });

            }
        });
    });
});


$(document).ready(function () {


    // Função para alternar o estado do campo selectImovelProprietarios
    function toggleSelectImovelProprietarios() {
        if ($('#naoIdentificadoProprietario').is(':checked')) {
            $('#selectImovelProprietarios').prop('disabled', true).val("Não Identificado");
        } else {
            $('#selectImovelProprietarios').prop('disabled', false).val("");
        }
    }

    // Inicializa o estado do campo ao carregar a página
    toggleSelectImovelProprietarios();

    // Alterna o estado do campo quando os botões de rádio mudam
    $('input[name="identificacao"]').on('change', toggleSelectImovelProprietarios);

});


// Carregar proprietários no select com Select2
function carrega_select_proprietarios() {
    var id = $('#id').val();
    $.ajax({
        url: "../../acoes/etapa4/mapa/carrega_select_proprietarios.php",
        method: "POST",
        dataType: "json",
        data: { id: id },
        beforeSend: function () {
            $('#selectImovelProprietarios').html('<option>Carregando...</option>');
        },
        success: function (data) {
            // Limpa o select e inicializa para múltipla seleção
            $('#selectImovelProprietarios').empty().select2({

                placeholder: '- Selecionar -',
                allowClear: true,
                data: data.map(item => ({
                    id: item.id,
                    text: `${item.nome} - ${item.identificacao}`
                }))
            });
        },
        error: function (xhr, status, error) {
            console.log(status + ": " + error);
            // Exibe uma mensagem de erro amigável ao usuário
            Swal.fire('Erro!', 'Não foi possível carregar os proprietários. Por favor, tente novamente mais tarde.', 'error');
            $('#selectImovelProprietarios').html('<option>Erro ao carregar</option>');
        }
    });
}


$(document).ready(function () {
    carrega_mapaLotes();
});



// Função para abrir a modal e preencher os campos com os dados do lote/proprietário
function abrirModalLote(lote) {

    $('#lote_id').val(lote.lote_id);
    $('#lote_number').val(lote.lote_number);
    $('#loteModal').modal('show');
}


// Função para abrir o modal de edição da quadra
function abrirModalEditarQuadra(quadra) {
    $('#quadra_id').val(quadra.quadra_id);
    $('#nova_quadra_nome').val(quadra.quadra_letra);
    $('#editarQuadraModal').modal('show');
}



// valida campo letra quadra // 
/*document.getElementById("nova_quadra_nome").addEventListener("input", function (e) {
    let value = e.target.value.toUpperCase(); // Converte para maiúsculo, se necessário
    e.target.value = value.replace(/[^A-Z]/g, ''); // Permite apenas letras de A a Z
});
*/

$(document).ready(function () {
    $('#lote_number').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});



