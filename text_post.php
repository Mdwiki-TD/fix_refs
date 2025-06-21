<?php
header('Content-Type: text/plain; charset=utf-8');

include_once __DIR__ . '/work.php';

use function WpRefs\FixPage\DoChangesToText1;

$lang         = isset($_POST['lang']) ? trim($_POST['lang']) : '';
$text         = isset($_POST['text']) ? trim($_POST['text']) : '';
$mdwiki_revid = isset($_POST['revid']) ? trim($_POST['revid']) : '';
$sourcetitle  = isset($_POST['sourcetitle']) ? trim($_POST['sourcetitle']) : '';
$title        = isset($_POST['title']) ? trim($_POST['title']) : '';

if (!empty($lang) && !empty($text) && !empty($title)) {
    // استدعاء الدالة التي تجري التعديلات على النص
    $new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);
    if ($new_text === $text) {
        echo 'no changes';
        return;
    }
    echo $new_text;
} else {
    echo 'no text';
}
