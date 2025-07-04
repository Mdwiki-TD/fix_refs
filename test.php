<?php
if (isset($_GET['test']) || $_SERVER['SERVER_NAME'] == 'localhost') {
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
include_once __DIR__ . '/csrf.php';

use function WpRefs\csrf\generate_csrf_token;

$test_text = file_get_contents(__DIR__ . '/test.php.md') ?? '';
// ---
$user = $GLOBALS['global_username'] ?? '';
// ---
$submit_or_login = (!empty($user))
    ? "<input class='btn btn-outline-primary' type='submit' value='start'>"
    : "<a class='btn btn-outline-primary' href='/auth/index.php?a=login'>login</a>";
// ---
$csrf_token = generate_csrf_token(); // <input name='csrf_token' value="$csrf_token" type="hidden"/>
//---
?>

<div class='card-header aligncenter' style='font-weight:bold;'>
    input infos
</div>
<div class='card-body'>
    <form action='text_post.php' method='POST'>
        <input name='csrf_token' value="<?php echo $csrf_token; ?>" type="hidden"/>
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
                    <div class='input-group mb-3'>
                        <div class='input-group-prepend'>
                            <span class='input-group-text'>test</span>
                        </div>
                        <input class='form-control' type='text' id='test' name='test' value='' />
                    </div>
                </div>
                <div class='col-md-3'>
                    <h4 class='aligncenter'>
                        <?php echo $submit_or_login; ?>
                    </h4>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Text:</label>
            <textarea id="text" name="text" rows="5" class="form-control" required><?php echo $test_text; ?></textarea>
        </div>
    </form>
</div>
