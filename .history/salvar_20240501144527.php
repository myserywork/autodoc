<?php
require_once('sqlite.php');
$db = new SQLiteDB('convenios.db');

$convenioId = $_GET['convenio'] ?? null;

if ($convenioId) {
    $stmt = $db->query("SELECT * FROM convenios WHERE NR_CONVENIO = $convenioId");
    $convenio = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['html'])) {
    $conteudoHtml = $_POST['html'];

    if (!empty($convenio)) {
        // Substitui as variáveis marcadas no HTML pelos valores correspondentes do banco de dados
        foreach ($convenio as $key => $value) {
            $conteudoHtml = preg_replace("/%{$key}%/", $value, $conteudoHtml);
        }
    }

    if (file_put_contents('conteudo.html', $conteudoHtml)) {
        echo json_encode(['status' => 'success', 'message' => 'Conteúdo salvo com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar o conteúdo.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum dado recebido.']);
}
?>
