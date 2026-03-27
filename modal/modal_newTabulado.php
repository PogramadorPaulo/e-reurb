<!-- modal novo  -->
<div id="modalNewTab" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-grande" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visulUsuarioModalLabel">Novo Proprietário Tabulado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-new-proprietario p-4 bg-light rounded shadow-sm" action="<?php echo BASE_URL ?>acoes/nucleo/addProprietario.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">

                                <fieldset class="border p-3 mb-3">
                                    <legend class="w-auto">Houve desmembramento? <span class="text-danger">*</span></legend>
                                    <div class="custom-radio-group mt-2">
                                        <label class="custom-radio">
                                            <input type="radio" name="desmembramento" value="Sim" id="fisica" required>
                                            <span class="radio-btn">Sim</span>
                                        </label>
                                        <label class="custom-radio">
                                            <input type="radio" name="desmembramento" value="Não" id="nao" required checked>
                                            <span class="radio-btn">Não</span>
                                        </label>
                                    </div>
                                </fieldset>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- tipo -->
                            <fieldset class="border p-3 mb-3">
                                <legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
                                <div class="custom-radio-group mt-2">
                                    <label class="custom-radio">
                                        <input type="radio" name="identificacao" value="Identificado" id="Identificado" checked required>
                                        <span class="radio-btn">Identificado</span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" name="identificacao" value="Não Identificado" id="Não Identificado" required>
                                        <span class="radio-btn">Não Identificado</span>
                                    </label>
                                </div>
                            </fieldset>

                        </div>
                        <div class="col-md-4">
                            <!-- tipo -->
                            <fieldset class="border p-3 mb-3">
                                <legend class="w-auto">Tipo pessoa: <span class="text-danger">*</span></legend>
                                <div class="custom-radio-group mt-2">
                                    <label class="custom-radio">
                                        <input type="radio" name="tipo" value="Física" id="fisica_tab" checked required>
                                        <span class="radio-btn">Física</span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" name="tipo" value="Jurídica" id="juridica_tab" required>
                                        <span class="radio-btn">Jurídica</span>
                                    </label>
                                </div>
                            </fieldset>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CPF</label>
                                <input value="" type="text" class="form-control" id="cpf_tab" name="cpf" placeholder="000.000.000-00">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input value="" type="text" class="form-control" name="cnpj" id="cnpj_tab" placeholder="00.000.000/0000-00">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Falecido:</label>
                                <select class="form-control" name="falecido">
                                    <option selected="selected" value=""></option>
                                    <option class="" value="Falecido">Sim</option>
                                    <option class="" value="Não">Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Inscrição Estadual:</label>
                                <input type="text" value="" class="form-control" name="i_estadual" id="i_estadual_tab" placeholder="Inscrição Estadual">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Inscrição Municipal:</label>
                                <input type="text" value="" class="form-control" name="i_municipal" id="i_municipal_tab" placeholder="Inscrição Municipal">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Representante Legal:</label>
                                <input type="text" value="" class="form-control" name="representante" id="representante_tab" placeholder="Representante Legal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cargo:</label>
                                <input type="text" value="" class="form-control" name="cargo" id="cargo_tab" placeholder="Cargo">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" value="" class="form-control" name="nome" id="nome" placeholder="Nome">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data de Nascimento</label>
                                <input value="" type="date" class="form-control" name="data_nasc" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sexo:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexo" id="masculino" value="Masculino">
                                    <label class="form-check-label" for="masculino">Masculino</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexo" id="feminino" value="Feminino">
                                    <label class="form-check-label" for="feminino">Feminino</label>
                                </div>
                            </div>
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


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Profissão:</label>
                                <input value="" type="text" class="form-control" name="profissao" placeholder="Profissão">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Capacidade:</label>
                                <select class="form-control" name="capacidade">
                                    <option selected="selected" value=""></option>
                                    <option class="" value="Capaz">Capaz</option>
                                    <option class="" value="Incapaz interditado">Incapaz interditado</option>
                                    <option class="" value="Incapaz parcial">Incapaz parcial</option>
                                    <option class="" value="Incapaz total">Incapaz total</option>
                                </select>
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
                                <input value="" type="text" class="form-control" name="telefone" id="telefone_tab" placeholder="(00) 0000-0000">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular:</label>
                                <input value="" type="text" class="form-control" name="celular" id="celular_tab" placeholder="(00) 00000-0000">
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
                                <input value="" type="text" class="form-control" name="cep" id="cep_tab" placeholder="CEP">
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
                                <input value="" type="text" class="form-control" name="pai" id="pai_tab" placeholder="Nome do Pai">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome da Mãe:</label>
                                <input value="" type="text" class="form-control" name="mae" id="mae_tab" placeholder="Nome do Mãe">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="enviar" id="btn-salvar-tab" class="btn btn-primary">Cadastrar</button>
                        <input value="<?php echo $id ?>" type="hidden" class="form-control" name="id" id="id">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

