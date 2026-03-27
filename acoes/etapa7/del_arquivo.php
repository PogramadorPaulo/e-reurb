<?php
require_once('../../config.php');

header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

// Recebe o ID do procedimento
$id_procedimento = filter_input(INPUT_POST, "id_procedimento", FILTER_SANITIZE_NUMBER_INT);

// Verificar se a etapa está concluída
if (isEtapaConcluida($id_procedimento, 7, $db)) {
    echo json_encode([
        'status' => 'error',
        'tittle' => 'Etapa Concluída',
        'message' => 'Não é possível salvar ou editar. A etapa já está concluída.',
        'icon' => 'warning'
    ]);
    exit;
}


$response = ['status' => 'error', 'message' => 'Nenhum item foi selecionado para exclusão.']; // Resposta padrão de erro

if (isset($_POST['id']) && !empty($_POST['id'])) {

    $id_user = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT);
    $ItemId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);;

    // Verifica se o documento existe no banco de dados
    $sql = $db->prepare("
        SELECT * FROM tb_etapas_anexos
        WHERE anexo_id =:id
    ");
    $sql->bindValue(":id", $ItemId);
    $sql->execute();
    $array = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (count($array) > 0) {
        $arquivo = $array[0]['anexo_arquivo'];
        $arquivo_title = $array[0]['anexo_titulo'];
        $_FileName = '../../assets/documentos/' . $arquivo;
        $destination = '../../assets/documentos/deletados/' . $arquivo;

        if (file_exists($_FileName)) {
            // Deleta o registro no banco de dados
            $deletar = $db->prepare("UPDATE tb_etapas_anexos SET anexo_status = :status WHERE anexo_id = :id");
            $deletar->bindValue(":status", 0, PDO::PARAM_INT); // Define o status como 0
            $deletar->bindValue(":id", $ItemId, PDO::PARAM_INT); // Bind do ID do item
            $deletar->execute();

            // Copia o arquivo para a pasta deletados e apaga o original
            if (copy($_FileName, $destination)) {
                unlink($_FileName); // Apaga o arquivo original
                $response['status'] = 'success'; // Atualiza o status para sucesso
                $response['message'] = 'O documento foi excluído com sucesso.'; // Mensagem de sucesso

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

                $atividade->bindValue(":atividade_user", $id_user);
                $atividade->bindValue(":atividade_data", date('Y-m-d H:i:s'));
                $atividade->bindValue(":atividade_name", "Arquivo " . $arquivo_title . " etapa 6 excluido");
                $atividade->execute();
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Não foi possível mover o arquivo ' . $arquivo_title . ' para a pasta deletados.'; // Mensagem de erro ao mover
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Arquivo ' . $arquivo_title . ' não encontrado.'; // Mensagem de erro se o arquivo não existir
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Arquivo com ID ' . $ItemId . ' não encontrado no banco de dados.'; // Mensagem se o ID não existir
    }
}

// Envia a resposta JSON
echo json_encode($response);
