<?php
require_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Receber os dados do formulário
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $municipio_name = filter_input(INPUT_POST, 'municipio_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_uf = filter_input(INPUT_POST, 'municipio_uf', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_cnpj = filter_input(INPUT_POST, 'municipio_cnpj', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_prefeito = filter_input(INPUT_POST, 'municipio_prefeito', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_autoridade = filter_input(INPUT_POST, 'municipio_autoridade', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_prefeito_cpf = filter_input(INPUT_POST, 'municipio_prefeito_cpf', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_prefeito_rg = filter_input(INPUT_POST, 'municipio_prefeito_rg', FILTER_SANITIZE_SPECIAL_CHARS);
        $municipio_normativas = filter_input(INPUT_POST, 'municipio_normativas', FILTER_SANITIZE_SPECIAL_CHARS);
       

        // Validação dos campos obrigatórios
        if (empty($id) || empty($municipio_name) || empty($municipio_uf) || empty($municipio_cnpj)) {
            echo json_encode([
                'status' => 'warning',
                'tittle' => 'Atenção',
                'message' => 'Preencha todos os campos obrigatórios!',
                'icon' => 'warning',
            ]);
            exit;
        }

        // Verificar se o município já existe com o mesmo CNPJ (excluindo o próprio registro)
        $check = $db->prepare("
            SELECT municipio_id FROM tb_municipios 
            WHERE municipio_cnpj = :municipio_cnpj 
            AND municipio_id != :id
        ");
        $check->bindValue(':municipio_cnpj', $municipio_cnpj);
        $check->bindValue(':id', $id);
        $check->execute();

        if ($check->rowCount() > 0) {
            echo json_encode([
                'status' => 'error',
                'tittle' => 'Erro',
                'message' => 'Já existe um município cadastrado com este CNPJ.',
                'icon' => 'error',
            ]);
            exit;
        }

        // Atualizar os dados no banco de dados
        $sql = $db->prepare("
            UPDATE tb_municipios SET
                municipio_name = :municipio_name,
                municipio_uf = :municipio_uf,
                municipio_cnpj = :municipio_cnpj,
                municipio_prefeito = :municipio_prefeito,
                municipio_autoridade = :municipio_autoridade,
                municipio_prefeito_cpf = :municipio_prefeito_cpf,
                municipio_prefeito_rg = :municipio_prefeito_rg,
                municipio_normativas = :municipio_normativas,
                municipio_update = NOW()
            WHERE municipio_id = :id
        ");

        $sql->bindValue(':id', $id);
        $sql->bindValue(':municipio_name', $municipio_name);
        $sql->bindValue(':municipio_uf', $municipio_uf);
        $sql->bindValue(':municipio_cnpj', $municipio_cnpj);
        $sql->bindValue(':municipio_prefeito', $municipio_prefeito);
        $sql->bindValue(':municipio_autoridade', $municipio_autoridade);
        $sql->bindValue(':municipio_prefeito_cpf', $municipio_prefeito_cpf);
        $sql->bindValue(':municipio_prefeito_rg', $municipio_prefeito_rg);
        $sql->bindValue(':municipio_normativas', $municipio_normativas);
     

        if ($sql->execute()) {
            echo json_encode([
                'status' => 'success',
                'tittle' => 'Sucesso',
                'message' => 'Município atualizado com sucesso!',
                'icon' => 'success',
            ]);
        } else {
            throw new Exception('Erro ao atualizar os dados no banco.');
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Erro ao atualizar o município: ' . $e->getMessage(),
            'icon' => 'error',
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Requisição inválida.',
        'icon' => 'error',
    ]);
}
