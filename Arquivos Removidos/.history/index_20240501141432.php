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

<meta name="robots" content="noindex">

<link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico">
<link rel="mask-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111">
<link rel="canonical" href="https://codepen.io/dearsina/pen/GBOpPy">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.4/quill.snow.css">

</head>

<body>
  <div class="row" style="padding:1rem;">
  <div class="col-sm-3">

    <select class="custom-select ql-insertCustomTags">
        <?php
        // Fetch column names from the 'convenios' table using PRAGMA table_info
        $columns = $db->query("PRAGMA table_info(convenios)");
        $columns->execute();

        // Display each column name as a selectable option
        while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$col['name']}' data-marker='[{$col['name']}]' data-title='{$col['name']}' data-colour='warning'>{$col['name']}</option>";
        }
        ?>
    </select>

 
    <p>
    <div id="editor"></div>
    </p>
  </div>
</div>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-delta-to-html@0.8.4/dist/browser/QuillDeltaToHtmlConverter.bundle.js"></script>
<script>
    var Embed = Quill.import('blots/embed');

        class TemplateMarker extends Embed {
        static create(value) {
            let node = super.create(value);

            node.setAttribute('class', 'badge badge-' + value.colour);
            //Set up the badge, and badge colour

            node.setAttribute('data-marker', value.marker);
            //The marker is the $ rel_table[id] reference

            node.setAttribute('data-title', value.title);
            //

            node.innerHTML = value.title;
            //The title is what the user sees in their editor

            return node;
        }

        static value(node) {
            return {
            marker: node.getAttribute('data-marker'),
            title: node.getAttribute('data-title') };

        }}


        TemplateMarker.blotName = 'TemplateMarker';
        TemplateMarker.tagName = 'span';

        Quill.register({
        'formats/TemplateMarker': TemplateMarker });


        var toolbarOptions = ['bold', 'italic', 'underline', 'strike'];

        var options = {
        modules: {
            toolbar: toolbarOptions },

        placeholder: 'This is where the magic happens...',
        theme: 'snow' };


        var quill = new Quill('#editor', options);

        $('.ql-insertCustomTags').on('change', function () {
        let range = quill.getSelection(true);

        quill.insertEmbed(
        range.index,
        //Insert the TemplateMarker in the same range as the cursor is

        'TemplateMarker',
        //This is the name of the Embed

        {
            colour: $(this).find(':selected').data('colour'),
            marker: $(this).find(':selected').data('marker'),
            title: $(this).find(':selected').data('title') }

        //These are the variables to enter
        );

        quill.insertText(range.index + 1, ' ', Quill.sources.USER);
        //Add a space after the marker

        quill.setSelection(range.index + 2, Quill.sources.SILENT);
        //Take the cursor to the end of the inserted TemplateMarker

        $(this).val("");
        //Reset the dropdown
        });

        quill.on('text-change', function (delta, oldDelta, source) {
        var delta = quill.getContents();
        var delta_json = JSON.stringify(delta);
        console.log(delta_json);
        // This is what you store in the DB so that you can edit the template later

        var qdc = new window.QuillDeltaToHtmlConverter(delta.ops, window.opts_ || {});
        // This requires the Quill Delta to HTML converter js

        // customOp is your custom blot op
        // contextOp is the block op that wraps this op, if any. 
        // If, for example, your custom blot is located inside a list item,
        // then contextOp would provide that op. 
        qdc.renderCustomWith(function (customOp, contextOp) {
            if (customOp.insert.type === 'TemplateMarker') {
            let val = customOp.insert.value;
            return val.marker;
            }
        });

        var html = qdc.convert();
        //Convert the Delta JSON to HTML

        console.log(html);
        //This is what will be used to render the template
        //You also need to store this in your DB
        });
    </script>
</body>

</html>