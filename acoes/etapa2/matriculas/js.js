function carrega_mapaMatriculas() {
    var id = $('#id').val();

    $('#mapaMatriculas').empty();

    $.ajax({
        url: '../../acoes/etapa2/matriculas/carregar_matriculas_proprietarios.php',
        type: 'GET',
        dataType: 'json',
        data: { id: id },
        success: function (data) {
            data.forEach(matricula => {
                let matriculaDiv = $('<div>')
                    .addClass('col-md-12 matricula')
                    .attr('data-matricula-id', matricula.matricula_id);

                // Cabeçalho da matrícula
                let matriculaHeader = $('<div>')
                    .addClass('matricula-header d-flex justify-content-between align-items-center');

                // Título da matrícula
                matriculaHeader.append(`<div class="matricula-title">Matrícula: ${matricula.matricula_nome}</div>`);

                // Grupo de botões
                let btnGroup = $('<div>').addClass('btn-group');

                let btnEditarMatricula = $('<button>')
                    .addClass('btn btn-outline-primary btn-sm btn-editar-matricula')
                    .html('<i class="fa fa-pencil" aria-hidden="true"></i> Editar')
                    .on('click', function () {
                        abrirModalEditarMatricula(matricula.matricula_id, matricula.matricula_nome);
                    });

                let btnExcluirMatricula = $('<button>')
                    .addClass('btn btn-outline-danger btn-sm btn-excluir-matricula')
                    .html('<i class="fa fa-trash" aria-hidden="true"></i> Excluir')
                    .on('click', function () {
                        excluirMatricula(matricula.matricula_id, matricula.matricula_nome);
                    });

                let btnAdicionarProprietario = $('<button>')
                    .addClass('btn btn-outline-success btn-sm btn-adicionar-proprietario')
                    .html('<i class="fa fa-plus" aria-hidden="true"></i> Adicionar Proprietário')
                    .on('click', function () {
                        $('#matriculaId').val(matricula.matricula_id);
                        carregarProprietariosMatriculas();
                        $('#adicionarProprietarioModal').modal('show');
                    });

                btnGroup.append(btnEditarMatricula, btnExcluirMatricula, btnAdicionarProprietario);
                matriculaHeader.append(btnGroup);
                matriculaDiv.append(matriculaHeader);

                // Contagem de proprietários
                matriculaDiv.append(`<div class="proprietarios-count">Total de Proprietários: ${matricula.proprietarios.length}</div>`);

                // Lista de proprietários
                let proprietariosList = $('<div>').addClass('proprietarios-list');

                matricula.proprietarios.forEach(proprietario => {
                    let proprietarioDiv = $('<div>').addClass('proprietario');

                    let nome = $('<strong>').text(proprietario.nome);
                    let docLabel = proprietario.tipo_pessoa === 'Jurídica' ? 'CNPJ' : 'CPF';
                    let docValue = proprietario.tipo_pessoa === 'Jurídica' ? (proprietario.cnpj ?? '') : (proprietario.cpf ?? '');
                    let documento = $('<div>').text(`${docLabel}: ${docValue}`);

                    let btnExcluirProprietario = $('<button>')
                        .addClass('btn btn-outline-danger btn-sm delete-proprietario')
                        .text('Excluir')
                        .attr('data-proprietario-id', proprietario.id)
                        .attr('data-matricula-id', matricula.matricula_id);

                    proprietarioDiv.append(nome, '<br>', documento, btnExcluirProprietario);
                    proprietariosList.append(proprietarioDiv);
                });

                matriculaDiv.append(proprietariosList);
                $('#mapaMatriculas').append(matriculaDiv);
            });

            // Evento de exclusão
            $('.delete-proprietario').on('click', function () {
                let proprietarioId = $(this).data('proprietario-id');
                let matriculaId = $(this).data('matricula-id');

                Swal.fire({
                    title: 'Tem certeza?',
                    html: 'Você não poderá desfazer essa ação!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        excluirProprietario(matriculaId, proprietarioId);
                    }
                });
            });
        },
        error: function () {
            Swal.fire({
                title: 'Erro',
                html: 'Erro ao carregar as matrículas.',
                icon: 'error'
            });
        }
    });
}


// Função para excluir a matrícula
function excluirMatricula(matriculaId, matriculaNome) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `Você está prestes a excluir a matrícula <strong>${matriculaNome}</strong>. Essa ação não pode ser desfeita!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var idProcedimento = $('#id').val();
            $.ajax({
                url: '../../acoes/etapa2/matriculas/excluir_matricula.php',
                type: 'POST',
                data: {
                    matricula_id: matriculaId,
                    idProcedimento: idProcedimento
                },
                dataType: 'json',
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        html: response.message,
                        icon: response.icon
                    }).then(() => {
                        if (response.status === 'success') {
                            carrega_mapaMatriculas(); // Recarrega as matrículas após a exclusão
                        }
                    });
                },
                error: function () {
                    Swal.fire({
                        title: 'Erro',
                        html: 'Não foi possível excluir a matrícula. Tente novamente.',
                        icon: 'error'
                    });
                }
            });
        }
    });
}


// Função para abrir o modal de edição da matrícula
function abrirModalEditarMatricula(matriculaId, matriculaNome) {
    $('#editarMatriculaModal').modal('show');
    $('#editarMatriculaId').val(matriculaId); // Preenche o ID da matrícula no modal
    $('#editarMatriculaNome').val(matriculaNome); // Preenche o nome da matrícula no modal
}
// Função para confirmar e excluir o proprietário
function excluirProprietario(matriculaId, proprietarioId) {
    Swal.fire({
        title: 'Tem certeza?',
        html: 'Você não poderá desfazer essa ação!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmou, prosseguir com a exclusão
            var idProcedimento = $('#id').val();
            $.ajax({
                url: '../../acoes/etapa2/matriculas/excluir_proprietario.php',
                type: 'POST',
                data: {
                    matricula_id: matriculaId,
                    proprietario_id: proprietarioId,
                    idProcedimento: idProcedimento
                },
                dataType: 'json',
                success: function (response) {
                    Swal.fire({
                        title: response.title || 'Atenção',
                        html: response.message,
                        icon: response.icon || (response.status === 'success' ? 'success' : 'error')
                    }).then(() => {
                        // Recarregar as matrículas e proprietários após a exclusão bem-sucedida
                        if (response.status === 'success') {
                            carrega_mapaMatriculas();
                        }
                    });
                },
                error: function () {
                    Swal.fire({
                        title: 'Erro',
                        html: 'Não foi possível excluir o proprietário. Tente novamente.',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

$(document).ready(function () {
    carrega_mapaMatriculas();
});

$('#formEditarMatricula').on('submit', function (event) {
    event.preventDefault();

    $.ajax({
        url: '../../acoes/etapa2/matriculas/salvar_edicao_matricula.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            Swal.fire({
                title: response.title,
                html: response.message,
                icon: response.icon
            }).then(() => {
                if (response.status === 'success') {
                    $('#editarMatriculaModal').modal('hide');
                    carrega_mapaMatriculas();
                }
            });
        },
        error: function () {
            Swal.fire({
                title: 'Erro',
                html: 'Não foi possível salvar as alterações. Tente novamente.',
                icon: 'error'
            });
        }
    });
});

$(document).ready(function () {
    // Abrir o modal de nova matrícula ao clicar no botão
    $('#btnNovaMatricula').on('click', function () {
        $('#novaMatriculaModal').modal('show');
    });

    // Submissão do formulário de nova matrícula
    $('#formNovaMatricula').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '../../acoes/etapa2/matriculas/salvar_nova_matricula.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                Swal.fire({
                    title: response.title,
                    html: response.message,
                    icon: response.icon
                }).then(() => {
                    if (response.status === 'success') {
                        $('#novaMatriculaModal').modal('hide');
                        carrega_mapaMatriculas(); // Recarregar as matrículas após o cadastro
                    }
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Erro',
                    html: 'Não foi possível cadastrar a matrícula. Tente novamente.',
                    icon: 'error'
                });
            }
        });
    });
});

$(document).ready(function () {
    // Submissão do formulário para adicionar proprietário
    $('#formAdicionarProprietario').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '../../acoes/etapa2/matriculas/adicionar_proprietario_matricula.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                Swal.fire({
                    title: response.title,
                    html: response.message,
                    icon: response.icon
                }).then(() => {
                    if (response.status === 'success') {
                        $('#adicionarProprietarioModal').modal('hide');
                        carrega_mapaMatriculas(); // Recarregar as matrículas após adicionar
                    }
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Erro',
                    html: 'Não foi possível adicionar o proprietário. Tente novamente.',
                    icon: 'error'
                });
            }
        });
    });
});

// Carregar proprietários no select
function carregarProprietariosMatriculas() {
    var id = $('#id').val();
    $.ajax({
        url: '../../acoes/etapa2/matriculas/carregar_proprietarios.php',
        type: 'GET',
        dataType: 'json',
        data: { id: id },
        success: function (data) {
            $('#proprietarioMatriculaSelect').empty();
            data.forEach(proprietario => {
                $('#proprietarioMatriculaSelect').append(new Option(proprietario.nome, proprietario.id));
            });
        },
        error: function () {
            Swal.fire({
                title: 'Erro',
                html: 'Erro ao carregar proprietários.',
                icon: 'error'
            });
        }
    });
}




