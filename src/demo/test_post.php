<?php

use App\Run;
use App\Csrf;

include_once __DIR__ . '/../bootstrap.php';

$fields = ['lang', 'title', 'text', 'revid', 'sourcetitle'];

$data = [];

$finalText = '';

foreach ($fields as $field) {
    $value = trim($_POST[$field] ?? '');
    $data[$field] = $value;
    if (in_array($field, ['lang', 'title', 'text']) && empty($value)) {
        $finalText = "Missing required field: $field";
        break;
    }
}

$lang         = $data['lang'];
$title        = $data['title'];
$text         = $data['text'];
$mdwikiRevid  = $data['revid'];
$sourcetitle  = $data['sourcetitle'];

if (!empty($lang) && !empty($title) && !empty($text)) {
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
    if (trim($newText) === trim($text)) {
        $finalText = 'no changes';
    } else {
        $finalText = $newText;
    }
} else {
    $finalText = 'no text';
}

if (!empty($finalText)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo $finalText;
}
