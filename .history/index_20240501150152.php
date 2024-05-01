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
<script src="https://cdn.tiny.cloud/1/hfofmtfpp0gi57e6pusrep3fg46pks9j9oa9elmentmojc3a/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
    <div class="row" style="padding:1rem;">
        <div class="col-sm-12">
            <select class="custom-select" id="insertCustomTags" style="margin-bottom: 10px;">
                <?php
                require_once('sqlite.php');
                $db = new SQLiteDB('convenios.db');
                $columns = $db->query("PRAGMA table_info(convenios)");
                while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$col['name']}' data-marker='%[{$col['name']}]%' data-title='%{$col['name']}%' data-colour='warning'>{$col['name']}</option>";
                }
                ?>
            </select>
            <textarea id="editor"></textarea>
            <button class="btn btn-success" onclick="salvarConteudo()">Salvar</button>
        </div>
    </div>

    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: "30s",
            autosave_prefix: "{path}{query}-{id}-",
            autosave_restore_when_empty: false,
            autosave_retention: "2m",
            image_advtab: true,
            link_list: [
              { title: 'My page 1', value: 'https://www.example.com/page1' },
              { title: 'My page 2', value: 'https://www.example.com/page2' }
            ],
            image_list: [
              { title: 'My image 1', value: 'https://www.example.com/my1.png' },
              { title: 'My image 2', value: 'https://www.example.com/my2.jpg' }
            ],
            image_class_list: [
              { title: 'None', value: '' },
              { title: 'Some class', value: 'class-name' }
            ],
            importcss_append: true,
            file_picker_callback: function (callback, value, meta) {
              /* Provide file and text for the link dialog */
              if (meta.filetype === 'file') {
                callback('https://www.example.com/myfile.pdf', { text: 'My file' });
              }
              /* Provide image and alt text for the image dialog */
              if (meta.filetype === 'image') {
                callback('https://www.example.com/myimage.jpg', { alt: 'My alt text' });
              }
              /* Provide alternative source and posted for the media dialog */
              if (meta.filetype === 'media') {
                callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.example.com/video_poster.jpg' });
              }
            },
            templates: [
                { title: 'Some title 1', description: 'Some desc 1', content: 'My content' },
                { title: 'Some title 2', description: 'Some desc 2', content: 'My content 2' }
            ],
            template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:S]',
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: "mceNonEditable",
            toolbar_mode: 'sliding',
            contextmenu: "link image imagetools table",
        });

        document.getElementById('insertCustomTags').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            tinymce.activeEditor.insertContent('[' + selectedOption.getAttribute('data-marker') + ']');
            this.selectedIndex = 0; // Reset the select after inserting
        });

        function salvarConteudo() {
            var conteudoHtml = tinymce.activeEditor.getContent(); // Obter o HTML do editor
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
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>
</html>
