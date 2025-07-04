<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../../include_files.php';

use function WpRefs\Bots\Sections\fix_sections_titles;

$texts = [
    "ru" => <<<'TXT'
        == Ссылки  ==

        ====Ссылки====

        == Примечания 3 ==
    TXT,
    "sw" => <<<'TXT'
        == Marejeleo 1 ==

        ====Marejeleo====

        === Marejeleo ===
    TXT,
];

foreach ($texts as $lang => $text) {
    echo "lang $lang:\n";
    $new_text = fix_sections_titles($text, $lang);

    if ($new_text != $text) {
        echo "Changes made\n";
    } else {
        echo "No changes made\n";
    }

    echo "new_text: $new_text:\n";
}
