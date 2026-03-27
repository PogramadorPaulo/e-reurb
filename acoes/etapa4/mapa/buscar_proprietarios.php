<?php
include_once "../../../config.php";
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
$resultado = '';
// Verifica se o ID está presente
if (!empty($id)) {
    $query = "SELECT * FROM tb_lotes WHERE lote_id = :lote_id AND lote_status = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':lote_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dados as $row) {
        $resultado = '
        <input type="hidden" name="id" id="id" value="' . $row['lote_id'] . '">
        <div class="form-group">
            <label for="lote_number">Número do Lote</label>
            <input type="text" class="form-control" value="' . $row['lote_number'] . '" id="lote_numberEdit" name="lote_number" required>
        </div>

        
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto">Tipo Lote: <span class="text-danger">*</span></legend>
                <div class="custom-radio-group mt-2">
                    <label class="custom-radio">
                        <input type="radio" name="loteTipoEdit" value="E" id="loteTipoE_edit" ' . (($row["lote_tipo"] == 'E') ? 'checked' : '') . ' required>
                        <span class="radio-btn">Específico(E)</span>
                    </label>
                    <label class="custom-radio">
                        <input type="radio" name="loteTipoEdit" value="S" id="loteTipoS_edit" ' . (($row["lote_tipo"] == 'S') ? 'checked' : '') . ' required>
                        <span class="radio-btn">Social(S)</span>
                    </label>
                </div>
            </fieldset>
        

         <fieldset class="border p-3 mb-3">
            <legend class="w-auto">Proprietário: <span class="text-danger">*</span></legend>
            <div class="custom-radio-group mt-2">
                <label class="custom-radio">
                    <input type="radio" name="identificacaoEdit" value="sim" id="IdentificadoProprietarioEdit" required checked>
                    <span class="radio-btn">Identificado</span>
                </label>
                <label class="custom-radio">
                    <input type="radio" name="identificacaoEdit" value="não" id="naoIdentificadoProprietarioEdit" required>
                    <span class="radio-btn">Não Identificado</span>
                </label>
            </div>
        </fieldset>

        <div class="form-group">
            <label>Beneficiários:</label><span class="text-danger">*</span>
            <select class="form-control " name="selectProprietarios[]" id="selectImovelProprietariosEdit" multiple="multiple" required>
                <!-- Opções são carregadas via JS -->
            </select>
        </div>

      
        <div class="form-group">
            <label for="coordenadas">Coordenadas de localização do Lote:</label>
            <div class="input-group">
                <input type="text" id="coordenadas" name="lote_coordenadas" class="form-control"
                    value="' . htmlspecialchars($row['lote_coordenadas'] ?? '') . '"
                    placeholder="Ex: -21.11111,-46.11111">
                
                <!-- Botão para visualizar ou buscar localização no mapa -->
                ' . (!empty($row["lote_coordenadas"]) ? '
                <a href="https://maps.google.com/maps?q=' . urlencode($row["lote_coordenadas"]) . '" 
                    target="_blank" class="btn btn-outline-primary" 
                    title="Visualizar localização no Mapa">
                    <i class="fa fa-map" aria-hidden="true"></i> Mapa
                </a>' : '
                <a href="https://www.google.com/maps" target="_blank" class="btn btn-outline-secondary"
                    title="Abrir Google Maps para buscar a localização">
                    <i class="fa fa-map" aria-hidden="true"></i> Buscar no Mapa
                </a>') . '
            </div>

            <!-- Mensagem de aviso -->
            <i id="coordenadas-vazio" class="text-muted" 
                style="display: ' . (empty($row["lote_coordenadas"]) ? 'inline' : 'none') . ';">
                Informe as coordenadas geográficas do local ou busque no mapa.
            </i>
        </div>




 
        <div class="form-group">
            <label>Memorial:</label>
            <textarea class="form-control" rows="8" name="memorial" id="memorial" placeholder="">' . $row['lote_memorial'] . '</textarea>
        </div>

    ';
    }
    $resultado .= '<div id="visualiza_proprietarios"></div>';
    echo $resultado;
} else {
    // Retorna um erro se o ID estiver vazio
    echo '<p>ID inválido ou ausente.</p>';
}
?>
<script>
    $(document).ready(function() {
        carregaDadosProprietario();
        carrega_select_proprietariosEdit();
        $('#lote_numberEdit').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });


        // Função para alternar o estado do campo selectImovelProprietarios
        function toggleSelectImovelProprietariosEdit() {
            if ($('#naoIdentificadoProprietarioEdit').is(':checked')) {
                $('#selectImovelProprietariosEdit').prop('disabled', true).val("Não Identificado");
            } else {
                $('#selectImovelProprietariosEdit').prop('disabled', false).val("");
            }
        }

        // Função para alternar o estado do campo selectImovelProprietarios
        function toggleSelectTiposEdit() {
            if ($('#naoIdentificadoProprietarioEdit').is(':checked')) {
                $('#selectImovelProprietariosEdit').prop('disabled', true).val("Não Identificado");
            } else {
                $('#selectImovelProprietariosEdit').prop('disabled', false).val("");
            }
        }


        // Inicializa o estado do campo ao carregar a página
        toggleSelectImovelProprietariosEdit();

        // Alterna o estado do campo quando os botões de rádio mudam
        $('input[name="identificacaoEdit"]').on('change', toggleSelectImovelProprietariosEdit);
    });
    var spinner = $('#loader');

    function carregaDadosProprietario() {
        $.ajax({
            url: '<?php echo BASE_URL ?>acoes/etapa4/mapa/carregar_dados_proprietarios.php?id=<?php echo $id ?>',
            method: 'GET',
            beforeSend: function() {
                spinner.show();
                $('#visualiza_proprietarios').html('Carregando...');
            },
            success: function(response) {
                $('#visualiza_proprietarios').html(response);
                spinner.hide();
            },
            error: function(xhr, status, error) {
                console.error('Erro ao buscar imagens:', error);
                spinner.hide();
            }
        });
    }

    // Carregar proprietários no select com Select2
    function carrega_select_proprietariosEdit() {
        var id = $('#id').val();
        $.ajax({
            url: "../../acoes/etapa4/mapa/carrega_select_proprietarios.php",
            method: "POST",
            dataType: "json",
            data: {
                id: id
            },
            beforeSend: function() {
                $('#selectImovelProprietariosEdit').html('<option>Carregando...</option>');
            },
            success: function(data) {
                // Limpa o select e inicializa para múltipla seleção
                $('#selectImovelProprietariosEdit').empty().select2({
                    placeholder: '- Selecionar o beneficiário -',
                    allowClear: true,
                    data: data.map(item => ({
                        id: item.id,
                        text: `${item.nome} - ${item.identificacao}`
                    }))
                });
            },
            error: function(xhr, status, error) {
                console.log(status + ": " + error);
                $('#selectImovelProprietariosEdit').html('<option>Erro ao carregar</option>');
            }
        });
    }

    // Deletar 
    $(document).on('click', '.delete-proprietario-lote', function() {
        const docId = $(this).data('id');
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você não poderá desfazer essa ação!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var idProcedimento = $('#id').val();
                $.ajax({
                    url: '../../acoes/etapa4/mapa/del_proprietarioLote.php',
                    method: 'POST',
                    data: {
                        id: docId,
                        idProcedimento: idProcedimento,
                    },
                    dataType: 'json', // Espera a resposta em formato JSON
                    success: function(response) {
                        // Verifica se a resposta contém um status e uma mensagem
                        if (response.status === 'success') {
                            Swal.fire('Excluído!', response.message, 'success');
                            carregaDadosProprietario();
                            carrega_mapaLotes();
                        } else {
                            // Se não for sucesso, mostra a mensagem de erro
                            Swal.fire('Erro!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Erro!', 'Não foi possível excluir o documento.', 'error');
                    }
                });
            }
        });
    });
</script>