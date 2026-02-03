<?php
// Simple test runner to verify fix_images.php implementation

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/work.php';
require_once __DIR__ . '/src/include_files.php';

use function WpRefs\Bots\FixImages\check_commons_image_exists;
use function WpRefs\Bots\FixImages\remove_missing_infobox_images;
use function WpRefs\Bots\FixImages\remove_missing_inline_images;
use function WpRefs\Bots\FixImages\remove_missing_images;

echo "Testing fix_images.php implementation...\n\n";

// Test 1: Check if a known image exists
echo "Test 1: Checking if AwareLogo.png exists on Commons...\n";
$exists = check_commons_image_exists('AwareLogo.png');
echo "Result: " . ($exists ? "EXISTS" : "MISSING") . "\n";
echo "Expected: EXISTS\n";
echo ($exists ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 2: Check if a non-existent image is detected
echo "Test 2: Checking if NonExistentImage12345.png exists on Commons...\n";
$exists = check_commons_image_exists('NonExistentImage12345.png');
echo "Result: " . ($exists ? "EXISTS" : "MISSING") . "\n";
echo "Expected: MISSING\n";
echo (!$exists ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 3: Empty filename
echo "Test 3: Checking empty filename...\n";
$exists = check_commons_image_exists('');
echo "Result: " . ($exists ? "EXISTS" : "MISSING") . "\n";
echo "Expected: MISSING\n";
echo (!$exists ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 4: Infobox image removal
echo "Test 4: Testing infobox image removal...\n";
$input = "|name             ={{PAGENAME}}\n|image            =NonExistentImage12345.png\n|caption          =This caption should be removed\n|specialty        =[[Orthopedics]]";
$result = remove_missing_infobox_images($input);
$hasImage = strpos($result, '|image') !== false;
$hasCaption = strpos($result, '|caption') !== false;
echo "Has |image: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Has |caption: " . ($hasCaption ? "YES" : "NO") . "\n";
echo "Expected: NO for both\n";
echo (!$hasImage && !$hasCaption ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 5: Inline image removal
echo "Test 5: Testing inline image removal...\n";
$input = "Some text [[File:NonExistentImage12345.png|thumb|caption]] more text";
$result = remove_missing_inline_images($input);
$hasFile = strpos($result, '[[File:') !== false;
echo "Has [[File:: " . ($hasFile ? "YES" : "NO") . "\n";
echo "Expected: NO\n";
echo (!$hasFile ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 6: Inline image with nested links
echo "Test 6: Testing inline image with nested links...\n";
$input = "[[File:NonExistentImage12345.png|thumb|See [[Orthopedics]] for info]]";
$result = remove_missing_inline_images($input);
echo "Input length: " . strlen($input) . "\n";
echo "Result length: " . strlen($result) . "\n";
echo "Expected: Result should be empty or near-empty\n";
echo (strlen($result) < 5 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 7: Image: prefix (alias)
echo "Test 7: Testing Image: prefix...\n";
$input = "[[Image:NonExistentImage12345.png|thumb|Old style]]";
$result = remove_missing_inline_images($input);
$hasImage = strpos($result, '[[Image:') !== false;
echo "Has [[Image:: " . ($hasImage ? "YES" : "NO") . "\n";
echo "Expected: NO\n";
echo (!$hasImage ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 8: Preserve existing images
echo "Test 8: Testing preservation of existing image (AwareLogo.png)...\n";
$input = "[[File:AwareLogo.png|thumb|Valid image]]";
$result = remove_missing_inline_images($input);
$hasFile = strpos($result, '[[File:AwareLogo.png') !== false;
echo "Has [[File:AwareLogo.png: " . ($hasFile ? "YES" : "NO") . "\n";
echo "Expected: YES\n";
echo ($hasFile ? "✓ PASS" : "✗ FAIL") . "\n\n";

echo "All tests completed!\n";
