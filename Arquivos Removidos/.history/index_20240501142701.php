<?php
require_once('sqlite.php');
$db =  new PDO($dsn);


// Preparação de ambiente e busca de dados
$convenioId = $_GET['convenio'] ?? '';
$convenioData = [];

if (!empty($convenioId)) {
    $stmt = $db->prepare("SELECT * FROM convenios WHERE NR_CONVENIO = :convenioId");
    $stmt->bindParam(':convenioId', $convenioId, PDO::PARAM_INT);
    $stmt->execute();
    $convenioData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <title>Editor de Documentos Avançado</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row" style="padding:1rem;">
            <div class="col-md-6">
                <select class="custom-select ql-insertCustomTags">
                    <?php
                    $columns = $db->query("PRAGMA table_info(convenios)");
                    $columns->execute();
                    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$col['name']}' data-marker='[{$col['name']}]' data-title='{$col['name']}' data-colour='warning'>{$col['name']}</option>";
                    }
                    ?>
                </select>
                <div id="editor" style="height: 400px;"></div>
            </div>
            <div class="col-md-6" id="preview-pane">
                <h3>Preview do Documento</h3>
                <div id="preview-content" style="border:1px solid #ccc; padding:10px; height:400px; overflow-y:auto;"></div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
    <script>
        var Quill = window.Quill;
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block', 'image', 'video'],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'align': [] }],
            ['clean']
        ];

        var options = {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Digite seu texto aqui...',
            theme: 'snow'
        };
        var quill = new Quill('#editor', options);

        function setupHandlers() {
            $('.ql-insertCustomTags').on('change', function () {
                let range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'TemplateMarker', {
                    colour: $(this).find(':selected').data('colour'),
                    marker: $(this).find(':selected').data('marker'),
                    title: $(this).find(':selected').data('title')
                });
                quill.insertText(range.index + 1, ' ', Quill.sources.USER);
                quill.setSelection(range.index + 2, Quill.sources.SILENT);
                $(this).val("");
            });

            quill.on('text-change', function () {
                var htmlContent = document.querySelector('.ql-editor').innerHTML;
                document.getElementById('preview-content').innerHTML = htmlContent;
            });
        }

        setupHandlers();
    </script>
</body>
</html>
