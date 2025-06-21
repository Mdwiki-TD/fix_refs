<!DOCTYPE html>
<HTML lang=en dir=ltr data-bs-theme="light" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Tools</title>
    <link href='https://tools-static.wmflabs.org/cdnjs/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
    <script src='https://tools-static.wmflabs.org/cdnjs/ajax/libs/jquery/3.7.0/jquery.min.js'></script>
    <script src='https://tools-static.wmflabs.org/cdnjs/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js'></script>
</head>

<?php

echo "
    <body>
        <div id='maindiv' class='container'>
            <div class='card'>
                <div class='card-header aligncenter' style='font-weight:bold;'>
                    input infos
                </div>
                <div class='card-body'>
";

// ---
$test_text = file_get_contents(__DIR__ . '/test.php.md');
// ---
// عرض نموذج لإرسال البيانات إلى text_changes.php
echo <<<HTML
    <form action='text_post.php' method='POST'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <span class='input-group-text'>Langcode</span>
                        </div>
                        <input class='form-control' type='text' name='lang' id='lang' value='ja' required />
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <span class='input-group-text'>title</span>
                        </div>
                        <input class='form-control' type='text' id='title' name='title' value='利用者:Doc James/Rh血液型不適合' />
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <span class='input-group-text'>sourcetitle</span>
                        </div>
                        <input class='form-control' type='text' id='sourcetitle' name='sourcetitle' value='Rhesus disease' required />
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <span class='input-group-text'>revid</span>
                        </div>
                        <input class='form-control' type='text' id='revid' name='revid' value='1457313' required />
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <h4 class='aligncenter'>
                        <input class='btn btn-outline-primary' type='submit' value='start'>
                    </h4>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Text:</label>
            <textarea id="text" name="text" rows="5" class="form-control" required>$test_text</textarea>
        </div>
    </form>
HTML;
echo <<<HTML
                </div>
            </div>
        </div>
    </body>
</html>
HTML;

?>
