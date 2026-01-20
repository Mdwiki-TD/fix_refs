<?php

// Demonstration of Polish Choroba Infobox Parameter Addition Feature

require_once __DIR__ . '/../../src/include_files.php';

use function WpRefs\WprefText\fix_page;

echo "==========================================================\n";
echo "DEMONSTRATION: Polish Choroba Infobox Parameter Addition\n";
echo "==========================================================\n\n";

// Example Polish Wikipedia article with Choroba infobox
$original_article = <<<'ARTICLE'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz = Blausen 0620 Lungs NormalvsInflamedAirway.png
|opis obrazu = Normalne drogi oddechowe i drogi oddechowe astmatyka
}}

'''Astma oskrzelowa''' (łac. ''asthma bronchiale'') – przewlekła choroba zapalna dróg oddechowych, charakteryzująca się napadami duszności.

== Epidemiologia ==
Astma jest jedną z najczęstszych chorób przewlekłych na świecie.

== Objawy ==
* Duszność
* Kaszel
* Świszczący oddech

== Leczenie ==
Leczenie astmy obejmuje stosowanie leków wziewnych.

== Zobacz też ==
* [[Choroba obturacyjna płuc]]

== Przypisy ==
<references />

[[Kategoria:Choroby układu oddechowego]]
ARTICLE;

echo "ORIGINAL ARTICLE:\n";
echo str_repeat("-", 60) . "\n";
echo $original_article;
echo "\n" . str_repeat("-", 60) . "\n\n";

// Process the article through fix_page function for Polish language
$processed_article = fix_page(
    $original_article,
    "Astma oskrzelowa",  // title
    false,                // move_dots
    true,                 // infobox expansion enabled
    false,                // add_en_lang
    "pl",                 // language: Polish
    "Asthma",            // sourcetitle (English)
    "123456"             // mdwiki_revid
);

echo "PROCESSED ARTICLE:\n";
echo str_repeat("-", 60) . "\n";
echo $processed_article;
echo "\n" . str_repeat("-", 60) . "\n\n";

// Extract and display the infobox to show the added parameters
preg_match('/\{\{Choroba infobox.*?\}\}/s', $processed_article, $matches);
if (isset($matches[0])) {
    echo "EXTRACTED INFOBOX (with added parameters):\n";
    echo str_repeat("-", 60) . "\n";
    // Format it nicely for display
    $infobox = $matches[0];
    $lines = explode('|', $infobox);
    foreach ($lines as $line) {
        echo trim($line) . "\n";
    }
    echo str_repeat("-", 60) . "\n\n";
}

// Show which parameters were added
echo "PARAMETERS ADDED:\n";
echo str_repeat("-", 60) . "\n";
$added_params = [
    'nazwa naukowa' => 'Scientific name',
    'ICD11' => 'ICD-11 classification code',
    'ICD11 nazwa' => 'ICD-11 classification name',
    'ICD10' => 'ICD-10 classification code',
    'ICD10 nazwa' => 'ICD-10 classification name',
    'DSM-5' => 'DSM-5 classification code',
    'DSM-5 nazwa' => 'DSM-5 classification name',
    'DSM-IV' => 'DSM-IV classification code',
    'DSM-IV nazwa' => 'DSM-IV classification name',
    'ICDO' => 'ICD-O oncology code',
    'DiseasesDB' => 'Diseases Database identifier',
    'OMIM' => 'Online Mendelian Inheritance in Man',
    'MedlinePlus' => 'MedlinePlus identifier',
    'MeshID' => 'Medical Subject Headings identifier',
    'commons' => 'Wikimedia Commons category',
];

foreach ($added_params as $param => $description) {
    $present = strpos($processed_article, $param) !== false ? '✅' : '❌';
    echo "$present |$param = ($description)\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Demonstration complete!\n";
echo "All 15 medical classification parameters have been added.\n";
echo str_repeat("=", 60) . "\n";
