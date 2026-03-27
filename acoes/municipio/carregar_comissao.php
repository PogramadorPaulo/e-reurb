<?php
include_once('../../config.php');

$municipio_id = $_POST['municipio_id'] ?? 0;

// Buscar membros da comissão
$stmt = $db->prepare("SELECT c.*, f.funcao_nome 
    FROM comissao c 
    LEFT JOIN comissao_funcao f ON c.funcao = f.id_funcao 
    WHERE c.id_municipio = :id AND c.status = 1 ORDER BY nome
");
$stmt->bindValue(':id', $municipio_id);
$stmt->execute();
$comissao = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar funções disponíveis
$stmtFuncoes = $db->prepare("SELECT id_funcao, funcao_nome FROM comissao_funcao WHERE id_funcao_municipio = :id ORDER BY funcao_nome");
$stmtFuncoes->bindValue(':id', $municipio_id);
$stmtFuncoes->execute();
$funcoes = $stmtFuncoes->fetchAll(PDO::FETCH_ASSOC);

// Exibir modal com dados
?>

<div class="mb-3">
    <div class="mb-2 d-flex justify-content-between">
        <button class="btn btn-success btn-sm" onclick="adicionarMembro(<?= $municipio_id ?>)">+ Adicionar Membro</button>
        <button class="btn btn-secondary btn-sm" onclick="gerenciarFuncoes(<?= $municipio_id ?>)">⚙️ Funções</button>
    </div>

</div>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Função</th>
                <th class="text-center" style="width: 140px;">Ações</th>
            </tr>
        </thead>
        <tbody id="lista_membros">
            <?php foreach ($comissao as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['nome']) ?></td>
                    <td><?= htmlspecialchars($m['funcao_nome']) ?></td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm me-1" onclick="editarMembro(<?= $m['id'] ?>)">
                            <i class="ti-pencil-alt"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="excluirMembro(<?= $m['id'] ?>)">
                            <i class="ti-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
    $('#modalMembroComissao').on('hidden.bs.modal', function() {
        $('#modalComissao').modal('show');
    });


    function adicionarMembro(municipio_id) {
        $('#modalComissao').modal('hide'); // Fecha a modal principal
        $.post('acoes/municipio/form_membro.php', {
            municipio_id
        }, function(data) {
            $('#conteudo_membro_comissao').html(data);
            $('#modalMembroComissao').modal('show');
        });
    }

    function editarMembro(id) {
        $('#modalComissao').modal('hide'); // Fecha a modal principal
        $.post('acoes/municipio/form_membro.php', {
            id
        }, function(data) {
            $('#conteudo_membro_comissao').html(data);
            $('#modalMembroComissao').modal('show');
        });
    }


    function excluirMembro(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Deseja remover este membro da comissão?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, remover',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Removendo...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.post('acoes/municipio/excluir_membro.php', {
                    id
                }, function(data) {
                    Swal.close();

                    if (data.trim() === 'ok') {
                        Swal.fire('Removido!', 'O membro foi removido com sucesso.', 'success');
                        carregarComissao(<?= $municipio_id ?>);
                    } else {
                        Swal.fire('Erro', data, 'error');
                    }
                });
            }
        });
    }


    function gerenciarFuncoes(municipio_id) {

        $.post('acoes/municipio/gerenciar_funcoes.php', {
            municipio_id
        }, function(data) {
            $('#conteudo_funcoes_comissao').html(data);
            $('#modalFuncoesComissao').modal('show');
        });
    }

    function carregarComissao(id) {
        $.post('acoes/municipio/carregar_comissao.php', {
            municipio_id: id
        }, function(data) {
            $('#conteudo_comissao').html(data);
        });
    }
</script>