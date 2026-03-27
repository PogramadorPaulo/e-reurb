<?php
include_once('../../config.php');

$municipio_id = $_POST['municipio_id'] ?? 0;

$stmt = $db->prepare("SELECT * FROM comissao_funcao WHERE id_funcao_municipio = :id ORDER BY funcao_nome");
$stmt->bindValue(':id', $municipio_id);
$stmt->execute();
$funcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="formNovaFuncao">
    <input type="hidden" name="municipio_id" value="<?= $municipio_id ?>">
    <div class="mb-3">
        <label>Nova Função</label>
        <div class="d-flex">
            <input type="text" name="nova_funcao" class="form-control me-2" required>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </div>
</form>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Função</th>
            <th width="80">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($funcoes as $f): ?>
            <tr>
                <td>
                    <input type="text" class="form-control form-control-sm editar-funcao"
                        data-id="<?= $f['id_funcao'] ?>" value="<?= htmlspecialchars($f['funcao_nome']) ?>">
                </td>
                <td>
                    <button class="btn btn-danger btn-sm excluir-funcao" data-id="<?= $f['id_funcao'] ?>">
                        <i class="ti-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $('#formNovaFuncao').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Salvando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.post('acoes/municipio/salvar_funcao.php', $(this).serialize(), function(resp) {
            Swal.close(); // Fecha loader

            if (resp.trim() === 'ok') {
                Swal.fire('Sucesso', 'Função adicionada com sucesso!', 'success');
                gerenciarFuncoes(<?= $municipio_id ?>);
                $('#modalFuncoesComissao').modal('hide')
            } else {
                Swal.fire('Erro', resp, 'error');
            }
        });
    });

    // Editar função
    $('.editar-funcao').on('blur', function() {
        const id = $(this).data('id');
        const nome = $(this).val();

        $.post('acoes/municipio/editar_funcao.php', {
            id,
            nome
        }, function(resp) {
            if (resp.trim() === 'ok') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Função atualizada',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#modalFuncoesComissao').modal('hide')
            } else {
                Swal.fire('Erro', resp, 'error');
            }
        });
    });


    // Excluir função
    $('.excluir-funcao').on('click', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação removerá a função.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Excluindo...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.post('acoes/municipio/excluir_funcao.php', {
                    id
                }, function(resp) {
                    Swal.close();
                    if (resp.trim() === 'ok') {
                        Swal.fire('Excluído!', 'Função removida com sucesso.', 'success');
                        gerenciarFuncoes(<?= $municipio_id ?>);
                        $('#modalFuncoesComissao').modal('hide')
                    } else {
                        Swal.fire('Erro', resp, 'error');
                    }
                });
            }
        });
    });
</script>