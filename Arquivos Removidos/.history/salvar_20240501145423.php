<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['html'])) {
    $conteudoHtml = $_POST['html'];

    // Utilizando expressão regular para extrair valores do atributo data-marker dentro de tags <span>
    preg_match_all('/<span[^>]*data-marker="([^"]+)"[^>]*>/', $conteudoHtml, $matches);

    // $matches[0] contém todas as ocorrências das tags span com data-marker
    // $matches[1] contém apenas os valores dos atributos data-marker

    if (!empty($matches[1])) {
        echo json_encode(['status' => 'success', 'variables' => $matches[1]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nenhuma variável encontrada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum dado recebido.']);
}
?>
