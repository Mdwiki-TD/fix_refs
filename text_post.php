<?php
header('Content-Type: text/plain; charset=utf-8');

include_once __DIR__ . '/work.php';

use function WpRefs\FixPage\DoChangesToText1;
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

// مصفوفة لتخزين القيم المنظفة
$data = [];

// تنظيف القيم: إزالة الفراغات + منع XSS
foreach ($fields as $field) {
    $value = $_POST[$field] ?? '';
    $value = trim($value);
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $data[$field] = $value;
}

// استخراج المتغيرات من المصفوفة
$lang         = $data['lang'];
$title        = $data['title'];
$text         = $data['text'];
$mdwiki_revid = $data['revid'];
$sourcetitle  = $data['sourcetitle'];

// التحقق من وجود الحقول المطلوبة
if ($lang !== '' && $title !== '' && $text !== '') {
    $new_text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);

    if ($new_text === $text) {
        echo 'no changes';
        return;
    }

    echo $new_text;
} else {
    echo 'no text';
}
