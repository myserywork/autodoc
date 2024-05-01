
<?php

require_once('sqlite.php');

$db = new SQLiteDB('convenios.db');

$convenioId = $_GET['convenio'];

if (isset($convenioId)) {
    $stmt = $db->query("SELECT * FROM convenios WHERE NR_CONVENIO = $convenioId");
    $stmt->execute();
    
    echo "<script>";
    echo "var convenio = " . json_encode($stmt->fetch(PDO::FETCH_ASSOC)) . ";";
    echo "</script>";
}

?>

<!DOCTYPE html>
<html lang='pt-BR'>

<head>

<meta charset='UTF-8'>
<title>Editor de Documentos</title>

<meta name="robots" content="noindex">

<link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico">
<link rel="mask-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111">
<link rel="canonical" href="https://codepen.io/dearsina/pen/GBOpPy">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.4/quill.snow.css">

</head>

<body>
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <title>Editor de Documentos</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.4/quill.snow.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row" style="padding:1rem;">
            <div class="col-sm-6">
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
            <div class="col-sm-6" id="preview-pane">
                <h3>Preview do Documento</h3>
                <div id="preview-content" style="border:1px solid #ccc; padding:10px; height:400px; overflow-y:auto;"></div>
            </div>
        </div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-delta-to-html@0.8.4/dist/browser/QuillDeltaToHtmlConverter.bundle.js"></script><script>
    var Embed = Quill.import('blots/embed');
    class TemplateMarker extends Embed {
        static create(value) {
            let node = super.create(value);
            node.setAttribute('class', 'badge badge-' + value.colour);
            node.setAttribute('data-marker', value.marker);
            node.setAttribute('data-title', value.title);
            node.innerHTML = value.title;
            return node;
        }
        static value(node) {
            return {
                marker: node.getAttribute('data-marker'),
                title: node.getAttribute('data-title')
            };
        }
    }
    TemplateMarker.blotName = 'TemplateMarker';
    TemplateMarker.tagName = 'span';
    Quill.register('formats/TemplateMarker', TemplateMarker);

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link', 'image']
    ];
    var options = {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Digite seu texto aqui...',
        theme: 'snow'
    };
    var quill = new Quill('#editor', options);

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
        var htmlContent = qdc.convert();
        document.getElementById('preview-content').innerHTML = htmlContent;
    });
</script>
</body>

</html>