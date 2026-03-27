<?php
require_once '../config.php';

// Recebe os parâmetros
$id_municipio = filter_input(INPUT_POST, "id_municipio", FILTER_SANITIZE_NUMBER_INT);;
$id_procedimento = filter_input(INPUT_POST, "id_procedimento", FILTER_SANITIZE_NUMBER_INT);
$id_user = filter_input(INPUT_POST, "id_user", FILTER_SANITIZE_NUMBER_INT);
$etapa = filter_input(INPUT_POST, "etapa", FILTER_SANITIZE_NUMBER_INT);
$justificativa = filter_input(INPUT_POST, "justificativa", FILTER_SANITIZE_SPECIAL_CHARS);

if (!hasPermission($id_user, 'processo_concluirEtapa', $db)) {
    json_response_send([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Você não tem permissão para realizar esta ação.',
        'icon' => 'error'
    ]);
}


// Validação dos dados
if (!is_numeric($id_procedimento) || !is_numeric($id_user) || !is_numeric($id_municipio) || !is_numeric($etapa)) {
    json_response_send([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Dados inválidos fornecidos.',
        'icon' => 'error'
    ]);
}

try {
    // Verificar se a etapa já está concluída
    $stmt = $db->prepare("
        SELECT procedimento_status 
        FROM etapas_procedimentos 
        WHERE processo_id = :id_procedimento AND etapa_id = :etapa
    ");
    $stmt->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $stmt->bindValue(':etapa', $etapa, PDO::PARAM_INT);
    $stmt->execute();
    $etapaStatus = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($etapaStatus && $etapaStatus['procedimento_status'] == 4) {
        json_response_send([
            'status' => 'warning',
            'title' => 'Atenção',
            'message' => 'Esta etapa já está concluída.',
            'icon' => 'warning'
        ]);
    }

    // Atualizar o status da etapa
    $update = $db->prepare("
        UPDATE etapas_procedimentos 
        SET procedimento_status = 4 
        WHERE processo_id = :id_procedimento AND etapa_id = :etapa
    ");
    $update->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $update->bindValue(':etapa', $etapa, PDO::PARAM_INT);
    $update->execute();

    if ($update->rowCount() === 0) {
        json_response_send([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Falha ao atualizar o status da etapa.',
            'icon' => 'error'
        ]);
    }
    // Buscar dados do processo na tabela `procedures`
    $processQuery = $db->prepare("
        SELECT numero_procedimento, nucleo_nome 
        FROM procedures 
        WHERE cod_procedimento = :id_procedimento
    ");
    $processQuery->bindValue(':id_procedimento', $id_procedimento, PDO::PARAM_INT);
    $processQuery->execute();
    $processData = $processQuery->fetch(PDO::FETCH_ASSOC);

    if (!$processData) {
        json_response_send([
            'status' => 'error',
            'title' => 'Erro',
            'message' => 'Processo não encontrado.',
            'icon' => 'error'
        ]);
    }

    $numero_procedimento = $processData['numero_procedimento'];
    $nucleo_nome = $processData['nucleo_nome'];

    // Nome dinâmico para o histórico
    $etapaName = "Etapa {$etapa} do processo: {$numero_procedimento}, Núcleo: {$nucleo_nome} foi Concluída.";

    // Registrar o histórico
    $historico = $db->prepare("
        INSERT INTO tb_etapa_historico 
        (h_idProcesso, h_statusID, h_name, h_date, h_user, h_justificativa) 
        VALUES (:processo, :h_statusID, :name, :data, :user, :justificativa)
    ");
    $historico->bindValue(':processo', $id_procedimento, PDO::PARAM_INT);
    $historico->bindValue(':h_statusID', 4);
    $historico->bindValue(':user', $id_user, PDO::PARAM_INT);
    $historico->bindValue(':name', $etapaName);
    $historico->bindValue(':justificativa', $justificativa);
    $historico->bindValue(':data', date('Y-m-d H:i:s'));
    $historico->execute();

    // Inserir no histórico do usuário
    $atividade = $db->prepare("
        INSERT INTO tb_atividades_usuarios 
        (atividade_user, atividade_name, atividade_data) 
        VALUES (:id_user, :atividade_name, :atividade_data)
    ");
    $atividade->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $atividade->bindValue(':atividade_name', "$etapaName");
    $atividade->bindValue(':atividade_data', date('Y-m-d H:i:s'));
    $atividade->execute();

    // Buscar usuários do município
    $usersQuery = $db->prepare("
    SELECT *
    FROM users 
    WHERE user_municipio = :id_municipio AND status = 1 ");
    $usersQuery->bindValue(':id_municipio', $id_municipio, PDO::PARAM_INT);
    $usersQuery->execute();

    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($users)) {
        $notificationTitle = "Atualização do Processo: {$numero_procedimento}";
        $notificationContent = "
            A etapa <strong>{$etapa}</strong> do processo <strong>{$numero_procedimento}</strong> - Núcleo: <strong>{$nucleo_nome}</strong> 
            foi <strong>'Concluída'</strong>.<br>
            {$justificativa}
        ";
        $notificationLink = BASE_URL . "processos/view/{$id_procedimento}?tab={$etapa}";


        foreach ($users as $user) {
            $notification = $db->prepare("
                INSERT INTO tb_notifications_painel 
                (not_codigo, not_user, not_tipo, not_titulo, not_conteudo, not_date, not_link) 
                VALUES (:not_codigo, :not_user, :not_tipo, :not_titulo, :not_conteudo, :not_date, :not_link)
            ");

            $notification->bindValue(':not_codigo', generateNotificationCode($db), PDO::PARAM_STR);
            $notification->bindValue(':not_user', $user['id'], PDO::PARAM_INT);
            $notification->bindValue(':not_tipo', 'Etapa concluída');
            $notification->bindValue(':not_titulo', $notificationTitle);
            $notification->bindValue(':not_conteudo', $notificationContent);
            $notification->bindValue(':not_date', date('Y-m-d H:i:s'));
            $notification->bindValue(':not_link', $notificationLink);
            $notification->execute();

            /*   try {
                // envia email com link 
                require '../PHPMailer-master/PHPMailerAutoload.php';
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->Host = "" . EMAIL_HOST . "";
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = "" . EMAIL . "";
                $mail->Password = "" . EMAIL_PASSWORD . "";
                $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
                $mail->From = "" . EMAIL . "";
                $mail->FromName = NAME;
                // Configuração do remetente e destinatário
                $mail->addAddress($user['email'], $user['name']);

                $mail->IsHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = "$notificationTitle";
                $emailBody = file_get_contents('email_template_notification.html');
                $emailBody = str_replace(
                    [
                        '{{title}}',
                        '{{name}}',
                        '{{etapa}}',
                        '{{notificationContent}}',
                        '{{link}}',
                        '{{year}}',
                        '{{NAME}}'
                    ],
                    [
                        $notificationTitle,
                        $user['name'],
                        $etapa,
                        $notificationContent,
                        $notificationLink,
                        date("Y"),
                        NAME
                    ],
                    $emailBody
                );
                $mail->Body = $emailBody;


                $mail->send();
            } catch (Exception $e) {
                error_log("Erro ao enviar e-mail para {$user['email']}: " . $mail->ErrorInfo);
            }
            */
        }
    }

    json_response_send([
        'status' => 'success',
        'title' => 'Sucesso',
        'message' => "{$etapaName}",
        'icon' => 'success'
    ]);
} catch (Exception $e) {
    json_response_send([
        'status' => 'error',
        'title' => 'Erro',
        'message' => 'Erro ao concluir a etapa: ' . $e->getMessage(),
        'icon' => 'error'
    ]);
}

/**
 * Gera um código único para `not_codigo`
 */
function generateNotificationCode($db)
{
    do {
        // Gera um número aleatório de 10 dígitos
        $code = random_int(1000000000, 9999999999);

        // Verifica se o código já existe no banco de dados
        $stmt = $db->prepare("SELECT COUNT(*) FROM tb_notifications_painel WHERE not_codigo = :code");
        $stmt->bindValue(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        // Confirma se o código já está em uso
        $exists = $stmt->fetchColumn() > 0;
    } while ($exists);

    return (string)$code; // Retorna o código como string
}
