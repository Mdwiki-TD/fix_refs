<?php
// Direct pattern testing without API calls

echo "Testing regex patterns for fix_images.php...\n\n";

// Test 1: Infobox pattern matching
echo "Test 1: Infobox pattern matching...\n";
$text = "|name             ={{PAGENAME}}\n|image            =Test.png\n|caption          =Test caption\n|specialty        =[[Orthopedics]]";
$pattern = '/^\s*\|(\s*image\d*\s*)=\s*([^\n]*?)\s*$/m';
preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
echo "Found " . count($matches) . " image field(s)\n";
if (count($matches) > 0) {
    echo "Field name: '" . trim($matches[0][1]) . "'\n";
    echo "Filename: '" . trim($matches[0][2]) . "'\n";
}
echo "\n";

// Test 2: Caption removal pattern
echo "Test 2: Caption field pattern...\n";
$text = "|name             ={{PAGENAME}}\n|image            =Test.png\n|caption          =Test caption\n|specialty        =[[Orthopedics]]";
$fieldName = 'caption';
$captionPattern = '/^\s*\|\s*' . preg_quote($fieldName, '/') . '\s*=\s*[^\n]*\s*\n?/m';
$result = preg_replace($captionPattern, '', $text);
$hasCaption = strpos($result, '|caption') !== false;
echo "Original has caption: " . (strpos($text, '|caption') !== false ? "YES" : "NO") . "\n";
echo "Result has caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo "\n";

// Test 3: Inline File pattern
echo "Test 3: Inline File pattern...\n";
$text = "Some text [[File:Test.png|thumb|caption]] more";
preg_match('/\[\[(File|Image):([^\]|]+)/i', $text, $matches);
if ($matches) {
    echo "Prefix: " . $matches[1] . "\n";
    echo "Filename: " . $matches[2] . "\n";
}
echo "\n";

// Test 4: Bracket counting logic
echo "Test 4: Testing bracket counting for nested links...\n";
$text = "[[File:Test.png|thumb|See [[Orthopedics]] for info]]";
echo "Input: $text\n";

preg_match('/\[\[(File|Image):([^\]|]+)/i', $text, $matches, PREG_OFFSET_CAPTURE);
$startPos = $matches[0][1];
$filename = $matches[2][0];

echo "Start position: $startPos\n";
echo "Filename: $filename\n";

// Find matching closing brackets
$bracketDepth = 2;
$pos = $startPos + strlen($matches[0][0]);
$endPos = false;

while ($pos < strlen($text) && $bracketDepth > 0) {
    if ($text[$pos] === '[' && isset($text[$pos + 1]) && $text[$pos + 1] === '[') {
        $bracketDepth += 2;
        $pos += 2;
    } elseif ($text[$pos] === ']' && isset($text[$pos + 1]) && $text[$pos + 1] === ']') {
        $bracketDepth -= 2;
        if ($bracketDepth === 0) {
            $endPos = $pos + 1;
            break;
        }
        $pos += 2;
    } else {
        $pos++;
    }
}

if ($endPos !== false) {
    $fullBlock = substr($text, $startPos, $endPos - $startPos + 1);
    echo "Full block: $fullBlock\n";
    echo "End position: $endPos\n";
}

echo "\n";

// Test 5: Multiple images
echo "Test 5: Multiple image fields...\n";
$text = "|image            =Test1.png\n|caption          =Cap1\n|image2           =Test2.png\n|caption2         =Cap2";
preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
echo "Found " . count($matches) . " image field(s)\n";
foreach ($matches as $match) {
    echo "  Field: '" . trim($match[1]) . "' -> Filename: '" . trim($match[2]) . "'\n";
    $fieldName = trim($match[1]);
    $number = preg_replace('/^image(\d*)$/i', '$1', $fieldName);
    $captionFieldName = 'caption' . $number;
    echo "  Corresponding caption field: $captionFieldName\n";
}

echo "\nAll pattern tests completed!\n";
