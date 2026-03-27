<?php
// Definir o cabeçalho para JSON antes de qualquer saída
header('Content-Type: application/json');

// Incluir as configurações do banco de dados
include_once "../../config.php";

// Adicionar Dompdf para geração do PDF
require 'pdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ativar suporte a URLs remotas
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Receber os dados do POST
$idUser = filter_input(INPUT_POST, "idUser", FILTER_SANITIZE_NUMBER_INT);
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$conteudo =  filter_input(INPUT_POST, "conteudo", FILTER_DEFAULT);

// Validação dos campos obrigatórios
if (empty($idUser) || empty($conteudo) || empty($id)) {
    $response = array(
        'status' => 'warning',
        'tittle' => 'Atenção',
        'message' => 'Preencha todos os campos obrigatórios!',
        'icon' => 'warning',
    );

    echo json_encode($response);
    exit;
}

try {
    // Buscar o caminho do arquivo atual no banco
    $getFile = $db->prepare("SELECT anexo_arquivo FROM tb_etapas_anexos WHERE anexo_id = :id");
    $getFile->bindValue(":id", $id);
    $getFile->execute();

    $file = $getFile->fetch(PDO::FETCH_ASSOC);
    if ($file && !empty($file['anexo_arquivo'])) {
        // Caminho completo do arquivo atual
        $currentFilePath = __DIR__ . "/../../assets/documentos/" . $file['anexo_arquivo'];

        // Apagar o arquivo atual se existir
        if (file_exists($currentFilePath)) {
            unlink($currentFilePath);
        }
    }

    // Atualizar o conteúdo no banco
    $sql = $db->prepare(
        "UPDATE tb_etapas_anexos 
         SET anexo_conteudo = :anexo_conteudo, anexo_update = :anexo_update 
         WHERE anexo_id = :id"
    );

    $sql->bindValue(":anexo_conteudo", $conteudo);
    $sql->bindValue(":anexo_update", date('Y-m-d H:i'));
    $sql->bindValue(":id", $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        // Gerar o PDF com o novo conteúdo
        $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <title>CRF</title>
                <meta charset='UTF-8'>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                        line-height: 1.6;
                    }
                </style>
            </head>
            <body>
                {$conteudo}
            </body>
            </html>";

        // Configurações do Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Adicionar números de página ao PDF
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont("Arial", "normal");
        $size = 10;

        // Adiciona a paginação no rodapé
        $canvas->page_text(520, 20, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));

        // Caminho para salvar o PDF
        $pdfDir = __DIR__ . "/../../assets/documentos/";
        $pdfName = 'crf_' . $id . '_' . time() . '.pdf';
        $pdfPath = $pdfDir . $pdfName;

        // Salvar o PDF no servidor
        file_put_contents($pdfPath, $dompdf->output());

        // Atualizar o caminho do PDF no banco de dados
        $updatePDF = $db->prepare(
            "UPDATE tb_etapas_anexos 
             SET anexo_arquivo = :anexo_arquivo 
             WHERE anexo_id = :id"
        );

        $updatePDF->bindValue(":anexo_arquivo", $pdfName);
        $updatePDF->bindValue(":id", $id);
        $updatePDF->execute();

        // Registrar a atividade do usuário
        $atividade = $db->prepare("
            INSERT INTO tb_atividades_usuarios 
            (
                atividade_user,
                atividade_name,
                atividade_data
            ) 
            VALUES (
                :atividade_user,
                :atividade_name,
                :atividade_data
            )
        ");

        $atividade->bindValue(":atividade_user", $idUser);
        $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
        $atividade->bindValue(":atividade_name", 'Editado e gerado PDF:');
        $atividade->execute();

        // Retornar sucesso
        $response = array(
            'status' => 'success',
            'tittle' => 'Sucesso',
            'message' => 'Documento editado e PDF gerado com sucesso.',
            'icon' => 'success',
        );
        echo json_encode($response);
    } else {
        $response = array(
            'status' => 'success',
            'tittle' => 'Sucesso',
            'message' => 'Documento salvo com sucesso.',
            'icon' => 'success',
        );
        echo json_encode($response);
    }
} catch (Exception $e) {
    $response = array(
        'status' => 'error',
        'tittle' => 'Erro',
        'message' => 'Erro ao processar a solicitação: ' . $e->getMessage(),
        'icon' => 'error',
    );
    echo json_encode($response);
}
