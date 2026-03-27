<?php if ($viewData['user']->hasPermission('processo_7etapa')) : ?>

    <div class="tab-pane" id="7etapa" aria-labelledby="7etapa-tab">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <input type="hidden" name="idUser" value="<?php echo $viewData['user']->getId(); ?>">
        <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?= htmlspecialchars($idMunicipio, ENT_QUOTES, 'UTF-8') ?>">
        <!-- Seção para Modelos de Notificação -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Gerar</h6>
            </div>
            <div class="card-body">
                <?php if ($viewData['user']->hasPermission('processso_7etapaGerarCRF')) : ?>
                    <button id="gerarCRF" class="btn btn-primary btn-sm" style="display: none;">
                        <i class="fa fa-file"></i> Gerar CRF</button>
                <?php endif; ?>
                <?php if ($viewData['user']->hasPermission('processso_7etapaGerarCRFe')) : ?>
                    <button id="gerarCRFe" class="btn btn-primary btn-sm" style="display: none;">
                        <i class="fa fa-file"></i> Gerar CRF-e</button>
                <?php endif; ?>

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Documentos Gerados</h6>
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                    <input type="text" name="search_documentosGerados" id="search_documentosGerados" class="form-control" placeholder="Buscar documento">
                </div>

                <div id="dadosDocumentosGerados"></div>
            </div>
        </div>

        <div class="card-body">
            <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="7">
                    <i class="fa fa-check-circle"></i> Concluir Etapa
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="7">
                    <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="7">
                    <i class="fa fa-hourglass-half"></i> Enviar para Análise
                </button>
            <?php endif; ?>
        </div>

    </div>
    <!-- modal edit -->
    <div id="modalEditDocetapa7" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Editar Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-edit-doc-epatap7 m-2" action="<?php echo BASE_URL ?>acoes/etapa7/update_documento.php" method="POST">

                    <div class="modal-body" id="visul_dados_doc_etapa7">
                        <!-- O conteúdo será carregado aqui -->
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /* Valida os botões Reurb-s Reurb-E */
        function validarBotoesModalidade() {
            const procedimentoId = $('#id').val(); // Suponha que tenha um campo oculto com o ID do procedimento
            if (!procedimentoId) {
                console.warn('ID do procedimento não informado.');
                return;
            }

            $.ajax({
                url: '<?php echo BASE_URL; ?>acoes/etapa7/getModalidade.php',
                type: 'POST',
                data: {
                    id: procedimentoId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const modalidade = response.modalidade;

                        // Controle da exibição dos botões
                        $('#gerarCRFe').hide();
                        $('#gerarCRF').hide();

                        if (modalidade === 'Reurb-E') {
                            $('#gerarCRFe').show();
                        } else if (modalidade === 'Reurb-S') {
                            $('#gerarCRF').show();
                        }else if (modalidade === 'Reurb-M') {
                            $('#gerarCRF').show();
                        }
                    } else {
                        console.error('Erro ao buscar a modalidade: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(`Erro na requisição AJAX: ${status} - ${error}`);
                }
            });
        }
        $(document).ready(function() {
            validarBotoesModalidade();
        });

        // Delegação de evento para suportar elementos dinâmicos
        $(document).on('click', '.view_data_doc_etapa7', function() {
            const id = $(this).data("id"); // Usar 'data-id' em vez de 'id' para evitar conflitos
            if (id) {
                // Exibir indicador de carregamento enquanto o conteúdo é carregado
                $("#visul_dados_doc_etapa7").html('<p>Carregando...</p>');

                $.ajax({
                    url: '<?php echo BASE_URL ?>acoes/etapa7/visualizarDocEdit.php',
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        // Insere o conteúdo retornado no modal
                        $("#visul_dados_doc_etapa7").html(response);

                        // Exibir o modal após o carregamento
                        $('#modalEditDocetapa7').modal('show');

                    },
                    error: function() {
                        // Tratar erro de carregamento
                        $("#visul_dados_doc_etapa7").html('<p>Erro ao carregar os dados. Tente novamente.</p>');
                    }
                });
            }
        });

        // Update documento// 
        $(document).ready(function() {
            var spinner = $('#loader');
            // Submissão do formulário usando AJAX
            $('.form-edit-doc-epatap7').on('submit', function(e) {
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

                            $('#content').css("opacity", "");
                            $('#modalEditDocetapa7').modal('hide');
                            initDcoumentosGeradosModule();
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

        // Deletar arquivo documento
        $(document).on('click', '.delete-document-etapa7', function() {
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
                    var id_procedimento = $('#id').val(); // Captura o ID do procedimento
                    $.ajax({
                        url: '<?php echo BASE_URL ?>acoes/etapa7/del_arquivo.php',
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
                                initDcoumentosGeradosModule();
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


        // Função reutilizável para gerar documentos
        $(document).ready(function() {
            var spinner = $('#loader');

            // Função reutilizável para gerar documentos
            function gerarDocumento(url, id_procedimento, id_municipio, id_user) {
                spinner.show();
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#content').css("opacity", ".5");
                    },
                    data: {
                        id_procedimento: id_procedimento,
                        id_municipio: id_municipio,
                        id_user: id_user
                    },
                    success: function(response) {
                        $('#content').css("opacity", "");
                        spinner.hide();
                        if (response.status === 'success') {
                            initDcoumentosGeradosModule(); // Atualiza os módulos
                        }
                        Swal.fire({
                            title: response.tittle || 'Erro!',
                            html: response.message || 'Ocorreu um erro inesperado.',
                            icon: response.icon || 'error'
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#content').css("opacity", "");
                        spinner.hide();
                        Swal.fire({
                            title: 'Erro!',
                            html: 'Ocorreu um erro no servidor. Por favor, tente novamente.',
                            icon: 'error'
                        });
                        console.error(`Erro na requisição AJAX: ${status}, ${error}`);
                    }
                });
            }

            // Evento para "Gerar CRF"
            $('#gerarCRF').click(function() {
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Você deseja gerar o CRF?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, gerar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var id_procedimento = $('#id').val();
                        var id_municipio = $('#idMunicipio').val();
                        var id_user = $('#idUser').val();
                        gerarDocumento('<?php echo BASE_URL ?>acoes/etapa7/gerar_crf.php', id_procedimento, id_municipio, id_user);
                    }
                });
            });

            // Evento para "Gerar CRFe"
            $('#gerarCRFe').click(function() {
                Swal.fire({
                    title: 'Confirmação',
                    text: 'Você deseja gerar o CRFe?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, gerar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var id_procedimento = $('#id').val();
                        var id_municipio = $('#idMunicipio').val();
                        var id_user = $('#idUser').val();
                        gerarDocumento('<?php echo BASE_URL ?>acoes/etapa7/gerar_crfe.php', id_procedimento, id_municipio, id_user);
                    }
                });
            });
        });


        // Carregamento 
        function initDcoumentosGeradosModule() {
            let currentPageDocGerados = 1; // Página atual
            let debounceTimeoutDocGerados;
            const debounceDelayDocGerados = 600;
            const contentDocGerados = $('#dadosDocumentosGerados');
            const searchInputDocGerados = $('#search_documentosGerados');

            // Função para atualizar o conteúdo da página
            function updateContent(html) {
                contentDocGerados.html(html);
            }

            // Função para carregar dados via AJAX
            function loadData(page = currentPageDocGerados) {
                const query = searchInputDocGerados.val();
                var idProcedimento = $('#id').val();
                $.ajax({
                    url: "../../acoes/etapa7/fetch.php",
                    method: "POST",
                    data: {
                        page,
                        query,
                        id: idProcedimento
                    },
                    success: (data) => {
                        updateContent(data);
                        currentPageDocGerados = page; // Atualiza a página atual
                    },
                    error: () => updateContent('<p>Erro ao carregar dados.</p>')
                });
            }

            // Função para recarregar a mesma página após uma edição
            function reloadCurrentPage() {
                loadData(currentPageDocGerados); // Recarrega a página atual
            }

            // Inicializa eventos e carrega os dados iniciais
            function carregamentoDocumentosGerados() {
                updateContent('<p>Carregando...</p>');
                loadData();

                // Evento de paginação
                $(document).on('click', '.page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page_number');
                    if (page !== currentPageDocGerados) {
                        loadData(page);
                    }
                });

                // Evento de busca com debounce
                searchInputDocGerados.on('keyup', () => {
                    clearTimeout(debounceTimeoutDocGerados);
                    debounceTimeoutDocGerados = setTimeout(() => loadData(1), debounceDelayDocGerados);
                });


            }

            $(document).ready(carregamentoDocumentosGerados);
        }

        // Chamada da função para iniciar o módulo carregamento
        initDcoumentosGeradosModule();
    </script>

<?php endif; ?>