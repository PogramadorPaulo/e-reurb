
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
        document.getElementById("btn-salvar-re").disabled = true;


    }
    if (TestaCPF(strCPF) == true) {

        document.getElementById("cpf").style.border = "2px solid green";
        document.getElementById("btn-salvar-re").disabled = false;
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
        document.getElementById("btn-salvar-re").disabled = true;

    }
    if (validarCNPJ(cnpj) == true) {
        document.getElementById("cnpj").style.border = "2px solid green";
        document.getElementById("btn-salvar-re").disabled = false;
    }


});
// FIM //

// VALIDAR TIPO PESSOA //
$(document).ready(function () {
    document.getElementById("fisica").checked = true;
    document.getElementById("cpf").focus();
    document.getElementById("cnpj").disabled = true;
    document.getElementById("i_estadual").disabled = true;
    document.getElementById("i_municipal").disabled = true;
    document.getElementById("pai").disabled = false;
    document.getElementById("mae").disabled = false;
    document.getElementById("representante").disabled = true;
    document.getElementById("cargo").disabled = true;
    $('#fisica').click(function () {
        document.getElementById("cpf").disabled = false;
        document.getElementById("i_estadual").disabled = true;
        document.getElementById("i_municipal").disabled = true;
        document.getElementById("pai").disabled = false;
        document.getElementById("mae").disabled = false;
        document.getElementById("representante").disabled = true;
        document.getElementById("cargo").disabled = true;
        document.getElementById("cnpj").disabled = true;
        document.getElementById("cnpj").value = '';
        document.getElementById("cpf").focus();
        document.getElementById("juridica").checked = false;

    })

    $('#juridica').click(function () {
        document.getElementById("cpf").disabled = true;
        document.getElementById("i_estadual").disabled = false;
        document.getElementById("i_municipal").disabled = false;
        document.getElementById("representante").disabled = false;
        document.getElementById("cargo").disabled = false;
        document.getElementById("pai").disabled = true;
        document.getElementById("mae").disabled = true;
        document.getElementById("cnpj").disabled = false;
        document.getElementById("cnpj").focus();
        document.getElementById("cpf").value = '';
        document.getElementById("fisica").checked = false;

    })
});
// FIM //

