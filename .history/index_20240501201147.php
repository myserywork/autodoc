
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

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <form id="convenioForm">
                    <div class="form-group">
                        <label for="nomeModelo">Nome do Modelo:</label>
                        <input type="text" id="nomeModelo" class="form-control" name="nome_modelo">
                    </div>
                    <div class="form-group">
                        <label for="tipoConvenio">Tipo:</label>
                        <input type="text" id="tipoConvenio" class="form-control" name="tipo_convenio">
                    </div>
                    <div class="form-group">
                        <label for="selectConvenio">Convênio:</label>
                        <select class="custom-select ql-insertCustomTags" style="margin-bottom: 10px;">
                            <?php
                            require_once('sqlite.php');
                            $db = new SQLiteDB('convenios.db');
                            $columns = $db->query("PRAGMA table_info(convenios)");
                            while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$col['name']}' data-marker='%[{$col['name']}]%' data-title='%{$col['name']}%' data-colour='warning'>{$col['name']}</option>";
                            }
                            ?>
                        </select>
                       
                    </div>
                  
                    <div id="editor" class="form-control" style="height: 200px;"></div>
                    <button type="button" class="btn btn-success mt-3" onclick="salvarConteudo()">Salvar</button>
                </form>
            </div>
        </div>
    </div>


    <style>
        #resize-image-modal .modal-body img {
            width: 100%; /* Default to full width */
            display: block;
            margin: 0 auto;
        }
    </style>


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
            data: {html: conteudoHtml, idConvenio : <?= $convenioId ?>}, // Enviar HTML como dado
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
                    uploadAndResizeImage(file);
                }
            };
        }


        function selectLocalImage() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.click();
            input.onchange = () => {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        $('#preview-image').attr('src', e.target.result);
                        $('#preview-image').resizable({
                            aspectRatio: true,
                            handles: 'se'
                        });
                        $('#resize-image-modal').modal('show');
                    };
                    reader.readAsDataURL(file);
                }
            };
        }


        function applyImageChanges() {
            const imgSrc = $('#preview-image').attr('src');
            const formData = new FormData();
            formData.append('image', imgSrc); // Sending image data as base64
            const range = quill.getSelection(true);

            // Simulated backend URL
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    quill.insertEmbed(range.index, 'image', result.url);
                    quill.setSelection(range.index + 1);
                } else {
                    console.error(result.error);
                }
            })
            .catch(error => {
                console.error('Error uploading image:', error);
            });

            $('#resize-image-modal').modal('hide');
        }

    </script>
</body>
</html>
