<?php

include_once __DIR__ . '/work.php';
include_once __DIR__ . '/csrf.php';

use function WpRefs\FixPage\DoChangesToText1;
use function WpRefs\csrf\verify_csrf_token; // if (verify_csrf_token())  {
/*
$lang         = trim($_POST['lang'] ?? '');
$title        = trim($_POST['title'] ?? '');
$text         = trim($_POST['text'] ?? '');
$mdwiki_revid = trim($_POST['revid'] ?? '');
$sourcetitle  = trim($_POST['sourcetitle'] ?? '');

if (!empty($lang) && !empty($text) && !empty($title)) {
    // ---
    $new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);
    // ---
    if ($new_text === $text) {
        echo 'no changes';
        return;
    }
    echo $new_text;
} else {
    echo 'no text';
}*/

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
	$new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);

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
