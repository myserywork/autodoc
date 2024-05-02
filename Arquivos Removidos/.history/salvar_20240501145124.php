<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['html'])) {
    $conteudoHtml = $_POST['html'];

    $conteudoHtml = str_replace('<span class="badge badge-warning" data-marker="','', $conteudoHtml);
    $conteudoHTML = str_replace('">﻿<span contenteditable="false">', '', $conteudoHtml);
    
    

    // Utilizando expressão regular para extrair todas as variáveis no formato %VARIAVEL%
    preg_match_all('/%([A-Z0-9_]+)%/', $conteudoHtml, $matches);

    // $matches[0] contém todas as variáveis encontradas, incluindo os delimitadores %
    // $matches[1] contém apenas os nomes das variáveis sem os delimitadores %

    if (!empty($matches[0])) {
        echo json_encode(['status' => 'success', 'variables' => $matches[0]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nenhuma variável encontrada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum dado recebido.']);
}
?>
