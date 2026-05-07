
// chama model editar requerente//
$(document).ready(function () {
    $(document).on('click', '.view_data_requerente', function () {
        var user_id = $(this).attr("id");
        //	alert(user_id);
        //Verificar se há valor na variável "user_id".
        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('../../acoes/etapa1/requerentes/visualizar_requerente.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                $("#visul_dados").html(retorna);
                inicializarFormularioRequerente($('.form-edit-requerente'));
                $('#modalEditRequerente').modal('show');
            });
        }
    });
});

// deletar // 
function deletarRequerente(id, status) {
    var spinner = $('#loader');
    var idProcedimento = $('#id').val();
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja prosseguir?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            spinner.show();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    id: id,
                    status: status,
                    idProcedimento: idProcedimento,
                },
                url: "../../acoes/etapa1/requerentes/excluir.php",
                beforeSend: function () {
                    $('#dynamic_content_requerentes').css("opacity", ".5");
                },
                success: function (response) {
                    if (response.status == 'success') {
                        carregarRequerentes();
                        spinner.hide();
                        $('#dynamic_content_requerentes').css("opacity", "");
                    }
                    spinner.hide();
                    $('#dynamic_content_requerentes').css("opacity", "");
                    Swal.fire({
                        title: jsonResponseTitle(response),
                        html: response.message,
                        icon: response.icon
                    });
                },
                error: function (error) {
                    $('#dynamic_content_requerentes').css("opacity", "");
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


// Chama modal novo Requerentes
$(document).on('click', '.newRequente', function () {
    $('#modalNewRequerente').modal('show');
});

// Variáveis globais
let currentPage = 1;
let debounceTimeout;
const debounceDelay = 600;
const content = $('#dynamic_content_requerentes');
const searchInput = $('#search_requerente');

// Função para atualizar o conteúdo da página
function updateContent(html) {
    content.html(html);
}

// Função para carregar dados via AJAX
function loadData(page = 1) {
    const query = searchInput.val();
    $.ajax({
        url: "../../acoes/etapa1/requerentes/fetch.php",
        method: "POST",
        data: { page, query, id: $('#id').val() },
        success: (data) => {
            updateContent(data);
            currentPage = page;
        },
        error: () => updateContent('<p>Erro ao carregar dados.</p>')
    });
}

// Função para inicializar eventos e carregar os dados iniciais
function carregarRequerentes() {
    updateContent('<p>Carregando...</p>');
    loadData();

    // Evento de paginação
    $(document).on('click', '.page-link', function () {
        loadData($(this).data('page_number'));
    });

    // Evento de busca com debounce
    searchInput.on('keyup', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => loadData(), debounceDelay);
    });
}
// Inicializa o carregamento
$(document).ready(carregarRequerentes);


// Update // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-requerente').on('submit', function (e) {
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
                    carregarRequerentes();
                    spinner.hide();
                    $('#content').css("opacity", "");
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: jsonResponseTitle(response),
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
// Fim update //


// New requerente //
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-new-requerente').on('submit', function (e) {
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);

        if (!validarDocumentoRequerente(form)) {
            Swal.fire({
                title: 'Atenção',
                text: form.find('input[name="tipo"]:checked').val() === 'Jurídica' ? 'Informe um CNPJ válido.' : 'Informe um CPF válido.',
                icon: 'warning'
            });
            return;
        }

        spinner.show();
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
                    carregarRequerentes();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalNewRequerente').modal('hide');
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: jsonResponseTitle(response),
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
// Fim new requerente

// Update requerente // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-requerente').on('submit', function (e) {
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);

        if (!validarDocumentoRequerente(form)) {
            Swal.fire({
                title: 'Atenção',
                text: form.find('input[name="tipo"]:checked').val() === 'Jurídica' ? 'Informe um CNPJ válido.' : 'Informe um CPF válido.',
                icon: 'warning'
            });
            return;
        }

        spinner.show();
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
                    carregarRequerentes();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalEditRequerente').modal('hide');
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: jsonResponseTitle(response),
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
// Fim // 

// Validar CPF/CNPJ e tipo de pessoa
function somenteNumeros(valor) {
    return (valor || '').replace(/\D+/g, '');
}

function validarCPF(cpf) {
    cpf = somenteNumeros(cpf);

    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    let soma = 0;
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i), 10) * (10 - i);
    }

    let resto = (soma * 10) % 11;
    if (resto === 10) {
        resto = 0;
    }

    if (resto !== parseInt(cpf.charAt(9), 10)) {
        return false;
    }

    soma = 0;
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i), 10) * (11 - i);
    }

    resto = (soma * 10) % 11;
    if (resto === 10) {
        resto = 0;
    }

    return resto === parseInt(cpf.charAt(10), 10);
}

function validarCNPJ(cnpj) {
    cnpj = somenteNumeros(cnpj);

    if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
        return false;
    }

    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;

    for (let i = tamanho; i >= 1; i--) {
        soma += parseInt(numeros.charAt(tamanho - i), 10) * pos--;
        if (pos < 2) {
            pos = 9;
        }
    }

    let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
    if (resultado !== parseInt(digitos.charAt(0), 10)) {
        return false;
    }

    tamanho += 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;

    for (let i = tamanho; i >= 1; i--) {
        soma += parseInt(numeros.charAt(tamanho - i), 10) * pos--;
        if (pos < 2) {
            pos = 9;
        }
    }

    resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
    return resultado === parseInt(digitos.charAt(1), 10);
}

function getBotaoSalvarRequerente($form) {
    return $form.hasClass('form-new-requerente') ? $('#btn-salvar-re') : $('#btn-salvar');
}

function marcarCampoDocumento($campo, valido, preenchido) {
    if (!preenchido) {
        $campo.css('border', '');
        return;
    }

    $campo.css('border', valido ? '2px solid green' : '2px solid red');
}

function validarDocumentoRequerente($form) {
    const tipoPessoa = $form.find('input[name="tipo"]:checked').val();
    const $cpf = $form.find('input[name="cpf"]');
    const $cnpj = $form.find('input[name="cnpj"]');
    const $botaoSalvar = getBotaoSalvarRequerente($form);
    let valido = true;

    if (tipoPessoa === 'Física') {
        const cpf = somenteNumeros($cpf.val());
        valido = validarCPF(cpf);
        marcarCampoDocumento($cpf, valido, cpf.length > 0);
        $cnpj.css('border', '');
    } else if (tipoPessoa === 'Jurídica') {
        const cnpj = somenteNumeros($cnpj.val());
        valido = validarCNPJ(cnpj);
        marcarCampoDocumento($cnpj, valido, cnpj.length > 0);
        $cpf.css('border', '');
    }

    $botaoSalvar.prop('disabled', !valido);
    return valido;
}

function aplicarMascaraRequerente($form) {
    if ($.fn.mask) {
        $form.find('input[name="cpf"]').mask('999.999.999-99');
        $form.find('input[name="cnpj"]').mask('99.999.999/9999-99');
        $form.find('input[name="cep"]').mask('99999-999');
        $form.find('input[name="celular"]').mask('(99) 99999-9999');
        $form.find('input[name="telefone"]').mask('(99) 9999-9999');
    }
}

function aplicarTipoPessoaRequerente($form) {
    const tipoPessoa = $form.find('input[name="tipo"]:checked').val() || 'Física';
    const pessoaFisica = tipoPessoa === 'Física';

    if (!$form.find('input[name="tipo"]:checked').length) {
        $form.find('input[name="tipo"][value="Física"]').prop('checked', true);
    }

    $form.find('input[name="cpf"]').prop('disabled', !pessoaFisica);
    $form.find('input[name="cnpj"]').prop('disabled', pessoaFisica);
    $form.find('input[name="i_estadual"], input[name="i_municipal"], input[name="representante"], input[name="cargo"]').prop('disabled', pessoaFisica);
    $form.find('input[name="pai"], input[name="mae"]').prop('disabled', !pessoaFisica);

    if (pessoaFisica) {
        $form.find('input[name="cnpj"], input[name="i_estadual"], input[name="i_municipal"], input[name="representante"], input[name="cargo"]').val('').css('border', '');
        $form.find('input[name="cpf"]').focus();
    } else {
        $form.find('input[name="cpf"], input[name="pai"], input[name="mae"]').val('').css('border', '');
        $form.find('input[name="cnpj"]').focus();
    }

    validarDocumentoRequerente($form);
}

function inicializarFormularioRequerente($form) {
    aplicarMascaraRequerente($form);
    aplicarTipoPessoaRequerente($form);
}

$(document).on('input keyup blur', '.form-new-requerente input[name="cpf"], .form-new-requerente input[name="cnpj"], .form-edit-requerente input[name="cpf"], .form-edit-requerente input[name="cnpj"]', function () {
    validarDocumentoRequerente($(this).closest('form'));
});

$(document).on('change click', '.form-new-requerente input[name="tipo"], .form-edit-requerente input[name="tipo"]', function () {
    aplicarTipoPessoaRequerente($(this).closest('form'));
});

$(document).ready(function () {
    inicializarFormularioRequerente($('.form-new-requerente'));
});

$('#modalNewRequerente').on('shown.bs.modal', function () {
    inicializarFormularioRequerente($(this).find('.form-new-requerente'));
});

$('#modalEditRequerente').on('shown.bs.modal', function () {
    inicializarFormularioRequerente($(this).find('.form-edit-requerente'));
});
// FIM //

