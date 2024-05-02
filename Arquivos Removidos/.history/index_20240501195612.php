
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#editor', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    [{ 'color': [] }, { 'background': [] }],
                    ['clean']
                ]
            },
            placeholder: 'Digite o conteúdo aqui...',
            theme: 'snow'
        });

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

        function uploadAndResizeImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.onload = function () {
                    const range = quill.getSelection();
                    const resize = window.prompt('Enter custom width (e.g., "300px" or "100%"):');
                    if(resize) {
                        img.style.width = resize;
                    }
                    const align = window.prompt('Enter alignment (left, center, right):');
                    if(align) {
                        img.style.display = 'block';
                        img.style.marginLeft = align === 'center' ? 'auto' : '0';
                        img.style.marginRight = align === 'center' ? 'auto' : '0';
                        img.style.float = align === 'left' ? 'left' : align === 'right' ? 'right' : 'none';
                    }
                    const formData = new FormData();
                    formData.append('image', file);
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
                };
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
