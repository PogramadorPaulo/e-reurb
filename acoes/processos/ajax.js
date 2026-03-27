$(document).ready(function () {
    // Inicializar filtros e carregar dados iniciais
    configurarFiltros();
    recarregarDados();
});

// Variáveis globais
var currentPage = 1; // Página inicial
var query = ''; // Consulta inicial
var tipos = ''; // Tipo inicial
var dataInicial = ''; // Data inicial
var dataFinal = ''; // Data final

// Alternar a visibilidade dos filtros
$('#toggleFilters').on('click', function () {
    $('#filters').toggle();
    var isVisible = $('#filters').is(':visible');
    $(this).html(isVisible ? '<i class="fa fa-times"></i> Esconder Filtros' : '<i class="fa fa-filter"></i> Mostrar Filtros');
});

// Função para validar datas
function validarDatas(dataInicial, dataFinal) {
    if (dataInicial && dataFinal && new Date(dataInicial) > new Date(dataFinal)) {
        Swal.fire('Atenção', 'A data final não pode ser anterior à data inicial.', 'warning');
        return false;
    }
    return true;
}

// Função para carregar dados
function loadData(page = 1, query = '', tipos = '', dataInicial = '', dataFinal = '') {
    if (!validarDatas(dataInicial, dataFinal)) return;

    $('#loader').show();
    id_municipio = $('#idMunicipio').val();
    $.ajax({
        url: "acoes/processos/fetch.php",
        method: "POST",
        data: {
            page: page,
            query: query,
            tipo: tipos,
            data_inicial: dataInicial,
            data_final: dataFinal,
            id_municipio: id_municipio
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
    loadData(currentPage, query, tipos, dataInicial, dataFinal);
}

// Configurar eventos de filtros e paginação
function configurarFiltros() {
    $('#search_obejto').on('keyup', function () {
        query = $(this).val();
        currentPage = 1;
        recarregarDados();
    });

    $('#select_search_tipos').on('change', function () {
        tipos = $(this).val();
        currentPage = 1;
        recarregarDados();
    });

    $('#btn_search').on('click', function () {
        dataInicial = $('#search_data_inicial').val();
        dataFinal = $('#search_data_final').val();
        currentPage = 1;
        recarregarDados();
    });

    $(document).on('click', '.page-link', function () {
        var page = $(this).data('page_number');
        if (page) {
            loadData(page, query, tipos, dataInicial, dataFinal);
        }
    });
}

// Cancelar Procedimento
$(document).on('click', '.cancelarProcedimento', function () {
    const procedimentoId = $(this).data('id');

    if (!procedimentoId) {
        Swal.fire('Erro!', 'ID do procedimento não encontrado.', 'error');
        return;
    }

    Swal.fire({
        title: 'Confirmação',
        text: 'Você tem certeza de que deseja cancelar este procedimento? Essa ação não poderá ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não, manter'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processando...',
                text: 'Por favor, aguarde.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: 'acoes/processos/cancelar_procedimento.php',
                type: 'POST',
                dataType: 'json',
                data: { id_procedimento: procedimentoId },
                success: function (response) {
                    Swal.close();
                    Swal.fire({
                        title: response.tittle,
                        html: response.message,
                        icon: response.icon
                    }).then(() => {
                        if (response.status === 'success') {
                            recarregarDados(); // Recarregar dados após o cancelamento
                        }
                    });
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Erro!', 'Ocorreu um erro ao tentar cancelar o procedimento. Tente novamente.', 'error');
                }
            });
        }
    });
});

// Ativar 
$(document).on('click', '.ativarProcedimento', function () {
    const procedimentoId = $(this).data('id'); // ID do procedimento a ser ativado

    if (!procedimentoId) {
        Swal.fire('Erro!', 'ID do procedimento não encontrado.', 'error');
        return;
    }

    Swal.fire({
        title: 'Confirmação',
        text: 'Você tem certeza de que deseja ativar este procedimento?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, ativar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'acoes/processos/ativar_procedimento.php', // Rota para o backend
                type: 'POST',
                dataType: 'json',
                data: { id_procedimento: procedimentoId },
                success: function (response) {
                    Swal.fire({
                        title: response.tittle,
                        html: response.message,
                        icon: response.icon
                    }).then(() => {
                        if (response.status === 'success') {
                            recarregarDados(); // Recarregar os dados após o sucesso
                        }
                    });
                },
                error: function () {
                    Swal.fire('Erro!', 'Ocorreu um erro ao tentar ativar o procedimento. Tente novamente.', 'error');
                }
            });
        }
    });
});

/* Novo processo */
$(document).ready(function () {
    $('#novoProcedimento').click(function () {
        Swal.fire({
            title: 'Novo Procedimento',
            html: `
                <div class="form-group">
                    <label for="modalidade">Modalidade</label>
                    <select id="modalidade" class="form-control">
                        <option value="Reurb-E">Reurb-E (Específico)</option>
                        <option value="Reurb-S">Reurb-S (Social)</option>
                        <option value="Reurb-M">Reurb-M (Misto)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nucleo_nome">Nome do Núcleo</label>
                    <input type="text" id="nucleo_nome" class="form-control" placeholder="Digite o nome do núcleo">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Salvar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const modalidade = $('#modalidade').val();
                const nucleo_nome = $('#nucleo_nome').val().trim();

                if (!modalidade || !nucleo_nome) {
                    Swal.showValidationMessage('Todos os campos são obrigatórios!');
                }

                return { modalidade, nucleo_nome };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const data = result.value;
                // Adiciona os campos ocultos id_user e id_municipio ao objeto de dados
                data.id_user = $('#idUser').val();
                data.id_municipio = $('#idMunicipio').val();

                $.ajax({
                    url: 'acoes/processos/novo_procedimento.php',
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        Swal.fire({
                            title: response.tittle,
                            html: response.message,
                            icon: response.icon
                        }).then(() => {
                            if (response.status === 'success') {
                                // Redireciona para a página do novo procedimento
                                window.location.href = `processos/view/${response.codigo}`;

                            }
                        });
                    },
                    error: function () {
                        Swal.fire('Erro!', 'Não foi possível criar o procedimento. Tente novamente.', 'error');
                    }
                });
            }
        });
    });
});
