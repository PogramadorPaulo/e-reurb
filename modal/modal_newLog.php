<!-- modal novo  -->
<div id="modalNewLog" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visulUsuarioModalLabel">Novo Logradouro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form_new_log" method="POST">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Proprietário Tabulado:</label>
                                <select class="form-control selectpicker show-tick" multiple data-container="body" data-size="8" data-live-search="true" name="tab[]" required>
                                   
                                    <?php foreach ($list_tab as $item) : ?>
                                        <option value="<?php echo $item['nome'] ?>"><?php echo $item['nome'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Logradouro:</label>
                                <input value="" type="text" class="form-control" id="log" name="log" placeholder="Endereço">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número Inicial:</label>
                                <input value="" type="text" class="form-control" name="n_inicial" placeholder="Número Inicial">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número Final:</label>
                                <input type="text" value="" class="form-control" name="n_final" placeholder="Número Final">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Memorial Descritivo:</label>
                                <textarea class="form-control" name="memorial" rows="4"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="enviar" id="btn-salvar" class="btn btn-primary">Cadastrar</button>
                        <input value="<?php echo $id ?>" type="hidden" class="form-control" name="id" id="id">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    /// Update procedure requerente

    $(function() {
        //
        $(".form_new_log").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var action = form.attr("action");
            var form_data = form.serialize() + "&action=" + action;

            $.ajax({
                url: "../../acoes/new_log.php",
                type: "POST",
                data: form_data,
                dataType: "JSON",
                beforeSend: function(xhr) {
                    form.find("#btn-salvar").after("<span class='load'> <i class='fa fa-spinner fa-spin fa-2x' aria-hidden='true'></i></span>");
                    $(".error, .success").fadeOut(400, function() {
                        $(this).remove();
                    });
                },
                success: function(response, textStatus, jqXHR) {
                    if (response.error) {
                        form.prepend("<div class='callout callout-danger' role='alert'>" + response.error + "</div>");

                    } else {
                        form.prepend("<div class='callout callout-success' role='alert'> Salvo com sucesso !</div>");
                        setTimeout(function() {
                            //  $('#modalNewRequente').modal('hide');
                            window.location.reload();
                        }, 2000);
                    }
                    $('.callout').fadeIn(500).delay(3000).fadeOut(500);

                    if (response.redirect) {
                        setTimeout(function() {
                            $('#modalNewLog').modal('hide');
                        }, 2000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrow) {
                    form.prepend("<div class='callout callout-danger'role='alert'>Desculpe, erro ao processar</div>");
                    $('.callout').fadeIn(500).delay(3000).fadeOut(500);
                },
                complete: function(jqXHR, textStatus) {
                    form.find(".load").fadeOut(function() {
                        $(this).remove();
                    });
                }
            });
        });

    });
</script>