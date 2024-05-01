<?php

$db = new PDO('sqlite:convenios.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['idConvenio'])) {
        $convenioId = filter_input(INPUT_POST, 'idConvenio', FILTER_SANITIZE_NUMBER_INT);

        $stmt = $db->prepare("SELECT * FROM convenios WHERE NR_CONVENIO = :convenioId");
        $stmt->bindValue(':convenioId', $convenioId, PDO::PARAM_INT);  // Correct constant for PDO
        $stmt->execute();

        $convenio = $stmt->fetch(PDO::FETCH_ASSOC);  // Correct fetching method for PDO
        if (!$convenio) {
            echo json_encode(['status' => 'error', 'message' => 'Convenio not found.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Convenio ID not provided.']);
        exit;
    }

    if (isset($_POST['html'])) {
        $conteudoHtml = $_POST['html'];

        // Processing HTML content
        $conteudoHtml = preg_replace_callback(
            '/<span[^>]*data-marker="([^"]+)"[^>]*>.*?<\/span>/',
            function ($matches) {
                return htmlspecialchars($matches[1]);
            },
            $conteudoHtml
        );

        // Replacing placeholders with actual data
        foreach ($convenio as $key => $value) {
            $conteudoHtml = str_replace("%[$key]%", htmlspecialchars($value), $conteudoHtml);
        }

        file_put_contents('conteudo.html', $conteudoHtml);
        echo json_encode(['status' => 'success', 'html' => $conteudoHtml]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'HTML content not received.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
