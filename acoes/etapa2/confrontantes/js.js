
// chama model editar requerente//
$(document).ready(function () {
    $(document).on('click', '.view_data_confrontante', function () {
        var id = $(this).attr("id");
        if (id !== '') {
            var dados = {
                id: id
            };
            $.post('../../acoes/etapa2/confrontantes/visualizar.php', dados, function (retorna) {
                //Carregar o conteúdo para o usuário
                $("#visul_dados_confrontantes").html(retorna);
                $('#modalEditConfrontante').modal('show');
            });
        }
    });
});


// Chama modal novo
$(document).on('click', '.newConfrontante', function () {
    $('#modalNewConfrontante').modal('show');
});

// Carregamento 
function initConfrontantesModule() {
    let currentPageConfrontante = 1; // Página atual
    let debounceTimeoutConfrontante;
    const debounceDelayConfrontante = 600;
    const contentConfrontante = $('#dynamic_content_confrontantes');
    const searchInputConfrontante = $('#search_confrontante');

    // Função para atualizar o conteúdo da página
    function updateContent(html) {
        contentConfrontante.html(html);
    }

    // Função para carregar dados via AJAX
    function loadData(page = currentPageConfrontante) {
        const query = searchInputConfrontante.val();
        $.ajax({
            url: "../../acoes/etapa2/confrontantes/fetch.php",
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
    function carregamentoConfrontantes() {
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

        // Evento de edição (exemplo de chamada após editar um item)
        $(document).on('click', '.view_data_requerente', function () {
            const id = $(this).attr('id');
            // Suponha que a edição seja feita aqui...
            // Após editar, recarrega a página atual:
            reloadCurrentPage();
        });
    }

    $(document).ready(carregamentoConfrontantes);
}

// Chamada da função para iniciar o módulo carregamento
initConfrontantesModule();


// deletar // 
function deletarConfrontante(id, status) {
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
                url: "../../acoes/etapa2/confrontantes/excluir.php",
                beforeSend: function () {
                    $('#dynamic_content_confrontantes').css("opacity", ".5");
                },
                success: function (response) {
                    if (response.status == 'success') {
                        initConfrontantesModule();
                        spinner.hide();
                        $('#dynamic_content_confrontantes').css("opacity", "");
                    }
                    spinner.hide();
                    $('#dynamic_content_confrontantes').css("opacity", "");
                    Swal.fire({
                        title: response.tittle,
                        html: response.message,
                        icon: response.icon
                    });
                },
                error: function (error) {
                    $('#dynamic_content_confrontantes').css("opacity", "");
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
    $('.form-new-confrontante').on('submit', function (e) {
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
                    initConfrontantesModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalNewConfrontante').modal('hide');
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


// Update  // 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-edit-confrontante').on('submit', function (e) {
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
                    initConfrontantesModule();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalEditConfrontante').modal('hide');
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

// Validar cpf_con/cnpj_con
$('#cpf_con').on('keyup', function () {
    var strcpf_con = document.getElementById("cpf_con").value;
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
        document.getElementById("cpf_con").style.border = "2px solid red";
        document.getElementById("btn-salvar-con").disabled = true;


    }
    if (Testacpf_con(strcpf_con) == true) {

        document.getElementById("cpf_con").style.border = "2px solid green";
        document.getElementById("btn-salvar-con").disabled = false;
    }

});

// Validar cnpj_con
$('#cnpj_con').on('keyup', function () {
    var cnpj_con = document.getElementById("cnpj_con").value;
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
        document.getElementById("cnpj_con").style.border = "2px solid red";
        document.getElementById("btn-salvar-con").disabled = true;

    }
    if (validarcnpj_con(cnpj_con) == true) {
        document.getElementById("cnpj_con").style.border = "2px solid green";
        document.getElementById("btn-salvar-con").disabled = false;
    }


});
// FIM //

// VALIDAR TIPO PESSOA //
$(document).ready(function () {
    document.getElementById("fisica_con").checked = true;
    document.getElementById("cpf_con").focus();
    document.getElementById("cnpj_con").disabled = true;
    document.getElementById("i_estadual_con").disabled = true;
    document.getElementById("i_municipal_con").disabled = true;
    document.getElementById("pai_con").disabled = false;
    document.getElementById("mae_con").disabled = false;
    document.getElementById("representante_con").disabled = true;
    document.getElementById("cargo_con").disabled = true;
    $('#fisica_con').click(function () {
        document.getElementById("cpf_con").disabled = false;
        document.getElementById("i_estadual_con").disabled = true;
        document.getElementById("i_municipal_con").disabled = true;
        document.getElementById("pai_con").disabled = false;
        document.getElementById("mae_con").disabled = false;
        document.getElementById("representante_con").disabled = true;
        document.getElementById("cargo_con").disabled = true;
        document.getElementById("cnpj_con").disabled = true;
        document.getElementById("cnpj_con").value = '';
        document.getElementById("cpf_con").focus();
        document.getElementById("juridica_con").checked = false;

    })

    $('#juridica_con').click(function () {
        document.getElementById("cpf_con").disabled = true;
        document.getElementById("i_estadual_con").disabled = false;
        document.getElementById("i_municipal_con").disabled = false;
        document.getElementById("representante_con").disabled = false;
        document.getElementById("cargo_con").disabled = false;
        document.getElementById("pai_con").disabled = true;
        document.getElementById("mae_con").disabled = true;
        document.getElementById("cnpj_con").disabled = false;
        document.getElementById("cnpj_con").focus();
        document.getElementById("cpf_con").value = '';
        document.getElementById("fisica_con").checked = false;

    })
});
// FIM //

$(document).ready(function () {
    $('#cnpj_con').mask('99.999.999/9999-99');
    $('#cpf_con').mask('999.999.999-99');
    $('#cep_con').mask('99999-999');
    $('#celular_con').mask('(99) 99999-9999');
    $('#telefone_con').mask('(99) 9999-9999');
});



