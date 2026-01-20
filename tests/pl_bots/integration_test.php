<?php

// Integration test for Polish language fixes in main workflow

require_once __DIR__ . '/../../src/include_files.php';

use function WpRefs\WprefText\fix_page;

echo "Integration Test: Polish Language Support\n";
echo "==========================================\n\n";

// Test case 1: Polish article with Choroba infobox
$text1 = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz = Blausen 0620 Lungs NormalvsInflamedAirway.png
}}

'''Astma oskrzelowa''' (łac. ''asthma bronchiale'') – przewlekła choroba zapalna dróg oddechowych.

== Przypisy ==
<references />
TXT;

echo "Test 1: Polish article with Choroba infobox\n";
echo "--------------------------------------------\n";
$result1 = fix_page($text1, "Astma oskrzelowa", false, true, false, "pl", "", "");
echo "Original text length: " . strlen($text1) . "\n";
echo "Result text length: " . strlen($result1) . "\n";

// Check if parameters were added
$params_added = [
    'nazwa naukowa' => strpos($result1, 'nazwa naukowa') !== false,
    'ICD11' => strpos($result1, 'ICD11') !== false,
    'ICD10' => strpos($result1, 'ICD10') !== false,
    'DSM-5' => strpos($result1, 'DSM-5') !== false,
    'OMIM' => strpos($result1, 'OMIM') !== false,
    'MeshID' => strpos($result1, 'MeshID') !== false,
    'commons' => strpos($result1, 'commons') !== false,
];

echo "Parameters added:\n";
foreach ($params_added as $param => $added) {
    echo "  - $param: " . ($added ? 'YES' : 'NO') . "\n";
}
echo "\n";

// Test case 2: Polish article WITHOUT Choroba infobox (should not be modified)
$text2 = <<<'TXT'
{{Infobox person
|name = Jan Kowalski
}}

'''Jan Kowalski''' był polskim lekarzem.

== Przypisy ==
<references />
TXT;

echo "Test 2: Polish article without Choroba infobox\n";
echo "-----------------------------------------------\n";
$result2 = fix_page($text2, "Jan Kowalski", false, false, false, "pl", "", "");
$has_disease_params = strpos($result2, 'ICD10') !== false;
echo "Has disease parameters: " . ($has_disease_params ? 'YES (UNEXPECTED!)' : 'NO (expected)') . "\n\n";

// Test case 3: Case insensitive template name
$text3 = <<<'TXT'
{{choroba INFOBOX
|nazwa polska = Grypa
}}

Artykuł o grypie.
TXT;

echo "Test 3: Case insensitive template matching\n";
echo "-------------------------------------------\n";
$result3 = fix_page($text3, "Grypa", false, true, false, "pl", "", "");
$has_icd10 = strpos($result3, 'ICD10') !== false;
echo "Has ICD10 parameter: " . ($has_icd10 ? 'YES' : 'NO') . "\n\n";

// Test case 4: Non-Polish language (should not apply Polish fixes)
$text4 = <<<'TXT'
{{Choroba infobox
|nazwa polska = Test
}}
TXT;

echo "Test 4: Non-Polish language (English)\n";
echo "--------------------------------------\n";
$result4 = fix_page($text4, "Test", false, true, false, "en", "", "");
// Since it's marked as English, Polish fixes should not be applied
// But infobox expansion might still happen
echo "Text processed for English language\n";
echo "Result length: " . strlen($result4) . "\n\n";

// Test case 5: Existing parameters should not be duplicated
$text5 = <<<'TXT'
{{Choroba infobox
|nazwa polska = Cukrzyca
|ICD10 = E10-E14
|OMIM = 222100
}}
TXT;

echo "Test 5: Don't duplicate existing parameters\n";
echo "--------------------------------------------\n";
$result5 = fix_page($text5, "Cukrzyca", false, true, false, "pl", "", "");
$icd10_count = preg_match_all('/\|ICD10\s*=/', $result5, $matches);
$omim_count = preg_match_all('/\|OMIM\s*=/', $result5, $matches);
echo "ICD10 parameter count: $icd10_count (should be 1)\n";
echo "OMIM parameter count: $omim_count (should be 1)\n\n";

echo "==========================================\n";
echo "All integration tests completed!\n";
