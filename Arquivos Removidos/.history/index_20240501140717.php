<!DOCTYPE html>
<html lang='pt-BR'>

<head>
    <meta charset='UTF-8'>
    <title>Document Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.4/quill.snow.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: white;
            font-size: 24px;
        }
        .watermark {
            position: absolute;
            z-index: -1;
            font-size: 50px;
            color: rgba(0, 0, 0, 0.1);
            transform: rotate(-45deg);
            top: 200px;
            width: 100%;
            text-align: center;
        }
        .editor-container, .preview-container {
            border: 1px solid #ccc;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">Document Editor</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 editor-container">
                <select class="custom-select ql-insertCustomTags">
                    <?php
                    require_once('sqlite.php');
                    $db = new SQLiteDB('convenios.db');
                    $columns = $db->query("PRAGMA table_info(convenios)");
                    $columns->execute();
                    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$col['name']}' data-marker='[{$col['name']}]' data-title='{$col['name']}' data-colour='warning'>{$col['name']}</option>";
                    }
                    ?>
                </select>
                <div id="editor" style="height: 300px;"></div>
            </div>
            <div class="col-md-6 preview-container">
                <div class="watermark">Preview</div>
                <div id="preview" style="padding: 20px;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: ['bold', 'italic', 'underline', 'strike']
            }
        });

        quill.on('text-change', function() {
            var html = quill.root.innerHTML;
            document.getElementById('preview').innerHTML = html;
        });

        $('.ql-insertCustomTags').on('change', function () {
            let value = $(this).val();
            let range = quill.getSelection();
            if (range) {
                quill.insertText(range.index, value, 'user');
                $(this).val(''); // Reset the dropdown after insertion
            }
        });
    </script>
</body>
</html>
