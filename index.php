<?php
/*

use function WpRefs\FixPage\DoChangesToText1;

*/
if (!empty($_GET['test'] ?? $_POST['test'] ?? '') || $_SERVER['SERVER_NAME'] == 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$header_path = __DIR__ . '/../header.php';

if (!file_exists($header_path)) {
    // "I:\mdwiki\mdwiki\public_html\header.php"
    $header_path = __DIR__ . '/../mdwiki/public_html/header.php';
}

include_once $header_path;

$lang         = trim($_POST['lang'] ?? '');
$title        = trim($_POST['title'] ?? '');
$mdwiki_revid = trim($_POST['revid'] ?? '');
$sourcetitle  = trim($_POST['sourcetitle'] ?? '');

$user = $GLOBALS['global_username'] ?? '';
// ---
$submit_or_login = (!empty($user))
    ? "<input class='btn btn-outline-primary' type='submit' value='start'>"
    : "<a class='btn btn-outline-primary' href='/auth/index.php?a=login'>login</a>";
// ---
include_once __DIR__ . '/csrf.php';
include_once __DIR__ . '/work.php';
include_once __DIR__ . '/wikibots/wikitext.php';
// include_once __DIR__ . '/wikibots/save.php';

use function WpRefs\FixPage\DoChangesToText1;
use function WpRefs\WikiText\get_wikipedia_text;
use function WpRefs\csrf\generate_csrf_token;
use function WpRefs\csrf\verify_csrf_token; // if (verify_csrf_token())  {

echo "
    <div class='card'>
        <div class='card-header aligncenter' style='font-weight:bold;'>
            <h3>Fix references in Wikipedia: <a href='https://hashtags.wmcloud.org/?query=mdwiki' target='_blank'>#mdwiki</a></h3>
        </div>
        <div class='card-body'>
";
// ---
$footer = <<<HTML
                </div>
            </div>
        </div>
    </body>
</html>
HTML;
// ---
function make_result($lang, $title, $sourcetitle, $mdwiki_revid)
{
    // ---
    $text = get_wikipedia_text($title, $lang);
    // ---
    if (empty($text)) {
        return <<<HTML
            <h2>Wikitext not found</h2>
        HTML;
    }
    // ---
    $new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);
    //---
    $new_text_sanitized = htmlspecialchars($new_text, ENT_QUOTES, 'UTF-8');
    //---
    $no_changes = (trim($new_text) === trim($text)) ? "true" : "false";
    //---
    return <<<HTML
        <h2>New Text: (no_changes: $no_changes)</h2>
            <textarea name="new_text" rows="15" cols="100">$new_text_sanitized</textarea>
    HTML;
}

if (empty($lang) || empty($title)) {
    //---
    $csrf_token = generate_csrf_token(); // <input name='csrf_token' value="$csrf_token" type="hidden"/>
    //---
    // عرض نموذج لإرسال البيانات إلى text_changes.php
    echo <<<HTML
        <form action='index.php' method='POST'>
            <input name='csrf_token' value="$csrf_token" type="hidden"/>
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
                            $submit_or_login
                        </h4>
                    </div>
                </div>
            </div>
        </form>
    HTML;
    //---
} else {
    if (verify_csrf_token()) {
        echo make_result($lang, $title, $sourcetitle, $mdwiki_revid);
    }
}
//---
echo $footer;
