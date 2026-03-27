<?php if ($viewData['user']->hasPermission('processo_2etapa')) : ?>
    <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
    <input type="hidden" name="idMunicipio" id="idMunicipio" value="<?php echo $idMunicipio; ?>">
    <div class="etapa-inner" id="2etapa" aria-labelledby="2etapa-tab">
        <div class="card-block accordion-block color-accordion-block">
            <div id="accordion_etapa2" role="tablist" aria-multiselectable="true">
                <div class="accordion-panel">
                    <div class=" accordion-heading" role="tab" id="headerProprietariosMatriculas">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg waves-effect waves-dark scale_active collapsed" data-toggle="collapse" data-parent="#accordion_etapa2" href="#collapseProprietariosMatriculas" aria-expanded="false" aria-controls="collapseProprietariosMatriculas">
                                Proprietários da Matrícula
                            </a>
                        </h3>
                    </div>
                    <div id="collapseProprietariosMatriculas" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headerProprietariosMatriculas">
                        <div class="accordion-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                        <button class="btn btn-outline-primary newProprietarioMatricula">
                                            <i class="fa fa-plus-circle"></i> Adicionar
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                        <input type="text" name="search_proprietariosMatriculas" id="search_proprietariosMatriculas" class="form-control" placeholder="Buscar proprietário">
                                    </div>
                                </div>
                            </div>
                            <div id="dynamic_content_proprietariosMatriculas" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="accordion-panel">
                    <div class=" accordion-heading" role="tab" id="headerMatriculas">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg waves-effect waves-dark scale_active collapsed" data-toggle="collapse" data-parent="#accordion_etapa2" href="#collapseMatriculas" aria-expanded="false" aria-controls="collapseMatriculas">
                                Matrículas
                            </a>
                        </h3>
                    </div>
                    <div id="collapseMatriculas" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headerMatriculas">
                        <div class="accordion-content">


                            <div class="d-flex justify-content-end mb-3">
                                <button class="btn btn-primary" id="btnNovaMatricula">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Nova Matrícula
                                </button>
                            </div>

                            <div id="mapaMatriculas"></div>

                        </div>
                    </div>
                </div>
                <div class="accordion-panel">
                    <div class=" accordion-heading" role="tab" id="headerConfrontantes">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg waves-effect waves-dark scale_active collapsed" data-toggle="collapse" data-parent="#accordion_etapa2" href="#collapseConfrontante" aria-expanded="false" aria-controls="collapseConfrontante">
                                Confrontantes
                            </a>
                        </h3>
                    </div>
                    <div id="collapseConfrontante" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headerConfrontantes">
                        <div class="accordion-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                        <button class="btn btn-outline-primary newConfrontante">
                                            <i class="fa fa-plus-circle"></i> Adicionar Confrontante
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                                        <input type="text" name="search_confrontante" id="search_confrontante" class="form-control" placeholder="Buscar confrontante">
                                    </div>
                                </div>
                            </div>
                            <div id="dynamic_content_confrontantes" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="accordion-panel">
                    <div class="accordion-heading" role="tab" id="headingOne">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg waves-effect waves-dark scale_active" data-toggle="collapse" data-parent="#accordion_etapa2" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Descrição do núcleo
                            </a>
                        </h3>
                    </div>
                    <div id="collapseOne" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="accordion-content">
                            <form class="form-update-descricoes" action="<?php echo BASE_URL ?>acoes/etapa2/nucleo/update_descricoes.php" method="POST">
                                <?php foreach ($procedimento as $row) : ?>

                                    <fieldset class="border p-3 mb-3 bg-light">
                                        <legend class="w-auto">Dados do profissional<span class="text-danger">*</span></legend>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nome:</label>
                                                    <input value="<?php echo $row["nucleo_profissional"] ?>" type="text" class="form-control" name="nucleo_profissional" id="nucleo_profissional" placeholder="Nome do profissional">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Registro:</label>
                                                    <input value="<?php echo $row["nucleo_profissional_registro"] ?>" type="text" class="form-control" name="nucleo_profissional_registro" id="nucleo_profissional_registro" placeholder="Nº do registro">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Data Registro:</label>
                                                    <input value="<?php echo $row["nucleo_profissional_rg_data"] ?>" type="date" class="form-control" name="nucleo_profissional_rg_data" id="nucleo_profissional_rg_data">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>


                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Características:</label>
                                                <select class="form-control" name="carac" id="carac">
                                                    <option value=""></option>
                                                    <option value="Apenas Titulação dos Ocupantes" <?php echo (($row["nucleo_caracteristica"] == 'Apenas Titulação dos Ocupantes') ? 'selected' : '') ?>>Apenas Titulação dos Ocupantes</option>
                                                    <option value="Clandestino" <?php echo (($row["nucleo_caracteristica"] == 'Clandestino') ? 'selected' : '') ?>>Clandestino</option>
                                                    <option value="Aprovação Ambiental pelo Orgão Estado" <?php echo (($row["nucleo_caracteristica"] == 'Aprovação Ambiental pelo Orgão Estado') ? 'selected' : '') ?>>Aprovação Ambiental pelo Orgão Estado</option>
                                                    <option value="Irregular" <?php echo (($row["nucleo_caracteristica"] == 'Irregular') ? 'selected' : '') ?>>Irregular</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Data de implantação:</label>
                                                <input value="<?php echo $row["nucleo_data_implantacao"] ?>" type="date" class="form-control" name="data_implantacao" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nome:</label>
                                                <input value="<?php echo $row["nucleo_nome"] ?>" type="text" class="form-control" name="nome" placeholder="Nome do Núcleo Urbano Informado">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="border p-3 mb-3 bg-light">
                                                <legend class="w-auto">Este imóvel é rural?<span class="text-danger">*</span></legend>
                                                <div class="form-group">
                                                    <div class="custom-radio-group mt-2">
                                                        <label class="custom-radio">
                                                            <input type="radio" name="rural" value="Sim"
                                                                <?php echo ($row['nucleo_rural'] == 'Sim') ? 'checked' : ''; ?>>
                                                            <span class="radio-btn">SIM</span>
                                                        </label>
                                                        <label class="custom-radio">
                                                            <input type="radio" name="rural" value="Não"
                                                                <?php echo ($row['nucleo_rural'] == 'Não') ? 'checked' : ''; ?>>
                                                            <span class="radio-btn">NÃO</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Denominação Rural:</label>
                                                <input value="<?php echo $row["nucleo_d_rural"] ?>" type="text" class="form-control" name="denominacao_rural" id="denominacao_rural" placeholder="Denominação Rural">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Número CDR:</label>
                                                <input value="<?php echo $row["nucleo_cdr"] ?>" type="text" class="form-control" name="cdr" id="cdr" placeholder="Número CDR">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Cep:</label>
                                                <input value="<?php echo $row["nucleo_cep"] ?>" type="text" class="form-control" name="cep" id="cep" placeholder="CEP">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Endereço:</label>
                                                <input value="<?php echo $row["nucleo_endereco"] ?>" type="text" class="form-control" name="endereco" placeholder="Endereço">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Número Inicial:</label>
                                                <input value="<?php echo $row["nucleo_numero_inical"] ?>" type="text" class="form-control" name="n_inicial" placeholder="Número Inicial">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Número Final:</label>
                                                <input value="<?php echo $row["nucleo_numero_final"] ?>" type="text" class="form-control" name="n_final" placeholder="Número Final">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Memorial Descritivo:</label>
                                                <textarea type="textarea" class="form-control" name="memorial" rows="4" placeholder="Memorial Descritivo"><?php echo $row["nucleo_memorial"] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="coordenadas">Mapa de localização:</label><br>

                                                <!-- Campo de coordenadas (supondo que existe um input para coordenadas) -->
                                                <input type="text" id="coordenadas" name="coordenadas" class="form-control"
                                                    value="<?php echo isset($row['nucleo_coordenadas']) ? $row['nucleo_coordenadas'] : ''; ?>"
                                                    placeholder="Ex: -21.11111,-46.11111">

                                                <!-- Mensagem de aviso -->
                                                <i id="coordenadas-vazio" class="text-muted" style="display:none;">Informe as coordenadas geográficas do local</i>

                                                <!-- Botão para visualizar no mapa -->
                                                <a href="https://maps.google.com/maps?q=<?php echo isset($row['nucleo_coordenadas']) ? urlencode($row['nucleo_coordenadas']) : ''; ?>"
                                                    target="_blank" id="map-link" class="btn btn-outline-primary" style="display:none;" title="Visualizar localização no Mapa">
                                                    <i class="fa fa-map" aria-hidden="true"></i> Visualizar localização no Mapa
                                                </a>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const coordenadasInput = document.getElementById('coordenadas');
                                                const mapLink = document.getElementById('map-link');
                                                const coordenadasVazio = document.getElementById('coordenadas-vazio');

                                                function verificarCoordenadas() {
                                                    const coordenadas = coordenadasInput.value.trim();
                                                    if (coordenadas === '') {
                                                        // Exibir mensagem e esconder o botão
                                                        coordenadasVazio.style.display = 'block';
                                                        mapLink.style.display = 'none';
                                                    } else {
                                                        // Esconder mensagem e mostrar o botão
                                                        coordenadasVazio.style.display = 'none';
                                                        mapLink.style.display = 'inline-block';

                                                        // Atualizar o link do Google Maps
                                                        mapLink.href = `https://maps.google.com/maps?q=${encodeURIComponent(coordenadas)}`;
                                                    }
                                                }

                                                // Verificar as coordenadas no carregamento inicial
                                                verificarCoordenadas();

                                                // Verificar quando o valor das coordenadas mudar
                                                coordenadasInput.addEventListener('input', verificarCoordenadas);
                                            });
                                        </script>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Aprovação Ambiental:</label>
                                                <select class="form-control" name="ap_ambiental" id="ap_ambiental">
                                                    <option value=""></option>
                                                    <option value="Dispensa do Projeto Ambiental" <?php echo (($row["nucleo_aprovacao_amb"] == 'Dispensa do Projeto Ambiental') ? 'selected' : '') ?>>Dispensa do Projeto Ambiental</option>
                                                    <option value="Aprovação Ambiental pelo Município" <?php echo (($row["nucleo_aprovacao_amb"] == 'Aprovação Ambiental pelo Município') ? 'selected' : '') ?>>Aprovação Ambiental pelo Município</option>
                                                    <option value="Aprovação Ambiental pelo Orgão Estado" <?php echo (($row["nucleo_aprovacao_amb"] == 'Aprovação Ambiental pelo Orgão Estado') ? 'selected' : '') ?>>Aprovação Ambiental pelo Orgão Estado</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Equipamentos Instalados:</label>
                                                <textarea type="textarea" class="form-control" name="equipamentos" rows="2" placeholder="Equipamentos Instalados"><?php echo $row["nucleo_equipamentos"] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Natureza:</label>
                                                <select class="form-control" name="natureza" id="natureza">
                                                    <option value=""></option>
                                                    <option value="Não aplicável" <?php echo (($row["nucleo_natureza"] == 'Não aplicável') ? 'selected' : '') ?>>Não aplicável</option>
                                                    <option value="Clandestino" <?php echo (($row["nucleo_natureza"] == 'Clandestino') ? 'selected' : '') ?>>Clandestino</option>
                                                    <option value="Irregular" <?php echo (($row["nucleo_natureza"] == 'Não aplicável') ? 'selected' : '') ?>>Irregular</option>
                                                    <option value="informalidade decorrente da impossibilidade de titulação pelos meios ordinários em virtude">informalidade decorrente da impossibilidade de titulação pelos meios ordinários em virtude</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Características:</label>
                                                <select class="form-control selectpicker" multiple data-actions-box="true" data-container="body" data-size="8" data-live-search="true" name="natureza_caracteristica[]" id="natureza_caracteristica">
                                                    <?php
                                                    // Recupera as características do banco de dados
                                                    $caracteristicas = explode(',', $row['nucleo_natureza_caract']); // Ajuste conforme necessário

                                                    // Lista das opções
                                                    $opcoes = [
                                                        "A proprietário é pessoa jurídica extinta",
                                                        "Proprietário falecido",
                                                        "Proprietário Não localizado",
                                                        "Sucessivas transferências de posse",
                                                        "Proprietários de baixa renda que Não tem condições de arcarem com as custas e emolumentos",
                                                        "Outro(s) motivo(s)",
                                                        "Projeto aprovado, mas sem registro imobiliário",
                                                        "Projeto aprovado mas implantado em desacordo com o projeto",
                                                        "Não houve a implantação das infraestrutura previsto no projetos",
                                                    ];

                                                    // Loop através das opções
                                                    foreach ($opcoes as $opcao) {
                                                        // Verifica se a opção está selecionada
                                                        $selected = in_array(trim($opcao), array_map('trim', $caracteristicas)) ? 'selected' : '';
                                                        echo "<option value=\"" . htmlspecialchars($opcao) . "\" $selected>" . htmlspecialchars($opcao) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Água potável:</label>
                                                <select class="form-control" name="agua">
                                                    <option value=""></option>
                                                    <option value="Sim" <?php echo (($row["nucleo_agua"] == 'Sim') ? 'selected' : '') ?>>Sim</option>
                                                    <option value="Não" <?php echo (($row["nucleo_agua"] == 'Não') ? 'selected' : '') ?>>Não</option>
                                                    <option value="Parcial" <?php echo (($row["nucleo_agua"] == 'Parcial') ? 'selected' : '') ?>>Parcial</option>
                                                    <option value="Não necessário" <?php echo (($row["nucleo_agua"] == 'Não necessário') ? 'selected' : '') ?>>Não necessário</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Energia Elétrica:</label>
                                                <select class="form-control" name="energia">
                                                    <option value=""></option>
                                                    <option value="Sim" <?php echo (($row["nucleo_energia"] == 'Sim') ? 'selected' : '') ?>>Sim</option>
                                                    <option value="Não" <?php echo (($row["nucleo_energia"] == 'Não') ? 'selected' : '') ?>>Não</option>
                                                    <option value="Parcial" <?php echo (($row["nucleo_energia"] == 'Parcial') ? 'selected' : '') ?>>Parcial</option>
                                                    <option value="Não necessário" <?php echo (($row["nucleo_energia"] == 'Não necessário') ? 'selected' : '') ?>>Não necessário</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Saneamento Básico:</label>
                                                <select class="form-control" name="saneamento">
                                                    <option value=""></option>
                                                    <option value="Sim" <?php echo (($row["nucleo_saneamento"] == 'Sim') ? 'selected' : '') ?>>Sim</option>
                                                    <option value="Não" <?php echo (($row["nucleo_saneamento"] == 'Não') ? 'selected' : '') ?>>Não</option>
                                                    <option value="Parcial" <?php echo (($row["nucleo_saneamento"] == 'Parcial') ? 'selected' : '') ?>>Parcial</option>
                                                    <option value="Não necessário" <?php echo (($row["nucleo_saneamento"] == 'Não necessário') ? 'selected' : '') ?>>Não necessário</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Soluções de Drenagem:</label>
                                                <select class="form-control" name="drenagem">
                                                    <option value=""></option>
                                                    <option value="Sim" <?php echo (($row["nucleo_drenagem"] == 'Sim') ? 'selected' : '') ?>>Sim</option>
                                                    <option value="Não" <?php echo (($row["nucleo_drenagem"] == 'Não') ? 'selected' : '') ?>>Não</option>
                                                    <option value="Parcial" <?php echo (($row["nucleo_drenagem"] == 'Parcial') ? 'selected' : '') ?>>Parcial</option>
                                                    <option value="Não necessário" <?php echo (($row["nucleo_drenagem"] == 'Não necessário') ? 'selected' : '') ?>>Não necessário</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3 mb-3 bg-light">
                                                <legend class="w-auto">Necessita de implantaçao de benfeitorias essênciais?<span class="text-danger">*</span></legend>
                                                <div class="form-group">
                                                    <div class="custom-radio-group mt-2">
                                                        <label class="custom-radio">
                                                            <input type="radio" name="benfeitorias" value="Não necessita"
                                                                <?php echo (($row["nucleo_benfeitorias"] == 'Não necessita') ? 'checked' : '') ?>>
                                                            <span class="radio-btn">Não necessita</span>
                                                        </label>
                                                        <label class="custom-radio">
                                                            <input type="radio" name="benfeitorias" value="Sim, pelo Município"
                                                                <?php echo (($row["nucleo_benfeitorias"] == 'Sim, pelo Município') ? 'checked' : '') ?>>
                                                            <span class="radio-btn">Sim, pelo Município</span>
                                                        </label>
                                                        <label class="custom-radio">
                                                            <input type="radio" name="benfeitorias" value="Sim, por Particular"
                                                                <?php echo (($row["nucleo_benfeitorias"] == 'Sim, por Particular') ? 'checked' : '') ?>>
                                                            <span class="radio-btn">Sim, por Particular</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>CPF do Responsável:</label>
                                                <input value="<?php echo $row["nucleo_cpf_res"] ?>" type="text" class="form-control" name="cpf_responsavel" id="cpf" placeholder="CPF">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Nome do Responsável:</label>
                                                <input value="<?php echo $row["nucleo_nome_res"] ?>" type="text" class="form-control" name="nome_responsavel" placeholder="Nome Completo">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <fieldset class="border p-3 mb-3 bg-light">
                                                <legend class="w-auto">Possui compensações urbanísticas a serem implementadas?<span class="text-danger">*</span></legend>
                                                <div class="form-group">
                                                    <div class="custom-radio-group mt-2">
                                                        <label class="custom-radio">
                                                            <input type="radio" name="urbanisticas" value="Não"
                                                                <?php echo (($row["nucleo_comp_urbanistica"] == 'Não') ? 'checked' : ''); ?>>
                                                            <span class="radio-btn">Não</span>
                                                        </label>
                                                        <label class="custom-radio">
                                                            <input type="radio" name="urbanisticas" value="Sim"
                                                                <?php echo (($row["nucleo_benfeitorias"] == 'Sim') ? 'checked' : '') ?>>
                                                            <span class="radio-btn">Sim</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-4">
                                            <fieldset class="border p-3 mb-3 bg-light">
                                                <legend class="w-auto">Possui compensações ambientais a serem implementadas?<span class="text-danger">*</span></legend>
                                                <div class="form-group">
                                                    <div class="custom-radio-group mt-2">
                                                        <label class="custom-radio">
                                                            <input type="radio" name="ambientais" value="Não"
                                                                <?php echo (($row["nucleo_comp_ambiental"] == 'Não') ? 'checked' : ''); ?>>
                                                            <span class="radio-btn">Não</span>
                                                        </label>
                                                        <label class="custom-radio">
                                                            <input type="radio" name="ambientais" value="Sim"
                                                                <?php echo (($row["nucleo_comp_ambiental"] == 'Sim') ? 'checked' : '') ?>>
                                                            <span class="radio-btn">Sim</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-4">
                                            <fieldset class="border p-3 mb-3 bg-light">
                                                <legend class="w-auto">O municipio irá notificar os titulares das matrículas e confinantes?<span class="text-danger">*</span></legend>
                                                <div class="form-group">
                                                    <div class="custom-radio-group mt-2">
                                                        <label class="custom-radio">
                                                            <input type="radio" name="notificar" value="Não"
                                                                <?php echo (($row["nucleo_notif"] == 'Não') ? 'checked' : ''); ?>>
                                                            <span class="radio-btn">Não</span>
                                                        </label>
                                                        <label class="custom-radio">
                                                            <input type="radio" name="notificar" value="Sim"
                                                                <?php echo (($row["nucleo_notif"] == 'Sim') ? 'checked' : '') ?>>
                                                            <span class="radio-btn">Sim</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>

                                    <!-- Campos Ocultos -->
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                                    <input type="hidden" name="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                                    <!-- Botão de Salvar -->
                                    <div class="text-right">
                                        <button class="btn btn-outline-success" type="submit">
                                            <i class="fa fa-check"></i>Salvar
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="accordion-panel">
                    <div class=" accordion-heading" role="tab" id="headerDocumentosEtapa2">
                        <h3 class="card-title accordion-title">
                            <a class="accordion-msg waves-effect waves-dark scale_active collapsed" data-toggle="collapse" data-parent="#accordion_etapa2" href="#collapseDocumentosEtapa2" aria-expanded="false" aria-controls="collapseDocumentosEtapa2">
                                Documentos
                            </a>
                        </h3>
                    </div>
                    <div id="collapseDocumentosEtapa2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headerDocumentosEtapa2">
                        <div class="accordion-content">
                            <!-- Documentos anexos -->
                            <h5 class="mb-3">Enviar Documentos Anexos</h5>

                            <div class="row">
                                <div class="col-md-7">
                                    <div id="dropzoneEtapa2" class="dropzone dotted-border rounded p-5 text-center mb-3">
                                        <i class="fa fa-cloud-upload fa-3x text-primary mb-3" aria-hidden="true"></i>
                                        <p class="lead">Arraste e solte o arquivo aqui ou <label for="arquivo_anexoEtapa2" class="text-primary fw-bold" style="cursor: pointer;">clique para selecionar</label></p>
                                        <input type="file" id="arquivo_anexoEtapa2" class="d-none" accept=".pdf" required />
                                        <small class="form-text text-muted" id="fileInfoAnexoEtapa2"></small>
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
                                <button type="button" id="botaoUploadEtapa2" class="btn btn-primary btn-block" style="display: none;">
                                    <i class="fa fa-upload"></i> Fazer Upload
                                </button>
                            </div>

                            <h5 class="mb-3">Documentos Anexos</h5>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="search" id="etapa2_search_query" autocomplete="off" class="form-control mb-1"
                                            placeholder="Pesquisar documento" />
                                    </div>
                                </div>
                            </div>
                            <div id="etapa2_anexos"></div>
                            <br>
                            <?php if ($viewData['user']->hasPermission('processo_gerarZip_2etapa')) : ?>
                                <!-- Botão Gerar ZIP -->
                                <div class="d-flex justify-content-end">
                                    <button
                                        id="btnGerarZipEtapa2"
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

        <div class="card-body">
            <?php if ($viewData['user']->hasPermission('processo_concluirEtapa')) : ?>
                <button class="btn btn-success btn-sm btn-concluir-etapa mb-2" data-etapa="2">
                    <i class="fa fa-check-circle"></i> Concluir Etapa
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaPendente')) : ?>
                <button class="btn btn-warning btn-sm marcarPendente mb-2" data-etapa="2">
                    <i class="fa fa-exclamation-circle"></i> Marcar como Pendente
                </button>
            <?php endif; ?>
            <?php if ($viewData['user']->hasPermission('processo_etapaAnalise')) : ?>
                <button class="btn btn-info btn-sm enviarAnalise mb-2" data-etapa="2">
                    <i class="fa fa-hourglass-half"></i> Enviar para Análise
                </button>
            <?php endif; ?>
        </div>


    </div>

    <!-- modal novo  -->
    <div id="modalNewConfrontante" class="modal fade" data-backdrop="static" conindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Novo Confrontante / Confinantes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-new-confrontante m-2" action="<?php echo BASE_URL ?>acoes/etapa2/confrontantes/add.php" method="POST">
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
                                <!-- tipo -->
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="tipo" value="Física" id="fisica_con" required>
                                            <span class="radio-btn">Física</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="tipo" value="Jurídica" id="juridica_con" required>
                                            <span class="radio-btn">Jurídica</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CPF</label>
                                    <input value="" type="text" class="form-control" id="cpf_con" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input value="" type="text" class="form-control" name="cnpj" id="cnpj_con" placeholder="00.000.000/0000-00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inscrição Estadual:</label>
                                    <input type="text" value="" class="form-control" name="i_estadual" id="i_estadual_con" placeholder="Inscrição Estadual">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inscrição Municipal:</label>
                                    <input type="text" value="" class="form-control" name="i_municipal" id="i_municipal_con" placeholder="Inscrição Municipal">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Representante Legal:</label>
                                    <input type="text" value="" class="form-control" name="representante" id="representante_con" placeholder="Representante Legal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargo:</label>
                                    <input type="text" value="" class="form-control" name="cargo" id="cargo_con" placeholder="Cargo">
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
                                    <legend class="w-auto">Sexo:</legend>
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
                                    <input value="" type="text" class="form-control" name="telefone" id="telefone_con" placeholder="(00) 0000-0000">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Celular:</label>
                                    <input value="" type="text" class="form-control" name="celular" id="celular_con" placeholder="(00) 00000-0000">
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
                                    <input value="" type="text" class="form-control" name="cep" id="cep_con" placeholder="CEP">
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
                                    <input value="" type="text" class="form-control" name="pai" id="pai_con" placeholder="Nome do Pai">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome da Mãe:</label>
                                    <input value="" type="text" class="form-control" name="mae" id="mae_con" placeholder="Nome do Mãe">
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="enviar" id="btn-salvar-con" class="btn btn-primary">Cadastrar</button>
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

    <!-- modal edit-->
    <div id="modalEditConfrontante" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Editar Confrontante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-edit-confrontante m-2" action="<?php echo BASE_URL ?>acoes/etapa2/confrontantes/update.php" method="POST">
                    <div class="modal-body">
                        <span id="visul_dados_confrontantes"></span>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">
                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar" id="btn-salvar-edit-confrontante" class="btn btn-primary">Salvar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <!-- modal novo proprietario da matricula  -->
    <div id="modalNewProprietarioMatricula" class="modal fade" data-backdrop="static" conindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Novo Proprietarios / Matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="formnewProprietariomatricula m-2" action="<?php echo BASE_URL ?>acoes/etapa2/proprietarios/add.php" method="POST">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Pessoa: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="identificacao" value="Identificado" id="IdentificadoProprietarioMatricula" required checked>
                                            <span class="radio-btn">Identificado</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="identificacao" value="Não Identificado" id="naoIdentificadoProprietarioMatricula" required>
                                            <span class="radio-btn">Não Identificado</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <!-- tipo -->
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="tipo" value="Física" id="fisica_ProprietarioMatricula" required>
                                            <span class="radio-btn">Física</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="tipo" value="Jurídica" id="juridica_ProprietarioMatricula" required>
                                            <span class="radio-btn">Jurídica</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CPF</label>
                                    <input value="" type="text" class="form-control" id="cpf_ProprietarioMatricula" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input value="" type="text" class="form-control" name="cnpj" id="cnpj_ProprietarioMatricula" placeholder="00.000.000/0000-00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inscrição Estadual:</label>
                                    <input type="text" value="" class="form-control" name="i_estadual" id="i_estadual_ProprietarioMatricula" placeholder="Inscrição Estadual">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inscrição Municipal:</label>
                                    <input type="text" value="" class="form-control" name="i_municipal" id="i_municipal_ProprietarioMatricula" placeholder="Inscrição Municipal">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Representante Legal:</label>
                                    <input type="text" value="" class="form-control" name="representante" id="representante_ProprietarioMatricula" placeholder="Representante Legal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargo:</label>
                                    <input type="text" value="" class="form-control" name="cargo" id="cargo_ProprietarioMatricula" placeholder="Cargo">
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

                            <div class="col-md-3" id="grupoSexo">
                                <!-- tipo -->
                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Sexo:</legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="sexo" value="Masculino" id="masculino1">
                                            <span class="radio-btn">Masculino</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="sexo" value="Feminino" id="feminino2">
                                            <span class="radio-btn">Feminino</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-3" id="grupoDataNasc">
                                <label>Data de Nascimento</label>
                                <input type="date" class="form-control" name="data_nasc">
                            </div>

                            <div class="col-md-3" id="grupoRG">
                                <label>RG:</label>
                                <input type="text" class="form-control" name="rg">
                            </div>

                            <div class="col-md-3" id="grupoEmissor">
                                <label>Orgão Emissor</label>
                                <input type="text" class="form-control" name="emissor">
                            </div>

                            <div class="col-md-6" id="grupoProfissao">
                                <label>Profissão:</label>
                                <input type="text" class="form-control" name="profissao">
                            </div>

                            <div class="col-md-3" id="grupoEstadoCivil">
                                <label>Estado Civil:</label>
                                <select class="form-control" name="estado_civil">
                                    <option value=""></option>
                                    <option value="Solteiro">Solteiro</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Viúvo">Viúvo</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="grupoUniao">
                                <label>União Estável:</label>
                                <select class="form-control" name="uniao">
                                    <option value=""></option>
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Telefone:</label>
                                    <input value="" type="text" class="form-control" name="telefone" id="telefone_ProprietarioMatricula" placeholder="(00) 0000-0000">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Celular:</label>
                                    <input value="" type="text" class="form-control" name="celular" id="celular_ProprietarioMatricula" placeholder="(00) 00000-0000">
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
                                    <input value="" type="text" class="form-control" name="cep" id="cep_ProprietarioMatricula" placeholder="CEP">
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
                            <div class="col-md-6" id="grupoPai">
                                <div class="form-group">
                                    <label>Nome do Pai:</label>
                                    <input value="" type="text" class="form-control" name="pai" id="pai_ProprietarioMatricula" placeholder="Nome do Pai">
                                </div>
                            </div>
                            <div class="col-md-6" id="grupoMae">
                                <div class="form-group">
                                    <label>Nome da Mãe:</label>
                                    <input value="" type="text" class="form-control" name="mae" id="mae_ProprietarioMatricula" placeholder="Nome do Mãe">
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="enviar" id="" class="btn btn-primary">Cadastrar</button>
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

    <!-- modal edit proprietario da matricula -->
    <div id="modalEditProprietarioMatricula" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-grande" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visulUsuarioModalLabel">Editar Proprietário da matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-edit-proprietario-matricula m-2" action="<?php echo BASE_URL ?>acoes/etapa2/proprietarios/update.php" method="POST">
                    <div class="modal-body">
                        <span id="visul_dados_proprietarios_matricula"></span>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="idUser" id="idUser" value="<?php echo $viewData['user']->getId(); ?>">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">

                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar" id="btn-salvar-EditProprietarioMatricula" class="btn btn-primary">Salvar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <!-- Modal de Edição de Matrícula -->
    <div class="modal fade" id="editarMatriculaModal" tabindex="-1" role="dialog" aria-labelledby="editarMatriculaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarMatriculaModalLabel">Editar Matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditarMatricula">
                        <input type="hidden" id="editarMatriculaId" name="matricula_id">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">


                        <div class="form-group">
                            <label for="editarMatriculaNome">Nome da Matrícula</label>
                            <input type="text" class="form-control" id="editarMatriculaNome" name="matricula_nome" required>
                        </div>

                        <!-- Adicione mais campos aqui conforme necessário -->

                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Nova Matrícula -->
    <div class="modal fade" id="novaMatriculaModal" tabindex="-1" role="dialog" aria-labelledby="novaMatriculaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="novaMatriculaModalLabel">Cadastrar Nova Matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formNovaMatricula">
                        <div class="form-group">
                            <label for="matriculaNome">Nome da Matrícula</label>
                            <input type="text" class="form-control" id="matriculaNome" name="matricula_nome" required>
                        </div>

                        <!-- Adicione outros campos necessários aqui -->
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">

                        <button type="submit" class="btn btn-primary">Cadastrar Matrícula</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Adicionar Proprietário -->
    <div class="modal fade" id="adicionarProprietarioModal" tabindex="-1" role="dialog" aria-labelledby="adicionarProprietarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarProprietarioModalLabel">Adicionar Proprietário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAdicionarProprietario">
                        <input type="hidden" id="matriculaId" name="matricula_id">
                        <input type="hidden" name="idProcedimento" id="idProcedimento" value="<?php echo $id; ?>">


                        <div class="form-group">
                            <label for="proprietarioSelect">Selecionar Proprietário</label>
                            <select class="form-control" id="proprietarioMatriculaSelect" name="proprietario_id" required>
                                <!-- Opções de proprietários serão carregadas dinamicamente -->
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa2/proprietarios/js.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa2/matriculas/js.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa2/nucleo/js.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa2/confrontantes/js.js"></script>
    <script type="text/javascript" src="<?php echo BASE_URL; ?>acoes/etapa2/anexos/js.js"></script>

<?php endif; ?>