<?php if ($viewData['user']->hasPermission('processo_5etapa')) : ?>

    <div class="etapa5-inner" id="etapa5-inner">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
        <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">

        <!-- Seção para Modelos de Notificação -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Pareceres e outros</h6>
            </div>
            <div class="card-body">
                <p>Baixe os modelos abaixo, preencha-os e anexe-os conforme necessário:</p>
                <div class="d-flex flex-wrap gap-2" id="listaModelosEtapa5">
                    <!-- Botões dos modelos serão carregados aqui via JS -->
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Enviar Notificação</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="NotificacoesSelectTipos">Selecionar tipo</label>
                    <select class="form-control" id="NotificacoesSelectTipos" name="tipo_id" required>
                        <!-- Opções carregadas dinamicamente -->
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <div id="dropzoneEtapa5" class="dropzone dotted-border rounded p-5 text-center mb-3">
                            <i class="fa fa-cloud-upload fa-3x text-primary mb-3" aria-hidden="true"></i>
                            <p class="lead">Arraste e solte o arquivo aqui ou <label for="arquivo_anexoEtapa5" class="text-primary fw-bold" style="cursor: pointer;">clique para selecionar</label></p>
                            <input type="file" id="arquivo_anexoEtapa5" class="d-none" accept=".pdf" required />
                            <small class="form-text text-muted" id="fileInfoAnexoEtapa5"></small>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 mb-3">
                            <h5>Atenção:</h5>
                            <p>Formatos permitidos:</p>
                            <div class="d-flex flex-wrap">
                                <span class="badge bg-primary me-1">PDF</span>
                            </div>
                            <p class="mt-2">Tamanho máximo do arquivo: <strong><?php echo TAMANHO_UPLOAD; ?> MB</strong></p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="button" id="botaoUploadEtapa5" class="btn btn-primary btn-block" style="display: none;">
                        <i class="fa fa-upload"></i> Fazer Upload
                    </button>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Notificações enviadas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="search" id="etapa5_search_query" autocomplete="off" class="form-control mb-1"
                                placeholder="Pesquisar notificação" />
                        </div>
                    </div>
                </div>
                <div id="etapa5_anexos"></div>
            </div>
        </div>

        <div class="card-body mb-2">
            <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="5">
                    <i class="fa fa-check-circle"></i> Concluir Etapa
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="5">
                    <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="5">
                    <i class="fa fa-hourglass-half"></i> Enviar para Análise
                </button>
            <?php endif; ?>
        </div>
        <?php if ($viewData['user']->hasPermission('processo_gerarZip_5etapa')) : ?>
            <!-- Botão Gerar ZIP -->
            <div class="d-flex justify-content-end">
                <button
                    id="btnGerarZipEtapa5"
                    class="btn btn-outline-success d-flex align-items-center gap-2"
                    data-toggle="tooltip"
                    data-placement="left"
                    title="Gerar um arquivo ZIP com todos os documentos">
                    <i class="fa fa-file-archive-o fa-lg"></i>
                    <span>Gerar ZIP (documentos)</span>
                </button>
            </div>
        <?php endif; ?>

        <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa5/anexos/js.js"></script>
    </div>

    <script>
        function carregarModelosEtapa5() {
            const idMunicipio = $('#idMunicipio').val();

            $.ajax({
                url: '../../acoes/etapa5/modelos/carregar_modelos.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    idMunicipio
                },
                success: function(response) {
                    const container = $('.card-body .d-flex.flex-wrap.gap-2').first();
                    container.empty();

                    if (response.length === 0) {
                        container.append('<p class="text-muted">Nenhum modelo de documento disponível para este município.</p>');
                        return;
                    }

                    response.forEach(modelo => {
                        const btn = $(`
                            <a href="../../assets/documentos/modelos/${modelo.modelo_documento}" 
                            class="btn btn-outline-primary ml-1" download>
                                <i class="fa fa-file-word-o me-1"></i> ${modelo.modelo_titulo}
                            </a>
                        `);
                        container.append(btn);
                    });
                },
                error: function() {
                    Swal.fire('Erro', 'Não foi possível carregar os modelos de documentos.', 'error');
                }
            });
        }
        window.carregarModelosEtapa5 = carregarModelosEtapa5;
     
    </script>

<?php endif; ?>