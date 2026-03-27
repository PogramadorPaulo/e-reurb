<?php if ($viewData['user']->hasPermission('processo_4etapa')) : ?>
    <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
    <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">
    <div class="etapa-inner" id="4etapa" aria-labelledby="4etapa-tab">

        <h5 class="mb-4">Mapa de Quadras e Lotes</h5>

        <button class="btn btn-outline-primary btn-nova-quadra" data-toggle="modal" data-target="#novaQuadraModal">
            <i class="fa fa-plus-circle"></i> Adicionar
        </button>


        <!-- Input Hidden para Armazenar o ID -->
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

        <div class="row">
            <!-- Container para o Mapa de Quadras -->
            <div class="mapa-quadras" id="mapaQuadras">
                <!-- Quadras e lotes serão carregados dinamicamente -->
            </div>

        </div>
        <div id="accordion" role="tablist" aria-multiselectable="true">
            <div class="accordion-panel">
                <div class=" accordion-heading" role="tab" id="headerDocumentosEtapa4">
                    <h3 class="card-title accordion-title">
                        <a class="accordion-msg waves-effect waves-dark scale_active collapsed" data-toggle="collapse" data-parent="#accordion_etapa4" href="#collapseDocumentosEtapa4" aria-expanded="false" aria-controls="collapseDocumentosEtapa4">
                            Documentos
                        </a>
                    </h3>
                </div>
                <div id="collapseDocumentosEtapa4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headerDocumentosEtapa4">
                    <div class="accordion-content">
                        <!-- Documentos anexos -->
                        <h5 class="mb-3">Enviar Documentos Anexos</h5>

                        <div class="row">
                            <div class="col-md-7">
                                <div id="dropzoneEtapa4" class="dropzone dotted-border rounded p-5 text-center mb-3">
                                    <i class="fa fa-cloud-upload fa-3x text-primary mb-3" aria-hidden="true"></i>
                                    <p class="lead">Arraste e solte o arquivo aqui ou <label for="arquivo_anexoEtapa4" class="text-primary fw-bold" style="cursor: pointer;">clique para selecionar</label></p>
                                    <input type="file" id="arquivo_anexoEtapa4" class="d-none" accept=".pdf" required />
                                    <small class="form-text text-muted" id="fileInfoAnexoEtapa4"></small>
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
                            <button type="button" id="botaoUploadEtapa4" class="btn btn-primary btn-block" style="display: none;">
                                <i class="fa fa-upload"></i> Fazer Upload
                            </button>
                        </div>

                        <h5 class="mb-3">Documentos Anexos</h5>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="search" id="etapa4_search_query" autocomplete="off" class="form-control mb-1"
                                        placeholder="Pesquisar documento" />
                                </div>
                            </div>
                        </div>
                        <div id="etapa4_anexos"></div>
                        <br>
                        <?php if ($viewData['user']->hasPermission('processo_gerarZip_4etapa')) : ?>
                            <!-- Botão Gerar ZIP -->
                            <div class="d-flex justify-content-end">
                                <button
                                    id="btnGerarZipEtapa4"
                                    class="btn btn-outline-success d-flex align-items-center gap-2"
                                    data-toggle="tooltip"
                                    data-placement="left"
                                    title="Gerar um arquivo ZIP com todos os documentos">
                                    <i class="fa fa-file-archive-o fa-lg"></i>
                                    <span>Gerar ZIP (documentos)</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-body">
            <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="4">
                    <i class="fa fa-check-circle"></i> Concluir Etapa
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="4">
                    <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="4">
                    <i class="fa fa-hourglass-half"></i> Enviar para Análise
                </button>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal para adicionar novo lote -->
    <div id="novoLoteModal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="novoLoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="novoLoteModalLabel">Adicionar Novo Lote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-new-lote shadow-sm m-2" action="<?php echo BASE_URL ?>acoes/etapa4/mapa/newLote.php" method="POST">
                    <input type="hidden" id="novo_lote_quadra" name="novo_lote_quadra">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="lote_number">Número do Lote</label>
                                    <input type="text" class="form-control" id="lote_number" name="lote_number" placeholder="1,2,3" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Tipo Lote: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="loteTipo" value="E" id="loteTipoE" required>
                                            <span class="radio-btn">Específico(E)</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="loteTipo" value="S" id="loteTipoS" required>
                                            <span class="radio-btn">Social(S)</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Beneficiários: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="identificacao" value="sim" id="IdentificadoProprietario" required checked>
                                            <span class="radio-btn">Identificado</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="identificacao" value="não" id="naoIdentificadoProprietario" required>
                                            <span class="radio-btn">Não Identificado</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Beneficiários:</label><span class="text-danger">*</span>
                                    <select class="form-control " name="selectProprietarios[]" id="selectImovelProprietarios" multiple="multiple" style="width: 75%">
                                        <!-- Opções são carregadas via JS -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="coordenadas">Coordenadas de localização do Lote:
                                        <!-- Botão para visualizar no mapa -->
                                        <a href="https://maps.google.com/maps?q="
                                            target="_blank" id="map-link" class="btn btn-outline-primary" title="Visualizar localização no Mapa">
                                            <i class="fa fa-map" aria-hidden="true"></i> Mapa
                                        </a></label><br>

                                    <!-- Campo de coordenadas (supondo que existe um input para coordenadas) -->
                                    <input type="text" id="coordenadas" name="lote_coordenadas" class="form-control"
                                        value=""
                                        placeholder="Ex: -21.11111,-46.11111">

                                    <!-- Mensagem de aviso -->
                                    <i id="coordenadas-vazio" class="text-muted" style="display:none;">Informe as coordenadas geográficas do local</i>

                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Memorial:</label>
                                    <textarea class="form-control" rows="8" name="memorial" id="memorial" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" id="saveNewLote">Salvar Novo Lote</button>
                            <!-- Campos Ocultos -->
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                            <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">
                            <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar quadra -->
    <div class="modal fade" id="editarQuadraModal" tabindex="-1" role="dialog" aria-labelledby="editarQuadraModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarQuadraModalLabel">Editar Quadra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-edit-quadra shadow-sm m-2" action="<?php echo BASE_URL ?>acoes/etapa4/mapa/editQuadra.php" method="POST">
                        <input type="hidden" id="quadra_id" name="quadra_id">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        <div class="form-group">
                            <label for="quadra_letra">Letra ou Número da Quadra</label>
                            <input type="text" class="form-control" id="nova_quadra_nome" name="nova_quadra_nome" placeholder="A,B,C" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="novaQuadraModal" tabindex="-1" role="dialog" aria-labelledby="novaQuadraModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="novaQuadraModalLabel">Adicionar Nova Quadra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-new-quadra m-2" action="<?php echo BASE_URL ?>acoes/etapa4/mapa/newQuadra.php" method="POST">

                    <div class="modal-body">

                        <div class="form-group">
                            <label for="nova_quadra_nome">LETRA ou Número da Quadra</label>
                            <input type="text" class="form-control" id="nova_quadra_nome" name="nova_quadra_nome" placeholder="A,B,C, ou 1,2" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="saveNewQuadra">Salvar</button>
                        <!-- Campos Ocultos -->
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="modal fade" id="modalProprietarios" tabindex="-1" role="dialog" aria-labelledby="modalProprietariosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProprietariosLabel">Beneficiários do Lote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-edit-lote m-2" action="<?php echo BASE_URL ?>acoes/etapa4/mapa/editLote.php" method="POST">
                    <div class="modal-body">
                        <div id="viewDataProprietarios"></div>
                        <!-- Dados dos proprietários serão carregados aqui -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" id="saveEditLote">Salvar</button>
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa4/mapa/js.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa4/anexos/js.js"></script>

<?php endif; ?>