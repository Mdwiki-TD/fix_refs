<?php

include_once __DIR__ . '/work.php';
include_once __DIR__ . '/csrf.php';

use function WpRefs\FixPage\fix_page_with_setting;
use function WpRefs\csrf\verify_csrf_token; // if (verify_csrf_token())  {

$fields = ['lang', 'title', 'text', 'revid', 'sourcetitle'];

$data = [];

$final_text = '';

foreach ($fields as $field) {
    $value = trim($_POST[$field] ?? '');
    // ---
    // $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    // ---
    $data[$field] = $value;
    // ---
    // Basic validation for required fields
    if (in_array($field, ['lang', 'title', 'text']) && empty($value)) {
        $final_text = "Missing required field: $field";
        break;
    }
    // ---
}

$lang         = $data['lang'];
$title        = $data['title'];
$text         = $data['text'];
$mdwiki_revid = $data['revid'];
$sourcetitle  = $data['sourcetitle'];


if (!empty($lang) && !empty($title) && !empty($text)) {
    // ---
    // if (verify_csrf_token()) {
    $newtext = fix_page_with_setting(
        $sourcetitle,
        $title,
        $text,
        $lang,
        $mdwiki_revid,
        $move_dots = null,
        $expand = null,
        $add_en_lang = null,
    );
    if (trim($new_text) === trim($text)) {
        $final_text = 'no changes';
    } else {
        $final_text = $new_text;
    }
    // }
} else {
    $final_text = 'no text';
}

if (!empty($final_text)) {

    header('Content-Type: text/plain; charset=utf-8');

    echo $final_text;
}
