<?php

use App\Wikibots\Wikitext;
use App\Csrf;
use App\Run;

$headerPath = __DIR__ . '/../header.php';
include_once __DIR__ . '/bootstrap.php';

if (!file_exists($headerPath)) {
    // "I:/MD_TOOLS/MDWIKI_MAIN_REPO/src/public_html/header.php"
    $headerPath = dirname(dirname(dirname(dirname(__DIR__)))) . '/MDWIKI_MAIN_REPO/src/public_html/header.php';
}

include_once $headerPath;

$lang         = trim($_POST['lang'] ?? '');
$title        = trim($_POST['title'] ?? '');
$mdwikiRevid  = trim($_POST['revid'] ?? '');
$sourcetitle  = trim($_POST['sourcetitle'] ?? '');

$user = $GLOBALS['global_username'] ?? '';

$submitOrLogin = (!empty($user))
    ? "<input class='btn btn-outline-primary' type='submit' value='start'>"
    : "<a class='btn btn-outline-primary' href='/auth/login.php'>login</a>";


echo "
    <div class='card'>
        <div class='card-header aligncenter' style='font-weight:bold;'>
            <h3>Fix references in Wikipedia: <a href='https://hashtags.wmcloud.org/?query=mdwiki' target='_blank'>#mdwiki</a></h3>
        </div>
        <div class='card-body'>
";

$footer = <<<HTML
                </div>
            </div>
        </div>
    </body>
</html>
HTML;

function make_result(string $lang, string $title, string $sourcetitle, int|string $mdwikiRevid): string
{

    $text = Wikitext::get_wikipedia_text($title, $lang);

    if (empty($text)) {
        return <<<HTML
            <h2>Wikitext not found</h2>
        HTML;
    }

    $newText = Run::fixPgeWithSetting(
        $sourcetitle,
        $title,
        $text,
        $lang,
        $mdwikiRevid,
        null,
        null,
        null,
    );

    $newTextSanitized = htmlspecialchars($newText, ENT_QUOTES, 'UTF-8');

    $noChanges = (trim($newText) === trim($text)) ? "true" : "false";

    return <<<HTML
        <h2>New Text: (no_changes: $noChanges)</h2>
            <textarea name="new_text" rows="15" cols="100">$newTextSanitized</textarea>
    HTML;
}

if (empty($lang) || empty($title)) {

    $csrfToken = Csrf::generateToken(); // <input name='csrf_token' value="$csrfToken" type="hidden"/>

    // عرض نموذج لإرسال البيانات إلى text_changes.php
    echo <<<HTML
        <form action='index.php' method='POST'>
            <input name='csrf_token' value="$csrfToken" type="hidden"/>
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
                                <span class='input-group-text'>mdwiki title</span>
                            </div>
                            <input class='form-control' type='text' id='sourcetitle' name='sourcetitle' value='' />
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='input-group mb-3'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'>revid</span>
                            </div>
                            <input class='form-control' type='text' id='revid' name='revid' value='' />
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-3'>
                        <h4 class='aligncenter'>
                            $submitOrLogin
                        </h4>
                    </div>
                </div>
            </div>
        </form>
    HTML;

} else {
    if (Csrf::verifyToken()) {
        echo make_result($lang, $title, $sourcetitle, $mdwikiRevid);
    }
}

echo $footer;
