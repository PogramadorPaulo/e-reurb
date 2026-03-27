
// chama model editar requerente//
$(document).ready(function () {
    $(document).on('click', '.view_data_ProprietarioMatricula', function () {
        var id = $(this).attr("id");
        if (id !== '') {
            var dados = {
                id: id
            };
            $.post('../../acoes/etapa2/proprietarios/visualizar.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                $("#visul_dados_proprietarios_matricula").html(retorna);
                $('#modalEditProprietarioMatricula').modal('show');
            });
        }
    });
});


// Chama modal novo
$(document).on('click', '.newProprietarioMatricula', function () {
    $('#modalNewProprietarioMatricula').modal('show');
    //carregarMatriculas();
});

// carrega o select matriculas
/*function carregarMatriculas() {
    $.ajax({
        url: '../../acoes/etapa2/proprietarios/carregar_matriculas.php', // URL do arquivo PHP
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Limpa o select e adiciona uma opção vazia
            $('#matriculaSelect').empty().append('<option value="">Selecione uma Matrícula</option>');

            // Itera sobre os dados e adiciona opções ao select
            $.each(data, function (index, matricula) {
                $('#matriculaSelect').append(
                    $('<option>', {
                        value: matricula.matricula_id,
                        text: matricula.matricula_nome
                    })
                );
            });
        },
        error: function () {
            $('#matriculaSelect').html('<option>Erro ao carregar matrículas</option>');
        }
    });
} */

// Carregamento 
function initProprietarioMatriculaModule() {
    let currentPageConfrontante = 1; // Página atual
    let debounceTimeoutConfrontante;
    const debounceDelayConfrontante = 600;
    const contentConfrontante = $('#dynamic_content_proprietariosMatriculas');
    const searchInputConfrontante = $('#search_proprietariosMatriculas');

    // Função para atualizar o conteúdo da página
    function updateContent(html) {
        contentConfrontante.html(html);
    }

    // Função para carregar dados via AJAX
    function loadData(page = currentPageConfrontante) {
        const query = searchInputConfrontante.val();
        $.ajax({
            url: "../../acoes/etapa2/proprietarios/fetch.php",
            method: "POST",
            data: { page, query, id: $('#id').val() },
            success: (data) => {
                updateContent(data);
                currentPageConfrontante = page; // Atualiza a página atual
            },
            error: () => updateContent('<p>Erro ao carregar dados.</p>')
        });
    }

    // Função para recarregar a mesma página após uma edição
    function reloadCurrentPage() {
        loadData(currentPageConfrontante); // Recarrega a página atual
    }

    // Inicializa eventos e carrega os dados iniciais
    function carregamentoProprietariosMatricula() {
        updateContent('<p>Carregando...</p>');
        loadData();

        // Evento de paginação
        $(document).on('click', '.page-link', function (e) {
            e.preventDefault();
            const page = $(this).data('page_number');
            if (page !== currentPageConfrontante) {
                loadData(page);
            }
        });

        // Evento de busca com debounce
        searchInputConfrontante.on('keyup', () => {
            clearTimeout(debounceTimeoutConfrontante);
            debounceTimeoutConfrontante = setTimeout(() => loadData(1), debounceDelayConfrontante);
        });


    }

    $(document).ready(carregamentoProprietariosMatricula);
}

// Chamada da função para iniciar o módulo carregamento
initProprietarioMatriculaModule();


// deletar // 
function deletarProprietarioMatricula(id, status) {
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
                url: "../../acoes/etapa2/proprietarios/excluir.php",
                beforeSend: function () {
                    $('#content').css("opacity", ".5");
                },
                success: function (response) {
                    if (response.status == 'success') {
                        initProprietarioMatriculaModule();
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

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Ação cancelada.');
        }
    });
}


// New  //
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.formnewProprietariomatricula').on('submit', function (e) {
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
                    initProprietarioMatriculaModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalNewProprietarioMatricula').modal('hide');
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


// Update  // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-proprietario-matricula').on('submit', function (e) {
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
                    initProprietarioMatriculaModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalEditProprietarioMatricula').modal('hide');
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

// Validar cpf_ProprietarioMatricula/cnpj_ProprietarioMatricula
$('#cpf_ProprietarioMatricula').on('keyup', function () {
    var strcpf_ProprietarioMatricula = document.getElementById("cpf_ProprietarioMatricula").value;
    strcpf_ProprietarioMatricula = strcpf_ProprietarioMatricula.replace(/[^\d]+/g, '');
    var qntNumero = strcpf_ProprietarioMatricula.length;
    if (qntNumero <= 11) {
        function Testacpf_ProprietarioMatricula(strcpf_ProprietarioMatricula) {
            var Soma;
            var Resto;
            Soma = 0;


            if (strcpf_ProprietarioMatricula == "00000000000" ||
                strcpf_ProprietarioMatricula == "11111111111" ||
                strcpf_ProprietarioMatricula == "22222222222" ||
                strcpf_ProprietarioMatricula == "33333333333" ||
                strcpf_ProprietarioMatricula == "44444444444" ||
                strcpf_ProprietarioMatricula == "55555555555" ||
                strcpf_ProprietarioMatricula == "66666666666" ||
                strcpf_ProprietarioMatricula == "77777777777" ||
                strcpf_ProprietarioMatricula == "88888888888" ||
                strcpf_ProprietarioMatricula == "99999999999")
                return false;

            for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strcpf_ProprietarioMatricula.substring(i - 1, i)) * (11 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11)) Resto = 0;
            if (Resto != parseInt(strcpf_ProprietarioMatricula.substring(9, 10))) return false;

            Soma = 0;
            for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strcpf_ProprietarioMatricula.substring(i - 1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11)) Resto = 0;
            if (Resto != parseInt(strcpf_ProprietarioMatricula.substring(10, 11))) return false;
            return true;
        }
    }

    if (Testacpf_ProprietarioMatricula(strcpf_ProprietarioMatricula) == false) {
        //alert('cpf_ProprietarioMatricula Inválido');
        //document.getElementById("cpf_ProprietarioMatricula").focus();
        document.getElementById("cpf_ProprietarioMatricula").style.border = "2px solid red";
        document.getElementById("btn-salvar-con").disabled = true;


    }
    if (Testacpf_ProprietarioMatricula(strcpf_ProprietarioMatricula) == true) {

        document.getElementById("cpf_ProprietarioMatricula").style.border = "2px solid green";
        document.getElementById("btn-salvar-con").disabled = false;
    }

});

// Validar cnpj_ProprietarioMatricula
$('#cnpj_ProprietarioMatricula').on('keyup', function () {
    var cnpj_ProprietarioMatricula = document.getElementById("cnpj_ProprietarioMatricula").value;
    cnpj_ProprietarioMatricula = cnpj_ProprietarioMatricula.replace(/[^\d]+/g, '');
    var qntNumero = cnpj_ProprietarioMatricula.length;

    if (qntNumero <= 14) {
        function validarcnpj_ProprietarioMatricula(cnpj_ProprietarioMatricula) {

            if (cnpj_ProprietarioMatricula == '') return false;

            if (cnpj_ProprietarioMatricula.length != 14)
                return false;

            // Elimina cnpj_ProprietarioMatriculas invalidos conhecidos
            if (cnpj_ProprietarioMatricula == "00000000000000" ||
                cnpj_ProprietarioMatricula == "11111111111111" ||
                cnpj_ProprietarioMatricula == "22222222222222" ||
                cnpj_ProprietarioMatricula == "33333333333333" ||
                cnpj_ProprietarioMatricula == "44444444444444" ||
                cnpj_ProprietarioMatricula == "55555555555555" ||
                cnpj_ProprietarioMatricula == "66666666666666" ||
                cnpj_ProprietarioMatricula == "77777777777777" ||
                cnpj_ProprietarioMatricula == "88888888888888" ||
                cnpj_ProprietarioMatricula == "99999999999999")
                return false;

            // Valida DVs
            tamanho = cnpj_ProprietarioMatricula.length - 2
            numeros = cnpj_ProprietarioMatricula.substring(0, tamanho);
            digitos = cnpj_ProprietarioMatricula.substring(tamanho);
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
            numeros = cnpj_ProprietarioMatricula.substring(0, tamanho);
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
    if (validarcnpj_ProprietarioMatricula(cnpj_ProprietarioMatricula) == false) {
        //document.getElementById("cnpj_ProprietarioMatricula").focus();
        document.getElementById("cnpj_ProprietarioMatricula").style.border = "2px solid red";
        document.getElementById("btn-salvar-con").disabled = true;

    }
    if (validarcnpj_ProprietarioMatricula(cnpj_ProprietarioMatricula) == true) {
        document.getElementById("cnpj_ProprietarioMatricula").style.border = "2px solid green";
        document.getElementById("btn-salvar-con").disabled = false;
    }


});
// FIM //

// VALIDAR TIPO PESSOA //
$(document).ready(function () {

    function configurarTipoPessoa(tipo) {
        if (tipo === 'Física') {
            $('#cpf_ProprietarioMatricula').prop('disabled', false).parent().show();
            $('#cnpj_ProprietarioMatricula').prop('disabled', true).val('');
            $('#i_estadual_ProprietarioMatricula, #i_municipal_ProprietarioMatricula').prop('disabled', true);
            $('#representante_ProprietarioMatricula, #cargo_ProprietarioMatricula').prop('disabled', true);
            $('#grupoSexo').show();
            $('#cpf_ProprietarioMatricula').focus();

            // Mostrar campos exclusivos de pessoa física
            $('#grupoDataNasc, #grupoRG, #grupoEmissor, #grupoProfissao, #grupoEstadoCivil, #grupoUniao').show();
            $('#grupoPai, #grupoMae').show();
        }
        else if (tipo === 'Jurídica') {
            $('#cpf_ProprietarioMatricula').prop('disabled', true).val('').parent().hide();
            $('#cnpj_ProprietarioMatricula').prop('disabled', false).focus();
            $('#i_estadual_ProprietarioMatricula, #i_municipal_ProprietarioMatricula').prop('disabled', false);
            $('#representante_ProprietarioMatricula, #cargo_ProprietarioMatricula').prop('disabled', false);
            $('#grupoSexo').hide();
            $('#masculino, #feminino').prop('checked', false);

            // Ocultar campos que não se aplicam
            $('#grupoDataNasc, #grupoRG, #grupoEmissor, #grupoProfissao, #grupoEstadoCivil, #grupoUniao').hide();
            $('#grupoPai, #grupoMae').hide();
        }
    }

    $('#fisica_ProprietarioMatricula').on('click', function () {
        configurarTipoPessoa('Física');
    });

    $('#juridica_ProprietarioMatricula').on('click', function () {
        configurarTipoPessoa('Jurídica');
    });

    $('#modalNewProprietarioMatricula').on('shown.bs.modal', function () {
        $('#fisica_ProprietarioMatricula').prop('checked', true);
        configurarTipoPessoa('Física');
    });
});


// FIM //

$(document).ready(function () {
    $('#cnpj_ProprietarioMatricula').mask('99.999.999/9999-99');
    $('#cpf_ProprietarioMatricula').mask('999.999.999-99');
    $('#cep_ProprietarioMatricula').mask('99999-999');
    $('#celular_ProprietarioMatricula').mask('(99) 99999-9999');
    $('#telefone_ProprietarioMatricula').mask('(99) 9999-9999');
});



