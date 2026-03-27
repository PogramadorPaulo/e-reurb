

/* input password login */
const senhaInput = document.getElementById('password');
const olhoSenha = document.getElementById('olhoSenha');
olhoSenha.addEventListener('click', function () {
    if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        olhoSenha.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
    } else {
        senhaInput.type = 'password';
        olhoSenha.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
    }
});
/* Fim input password login */


// Login // 

$('#form_login').submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var spinner = $('#loader');
    spinner.show();
    $.ajax({
        type: 'POST',
        url: 'acoes/login.php',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function () {

            $('#btnSend').attr("disabled", "disabled");
            $('#form_login').css("opacity", ".5");

        },
        success: function (response) {

            if (response.status == 'success') {
                spinner.hide();
                $('#form_login').css("opacity", "");
                $("#btnSend").removeAttr("disabled");
                Swal.fire({
                    title: response.tittle,
                    html: response.message,
                    icon: response.icon,
                    timer: 2000, // tempo em milissegundos
                    timerProgressBar: true,
                    showConfirmButton: false,
                  
                }).then((result) => {
                    location.reload(true);
                });

            } else {
                $('#form_login').css("opacity", "");
                $("#btnSend").removeAttr("disabled");
                spinner.hide();
                Swal.fire({
                    title: response.tittle,
                    html: response.message,
                    icon: response.icon
                });
            }
        },
        error: function (xhr, response, error) {
            $('#form_login').css("opacity", "");
            $("#btnSend").removeAttr("disabled");
            spinner.hide();
            Swal.fire({
                icon: 'error',
                title: 'Erro na requisição',
                text: 'Falha ao enviar os dados para o servidor'
            });

        }
    });
});

// Fim login // 

// verifica força da senha //
function verificaForcaSenha() {
    var numeros = /([0-9])/;
    var alfabetoa = /([a-z])/;
    var alfabetoA = /([A-Z])/;
    var chEspeciais = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;

    if ($('#password_new').val().length < 6) {
        $('#password-status').html("<div class='alert alert-danger'><b>Senha inválida</b>, insira no mínimo 6 caracteres</div>");
        document.getElementById("password_new").style.borderBottom = "2px solid red";
        $('#btn_cadastrar').attr("disabled", "disabled");
    } else {
        if ($('#password_new').val().match(numeros) && $('#password_new').val().match(alfabetoa) && $('#password_new').val().match(alfabetoA) && $('#password_new').val().match(chEspeciais)) {
            $('#password-status').html("<div class='alert alert-success'><b>Senha Válida</b></div>");
            document.getElementById("password_new").style.borderBottom = "2px solid green";
            $("#btn_cadastrar").removeAttr("disabled");
        } else {
            $('#password-status').html("<div class='alert alert-danger'><b>Senha inválida</b>, insira um caracter especial, letra Maiúscula e Minúscula </div>");
            $('#btn_cadastrar').attr("disabled", "disabled");
            document.getElementById("password_new").style.borderBottom = "2px solid red";
        }
    }
}
// Fim verifica força da senha //