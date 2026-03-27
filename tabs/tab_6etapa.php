<?php if ($viewData['user']->hasPermission('processo_6etapa')) : ?>

    <div class="etapa-inner" id="6etapa" aria-labelledby="6etapa-tab">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <input type="hidden" name="idUser" value="<?php echo $viewData['user']->getId(); ?>">
        <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?= htmlspecialchars($idMunicipio, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
        <!-- Seção para Modelos de Notificação -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Gerar</h6>
            </div>
            <div class="card-body">
                <?php if ($viewData['user']->hasPermission('processso_6etapaGerarProjeto')) : ?>
                    <button id="gerarProjeto" class="btn btn-primary btn-sm">
                        <i class="fa fa-file"></i> Gerar Projeto
                    </button>
                <?php endif; ?>
                <?php if ($viewData['user']->hasPermission('processso_6etapaOrdenarDocumentos')) : ?>
                    <!-- Botão para abrir o modal -->
                    <button type="button" id="btnOrderDocuments" class="btn btn-primary btn-sm">
                        <i class="fa fa-sort"></i> Ordenar Documentos
                    </button>
                <?php endif; ?>
                <?php if ($viewData['user']->hasPermission('processso_6etapaMontarProjeto')) : ?>
                    <button id="juntarPdf" class="btn btn-primary btn-sm">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Montar Projeto Final
                    </button>
                <?php endif; ?>
            </div>

        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Documentos Gerados</h6>
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                    <input type="text" name="search_documentosGeradosEtapa6" id="search_documentosGeradosEtapa6" class="form-control" placeholder="Buscar documento">
                </div>

                <div id="dadosDocumentosGeradosEtapa6"></div>
            </div>
        </div>

        <div class="card-body">
            <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="6">
                    <i class="fa fa-check-circle"></i> Concluir Etapa
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="6">
                    <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="6">
                    <i class="fa fa-hourglass-half"></i> Enviar para Análise
                </button>
            <?php endif; ?>
        </div>

    </div>
    <!-- modal edit -->
    <div id="modalEditDocEtapa6" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Editar Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-edit-doc-epatap6 m-2" action="<?php echo BASE_URL ?>acoes/etapa6/update_documento.php" method="POST">

                    <div class="modal-body" id="visul_dados_doc_etapa6">
                        <!-- O conteúdo será carregado aqui -->
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="orderDocumentsModal" tabindex="-1" aria-labelledby="orderDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDocumentsModalLabel">Ordenar Documentos Anexados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="documentList" class="list-group">
                        <!-- Lista de documentos será carregada via AJAX -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="saveOrder" class="btn btn-primary">Salvar Ordem</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const listItems = document.querySelectorAll('.draggable');

            listItems.forEach(item => {
                item.addEventListener('dragstart', function() {
                    item.style.opacity = '0.5';
                });

                item.addEventListener('dragend', function() {
                    item.style.opacity = '1';
                });
            });
        });

        $(document).ready(function() {
            const procedureId = $('#id').val(); // ID do procedimento, forneça de onde necessário

            // Função para abrir o modal e carregar documentos
            function openOrderModal() {
                if (!procedureId) {
                    Swal.fire({
                        title: 'Erro',
                        html: 'ID do procedimento não encontrado.',
                        icon: 'error'
                    });
                    return;
                }

                $.ajax({
                    url: '<?php echo BASE_URL; ?>acoes/etapa6/getDocuments.php',
                    method: 'GET',
                    data: {
                        id: procedureId
                    },
                    dataType: 'html',
                    success: function(response) {
                        $('#documentList').html(response);

                        // Ativar drag and drop na lista
                        $('#documentList').sortable({
                            placeholder: "ui-state-highlight"
                        });

                        // Abrir o modal
                        $('#orderDocumentsModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Erro',
                            html: 'Erro ao carregar documentos.',
                            icon: 'error'
                        });
                        console.error('Erro ao carregar documentos:', error);
                    }
                });
            }

            // Função para salvar a nova ordem
            function saveOrder() {
                const ordem = $('#documentList').sortable('toArray'); // Obter IDs na ordem
                $.ajax({
                    url: '<?php echo BASE_URL; ?>acoes/etapa6/saveOrder.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        ordem
                    }),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: response.tittle || 'Sucesso',
                                html: response.message || 'Ordem salva com sucesso!',
                                icon: response.icon || 'success'
                            }).then(() => {
                                $('#orderDocumentsModal').modal('hide');
                            });
                        } else {
                            Swal.fire({
                                title: response.tittle || 'Erro',
                                html: response.message || 'Erro ao salvar a ordem.',
                                icon: response.icon || 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Erro',
                            html: 'Erro ao salvar ordem: ' + error,
                            icon: 'error'
                        });
                        console.error('Erro ao salvar ordem:', error);
                    }
                });
            }

            // Associar eventos
            $('#btnOrderDocuments').on('click', openOrderModal);
            $('#saveOrder').on('click', saveOrder);
        });
    </script>

    <script>
        // Delegação de evento para suportar elementos dinâmicos
        $(document).on('click', '.view_data_doc_etapa6', function() {
            const id = $(this).data("id"); // Usar 'data-id' em vez de 'id' para evitar conflitos
            if (id) {
                // Exibir indicador de carregamento enquanto o conteúdo é carregado
                $("#visul_dados_doc_etapa6").html('<p>Carregando...</p>');

                $.ajax({
                    url: '<?php echo BASE_URL ?>acoes/etapa6/visualizarDocEdit.php',
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        // Insere o conteúdo retornado no modal
                        $("#visul_dados_doc_etapa6").html(response);

                        // Exibir o modal após o carregamento
                        $('#modalEditDocEtapa6').modal('show');

                    },
                    error: function() {
                        // Tratar erro de carregamento
                        $("#visul_dados_doc_etapa6").html('<p>Erro ao carregar os dados. Tente novamente.</p>');
                    }
                });
            }
        });

        // Update documento// 
        $(document).ready(function() {
            var spinner = $('#loader');
            // Submissão do formulário usando AJAX
            $('.form-edit-doc-epatap6').on('submit', function(e) {
                tinyMCE.triggerSave(true, true); // para enviar o conteudo da matéria no tinyMCE
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
                    beforeSend: function() {
                        $('#content').css("opacity", ".5");
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            initDocumentosGeradosModuleEtapa6();
                            spinner.hide();
                            $('#content').css("opacity", "");
                            $('#modalEditDocEtapa6').modal('hide');

                        }
                        spinner.hide();
                        $('#content').css("opacity", "");
                        Swal.fire({
                            title: response.tittle,
                            html: response.message,
                            icon: response.icon
                        });
                    },
                    error: function(error) {
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

        function excluirDocumentoEtapa6(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você não poderá desfazer essa ação!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Exibe um alerta de carregamento enquanto o processo está em andamento
                    Swal.fire({
                        title: 'Excluindo...',
                        text: 'Por favor, aguarde.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '<?php echo BASE_URL ?>acoes/etapa6/excluir_documento.php',
                        method: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json', // Espera a resposta como JSON
                        success: function(response) {
                            if (response.success) { // Verifica se a operação foi bem-sucedida
                                Swal.fire('Excluído!', response.message, 'success');

                                // Recarregar a lista de documentos após a exclusão
                                reloadDocumentList();
                            } else {
                                Swal.fire('Erro!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Erro!', 'Não foi possível excluir o documento.', 'error');
                        }
                    });
                }
            });
        }

        // Função para recarregar a lista de documentos
        function reloadDocumentList() {
            $.ajax({
                url: '<?php echo BASE_URL ?>acoes/etapa6/getDocuments.php',
                method: 'GET',
                data: {
                    id: <?php echo $id; ?> // Utilize o ID do procedimento conforme necessário
                },
                dataType: 'html',
                success: function(response) {
                    // Atualiza o conteúdo da lista de documentos
                    $('#documentList').html(response);

                    // Reativar drag-and-drop na nova lista
                    $('#documentList').sortable({
                        placeholder: "ui-state-highlight"
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Erro',
                        html: 'Erro ao carregar documentos.',
                        icon: 'error'
                    });
                    console.error('Erro ao carregar documentos:', error);
                }
            });
        }



        // Deletar arquivo documento
        $(document).on('click', '.delete-document-etapa6', function() {
            const docId = $(this).data('id');
            var id_user = $('#idUser').val(); // Captura o ID do user

            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você não poderá desfazer essa ação!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const id_procedimento = $('#id').val();
                    $.ajax({
                        url: '<?php echo BASE_URL ?>acoes/etapa6/del_arquivo.php',
                        method: 'POST',
                        data: {
                            id: docId,
                            id_user: id_user,
                            id_procedimento: id_procedimento
                        },
                        dataType: 'json', // Espera a resposta em formato JSON
                        success: function(response) {
                            // Verifica se a resposta contém um status e uma mensagem
                            if (response.status === 'success') {
                                Swal.fire('Excluído!', response.message, 'success');
                                initDocumentosGeradosModuleEtapa6();
                            } else {
                                // Se não for sucesso, mostra a mensagem de erro
                                Swal.fire('Erro!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Erro!', 'Não foi possível excluir o documento.', 'error');
                        }
                    });
                }
            });
        });

        /* juntar pdf */
        $('#juntarPdf').on('click', function() {
            Swal.fire({
                title: 'Confirmação',
                text: 'Você tem certeza de que deseja juntar os PDFs e gerar o projeto?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, juntar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    const spinner = $('#loader');
                    spinner.show();
                    const id_procedimento = $('#id').val();

                    $.ajax({
                        url: '<?= BASE_URL ?>acoes/etapa6/juntar_pdfs.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_procedimento
                        },
                        beforeSend: function() {
                            $('#content').css('opacity', '.5');
                        },
                        success: function(response) {
                            spinner.hide();
                            $('#content').css('opacity', '');
                            Swal.fire({
                                title: response.tittle,
                                text: response.message,
                                icon: response.icon,
                            });

                            if (response.status === 'success' && response.file) {
                                const link = document.createElement('a');
                                link.href = response.file;
                                link.download = 'projeto.pdf';
                                link.click();
                            }
                        },
                        error: function() {
                            spinner.hide();
                            $('#content').css('opacity', '');
                            Swal.fire({
                                title: 'Erro',
                                text: 'Erro ao processar a solicitação.',
                                icon: 'error',
                            });
                        },
                    });
                }
            });
        });

        /* Gerar Projeto */
        $(document).ready(function() {
            var spinner = $('#loader');
            $('#gerarProjeto').click(function() {
                // Exibe mensagem de confirmação
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Você tem certeza de que deseja gerar o projeto?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, gerar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        spinner.show();
                        var button = $(this); // Armazena o botão
                        button.prop('disabled', true); // Desativa o botão temporariamente
                        var id_procedimento = $('#id').val(); // Captura o ID do procedimento
                        var id_municipio = $('#idMunicipio').val(); // Captura o ID do município
                        var id_user = $('#idUser').val(); // Captura o ID do user

                        $.ajax({
                            url: '<?php echo BASE_URL ?>acoes/etapa6/gerar_projeto.php',
                            type: 'POST',
                            dataType: 'json',
                            beforeSend: function() {
                                $('#content').css("opacity", ".5");
                            },
                            data: {
                                id_procedimento: id_procedimento,
                                id_municipio: id_municipio, // Inclui no envio
                                id_user: id_user,
                            },
                            success: function(response) {
                                try {
                                    if (response.status === 'success') {
                                        initDocumentosGeradosModuleEtapa6();
                                    }
                                    Swal.fire({
                                        title: response.tittle,
                                        html: response.message,
                                        icon: response.icon,
                                    });
                                } catch (e) {
                                    console.error(e);
                                    Swal.fire('Erro!', 'Resposta inválida do servidor.', 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                Swal.fire('Erro!', 'Ocorreu um erro ao gerar o projeto.', 'error');
                            },
                            complete: function() {
                                // Sempre restaura o estado
                                $('#content').css("opacity", "");
                                spinner.hide();
                                button.prop('disabled', false); // Reativa o botão
                            },
                        });
                    }
                });
            });
        });


        // Carregamento 
        function initDocumentosGeradosModuleEtapa6() {
            let currentPageDocGeradosEtapa6 = 1; // Página atual
            let debounceTimeoutDocGeradosEtapa6;
            const debounceDelayDocGeradosEtapa6 = 600;
            const contentDocGeradosEtapa6 = $('#dadosDocumentosGeradosEtapa6');
            const searchInputDocGeradosEtapa6 = $('#search_documentosGeradosEtapa6');

            // Função para atualizar o conteúdo da página
            function updateContentEtapa6(html) {
                contentDocGeradosEtapa6.html(html);
            }

            // Função para carregar dados via AJAX
            function loadData(page = currentPageDocGeradosEtapa6) {
                const query = searchInputDocGeradosEtapa6.val();
                var idProcedimento = $('#id').val();
                $.ajax({
                    url: "../../acoes/etapa6/fetch.php",
                    method: "POST",
                    data: {
                        page,
                        query,
                        id: idProcedimento
                    },
                    success: (data) => {
                        updateContentEtapa6(data);
                        currentPageDocGerados = page; // Atualiza a página atual
                    },
                    error: () => updateContentEtapa6('<p>Erro ao carregar dados.</p>')
                });
            }

            // Função para recarregar a mesma página após uma edição
            function reloadCurrentPageEtapa6() {
                loadData(currentPageDocGeradosEtapa6); // Recarrega a página atual
            }

            // Inicializa eventos e carrega os dados iniciais
            function carregamentoDocumentosGeradosEtapa6() {
                updateContentEtapa6('<p>Carregando...</p>');
                loadData();

                // Evento de paginação
                $(document).on('click', '.page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page_number');
                    if (page !== currentPageDocGeradosEtapa6) {
                        loadData(page);
                    }
                });

                // Evento de busca com debounce
                searchInputDocGeradosEtapa6.on('keyup', () => {
                    clearTimeout(debounceTimeoutDocGeradosEtapa6);
                    debounceTimeoutDocGeradosEtapa6 = setTimeout(() => loadData(1), debounceDelayDocGeradosEtapa6);
                });


            }

            $(document).ready(carregamentoDocumentosGeradosEtapa6);
        }

        // Chamada da função para iniciar o módulo carregamento
        initDocumentosGeradosModuleEtapa6();
    </script>



<?php endif; ?>