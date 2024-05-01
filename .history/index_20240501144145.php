
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
<title>Editor de Convênios</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
</head>

<body>
    <div class="row" style="padding:1rem;">
        <div class="col-sm-12">
            <select class="custom-select ql-insertCustomTags" style="margin-bottom: 10px;">
                <?php
                require_once('sqlite.php');
                $db = new SQLiteDB('convenios.db');
                $columns = $db->query("PRAGMA table_info(convenios)");
                while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$col['name']}' data-marker='[{$col['name']}]' data-title='{$col['name']}' data-colour='warning'>{$col['name']}</option>";
                }
                ?>
            </select>
            <div id="editor"></div>
            <button class="btn btn-success" onclick="salvarConteudo()">Salvar</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
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
            ['link', 'image', 'video'],
            [{ 'color': [] }, { 'background': [] }],
            ['clean']
        ];

        var options = {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Digite o conteúdo aqui...',
            theme: 'snow'
        };

        var quill = new Quill('#editor', options);


        $('.ql-insertCustomTags').on('change', function () {
        let selectedOption = $(this).find(':selected');
        let range = quill.getSelection(true);

        quill.insertEmbed(
            range.index,
            'TemplateMarker',
            {
                colour: selectedOption.data('colour'),
                marker: selectedOption.data('marker'),
                title: selectedOption.data('title')
            },
            Quill.sources.USER
        );

        quill.insertText(range.index + 1, ' ', Quill.sources.USER);
        quill.setSelection(range.index + 2, Quill.sources.SILENT);
        $(this).val("");
    });

        function salvarConteudo() {
        var conteudoHtml = quill.root.innerHTML; // Obter o HTML do editor
        $.ajax({
            type: "POST",
            url: "salvar.php", // URL do script PHP para salvar o conteúdo
            data: {html: conteudoHtml}, // Enviar HTML como dado
            success: function(response) {
                alert('Conteúdo salvo com sucesso!');
            },
            error: function() {
                alert('Erro ao salvar o conteúdo.');
            }
        });
    }


        quill.getModule('toolbar').addHandler('image', () => {
            selectLocalImage();
        });

        function selectLocalImage() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.click();
            input.onchange = () => {
                const file = input.files[0];
                if (file) {
                    uploadImage(file);
                }
            };
        }

        function uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', result.url);
                } else {
                    console.error(result.error);
                }
            })
            .catch(error => {
                console.error('Erro ao fazer upload da imagem:', error);
            });
        }
    </script>
</body>
</html>
