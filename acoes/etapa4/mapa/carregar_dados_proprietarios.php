<?php
include_once "../../../config.php";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// Verifica se o ID está presente
if (!empty($id)) {
    // Buscar proprietários do lote com CPF e data de nascimento
    $queryProprietarios = "
        SELECT *
        FROM tb_lotes_proprietarios
        LEFT JOIN proprietarios_tabulares ON tb_lotes_proprietarios.loteP_idProprietario = proprietarios_tabulares.id_tab 
        WHERE loteP_idLote = :loteId AND loteP_status = 1";

    $stmtProprietarios = $db->prepare($queryProprietarios);
    $stmtProprietarios->bindParam(':loteId', $id, PDO::PARAM_INT);
    $stmtProprietarios->execute();
    $proprietarios = $stmtProprietarios->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($proprietarios)) {
        $html = '<div class="proprietarios-container">';

        foreach ($proprietarios as $proprietario) {
            $html .= '<div class="proprietario-card">';
            $html .= '<h4>' . htmlspecialchars($proprietario['nome']) . '</h4>';
            $html .= '<p><strong>CPF:</strong> ' . htmlspecialchars($proprietario['cpf']) . '</p>';
            $html .= '<button class="btn btn-outline-danger btn-sm delete-proprietario-lote" title="Excluir" data-id="' . $proprietario['loteP_id'] . '">Remover</button>';
            $html .= '</div>';
        }

        $html .= '</div>';
        echo $html;
    } else {
        echo '<p>Nenhum Beneficiário encontrado.</p>';
    }
} else {
    echo '<p>ID inválido ou ausente.</p>';
}
