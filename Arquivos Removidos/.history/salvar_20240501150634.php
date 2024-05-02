<?php


require_once('sqlite.php');

$db = new SQLiteDB('convenios.db');

$convenioId = $_GET['convenio'];

if (isset($convenioId)) {
    $stmt = $db->query("SELECT * FROM convenios WHERE NR_CONVENIO = $convenioId");
    $convenio = $stmt->execute();
     
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['html'])) {
    $conteudoHtml = $_POST['html'];

    // Utilizando expressão regular para substituir <span> que contém data-marker pelo seu valor
    $conteudoHtml = preg_replace_callback(
        '/<span[^>]*data-marker="([^"]+)"[^>]*>.*?<\/span>/',
        function ($matches) {
            // $matches[1] é o valor do atributo data-marker
            return $matches[1];
        },
        $conteudoHtml
    );


    if ($conteudoHtml) {
        file_put_contents('conteudo.html', $conteudoHtml);
        echo json_encode(['status' => 'success', 'html' => $conteudoHtml]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao processar o HTML.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum dado recebido.']);
}
?>
