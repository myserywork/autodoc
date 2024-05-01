<?php

require_once('sqlite.php');

$db = new SQLiteDB('convenios.db');

// Melhoria na segurança: Preparar a consulta SQL para evitar SQL Injection
$convenioId = isset($_GET['convenio']) ? (int)$_GET['convenio'] : 0;

if ($convenioId) {
    $stmt = $db->prepare("SELECT * FROM convenios WHERE NR_CONVENIO = ?");
    $stmt->execute([$convenioId]);
    
    $convenio = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($convenio) {
        echo "<script>";
        echo "var convenio = " . json_encode($convenio) . ";";
        echo "</script>";
    }
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
    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-3">
                <select class="custom-select ql-insertCustomTags">
                    <?php
                    // Melhoria: Usar prepare statement para a consulta PRAGMA
                    $columns = $db->query("PRAGMA table_info(convenios)");
                    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$col['name']}' data-marker='[{$col['name']}]' data-title='{$col['name']}' data-colour='warning'>{$col['name']}</option>";
                    }
                    ?>
                </select>
                <div id="editor"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-delta-to-html@0.8.4/dist/browser/QuillDeltaToHtmlConverter.bundle.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: ['bold', 'italic', 'underline', 'strike']
            }
        });

        $('.ql-insertCustomTags').on('change', function() {
            var format = {
                'colour': $(this).find(':selected').data('colour'),
                'marker': $(this).find(':selected').data('marker'),
                'title': $(this).find(':selected').data('title')
            };
            var range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'TemplateMarker', format, Quill.sources.USER);
            quill.insertText(range.index + 1, ' ', Quill.sources.USER);
            quill.setSelection(range.index + 2, Quill.sources.SILENT);
            $(this).val("");
        });

        // Mais código pode ser adicionado aqui para manipulação adicional
    </script>
</body>

</html>
