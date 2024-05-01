<!DOCTYPE html>
<html lang='pt-BR'>

<head>
    <meta charset='UTF-8'>
    <title>Government Document Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.4/quill.snow.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            padding: 10px;
            text-align: center;
            background: #004085;
            color: white;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 3px solid #ffc107;
        }
        .watermark {
            position: absolute;
            z-index: -1;
            font-size: 100px;
            color: rgba(0, 0, 255, 0.1);
            transform: rotate(-45deg);
            top: 50%;
            width: 100%;
            text-align: center;
            user-select: none; /* prevents text selection */
        }
        .editor-container, .preview-container {
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            height: 400px;
            overflow-y: auto; /* Adds scroll to containers */
        }
        #editor {
            height: 350px; /* Adjust height for padding */
        }
    </style>
</head>

<body>
    <div class="header">Government Document Editor</div>
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
                <div id="editor"></div>
            </div>
            <div class="col-md-6 preview-container">
                <div class="watermark">CONFIDENTIAL</div>
                <div id="preview"></div>
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
            let range = quill.getSelection(true);
            if (range) {
                quill.insertText(range.index, ' ' + value + ' ', { bold: true, color: 'red' }, Quill.sources.USER);
                $(this).val(''); // Reset the dropdown after insertion
            }
        });
    </script>
</body>
</html>
