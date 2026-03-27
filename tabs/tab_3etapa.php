<?php if ($viewData['user']->hasPermission('processo_3etapa')) : ?>
    <div class="tab-pane" id="3etapa" aria-labelledby="3etapa-tab">
        <div class="card-block accordion-block color-accordion-block">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <div class="accordion-panel">
                    <div class=" accordion-heading" role="tab" id="headerProprietarios">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg waves-effect waves-dark scale_active collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseProprietarios" aria-expanded="false" aria-controls="collapseProprietarios">
                                Beneficiários
                            </a>
                        </h3>
                    </div>
                    <div id="collapseProprietarios" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headerProprietarios">
                        <div class="accordion-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                        <button class="btn btn-outline-primary newProprietario">
                                            <i class="fa fa-plus-circle"></i> Adicionar Beneficiário
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                        <input type="text" name="search_proprietario" id="search_proprietario" class="form-control" placeholder="Buscar">
                                    </div>
                                </div>
                            </div>
                            <div id="dynamic_content_proprietarios" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="3">
                    <i class="fa fa-check-circle"></i> Concluir Etapa
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="3">
                    <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="3">
                    <i class="fa fa-hourglass-half"></i> Enviar para Análise
                </button>
            <?php endif; ?>
        </div>
    </div>
    <!-- modal novo-->
    <div id="modalNewProprietario" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Novo Beneficiário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-new-proprietario m-2" action="<?php echo BASE_URL ?>acoes/etapa3/proprietarios/add.php" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Pessoa: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="identificacao" value="Identificado" id="Identificado" required checked>
                                            <span class="radio-btn">Identificado</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="identificacao" value="Não Identificado" id="naoIdentificado" required>
                                            <span class="radio-btn">Não Identificado</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="tipo" value="Física" id="fisicaProprietario">
                                            <span class="radio-btn">Física</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="tipo" value="Jurídica" id="juridicaProprietario">
                                            <span class="radio-btn">Jurídica</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CPF</label>
                                    <input value="" type="text" class="form-control" id="cpfProprietario" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input value="" type="text" class="form-control" id="cnpjProprietario" name="cnpj" placeholder="00.000.000/0000-00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inscrição Estadual:</label>
                                    <input type="text" value="" class="form-control" name="i_estadual" id="i_estadualProprietario" placeholder="Inscrição Estadual">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inscrição Municipal:</label>
                                    <input type="text" value="" class="form-control" name="i_municipal" id="i_municipalProprietario" placeholder="Inscrição Municipal">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Representante Legal:</label>
                                    <input type="text" value="" class="form-control" name="representante" id="representanteProprietario" placeholder="Representante Legal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargo:</label>
                                    <input type="text" value="" class="form-control" name="cargo" id="cargoProprietario" placeholder="Cargo">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nome</label><span class="text-danger"> *</span>
                                    <input type="text" value="" class="form-control" name="nome" id="nome" placeholder="Nome" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Data de Nascimento</label>
                                    <input value="" type="date" class="form-control" name="data_nasc" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <!-- tipo -->
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Sexo: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="sexo" value="Masculino" id="masculino">
                                            <span class="radio-btn">Masculino</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="sexo" value="Feminino" id="feminino">
                                            <span class="radio-btn">Feminino</span>
                                        </label>
                                    </div>
                                </fieldset>


                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>RG:</label>
                                    <input value="" type="text" class="form-control" name="rg" placeholder="RG">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Orgão Emissor</label>
                                    <input value="" type="text" class="form-control" name="emissor" placeholder="Orgão Emissor">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Profissão:</label>
                                    <input value="" type="text" class="form-control" name="profissao" placeholder="Profissão">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Estado Civil:</label>
                                    <select class="form-control" name="estado_civil" id="estado_civil">
                                        <option selected="selected" value=""></option>
                                        <option class="" value="Solteiro">Solteiro</option>
                                        <option class="" value="Casado">Casado</option>
                                        <option class="" value="Divorciado">Divorciado</option>
                                        <option class="" value="Viúvo">Viúvo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>União Estável:</label>
                                    <select class="form-control" name="uniao" id="uniao">
                                        <option selected="selected" value=""></option>
                                        <option class="" value="Sim">Sim</option>
                                        <option class="" value="Não">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Telefone:</label>
                                    <input value="" type="text" class="form-control" name="telefone" id="telefoneProprietario" placeholder="(00) 0000-0000">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Celular:</label>
                                    <input value="" type="text" class="form-control" name="celular" id="celularProprietario" placeholder="(00) 00000-0000">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>E-mail:</label>
                                    <input value="" type="email" class="form-control" autocomplete="on" name="email" id="email" placeholder="E-mail">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>CEP:</label>
                                    <input value="" type="text" class="form-control" name="cep" id="cep" placeholder="CEP">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Logradouro:</label>
                                    <input value="" type="text" class="form-control" name="logradouro" id="logradouro" placeholder="Endereço">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nº:</label>
                                    <input value="" type="text" class="form-control" name="numero" id="numero" placeholder="Número">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Complemente:</label>
                                    <input value="" type="text" class="form-control" name="complemente" id="complemente" placeholder="Complemente">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bairro:</label>
                                    <input value="" type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Município:</label>
                                    <input value="" type="text" class="form-control" name="municipio" id="municipio" placeholder="Cidade">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado:</label>
                                    <input value="" type="text" class="form-control" name="estado" id="estado" placeholder="Estado">
                                </div>
                            </div>

                            <hr>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome do Pai:</label>
                                    <input value="" type="text" class="form-control" name="pai" id="paiProprietario" placeholder="Nome do Pai">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome da Mãe:</label>
                                    <input value="" type="text" class="form-control" name="mae" id="maeProprietario" placeholder="Nome do Mãe">
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="enviar" id="btn-salvar-proprietario" class="btn btn-primary">Cadastrar</button>
                            <!-- Campos Ocultos -->
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                            <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal edit -->
    <div id="modalEditProprietario" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Editar Beneficiário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-edit-proprietario m-2" action="<?php echo BASE_URL ?>acoes/etapa3/proprietarios/update.php" method="POST">
                    <div class="modal-body">
                        <span id="visul_dados_proprietarios"></span>
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

    <!-- modal edit -->
    <div id="modalEditProprietarioConjuge" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Beneficiário cônjuge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-edit-proprietario-conjuge m-2" action="<?php echo BASE_URL ?>acoes/etapa3/proprietarios/updateConjuge.php" method="POST">
                    <div class="modal-body">
                        <span id="visul_dados_proprietarios_conjuge"></span>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">

                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar" id="btn-salvar-edit-proprietario-conjuge" class="btn btn-primary">Salvar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <!-- modal documentos -->
    <div id="modalProprietarioDocumentos" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Documentos</h5>
                    <input type="hidden" name="id" id="idProcesso" value="<?php echo $id; ?>">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="visul_dados_proprietarios_documentos"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa3/proprietarios/js.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa3/anexos/js.js"></script>
<?php endif; ?>