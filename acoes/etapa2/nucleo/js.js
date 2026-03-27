// form-update-descricoes núcleo// 
$(document).ready(function () {
    var spinner = $('#loader');
    // Submissão do formulário usando AJAX
    $('.form-update-descricoes').on('submit', function (e) {
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
            beforeSend: function () {
                $('#content').css("opacity", ".5");
            },
            success: function (response) {
                if (response.status == 'success') {
                    carregarRequerentes();
                    spinner.hide();
                    $('#content').css("opacity", "");
                    $('#modalEditRequerente').modal('hide');
                }
                spinner.hide();
                $('#content').css("opacity", "");
                Swal.fire({
                    title: response.tittle,
                    html: response.message,
                    icon: response.icon
                });
            },
            error: function (error) {
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
// Fim // 





