

// Modal upload  
$(document).on('click', '.modalUpload', function () {
    $('#modalProprietarioDocumentos').modal('show');
    $("#visul_dados_proprietarios_documentos").html('Carregando...');
    var id = $(this).attr("id");
    var idProcesso = $('#idProcesso').val()
    if (id !== '') {
        var dados = {
            id: id,
            idProcesso: idProcesso
        };
        $.post('../../acoes/etapa3/anexos/visualizar_upload.php', dados, function (retorna) {
            $("#visul_dados_proprietarios_documentos").html(retorna);
        });
    }
});




