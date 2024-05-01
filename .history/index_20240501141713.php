<?php
require_once('sqlite.php');
$db = new SQLiteDB('convenios.db');
$convenioId = filter_input(INPUT_GET, 'convenio', FILTER_SANITIZE_NUMBER_INT);

if ($convenioId) {
    $stmt = $db->prepare("SELECT * FROM convenios WHERE NR_CONVENIO = ?");
    $stmt->execute([$convenioId]);
    $convenio = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<script>var convenio = " . json_encode($convenio, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ";</script>";
}

?>
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <title>Editor de Documentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.4/quill.snow.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <select class="custom-select ql-insertCustomTags">
                    <?php
                    $columns = $db->query("PRAGMA table_info(convenios)");
                    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$col['name']}' data-marker='[{$col['name']}]' data-title='{$col['name']}' data-colour='warning'>{$col['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-9">
                <div id="editor"></div>
            </div>
        </div>
        <div id="preview" class="mt-3">
            <h3>Preview do Documento:</h3>
            <div id="document-preview"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: { toolbar: ['bold', 'italic', 'underline', 'strike'] }
        });

        $('.ql-insertCustomTags').on('change', function () {
            var tag = $(this).find(':selected').data('marker');
            var range = quill.getSelection(true);
            quill.insertText(range.index, tag, { 'bold': true }, Quill.sources.USER);
            quill.setSelection(range.index + tag.length, Quill.sources.SILENT);
            $(this).val("");
        });

        // Atualiza o preview do documento a cada mudança no editor
        quill.on('text-change', function () {
            var text = quill.getText();
            $('#document-preview').text(text);
        });
    </script>
</body>
</html>
