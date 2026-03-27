$(document).ready(function () {
    // Inicializar filtros e carregar dados iniciais
    configurarFiltros();
    recarregarDados();
});

// Variáveis globais
var currentPage = 1; // Página inicial
var query = ''; // Consulta inicial


// Alternar a visibilidade dos filtros
$('#toggleFilters').on('click', function () {
    $('#filters').toggle();
    var isVisible = $('#filters').is(':visible');
    $(this).html(isVisible ? '<i class="fa fa-times"></i> Esconder Filtros' : '<i class="fa fa-filter"></i> Mostrar Filtros');
});



// Função para carregar dados
function loadData(page = 1, query = '') {
    $('#loader').show();
    $.ajax({
        url: "acoes/municipio/fetch.php",
        method: "POST",
        data: {
            page: page,
            query: query
        },
        success: function (data) {
            $('#dynamic_content').html(data);
            currentPage = page;
        },
        complete: function () {
            $('#loader').hide();
        }
    });
}

// Recarregar dados
function recarregarDados() {
    loadData(currentPage, query);
}

// Configurar eventos de filtros e paginação
function configurarFiltros() {
    $('#search_obejto').on('keyup', function () {
        query = $(this).val();
        currentPage = 1;
        recarregarDados();
    });


    $(document).on('click', '.page-link', function () {
        var page = $(this).data('page_number');
        if (page) {
            loadData(page, query, tipos);
        }
    });
}

// Chama modal novo Requerentes
$(document).on('click', '.btn_modalNew', function () {
    $('#modalNew').modal('show');
});

// Cadastro via AJAX
$(document).ready(function () {
    var spinner = $('#loader'); // Spinner de carregamento

    // Submissão do formulário usando AJAX
    $('.form_new').on('submit', function (e) {
        spinner.show(); // Exibe o spinner
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);
        let formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false, // Impede o processamento automático de dados
            contentType: false, // Define o tipo de conteúdo como `false`
            dataType: 'json', // Espera resposta no formato JSON
            beforeSend: function () {
                $('#content').css("opacity", ".5"); // Adiciona um efeito de opacidade
            },
            success: function (response) {
                // Caso sucesso
                if (response.status == 'success') {

                    $('#content').css("opacity", ""); // Remove o efeito de opacidade
                    $('#modalNew').modal('hide'); // Fecha o modal
                    form[0].reset(); // Reseta os campos do formulário
                    $('#modalNew').modal('hide');
                    recarregarDados(); // Atualiza os dados (se necessário)
                    spinner.hide();
                }

                spinner.hide();
                $('#content').css("opacity", ""); // Remove o efeito de opacidade

                // SweetAlert para o usuário
                Swal.fire({
                    title: jsonResponseTitle(response),
                    html: response.message,
                    icon: response.icon // Ícone da resposta (success, warning, error, etc.)
                });
            },
            error: function (xhr, status, error) {
                $('#content').css("opacity", ""); // Remove o efeito de opacidade
                spinner.hide(); // Esconde o spinner
                let errorMessage = xhr.responseJSON?.message || 'Erro inesperado. Tente novamente mais tarde.';

                // SweetAlert para erros
                Swal.fire({
                    title: 'Erro',
                    text: errorMessage,
                    icon: 'error' // Ícone de erro
                });
            }
        });
    });
});

// Atualizar via AJAX
$(document).ready(function () {
    var spinner = $('#loader'); // Spinner de carregamento

    // Submissão do formulário usando AJAX
    $('.form_edit').on('submit', function (e) {
        spinner.show(); // Exibe o spinner
        e.preventDefault(); // Previne o envio padrão do formulário
        let form = $(this);
        let formData = new FormData(this); // Captura os dados do formulário

        $.ajax({
            url: form.attr('action'), // Rota de envio
            type: form.attr('method'), // Método de envio (POST/GET)
            data: formData,
            processData: false, // Impede o processamento automático de dados
            contentType: false, // Define o tipo de conteúdo como `false`
            dataType: 'json', // Espera resposta no formato JSON
            beforeSend: function () {
                $('#content').css("opacity", ".5"); // Adiciona um efeito de opacidade
            },
            success: function (response) {
                // Caso sucesso
                if (response.status == 'success') {
                    $('#content').css("opacity", ""); // Remove o efeito de opacidade
                    $('#modalEdit').modal('hide'); // Fecha o modal
                    form[0].reset(); // Reseta os campos do formulário
                    recarregarDados(); // Atualiza os dados (se necessário)
                    spinner.hide();
                }

                spinner.hide();
                $('#content').css("opacity", ""); // Remove o efeito de opacidade

                // SweetAlert para o usuário
                Swal.fire({
                    title: jsonResponseTitle(response),
                    html: response.message,
                    icon: response.icon // Ícone da resposta (success, warning, error, etc.)
                });
            },
            error: function (xhr, status, error) {
                $('#content').css("opacity", ""); // Remove o efeito de opacidade
                spinner.hide(); // Esconde o spinner
                let errorMessage = xhr.responseJSON?.message || 'Erro inesperado. Tente novamente mais tarde.';

                // SweetAlert para erros
                Swal.fire({
                    title: 'Erro',
                    text: errorMessage,
                    icon: 'error' // Ícone de erro
                });
            }
        });
    });
});

// Validar CPF/CNPJ
$('#cpf').on('keyup', function () {
    var strCPF = document.getElementById("cpf").value;
    strCPF = strCPF.replace(/[^\d]+/g, '');
    var qntNumero = strCPF.length;
    if (qntNumero <= 11) {
        function TestaCPF(strCPF) {
            var Soma;
            var Resto;
            Soma = 0;


            if (strCPF == "00000000000" ||
                strCPF == "11111111111" ||
                strCPF == "22222222222" ||
                strCPF == "33333333333" ||
                strCPF == "44444444444" ||
                strCPF == "55555555555" ||
                strCPF == "66666666666" ||
                strCPF == "77777777777" ||
                strCPF == "88888888888" ||
                strCPF == "99999999999")
                return false;

            for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11)) Resto = 0;
            if (Resto != parseInt(strCPF.substring(9, 10))) return false;

            Soma = 0;
            for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11)) Resto = 0;
            if (Resto != parseInt(strCPF.substring(10, 11))) return false;
            return true;
        }
    }

    if (TestaCPF(strCPF) == false) {
        //alert('CPF Inválido');
        //document.getElementById("cpf").focus();
        document.getElementById("cpf").style.border = "2px solid red";
        document.getElementById("btn_new_send").disabled = true;


    }
    if (TestaCPF(strCPF) == true) {

        document.getElementById("cpf").style.border = "2px solid green";
        document.getElementById("btn_new_send").disabled = false;
    }

});

// Validar cnpj
$('#cnpj').on('keyup', function () {
    var cnpj = document.getElementById("cnpj").value;
    cnpj = cnpj.replace(/[^\d]+/g, '');
    var qntNumero = cnpj.length;

    if (qntNumero <= 14) {
        function validarCNPJ(cnpj) {

            if (cnpj == '') return false;

            if (cnpj.length != 14)
                return false;

            // Elimina CNPJs invalidos conhecidos
            if (cnpj == "00000000000000" ||
                cnpj == "11111111111111" ||
                cnpj == "22222222222222" ||
                cnpj == "33333333333333" ||
                cnpj == "44444444444444" ||
                cnpj == "55555555555555" ||
                cnpj == "66666666666666" ||
                cnpj == "77777777777777" ||
                cnpj == "88888888888888" ||
                cnpj == "99999999999999")
                return false;

            // Valida DVs
            tamanho = cnpj.length - 2
            numeros = cnpj.substring(0, tamanho);
            digitos = cnpj.substring(tamanho);
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
            numeros = cnpj.substring(0, tamanho);
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
    if (validarCNPJ(cnpj) == false) {
        //document.getElementById("cnpj").focus();
        document.getElementById("cnpj").style.border = "2px solid red";
        document.getElementById("btn_new_send").disabled = true;

    }
    if (validarCNPJ(cnpj) == true) {
        document.getElementById("cnpj").style.border = "2px solid green";
        document.getElementById("btn_new_send").disabled = false;
    }


});
// FIM //

// chama model editar//
$(document).ready(function () {
    $(document).on('click', '.view_data', function () {
        var id = $(this).attr("id");
        //Verificar se há valor na variável "user_id".
        if (id !== '') {
            var dados = {
                id: id
            };
            $.post('acoes/municipio/visualizar.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                $("#view_dados").html(retorna);
                $('#modalEdit').modal('show');
            });
        }
    });
});


/* modal upload */
$(document).ready(function () {
    // Abrir a modal e definir o ID do município
    $(document).on('click', '.upload_logo', function () {
        const municipioId = $(this).data('id');
        const municipioName = $(this).data('name');

        $('#uploadLogoModalLabel').text(`Upload do Logo para: ${municipioName}`);
        $('#municipioId').val(municipioId);
        $('#uploadLogoModal').modal('show');
    });

    // Submeter o formulário via AJAX
    $('#uploadLogoForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#uploadLogoModal').css('opacity', '.5');
            },
            success: function (response) {
                $('#uploadLogoModal').css('opacity', '').modal('hide');

                Swal.fire({
                    title: jsonResponseTitle(response),
                    text: response.message,
                    icon: response.icon,
                });

                if (response.status === 'success') {
                    // setTimeout(() => location.reload(), 2000);
                    recarregarDados();
                }
            },
            error: function () {
                Swal.fire({
                    title: 'Erro',
                    text: 'Erro ao realizar o upload. Tente novamente.',
                    icon: 'error',
                });
            },
        });
    });
});


