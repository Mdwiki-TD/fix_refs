<?php

// Comprehensive test to verify Polish language fixes work correctly

use function WpRefs\WprefText\fix_page;

echo "=======================================================\n";
echo "COMPREHENSIVE TEST: Polish Choroba Infobox Support\n";
echo "=======================================================\n\n";

$testCases = [
    [
        'name' => 'Standard Choroba infobox - all parameters missing',
        'input' => '{{Choroba infobox|nazwa polska=Astma}}',
        'lang' => 'pl',
        'expected_params' => ['nazwa naukowa', 'ICD11', 'ICD10', 'DSM-5', 'OMIM', 'MeshID', 'commons'],
    ],
    [
        'name' => 'Lowercase template name',
        'input' => '{{choroba infobox|nazwa polska=Test}}',
        'lang' => 'pl',
        'expected_params' => ['ICD10'],
    ],
    [
        'name' => 'UPPERCASE template name',
        'input' => '{{CHOROBA INFOBOX|nazwa polska=Test}}',
        'lang' => 'pl',
        'expected_params' => ['ICD10'],
    ],
    [
        'name' => 'Mixed case template name',
        'input' => '{{ChOrObA InFoBoX|nazwa polska=Test}}',
        'lang' => 'pl',
        'expected_params' => ['ICD10'],
    ],
    [
        'name' => 'Some parameters already exist',
        'input' => '{{Choroba infobox|nazwa polska=Test|ICD10=J45|OMIM=123456}}',
        'lang' => 'pl',
        'expected_params' => ['nazwa naukowa', 'ICD11', 'DSM-5', 'MeshID'],
        'not_duplicated' => ['ICD10', 'OMIM'],
    ],
    [
        'name' => 'Full article with Choroba infobox',
        'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Cukrzyca
|obraz = Insulin glucose metabolism ZP.svg
}}

'''Cukrzyca''' – grupa chorób metabolicznych.

== Zobacz też ==
* [[Insulina]]

== Przypisy ==
<references />
TXT
        ,
        'lang' => 'pl',
        'expected_params' => ['ICD10', 'ICD11', 'OMIM'],
    ],
    [
        'name' => 'Non-Polish language should not apply fixes',
        'input' => '{{Choroba infobox|nazwa polska=Test}}',
        'lang' => 'en',
        'expected_params' => [],
        'should_not_add' => ['ICD10', 'ICD11'],
    ],
    [
        'name' => 'Different template (not Choroba) - no changes',
        'input' => '{{Infobox person|name=Test}}',
        'lang' => 'pl',
        'expected_params' => [],
        'should_not_add' => ['ICD10'],
    ],
];

$passed = 0;
$failed = 0;

foreach ($testCases as $index => $test) {
    echo "Test " . ($index + 1) . ": " . $test['name'] . "\n";
    echo str_repeat('-', 60) . "\n";

    $result = fix_page($test['input'], "Test Article", false, true, false, $test['lang'], "", "");

    $allChecksPassed = true;

    // Check expected parameters are present
    if (!empty($test['expected_params'])) {
        foreach ($test['expected_params'] as $param) {
            $found = strpos($result, $param) !== false;
            if (!$found) {
                echo "  ❌ FAIL: Expected parameter '$param' not found\n";
                $allChecksPassed = false;
            }
        }
    }

    // Check parameters that should not be added
    if (!empty($test['should_not_add'])) {
        foreach ($test['should_not_add'] as $param) {
            $found = strpos($result, $param) !== false;
            if ($found) {
                echo "  ❌ FAIL: Parameter '$param' should not be present\n";
                $allChecksPassed = false;
            }
        }
    }

    // Check parameters are not duplicated
    if (!empty($test['not_duplicated'])) {
        foreach ($test['not_duplicated'] as $param) {
            $count = preg_match_all('/\|' . preg_quote($param, '/') . '\s*=/', $result, $matches);
            if ($count > 1) {
                echo "  ❌ FAIL: Parameter '$param' duplicated ($count occurrences)\n";
                $allChecksPassed = false;
            }
        }
    }

    if ($allChecksPassed) {
        echo "  ✅ PASS\n";
        $passed++;
    } else {
        $failed++;
        // Show input (preserve template structure)
        $inputLines = explode("\n", $test['input']);
        $inputPreview = implode("\n  ", array_slice($inputLines, 0, 5));
        if (count($inputLines) > 5) {
            $inputPreview .= "\n  ... (" . (count($inputLines) - 5) . " more lines)";
        }
        echo "  Input:\n  $inputPreview\n";

        // Show result (preserve template structure)
        $resultLines = explode("\n", $result);
        $resultPreview = implode("\n  ", array_slice($resultLines, 0, 10));
        if (count($resultLines) > 10) {
            $resultPreview .= "\n  ... (" . (count($resultLines) - 10) . " more lines)";
        }
        echo "  Result:\n  $resultPreview\n";
    }
    echo "\n";
}

echo "=======================================================\n";
echo "RESULTS: $passed passed, $failed failed\n";
echo "=======================================================\n";

if ($failed === 0) {
    echo "✅ ALL TESTS PASSED!\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED!\n";
    exit(1);
}
