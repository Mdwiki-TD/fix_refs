<?php
// Test runner with mock API responses

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/work.php';
require_once __DIR__ . '/src/include_files.php';

use function WpRefs\Bots\FixImages\remove_missing_infobox_images;
use function WpRefs\Bots\FixImages\remove_missing_inline_images;
use function WpRefs\Bots\FixImages\remove_missing_images;

// Mock the check_commons_image_exists function for testing
namespace WpRefs\Bots\FixImages;

// Override the function for testing
function check_commons_image_exists_test(string $filename): bool
{
    // Mock responses for testing
    $existingImages = [
        'AwareLogo.png',
        'Valid_image.png',
        'AwareLogo.PNG' // Case variations
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

// Temporarily replace the real function
eval('
namespace WpRefs\Bots\FixImages {
    function check_commons_image_exists(string $filename): bool {
        return check_commons_image_exists_test($filename);
    }
}
');

echo "Testing fix_images.php implementation (with mocked API)...\n\n";

// Test 4: Infobox image removal
echo "Test 4: Testing infobox image removal (missing image)...\n";
$input = "|name             ={{PAGENAME}}\n|image            =NonExistentImage12345.png\n|caption          =This caption should be removed\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images($input);
$hasImage = strpos($result, '|image') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo "Expected: NO for both\n";
echo (!$hasImage && !$hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 4b: Infobox image preservation (existing image)
echo "Test 4b: Testing infobox image preservation (existing image)...\n";
$input = "|name             ={{PAGENAME}}\n|image            =AwareLogo.png\n|caption          =This is a valid image\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images($input);
$hasImage = strpos($result, '|image            =AwareLogo.png') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo "Expected: YES for both\n";
echo ($hasImage && $hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 5: Inline image removal
echo "Test 5: Testing inline image removal (missing image)...\n";
$input = "Some text [[File:NonExistentImage12345.png|thumb|caption]] more text";
$result = remove_missing_inline_images($input);
$hasFile = strpos($result, '[[File:') !== false;
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Has [[File:: " . ($hasFile ? "YES" : "NO") . "\n";
echo "Expected: NO\n";
echo (!$hasFile ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 5b: Inline image preservation
echo "Test 5b: Testing inline image preservation (existing image)...\n";
$input = "Some text [[File:AwareLogo.png|thumb|caption]] more text";
$result = remove_missing_inline_images($input);
$hasFile = strpos($result, '[[File:AwareLogo.png') !== false;
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Has [[File:AwareLogo.png: " . ($hasFile ? "YES" : "NO") . "\n";
echo "Expected: YES\n";
echo ($hasFile ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 6: Inline image with nested links
echo "Test 6: Testing inline image with nested links (missing)...\n";
$input = "[[File:NonExistentImage12345.png|thumb|See [[Orthopedics]] for info]]";
$result = remove_missing_inline_images($input);
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Result length: " . strlen($result) . "\n";
echo "Expected: Result should be empty or near-empty\n";
echo (strlen($result) < 5 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 7: Image: prefix (alias)
echo "Test 7: Testing Image: prefix (missing)...\n";
$input = "[[Image:NonExistentImage12345.png|thumb|Old style]]";
$result = remove_missing_inline_images($input);
$hasImage = strpos($result, '[[Image:') !== false;
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Has [[Image:: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Expected: NO\n";
echo (!$hasImage ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 8: Empty infobox field
echo "Test 8: Testing empty infobox image field...\n";
$input = "|name             ={{PAGENAME}}\n|image            =\n|caption          =Caption for empty image\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images($input);
$hasImage = strpos($result, '|image') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Input:\n$input\n\n";
echo "Result:\n$result\n\n";
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo "Expected: NO for both\n";
echo (!$hasImage && !$hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

echo "All tests completed!\n";
