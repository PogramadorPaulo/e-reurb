<?php
require_once('../../../config.php'); // Inclua sua configuração do banco de dados

// Obtenha o ID do processo através de uma requisição GET
$processoId = addslashes($_GET['processo_id']);
$idEtapa = 1;
// Diretório onde todos os arquivos estão armazenados
$diretorio = "../../../assets/documentos/";

// Nome do arquivo ZIP que será criado
$zipFileName = "processo_etapa1" . $processoId . ".zip";
$zipFilePath = $diretorio . $zipFileName;

// Cria uma nova instância da classe ZipArchive
$zip = new ZipArchive();

// Tenta criar o arquivo ZIP
if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    // Consulta ao banco de dados para pegar os arquivos do processo
    $query = $db->prepare("SELECT anexo_arquivo FROM tb_etapas_anexos WHERE anexo_prc = :processo_id 
    AND anexo_etapa = :idetapa AND anexo_status = 1");
    $query->bindValue(":processo_id", $processoId);
    $query->bindValue(":idetapa", $idEtapa);
    $query->execute();

    // Verifica se encontrou resultados
    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $nomeArquivo = $row['anexo_arquivo'];
            $caminhoArquivo = $diretorio . $nomeArquivo;

            // Adiciona o arquivo ao ZIP se ele existir
            if (file_exists($caminhoArquivo)) {
                $zip->addFile($caminhoArquivo, $nomeArquivo);
            }
        }

        // Fecha o arquivo ZIP
        $zip->close();

        // Define os cabeçalhos para download
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipFileName);
        header('Content-Length: ' . filesize($zipFilePath));

        // Envia o arquivo ZIP para o navegador
        readfile($zipFilePath);

        // (Opcional) Deleta o arquivo ZIP após o download
        unlink($zipFilePath);
        exit;
    } else {
        // Retorne JSON em caso de erro
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Nenhum arquivo encontrado para o processo.']);
        exit;
    }
} else {
    // Retorne JSON em caso de erro
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Falha ao criar o arquivo ZIP.']);
}
