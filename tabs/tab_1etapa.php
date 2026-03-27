 <?php if ($viewData['user']->hasPermission('processo_1etapa')) : ?>
     <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
     <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">
     <div class="etapa1-inner" id="etapa1-inner">
         <div class="card-block accordion-block color-accordion-block">
             <div id="accordion_etapa1" role="tablist" aria-multiselectable="true">
                 <div class="accordion-panel">
                     <div class="accordion-heading" role="tab" id="headingTipo">
                         <h3 class="card-title accordion-title">
                             <a class="accordion-msg waves-effect waves-dark scale_active" data-toggle="collapse" data-parent="#accordion_etapa1" href="#collapseTipo" aria-expanded="true" aria-controls="collapseTipo">
                                 Tipo
                             </a>
                         </h3>
                     </div>
                     <div id="collapseTipo" class="panel-collapse in collapse show" role="tabpanel" aria-labelledby="headingTipo">
                         <div class="accordion-content">
                             <div class="mb-3">
                                 <form class="form-tipo" action="<?php echo BASE_URL ?>acoes/etapa1/tipo/update_tipo.php" method="POST">
                                     <?php foreach ($procedimento as $item) : ?>
                                         <!-- Município -->
                                         <div class="form-group">
                                             <h5 class="text-muted">Município: <strong><?php echo $item['municipio_name'] . ' - ' . $item['municipio_uf']; ?></strong></h5>
                                         </div>
                                         <div class="row">
                                             <div class="col-md-4">
                                                 <fieldset class="border p-3 mb-3 bg-light ">
                                                     <legend class="w-auto">Portaria de abertura <span class="text-danger">*</span></legend>
                                                     <input type="text" name="n_portaria" id="n_portaria" value="<?php echo $item['n_portaria'] ?>" class="form-control" placeholder="Nº portaria">
                                                 </fieldset>
                                             </div>
                                             <div class="col-md-8">
                                                 <fieldset class="border p-3 mb-3 bg-light ">
                                                     <legend class="w-auto">Presidente da Comissão <span class="text-danger">*</span></legend>
                                                     <input type="text" name="presidente_comissao" id="presidente_comissao" value="<?php echo $item['presidente_comissao'] ?>" class="form-control" placeholder="Nome">
                                                 </fieldset>
                                             </div>

                                         </div>

                                         <!-- Modalidade Reurb -->
                                         <fieldset class="border p-3 mb-3 bg-light ">
                                             <legend class="w-auto">Modalidade Reurb <span class="text-danger">*</span></legend>
                                             <div class="custom-radio-group mt-2">
                                                 <label class="custom-radio">
                                                     <input type="radio" name="modalidade" id="Reurb-E" value="Reurb-E" <?php echo ($item['modalidade'] == 'Reurb-E') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Reurb-E</span>
                                                 </label>
                                                 <label class="custom-radio">
                                                     <input type="radio" name="modalidade" id="Reurb-S" value="Reurb-S" <?php echo ($item['modalidade'] == 'Reurb-S') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Reurb-S</span>
                                                 </label>
                                                  <label class="custom-radio">
                                                     <input type="radio" name="modalidade" id="Reurb-M" value="Reurb-M" <?php echo ($item['modalidade'] == 'Reurb-M') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Reurb-M</span>
                                                 </label>
                                             </div>
                                         </fieldset>

                                         <!-- Forma de Organização -->
                                         <fieldset class="border p-3 mb-3 bg-light ">
                                             <legend class="w-auto">Forma de Organização <span class="text-danger">*</span></legend>
                                             <div class="custom-radio-group mt-2">
                                                 <label class="custom-radio">
                                                     <input type="radio" name="forma" value="Parcelamento do solo" <?php echo ($item['forma_organizacao'] == 'Parcelamento do solo') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Parcelamento do solo</span>
                                                 </label>

                                                 <label class="custom-radio">
                                                     <input type="radio" name="forma" value="Condomínio edilício" <?php echo ($item['forma_organizacao'] == 'Condomínio edilício') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Condomínio edilício</span>
                                                 </label>

                                                 <label class="custom-radio">
                                                     <input type="radio" name="forma" value="Condomínio urbano simples" <?php echo ($item['forma_organizacao'] == 'Condomínio urbano simples') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Condomínio urbano simples</span>
                                                 </label>

                                                 <label class="custom-radio">
                                                     <input type="radio" name="forma" value="Condomínio de Lotes" <?php echo ($item['forma_organizacao'] == 'Condomínio de Lotes') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Condomínio de Lotes</span>
                                                 </label>

                                                 <label class="custom-radio">
                                                     <input type="radio" name="forma" value="Conjunto Habitacional" <?php echo ($item['forma_organizacao'] == 'Conjunto Habitacional') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Conjunto Habitacional</span>
                                                 </label>
                                             </div>
                                         </fieldset>


                                         <!-- Tipo do Procedimento -->
                                         <fieldset class="border p-3 mb-3 bg-light ">
                                             <legend class="w-auto">Tipo do Procedimento <span class="text-danger">*</span></legend>
                                             <div class="custom-radio-group mt-2">
                                                 <label class="custom-radio">
                                                     <input type="radio" name="tipo" value="INDIVIDUAL" <?php echo ($item['tipo'] == 'INDIVIDUAL') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Individual</span>
                                                 </label>
                                                 <label class="custom-radio">
                                                     <input type="radio" name="tipo" value="COLETIVO" <?php echo ($item['tipo'] == 'COLETIVO') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Coletivo</span>
                                                 </label>
                                             </div>
                                         </fieldset>

                                         <!-- Rito -->
                                         <fieldset class="border p-3 mb-3 bg-light ">
                                             <legend class="w-auto">Rito <span class="text-danger">*</span></legend>
                                             <div class="custom-radio-group mt-2">
                                                 <label class="custom-radio">
                                                     <input class="form-check-input" type="radio" name="rito" id="DEMARCACAO" value="COM DEMARCAÇÃO URBANISTICA" <?php echo ($item['rito'] == 'COM DEMARCAÇÃO URBANISTICA') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Com Demarcação Urbanística</span>
                                                 </label>
                                                 <label class="custom-radio">
                                                     <input class="form-check-input" type="radio" name="rito" id="SEM_DEMARCACAO" value="SEM DEMARCAÇÃO URBANÍSTICA" <?php echo ($item['rito'] == 'SEM DEMARCAÇÃO URBANÍSTICA') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Sem Demarcação Urbanística</span>
                                                 </label>
                                                 <label class="custom-radio">
                                                     <input class="form-check-input" type="radio" name="rito" id="CONSOLIDADO" value="NÚCLEO URBANO CONSOLIDADO ANTES DE 2016" <?php echo ($item['rito'] == 'NÚCLEO URBANO CONSOLIDADO ANTES DE 2016') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Núcleo Urbano Consolidado Antes de 2016</span>
                                                 </label>
                                                 <label class="custom-radio">
                                                     <input class="form-check-input" type="radio" name="rito" id="TITULAR_OCUPANTES" value="APENAS PARA TITULAR OS OCUPANTES" <?php echo ($item['rito'] == 'APENAS PARA TITULAR OS OCUPANTES') ? 'checked' : ''; ?> required>
                                                     <span class="radio-btn">Apenas para Titular os Ocupantes</span>
                                                 </label>
                                             </div>
                                         </fieldset>

                                         <!-- Campos Ocultos -->
                                         <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                         <input type="hidden" name="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                                         <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">


                                         <!-- Botão de Salvar -->
                                         <?php if ($viewData['user']->hasPermission('processo_1etapa_salvar_tipo')) : ?>
                                             <div class="text-right">
                                                 <button class="btn btn-outline-success" type="submit">
                                                     <i class="fa fa-check"></i>Salvar
                                                 </button>
                                             </div>
                                         <?php endif; ?>
                                     <?php endforeach; ?>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="accordion-panel">
                     <div class="accordion-heading" role="tab" id="headingRequerentes">
                         <h3 class="card-title accordion-title">
                             <a class="accordion-msg waves-effect waves-dark scale_active" data-toggle="collapse" data-parent="#accordion_etapa1" href="#collapseRequerentes" aria-expanded="true" aria-controls="collapseRequerentes">
                                 Requerente(s)
                             </a>
                         </h3>
                     </div>
                     <div id="collapseRequerentes" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="headingRequerentes">
                         <div class="accordion-content">
                             <!-- Requerentes -->
                             <div class="mb-3">
                                 <div class="d-flex justify-content-between align-items-center mb-3">
                                     <h5 class="text-muted">Requerente(s)</h5>
                                 </div>
                                 <fieldset class="border p-3 mb-3 bg-light">
                                     <legend class="w-auto">Abertura de ofício pelo Município:<span class="text-danger">*</span></legend>
                                     <?php foreach ($procedimento as $item) : ?>
                                         <form class="form-requerente" action="<?php echo BASE_URL ?>acoes/etapa1/requerentes/update_abertura.php" method="POST">
                                             <div class="form-group">
                                                 <div class="custom-radio-group mt-2">
                                                     <label class="custom-radio">
                                                         <input type="radio" name="abertura" value="SIM" required
                                                             <?php echo ($item['abertura_oficio'] == 'SIM') ? 'checked' : '';  ?>>
                                                         <span class="radio-btn">SIM</span>
                                                     </label>
                                                     <label class="custom-radio">
                                                         <input type="radio" name="abertura" value="NÃO" required
                                                             <?php echo ($item['abertura_oficio'] == 'NÃO') ? 'checked' : ''; ?>>
                                                         <span class="radio-btn">NÃO</span>
                                                     </label>
                                                 </div>
                                             </div>

                                             <!-- Campos Ocultos -->
                                             <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                             <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                                             <input type="hidden" name="idUser" value="<?php echo $viewData['user']->getId(); ?>">

                                             <!-- Botão de Salvar -->
                                             <?php if ($viewData['user']->hasPermission('processo_1etapa_salvar_requerente')) : ?>
                                                 <div class="text-right">
                                                     <button class="btn btn-outline-success" type="submit">
                                                         <i class="fa fa-check"></i>Salvar
                                                     </button>
                                                 </div>
                                             <?php endif; ?>
                                         </form>
                                     <?php endforeach; ?>
                                 </fieldset>
                                 <!-- Botão Adicionar e Campo de Busca -->
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                             <button class="btn btn-outline-primary newRequente">
                                                 <i class="fa fa-plus-circle"></i> Adicionar Requerente
                                             </button>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                             <input type="text" name="search_requerente" id="search_requerente" class="form-control" placeholder="Buscar requerente">
                                         </div>
                                     </div>
                                 </div>
                                 <div id="dynamic_content_requerentes" class="mt-3"></div>
                                 </fieldset>
                                 <!-- Requerentes Fim -->
                             </div>
                         </div>
                     </div>

                 </div>
                 <div class="accordion-panel">
                     <div class="accordion-heading" role="tab" id="headingDocumentosEtapa1">
                         <h3 class="card-title accordion-title">
                             <a class="accordion-msg waves-effect waves-dark scale_active" data-toggle="collapse" data-parent="#accordion_etapa1" href="#collapseDocumentosEtapa1" aria-expanded="true" aria-controls="collapseDocumentosEtapa1">
                                 Documentos
                             </a>
                         </h3>
                     </div>
                     <div id="collapseDocumentosEtapa1" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="headingDocumentosEtapa1">
                         <div class="accordion-content">
                             <!-- Documentos anexos -->
                             <div class="mb-3">
                                 <input type="hidden" name="idEtapa" id="idEtapa" value="1">
                                 <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                                 <h5 class="mb-3">Enviar Documentos Anexos</h5>

                                 <div class="row">
                                     <div class="col-md-7">
                                         <div id="dropzone" class="dropzone dotted-border rounded p-5 text-center mb-3">
                                             <i class="fa fa-cloud-upload fa-3x text-primary mb-3" aria-hidden="true"></i>
                                             <p class="lead">Arraste e solte o arquivo aqui ou <label for="arquivo_anexo" class="text-primary fw-bold" style="cursor: pointer;">clique para selecionar</label>.</p>
                                             <input type="file" id="arquivo_anexo" class="d-none" accept=".pdf" required />
                                             <small class="form-text text-muted" id="fileInfoAnexo"></small>
                                         </div>

                                     </div>
                                     <div class="col-md-4">
                                         <div class="border p-3 mb-3">
                                             <h5>Atenção:</h5>
                                             <p>Formato permitido:</p>
                                             <div class="d-flex flex-wrap">
                                                 <span class="badge bg-primary me-1">PDF</span>
                                             </div>
                                             <p class="mt-2">Tamanho máximo do arquivo: <strong><?php echo TAMANHO_UPLOAD; ?> MB</strong></p>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="mb-4">
                                     <button type="button" id="botaoUpload" class="btn btn-primary btn-block" style="display: none;">
                                         <i class="fa fa-upload"></i> Fazer Upload</button>
                                 </div>

                                 <h5 class="mb-3">Documentos Anexos</h5>
                                 <div class="row">
                                     <div class="col-lg-4">
                                         <div class="form-group">
                                             <input type="search" id="search_query" autocomplete="off" class="form-control mb-1"
                                                 placeholder="Pesquisar documento" />
                                         </div>
                                     </div>
                                 </div>
                                 <div id="conteudo_anexos"></div>

                                 <br>
                                 <?php if ($viewData['user']->hasPermission('processo_gerarZip_1etapa')) : ?>
                                     <!-- Botão Gerar ZIP melhorado -->
                                     <div class="d-flex justify-content-end">
                                         <button
                                             id="btnGerarZip"
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
             </div>
         </div>

         <div class="card-body">
             <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                 <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="1">
                     <i class="fa fa-check-circle"></i> Concluir Etapa
                 </button>
             <?php endif; ?>
             <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                 <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="1">
                     <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                 </button>
             <?php endif; ?>
             <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                 <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="1">
                     <i class="fa fa-hourglass-half"></i> Enviar para Análise
                 </button>
             <?php endif; ?>
         </div>



     </div>

     <!-- modal novo  requerente-->
     <div id="modalNewRequerente" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-grande" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="visulUsuarioModalLabel">Novo Requerente</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <form class="form-new-requerente m-2" action="<?php echo BASE_URL ?>acoes/etapa1/requerentes/add.php" method="POST">
                     <div class="modal-body">
                         <div class="row">
                             <div class="col-md-12">
                                 <!-- tipo -->
                                 <fieldset class="border p-3 mb-3">
                                     <legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
                                     <div class="custom-radio-group mt-2">
                                         <label class="custom-radio">
                                             <input type="radio" name="tipo" value="Física" id="fisica">
                                             <span class="radio-btn">Física</span>
                                         </label>
                                         <label class="custom-radio">
                                             <input type="radio" name="tipo" value="Jurídica" id="juridica">
                                             <span class="radio-btn">Jurídica</span>
                                         </label>
                                     </div>
                                 </fieldset>
                             </div>

                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label>CPF</label>
                                     <input value="" type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">
                                 </div>
                             </div>

                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label>CNPJ</label>
                                     <input value="" type="text" class="form-control" name="cnpj" id="cnpj" placeholder="00.000.000/0000-00">
                                 </div>
                             </div>

                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label>Inscrição Estadual:</label>
                                     <input type="text" value="" class="form-control" name="i_estadual" id="i_estadual" placeholder="Inscrição Estadual">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label>Inscrição Municipal:</label>
                                     <input type="text" value="" class="form-control" name="i_municipal" id="i_municipal" placeholder="Inscrição Municipal">
                                 </div>
                             </div>

                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label>Representante Legal:</label>
                                     <input type="text" value="" class="form-control" name="representante" id="representante" placeholder="Representante Legal">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label>Cargo:</label>
                                     <input type="text" value="" class="form-control" name="cargo" id="cargo" placeholder="Cargo">
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
                                     <input value="" type="text" class="form-control" name="telefone" id="telefone" placeholder="(00) 0000-0000">
                                 </div>
                             </div>

                             <div class="col-md-3">
                                 <div class="form-group">
                                     <label>Celular:</label>
                                     <input value="" type="text" class="form-control" name="celular" id="celular" placeholder="(00) 00000-0000">
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
                                     <input value="" type="text" class="form-control" name="pai" id="pai" placeholder="Nome do Pai">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label>Nome da Mãe:</label>
                                     <input value="" type="text" class="form-control" name="mae" id="mae" placeholder="Nome do Mãe">
                                 </div>
                             </div>

                         </div>

                         <div class="modal-footer">
                             <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                             <button type="submit" name="enviar" id="btn-salvar-re" class="btn btn-primary">Cadastrar</button>
                             <!-- Campos Ocultos -->
                             <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                             <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                         </div>
                     </div>
                 </form>
             </div>
         </div>
     </div>

     <!-- modal edit  requerente-->
     <div id="modalEditRequerente" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-grande" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="visulUsuarioModalLabel">Editar Requerente</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>

                 <form class="form-edit-requerente m-2" action="<?php echo BASE_URL ?>acoes/etapa1/requerentes/update.php" method="POST">
                     <div class="modal-body">
                         <span id="visul_dados"></span>
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


     <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa1/tipo/anexos.js"></script>
     <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa1/tipo/js.js"></script>
     <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa1/requerentes/js.js"></script>

 <?php endif; ?>