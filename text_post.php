<?php
header('Content-Type: text/plain; charset=utf-8');

include_once __DIR__ . '/work.php';

use function WpRefs\FixPage\DoChangesToText1;

$lang         = trim($_POST['lang'] ?? '');
$title        = trim($_POST['title'] ?? '');
$text         = trim($_POST['text'] ?? '');
$mdwiki_revid = trim($_POST['revid'] ?? '');
$sourcetitle  = trim($_POST['sourcetitle'] ?? '');

if (!empty($lang) && !empty($text) && !empty($title)) {
    // ---
    $new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);
    // ---
    // ---
    if ($new_text === $text) {
        echo 'no changes';
        return;
    }
    echo $new_text;
} else {
    echo 'no text';
}
