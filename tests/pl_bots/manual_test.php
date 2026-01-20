<?php

// Simple manual test for Polish infobox functionality

require_once __DIR__ . '/../../src/include_files.php';

use function WpRefs\PL\FixPlInfobox\add_missing_params_to_choroba_infobox;
use function WpRefs\PL\FixPlInfobox\pl_fixes;

echo "Testing Polish Choroba Infobox Parameter Addition\n";
echo "==================================================\n\n";

// Test 1: Basic functionality
echo "Test 1: Add missing parameters to Choroba infobox\n";
$input1 = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz = 
|opis obrazu = 
}}
TXT;

$result1 = add_missing_params_to_choroba_infobox($input1);
echo "Input:\n$input1\n\n";
echo "Output:\n$result1\n\n";

// Check for expected parameters
$params_to_check = ['nazwa naukowa', 'ICD11', 'ICD10', 'DSM-5', 'OMIM', 'MeshID', 'commons'];
echo "Checking for parameters:\n";
foreach ($params_to_check as $param) {
    $found = strpos($result1, $param) !== false ? 'FOUND' : 'MISSING';
    echo "  - $param: $found\n";
}
echo "\n";

// Test 2: Case insensitive template name
echo "Test 2: Case insensitive template name matching\n";
$input2 = '{{choroba INFOBOX|nazwa polska=Test}}';
$result2 = add_missing_params_to_choroba_infobox($input2);
$has_icd10 = strpos($result2, 'ICD10') !== false;
echo "Input: $input2\n";
echo "Has ICD10 parameter: " . ($has_icd10 ? 'YES' : 'NO') . "\n\n";

// Test 3: Don't add existing parameters
echo "Test 3: Don't duplicate existing parameters\n";
$input3 = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma
|ICD10 = J45
|MeshID = D001249
}}
TXT;
$result3 = add_missing_params_to_choroba_infobox($input3);
$icd10_count = preg_match_all('/\|ICD10\s*=/', $result3, $icd10_matches);
$meshid_count = preg_match_all('/\|MeshID\s*=/', $result3, $meshid_matches);
echo "Input:\n$input3\n";
echo "ICD10 parameter count: $icd10_count (should be 1)\n";
echo "MeshID parameter count: $meshid_count (should be 1)\n\n";

// Test 4: Ignore other templates
echo "Test 4: Ignore non-Choroba templates\n";
$input4 = '{{Some other template|param=value}}';
$result4 = add_missing_params_to_choroba_infobox($input4);
$unchanged = ($input4 === $result4);
echo "Input: $input4\n";
echo "Unchanged: " . ($unchanged ? 'YES' : 'NO') . "\n\n";

// Test 5: pl_fixes function
echo "Test 5: pl_fixes wrapper function\n";
$input5 = '{{Choroba infobox|nazwa polska=Test}}';
$result5 = pl_fixes($input5);
$has_params = strpos($result5, 'nazwa naukowa') !== false && strpos($result5, 'ICD10') !== false;
echo "Input: $input5\n";
echo "Has required parameters: " . ($has_params ? 'YES' : 'NO') . "\n\n";

echo "==================================================\n";
echo "All manual tests completed!\n";
