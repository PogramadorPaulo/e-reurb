<?php
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Dados recebidos do formulário
        $data = [
            'municipio_name' => filter_input(INPUT_POST, 'municipio_name', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_uf' => filter_input(INPUT_POST, 'municipio_uf', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_cnpj' => filter_input(INPUT_POST, 'municipio_cnpj', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_prefeito' => filter_input(INPUT_POST, 'municipio_prefeito', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_autoridade' => filter_input(INPUT_POST, 'municipio_autoridade', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_prefeito_cpf' => filter_input(INPUT_POST, 'municipio_prefeito_cpf', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_prefeito_rg' => filter_input(INPUT_POST, 'municipio_prefeito_rg', FILTER_SANITIZE_SPECIAL_CHARS),
            'municipio_normativas' => filter_input(INPUT_POST, 'municipio_normativas', FILTER_SANITIZE_SPECIAL_CHARS),

        ];

        // Validação de campos obrigatórios
        if (empty($data['municipio_name']) || empty($data['municipio_uf']) || empty($data['municipio_cnpj'])) {
            $response = array(
                'status' => 'warning',
                'tittle' => 'Atenção',
                'message' => 'Preencha todos os campos obrigatórios!',
                'icon' => 'warning',
            );
            echo json_encode($response);
            exit;
        }

        // Verificar se o município já está cadastrado pelo código CNPJ
        $check = $db->prepare("
            SELECT municipio_id 
            FROM tb_municipios 
            WHERE municipio_cnpj = :cnpj 
            LIMIT 1
        ");
        $check->bindValue(':cnpj', $data['municipio_cnpj']);
        $check->execute();

        if ($check->rowCount() > 0) {
            $response = array(
                'status' => 'warning',
                'tittle' => 'Atenção',
                'message' => 'Este município já está cadastrado.',
                'icon' => 'warning',
            );
            echo json_encode($response);
            exit;
        }

        // Inserir dados no banco de dados
        $sql = $db->prepare("
            INSERT INTO tb_municipios 
            (municipio_name, municipio_uf, municipio_cnpj, municipio_prefeito, municipio_autoridade, 
             municipio_prefeito_cpf, municipio_prefeito_rg, municipio_normativas, municipio_status, municipio_cadastro) 
            VALUES 
            (:municipio_name, :municipio_uf, :municipio_cnpj, :municipio_prefeito, :municipio_autoridade, 
             :municipio_prefeito_cpf, :municipio_prefeito_rg, municipio_normativas , 1, :municipio_cadastro)
        ");

        $sql->bindValue(':municipio_name', $data['municipio_name']);
        $sql->bindValue(':municipio_uf', $data['municipio_uf']);
        $sql->bindValue(':municipio_cnpj', $data['municipio_cnpj']);
        $sql->bindValue(':municipio_prefeito', $data['municipio_prefeito']);
        $sql->bindValue(':municipio_autoridade', $data['municipio_autoridade']);
        $sql->bindValue(':municipio_prefeito_cpf', $data['municipio_prefeito_cpf']);
        $sql->bindValue(':municipio_prefeito_rg', $data['municipio_prefeito_rg']);
        $sql->bindValue(':municipio_normativas', $data['municipio_normativas']);
        $sql->bindValue(':municipio_cadastro', date('Y-m-d H:i:s'));

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $response = array(
                'status' => 'success',
                'tittle' => 'Sucesso',
                'message' => 'Município cadastrado com sucesso.',
                'icon' => 'success',
            );
        } else {
            throw new Exception('Erro ao cadastrar o município.');
        }

        echo json_encode($response);
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'tittle' => 'Erro',
            'message' => 'Ocorreu um erro ao salvar os dados: ' . $e->getMessage(),
            'icon' => 'error',
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Método de requisição inválido.',
        'icon' => 'error',
    );
    echo json_encode($response);
}
