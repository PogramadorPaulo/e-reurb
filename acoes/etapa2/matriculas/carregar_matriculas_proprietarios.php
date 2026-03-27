<?php
include_once "../../../config.php";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    try {
        $queryMatriculas = "
            SELECT 
                m.matricula_id, 
                m.matricula_nome, 
                m.matricula_status,
                p.id AS proprietario_id, 
                p.nome AS proprietario_nome, 
                p.cpf AS proprietario_cpf,
                p.cnpj AS proprietario_cnpj,
                p.tipo_pessoa AS proprietario_tipo_pessoa
            FROM tb_matriculas AS m
            LEFT JOIN tb_matriculas_proprietarios_relacionamento AS mp 
                ON m.matricula_id = mp.matricula_id AND mp.status = 1
            LEFT JOIN tb_matriculasproprietarios AS p 
                ON mp.proprietario_id = p.id
            WHERE m.matricula_prc = :id AND m.matricula_status = 1
            ORDER BY m.matricula_nome, p.nome
        ";

        $stmtMatriculas = $db->prepare($queryMatriculas);
        $stmtMatriculas->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtMatriculas->execute();

        $matriculas = [];
        $currentMatriculaId = null;
        $matriculaData = [];

        while ($matricula = $stmtMatriculas->fetch(PDO::FETCH_ASSOC)) {
            // Verifica se mudou a matrícula
            if ($currentMatriculaId !== $matricula['matricula_id']) {
                if ($currentMatriculaId !== null) {
                    $matriculas[] = $matriculaData;
                }

                $currentMatriculaId = $matricula['matricula_id'];
                $matriculaData = [
                    'matricula_id' => $matricula['matricula_id'],
                    'matricula_nome' => $matricula['matricula_nome'],
                    'proprietarios' => []
                ];
            }

            // Adiciona o proprietário se existir
            if ($matricula['proprietario_id']) {
                $matriculaData['proprietarios'][] = [
                    'id' => $matricula['proprietario_id'],
                    'nome' => $matricula['proprietario_nome'],
                    'cpf' => $matricula['proprietario_cpf'],
                    'cnpj' => $matricula['proprietario_cnpj'],
                    'tipo_pessoa' => $matricula['proprietario_tipo_pessoa']
                ];
            }
        }

        if ($matriculaData) {
            $matriculas[] = $matriculaData;
        }

        header('Content-Type: application/json');
        echo json_encode($matriculas);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erro ao buscar matrículas e proprietários: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID inválido ou ausente']);
}
