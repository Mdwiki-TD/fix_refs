<?php

use function WpRefs\FixPage\fix_page_with_setting;
use function WpRefs\csrf\verify_csrf_token;

include_once __DIR__ . '/work.php';
include_once __DIR__ . '/csrf.php';

$fields = ['lang', 'title', 'text', 'revid', 'sourcetitle'];

$data = [];

$finalText = '';

foreach ($fields as $field) {
    $value = trim($_POST[$field] ?? '');
    // ---
    // $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    // ---
    $data[$field] = $value;
    // ---
    // Basic validation for required fields
    if (in_array($field, ['lang', 'title', 'text']) && empty($value)) {
        $finalText = "Missing required field: $field";
        break;
    }
    // ---
}

$lang         = $data['lang'];
$title        = $data['title'];
$text         = $data['text'];
$mdwikiRevid = $data['revid'];
$sourcetitle  = $data['sourcetitle'];


if (!empty($lang) && !empty($title) && !empty($text)) {
    // ---
    // if (verify_csrf_token()) {
    $newText = fix_page_with_setting(
        $sourcetitle,
        $title,
        $text,
        $lang,
        $mdwikiRevid,
        null,
        null,
        null,
    );
    if (trim($newText) === trim($text)) {
        $finalText = 'no changes';
    } else {
        $finalText = $newText;
    }
    // }
} else {
    $finalText = 'no text';
}

if (!empty($finalText)) {

    header('Content-Type: text/plain; charset=utf-8');

    echo $finalText;
}
