<?php
include_once "../../../config.php";

try {
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    // Verifica se o ID está presente
    if (!empty($id)) {
        // Buscar todas as quadras com status ativo e filtradas pelo ID
        $queryQuadras = "SELECT quadra_id, quadra_letra FROM tb_quadras WHERE quadra_prc = :id AND quadra_status = 1";
        $stmtQuadras = $db->prepare($queryQuadras);
        $stmtQuadras->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtQuadras->execute();
        $quadras = [];

        // Para cada quadra, buscar os lotes e os dados dos proprietários
        while ($quadra = $stmtQuadras->fetch(PDO::FETCH_ASSOC)) {
            $quadraId = $quadra['quadra_id'];

            // Buscar lotes para a quadra atual, juntando os proprietários
            $queryLotes = "
               SELECT 
                    l.lote_id,
                    l.lote_number,
                   COALESCE(GROUP_CONCAT(p.id_tab ORDER BY p.nome SEPARATOR ', '), 'Sem proprietário') AS idProprietarios,
                   COALESCE(GROUP_CONCAT(p.nome ORDER BY p.nome SEPARATOR ', '), '') AS nomeProprietarios
                FROM tb_lotes l
                LEFT JOIN tb_lotes_proprietarios lp 
                    ON l.lote_id = lp.loteP_idLote AND lp.loteP_status = 1
                LEFT JOIN proprietarios_tabulares p 
                    ON lp.loteP_idProprietario = p.id_tab
                WHERE l.lote_quadra = :quadraId AND l.lote_status = 1
                GROUP BY l.lote_id
                ORDER BY l.lote_number";
            $stmtLotes = $db->prepare($queryLotes);
            $stmtLotes->bindParam(':quadraId', $quadraId, PDO::PARAM_INT);
            $stmtLotes->execute();

            $lotes = [];
            while ($lote = $stmtLotes->fetch(PDO::FETCH_ASSOC)) {
                $lotes[] = [
                    'lote_id' => $lote['lote_id'],
                    'lote_number' => $lote['lote_number'],
                    'proprietarios' => [
                        'nomes' => $lote['nomeProprietarios'], // Nomes concatenados
                        'ids' => $lote['idProprietarios'] // IDs concatenados
                    ]
                ];
            }

            $quadras[] = [
                'quadra_id' => $quadra['quadra_id'],
                'quadra_letra' => $quadra['quadra_letra'],
                'lotes' => $lotes
            ];
        }

        echo json_encode($quadras);
    } else {
        // Retorna um erro se o ID estiver vazio
        echo json_encode(['error' => 'ID inválido ou ausente.']);
    }
} catch (PDOException $e) {
    // Retorna um erro se ocorrer uma exceção
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}
