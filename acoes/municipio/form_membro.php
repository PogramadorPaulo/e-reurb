<?php
include_once('../../config.php');

$id = $_POST['id'] ?? null;
$municipio_id = $_POST['municipio_id'] ?? null;
$dados = ['nome' => '', 'funcao' => ''];

if ($id) {
    $stmt = $db->prepare("SELECT * FROM comissao WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    $municipio_id = $dados['id_municipio'];
}

// Buscar funções disponíveis
$stmtFuncoes = $db->prepare("SELECT id_funcao, funcao_nome FROM comissao_funcao WHERE id_funcao_municipio = :id");
$stmtFuncoes->bindValue(':id', $municipio_id);
$stmtFuncoes->execute();
$funcoes = $stmtFuncoes->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="formComissaoMembro">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="municipio_id" value="<?= $municipio_id ?>">

    <div class="mb-3">
        <label>Nome do Membro</label>
        <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label>Função</label>
        <select name="funcao" class="form-control" required>
            <option value="">Selecione</option>
            <?php foreach ($funcoes as $f): ?>
                <option value="<?= $f['id_funcao'] ?>" <?= ($f['id_funcao'] == ($dados['funcao'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['funcao_nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</form>

<script>
    $('#formComissaoMembro').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Salvando...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: 'acoes/municipio/salvar_membro.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resposta) {
                Swal.close();

                if (resposta.trim() === 'ok') {
                    Swal.fire('Sucesso', 'Membro salvo com sucesso!', 'success');
                    $('#modalMembroComissao').modal('hide');
                    carregarComissao(<?= $municipio_id ?>);
                } else {
                    Swal.fire('Erro', resposta, 'error');
                }
            }
        });
    });


    function carregarComissao(id) {
        $.post('acoes/municipio/carregar_comissao.php', {
            municipio_id: id
        }, function(data) {
            $('#conteudo_comissao').html(data);
        });
    }
</script>