<?php
// Integration test with local mock

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/work.php';

// Create a modified version of the fix_images functions for testing
// that doesn't depend on the external API

function check_commons_image_exists_mock(string $filename): bool
{
    // Mock responses for testing
    $existingImages = [
        'AwareLogo.png',
        'Valid_image.png',
    ];
    
    $filename = preg_replace('/^(File|Image):/i', '', trim($filename));
    
    if (empty($filename)) {
        return false;
    }
    
    // Check case-insensitively
    foreach ($existingImages as $img) {
        if (strcasecmp($filename, $img) === 0) {
            return true;
        }
    }
    
    return false;
}

function remove_missing_infobox_images_test(string $text): string
{
    $pattern = '/^\s*\|(\s*image\d*\s*)=\s*([^\n]*?)\s*$/m';

    // Collect fields to remove
    $fieldsToRemove = [];
    
    preg_replace_callback($pattern, function ($matches) use (&$fieldsToRemove) {
        $fullMatch = $matches[0];
        $fieldName = trim($matches[1]);
        $filename = trim($matches[2]);

        // If empty or doesn't exist, mark for removal
        if (empty($filename) || !check_commons_image_exists_mock($filename)) {
            // Add both image and caption fields to removal list
            $fieldsToRemove[] = $fieldName;
            
            // The caption field would be like caption or caption2
            $number = preg_replace('/^image(\d*)$/i', '$1', $fieldName);
            $captionFieldName = 'caption' . $number;
            $fieldsToRemove[] = $captionFieldName;
        }

        return $fullMatch;
    }, $text);

    // Remove the marked fields
    foreach ($fieldsToRemove as $field) {
        $fieldPattern = '/^\s*\|\s*' . preg_quote($field, '/') . '\s*=\s*[^\n]*\s*\n?/m';
        $text = preg_replace($fieldPattern, '', $text);
    }

    return $text;
}

function remove_missing_inline_images_test(string $text): string
{
    $offset = 0;
    while (preg_match('/\[\[(File|Image):([^\]|]+)/i', $text, $matches, PREG_OFFSET_CAPTURE, $offset)) {
        $startPos = $matches[0][1];
        $prefix = $matches[1][0];
        $filename = $matches[2][0];
        
        // Find the matching closing brackets by counting bracket depth
        $bracketDepth = 2; // We start with [[
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
            $fullImageBlock = substr($text, $startPos, $endPos - $startPos + 1);
            
            // Check if the image exists
            if (!check_commons_image_exists_mock($filename)) {
                // Remove the entire image block
                $text = substr($text, 0, $startPos) . substr($text, $endPos + 1);
                $offset = $startPos;
            } else {
                // Move past this image
                $offset = $endPos + 1;
            }
        } else {
            // Malformed image tag, skip it
            $offset = $startPos + 1;
        }
    }
    
    return $text;
}

echo "Testing fix_images.php functions (with mocked API)...\n\n";

// Test 1: Infobox image removal (missing image)
echo "Test 1: Infobox image removal (missing image)...\n";
$input = "|name             ={{PAGENAME}}\n|image            =NonExistentImage12345.png\n|caption          =This caption should be removed\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images_test($input);
$hasImage = strpos($result, '|image') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Result:\n" . $result . "\n";
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo (!$hasImage && !$hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 2: Infobox image preservation (existing image)
echo "Test 2: Infobox image preservation (existing image)...\n";
$input = "|name             ={{PAGENAME}}\n|image            =AwareLogo.png\n|caption          =This is a valid image\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images_test($input);
$hasImage = strpos($result, '|image            =AwareLogo.png') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Result:\n" . $result . "\n";
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo ($hasImage && $hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 3: Empty infobox field
echo "Test 3: Empty infobox image field...\n";
$input = "|name             ={{PAGENAME}}\n|image            =\n|caption          =Caption for empty image\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images_test($input);
$hasImage = strpos($result, '|image') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Result:\n" . $result . "\n";
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo (!$hasImage && !$hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 4: Multiple images (mixed)
echo "Test 4: Multiple infobox images (mixed existence)...\n";
$input = "|name             ={{PAGENAME}}\n|image            =AwareLogo.png\n|caption          =Valid caption\n|image2           =Missing_image_xyz123.png\n|caption2         =This should be removed\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images_test($input);
$hasImage = strpos($result, '|image            =AwareLogo.png') !== false;
$hasCaption = strpos($result, '|caption          =Valid caption') !== false;
$hasImage2 = strpos($result, '|image2') !== false;
$hasCaption2 = strpos($result, '|caption2') !== false;
echo "Result:\n" . $result . "\n";
echo "Has |image (valid): " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption (valid): " . ($hasCaption ? "YES" : "NO") . "\n";
echo "Has |image2 (missing): " . ($hasImage2 ? "YES" : "NO") . "\n";
echo "Has |caption2 (missing): " . ($hasCaption2 ? "YES" : "NO") . "\n";
echo ($hasImage && $hasCaption && !$hasImage2 && !$hasCaption2 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 5: Inline image removal (missing)
echo "Test 5: Inline image removal (missing)...\n";
$input = "Some text [[File:NonExistentImage12345.png|thumb|caption]] more text";
$result = remove_missing_inline_images_test($input);
$hasFile = strpos($result, '[[File:') !== false;
echo "Result: $result\n";
echo "Has [[File:: " . ($hasFile ? "YES" : "NO") . "\n";
echo (!$hasFile ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 6: Inline image preservation (existing)
echo "Test 6: Inline image preservation (existing)...\n";
$input = "Some text [[File:AwareLogo.png|thumb|caption]] more text";
$result = remove_missing_inline_images_test($input);
$hasFile = strpos($result, '[[File:AwareLogo.png') !== false;
echo "Result: $result\n";
echo "Has [[File:AwareLogo.png: " . ($hasFile ? "YES" : "NO") . "\n";
echo ($hasFile ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 7: Nested links (missing image)
echo "Test 7: Nested links in caption (missing image)...\n";
$input = "[[File:NonExistentImage12345.png|thumb|See [[Orthopedics]] for info]]";
$result = remove_missing_inline_images_test($input);
echo "Result: '$result'\n";
echo "Result length: " . strlen($result) . "\n";
echo (strlen($result) < 5 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 8: Image: prefix (missing)
echo "Test 8: Image: prefix (missing)...\n";
$input = "[[Image:NonExistentImage12345.png|thumb|Old style]]";
$result = remove_missing_inline_images_test($input);
$hasImage = strpos($result, '[[Image:') !== false;
echo "Result: '$result'\n";
echo "Has [[Image:: " . ($hasImage ? "YES" : "NO") . "\n";
echo (!$hasImage ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 9: Image: prefix (existing)
echo "Test 9: Image: prefix (existing)...\n";
$input = "[[Image:AwareLogo.png|thumb|Old style but valid]]";
$result = remove_missing_inline_images_test($input);
$hasImage = strpos($result, '[[Image:AwareLogo.png') !== false;
echo "Result: $result\n";
echo "Has [[Image:AwareLogo.png: " . ($hasImage ? "YES" : "NO") . "\n";
echo ($hasImage ? "✓ PASS" : "✗ FAIL") . "\n\n";

echo "All tests completed!\n";
