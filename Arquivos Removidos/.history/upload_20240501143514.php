<?php
$targetDir = "uploads/";
$imagePath = $targetDir . basename($_FILES["image"]["name"]);
$response = [];

if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
    $response['status'] = 'success';
    $response['url'] = $imagePath;
} else {
    $response['status'] = 'error';
    $response['error'] = 'Falha ao fazer upload da imagem.';
}

echo json_encode($response);
?>
