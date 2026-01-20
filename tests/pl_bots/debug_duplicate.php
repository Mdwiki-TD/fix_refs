<?php

// Debug test to see what's happening with duplicate parameters

require_once __DIR__ . '/../../src/include_files.php';

use function WpRefs\PL\FixPlInfobox\add_missing_params_to_choroba_infobox;
use function WikiParse\Template\getTemplates;

$input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma
|ICD10 = J45
|MeshID = D001249
}}
TXT;

echo "Debugging duplicate parameter issue:\n";
echo "=====================================\n\n";

echo "Original template:\n";
$temps = getTemplates($input);
foreach ($temps as $temp) {
    $params = $temp->getParameters();
    echo "Parameters before:\n";
    foreach ($params as $key => $value) {
        echo "  '" . $key . "'\n";
    }
}

echo "\nProcessing...\n\n";

$result = add_missing_params_to_choroba_infobox($input);

echo "Result:\n$result\n\n";

echo "Counting ICD10 occurrences:\n";
$count = substr_count($result, '|ICD10');
echo "Count of '|ICD10': $count\n";

// Let's also check with different patterns
$count2 = preg_match_all('/\|ICD10\s*=/', $result, $matches);
echo "Count of '|ICD10 =' pattern: $count2\n";
