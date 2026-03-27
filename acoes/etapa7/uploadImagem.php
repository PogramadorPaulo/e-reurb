<?php
if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../assets/tema/images/';
    $fileName = uniqid() . '-' . basename($_FILES['file']['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        echo json_encode(['location' => 'uploads/' . $fileName]); // URL da imagem
        exit;
    }
}

http_response_code(400);
echo json_encode(['error' => 'Falha no upload']);
