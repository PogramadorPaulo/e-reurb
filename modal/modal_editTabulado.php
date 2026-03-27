<!-- modal novo  -->

<div id="modalEditTabulado" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visulUsuarioModalLabel">Editar Proprietário Tabulado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form class="form_edit_tabulado" method="POST">
                    <div class="modal-body">
                        <span id="visul_dados_tab"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn-salvar" id="btn-salvar-tab" class="btn btn-primary">Salvar</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<script>
  

    $(document).ready(function() {
        $(document).on('click', '.view_data_tabulado', function() {

            var user_id = $(this).attr("id");
            //	alert(user_id);
            //Verificar se há valor na variável "user_id".
            if (user_id !== '') {
                var dados = {
                    user_id: user_id
                };
                $.post('<?php echo BASE_URL ?>views/visualizar_tabulado.php', dados, function(retorna) {
                    //Carregar o conteúdo para o usuário
                    $("#visul_dados_tab").html(retorna);
                    $('#modalEditTabulado').modal('show');
                });
            }
        });
    });

    /// Update procedure requerente
    $(function() {
        //
        $(".form_edit_tabulado").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var action = form.attr("action");
            var form_data = form.serialize() + "&action=" + action;

            $.ajax({
                url: "<?php echo BASE_URL ?>acoes/edit_tabulado.php",
                type: "POST",
                data: form_data,
                dataType: "JSON",
                beforeSend: function(xhr) {
                    form.find("#btn-salvar-tab").after("<span class='load'> <i class='fa fa-spinner fa-spin fa-2x' aria-hidden='true'></i></span>");
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
                            $('#modalEditTabulado').modal('hide');
                            window.location.reload();
                        }, 2000);
                    }
                    $('.callout').fadeIn(500).delay(3000).fadeOut(500);

                    if (response.redirect) {
                        setTimeout(function() {
                            $('#modalEditTabulado').modal('hide');
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