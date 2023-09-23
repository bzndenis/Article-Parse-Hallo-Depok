
<!DOCTYPE html>
<html>
<head>
    <title>Article Parser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #2C2F33;
            color: #748B9C;
            margin-bottom: 80px; /* Menambahkan margin-bottom untuk memberikan ruang pada footer */
        }
        
        a {
            color: #576CBC;
        }
        
        #parsed-article {
            margin-bottom: 20px;
        }
        
        #footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 80px; /* Menetapkan tinggi footer */
            text-align: center;
            color: #748B9C;
            background-color: #2C2F33;
        }

        @media (max-width: 767px) {
            /* Mengatur posisi footer untuk layar kecil */
            #footer {
                position: relative;
                margin-top: 20px;
            }
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/vmzgpqebnq23hnpw7i0ubynejm8aur0uhmp7benl2c6k6ln6/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#article',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            skin: "oxide-dark",
            content_css: "dark"
        });

        function copyToClipboard() {
            var articleText = document.getElementById("parsed-article").innerText;
            articleText = articleText.replace(/\*/g, ''); // Menghapus karakter '*'
            navigator.clipboard.writeText(articleText).then(function() {
                alert("Artikel berhasil disalin!");
            }, function() {
                alert("Gagal menyalin artikel.");
            });
        }
        
        function copyAsHyperlink() {
            var range = document.createRange();
            range.selectNodeContents(document.getElementById("parsed-article"));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            
            try {
                var successful = document.execCommand('copy');
                var message = successful ? 'Teks dengan gaya hyperlink berhasil disalin!' : 'Gagal menyalin teks dengan gaya hyperlink!';
                alert(message);
            } catch (err) {
                alert('Browser tidak mendukung operasi menyalin teks dengan gaya hyperlink.');
            }
            
            window.getSelection().removeAllRanges();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Article Parser</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="article">Masukan Artikel Disini :</label>
                <textarea id="article" name="article" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button><br>
        </form><br>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article = $_POST['article'];
            
            // Hapus karakter '*' dalam artikel
            $article = str_replace('*', '', $article);
            $article = str_replace('"', '', $article);

            // Tidy the paragraphs
            $article = preg_replace('/<p>\s*(.*?)\s*<\/p>/', '<p>$1</p>', $article, -1);
            $article = preg_replace('/\s{2,}/', ' ', $article);

            // Add "HALLO.DEPOK.ID" before the first paragraph
            //$article = preg_replace('/<p>/', '<p><a href="https://depok.hallo.id/">HALLO.DEPOK.ID - </a> ', $article, 1);

            // Add (HD) after the last paragraph
            //$article = preg_replace('/<\/p>$/', ' <strong>(HD)</strong></p>', $article);

            echo '<h2>Ouput Artikel : </h2>';
            echo '<div id="parsed-article">' . nl2br($article) . '</div>';
        }
        ?>

        <button id="copyButton" class="btn btn-primary" onclick="copyToClipboard()">Salin Artikel Hanya Text</button>
        <button id="copyAsHyperlinkButton" class="btn btn-info" onclick="copyAsHyperlink()">Salin Artikel dengan Hyperlink</button>
        <textarea id="textToCopy" style="display: none;"><?php echo strip_tags($article); ?></textarea>
    </div>
    
    <div id="footer" class="fixed-bottom">
        <p>	
            Made with <span id="icon-love" class="et-pb-icon et-waypoint et_pb_animation_top et-animated">&#10084;</span> by <a href="http://koys.my.id" target="_blank">Bekoy</a>
        </p>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>