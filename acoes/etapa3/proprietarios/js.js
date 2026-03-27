
// chama model editar //
$(document).ready(function () {
    $(document).on('click', '.view_data_proprietarios', function () {
        var id = $(this).attr("id");
        if (id !== '') {
            var dados = {
                id: id
            };
            $.post('../../acoes/etapa3/proprietarios/visualizar.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                $("#visul_dados_proprietarios").html(retorna);
                $('#modalEditProprietario').modal('show');
            });
        }
    });
});

// chama model cadastro do conjuge//
$(document).ready(function () {
    $(document).on('click', '.view_data_proprietarios_conjuge', function () {
        var id = $(this).attr("id");
        if (id !== '') {
            var dados = {
                id: id
            };
            $.post('../../acoes/etapa3/proprietarios/visualizarConjuge.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                $("#visul_dados_proprietarios_conjuge").html(retorna);
                $('#modalEditProprietarioConjuge').modal('show');
            });
        }
    });
});


// Chama modal novo
$(document).on('click', '.newProprietario', function () {
    $('#modalNewProprietario').modal('show');
});

// Carregamento 
function initProprietariosModule() {
    let currentPageProprietarios = 1; // Página atual
    let timeProprietarios;
    const delayProprietarios = 600;
    const contentProprietarios = $('#dynamic_content_proprietarios');
    const searchInputProprietarios = $('#search_proprietario');

    // Função para atualizar o conteúdo da página
    function updateContent(html) {
        contentProprietarios.html(html);
    }

    // Função para carregar dados via AJAX
    function loadData(page = currentPageProprietarios) {
        const query = searchInputProprietarios.val();
        $.ajax({
            url: "../../acoes/etapa3/proprietarios/fetch.php",
            method: "POST",
            data: { page, query, id: $('#id').val() },
            success: (data) => {
                updateContent(data);
                currentPageProprietarios = page; // Atualiza a página atual
            },
            error: () => updateContent('<p>Erro ao carregar dados.</p>')
        });
    }

    // Função para recarregar a mesma página após uma edição
    function reloadCurrentPage() {
        loadData(currentPageProprietarios); // Recarrega a página atual
    }

    // Inicializa eventos e carrega os dados iniciais
    function carregamentoProprietarios() {
        updateContent('<p>Carregando...</p>');
        loadData();

        // Evento de paginação
        $(document).on('click', '.page-link', function (e) {
            e.preventDefault();
            const page = $(this).data('page_number');
            if (page !== currentPageProprietarios) {
                loadData(page);
            }
        });

        // Evento de busca com debounce
        searchInputProprietarios.on('keyup', () => {
            clearTimeout(timeProprietarios);
            timeProprietarios = setTimeout(() => loadData(1), delayProprietarios);
        });

        // Evento de edição (exemplo de chamada após editar um item)
        $(document).on('click', '.view_data_proprietarios', function () {
            const id = $(this).attr('id');
            // Suponha que a edição seja feita aqui...
            // Após editar, recarrega a página atual:
            reloadCurrentPage();
        });
    }

    $(document).ready(carregamentoProprietarios);
}

// Chamada da função para iniciar o módulo carregamento
initProprietariosModule();


// deletar // 
function deletarProprietario(id, status) {
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
                url: "../../acoes/etapa3/proprietarios/excluir.php",
                beforeSend: function () {
                    $('#dynamic_content_proprietarios').css("opacity", ".5");
                },
                success: function (response) {
                    if (response.status == 'success') {
                        initProprietariosModule();
                        spinner.hide();
                        $('#dynamic_content_proprietarios').css("opacity", "");
                    }
                    spinner.hide();
                    $('#dynamic_content_proprietarios').css("opacity", "");
                    Swal.fire({
                        title: response.tittle,
                        html: response.message,
                        icon: response.icon
                    });
                },
                error: function (error) {
                    $('#dynamic_content_proprietarios').css("opacity", "");
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


// New  proprietário//
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-new-proprietario').on('submit', function (e) {
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
                    initProprietariosModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalNewProprietario').modal('hide');
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

// Update proprietário // 
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
                    initProprietariosModule();
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
// Fim // 

// Update proprietário conjuge // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-proprietario-conjuge').on('submit', function (e) {
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
                    initProprietariosModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalEditProprietarioConjuge').modal('hide');
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
// Fim // 


// Verificação de CPF via AJAX
$("#cpfProprietario").on("blur", function () {
    var cpf = $(this).val();
    var id = $('#id').val()
    // Verifica se o campo CPF não está vazio
    if (cpf.length > 0) {
        $.ajax({
            url: "../../acoes/etapa3/proprietarios/verificar_cpf.php",
            type: "POST",
            data: {
                cpf: cpf,
                id: id
            },
            success: function (response) {
                if (response.status === 'exists') {
                    // Se o CPF já estiver cadastrado, exibe alerta e limpa o campo
                    Swal.fire({
                        icon: 'error',
                        title: 'CPF já cadastrado',
                        text: 'Este CPF já foi utilizado para este proprietário.'
                    });
                    $("#cpfProprietario").val(''); // Limpa o campo CPF
                }
            },
            error: function (xhr, status, error) {
                console.error("Erro ao verificar CPF: " + error);
            }
        });
    }
});

// Verificação de CNPJ via AJAX
$("#cnpjProprietario").on("blur", function () {
    var cnpj = $(this).val();
    var id = $('#id').val()
    // Verifica se o campo CPF não está vazio
    if (cnpj.length > 0) {
        $.ajax({
            url: "../../acoes/etapa3/proprietarios/verificar_cnpj.php",
            type: "POST",
            data: {
                cnpj: cnpj,
                id: id
            },
            success: function (response) {
                if (response.status === 'exists') {
                    // Se o CPF já estiver cadastrado, exibe alerta e limpa o campo
                    Swal.fire({
                        icon: 'error',
                        title: 'CNPJ já cadastrado',
                        text: 'Este CNPJ já foi utilizado para este proprietário.'
                    });
                    $("#cnpjProprietario").val(''); // Limpa o campo CPF
                }
            },
            error: function (xhr, status, error) {
                console.error("Erro ao verificar CNPJ: " + error);
            }
        });
    }
});


// Validar cpf_con/cnpj_con
$('#cpfProprietario').on('keyup', function () {
    var strcpf_con = document.getElementById("cpfProprietario").value;
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
        document.getElementById("cpfProprietario").style.border = "2px solid red";
        document.getElementById("btn-salvar-proprietario").disabled = true;


    }
    if (Testacpf_con(strcpf_con) == true) {

        document.getElementById("cpfProprietario").style.border = "2px solid green";
        document.getElementById("btn-salvar-proprietario").disabled = false;
    }

});

// Validar cnpj_con
$('#cnpjProprietario').on('keyup', function () {
    var cnpj_con = document.getElementById("cnpjProprietario").value;
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
        document.getElementById("cnpjProprietario").style.border = "2px solid red";
        document.getElementById("btn-salvar-proprietario").disabled = true;

    }
    if (validarcnpj_con(cnpj_con) == true) {
        document.getElementById("cnpjProprietario").style.border = "2px solid green";
        document.getElementById("btn-salvar-proprietario").disabled = false;
    }


});
// FIM //

// VALIDAR TIPO PESSOA //
$(document).ready(function () {
    document.getElementById("fisicaProprietario").checked = true;
    document.getElementById("cpfProprietario").focus();
    document.getElementById("cnpjProprietario").disabled = true;
    document.getElementById("i_estadualProprietario").disabled = true;
    document.getElementById("i_municipalProprietario").disabled = true;
    document.getElementById("paiProprietario").disabled = false;
    document.getElementById("maeProprietario").disabled = false;
    document.getElementById("representanteProprietario").disabled = true;
    document.getElementById("cargoProprietario").disabled = true;
    $('#fisicaProprietario').click(function () {
        document.getElementById("cpfProprietario").disabled = false;
        document.getElementById("i_estadualProprietario").disabled = true;
        document.getElementById("i_municipalProprietario").disabled = true;
        document.getElementById("paiProprietario").disabled = false;
        document.getElementById("maeProprietario").disabled = false;
        document.getElementById("representanteProprietario").disabled = true;
        document.getElementById("cargoProprietario").disabled = true;
        document.getElementById("cnpjProprietario").disabled = true;
        document.getElementById("cnpjProprietario").value = '';
        document.getElementById("cpfProprietario").focus();
        document.getElementById("juridicaProprietario").checked = false;

    })

    $('#juridicaProprietario').click(function () {
        document.getElementById("cpfProprietario").disabled = true;
        document.getElementById("i_estadualProprietario").disabled = false;
        document.getElementById("i_municipalProprietario").disabled = false;
        document.getElementById("representanteProprietario").disabled = false;
        document.getElementById("cargoProprietario").disabled = false;
        document.getElementById("paiProprietario").disabled = true;
        document.getElementById("maeProprietario").disabled = true;
        document.getElementById("cnpjProprietario").disabled = false;
        document.getElementById("cnpjProprietario").focus();
        document.getElementById("cpfProprietario").value = '';
        document.getElementById("fisicaProprietario").checked = false;

    })
});
// FIM //

// mascaras //
$(document).ready(function () {
    $('#cnpjProprietario').mask('99.999.999/9999-99');
    $('#cpfProprietario').mask('999.999.999-99');
    $('#cepProprietario').mask('99999-999');
    $('#celularProprietario').mask('(99) 99999-9999');
    $('#telefoneProprietario').mask('(99) 9999-9999');
});



