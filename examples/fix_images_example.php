<?php
/**
 * Example usage of fix_images.php functions
 * 
 * This file demonstrates how to use the image checking and removal functions
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../work.php';
require_once __DIR__ . '/../src/include_files.php';

use function WpRefs\Bots\FixImages\check_commons_image_exists;
use function WpRefs\Bots\FixImages\remove_missing_infobox_images;
use function WpRefs\Bots\FixImages\remove_missing_inline_images;
use function WpRefs\Bots\FixImages\remove_missing_images;

// Example 1: Check if an image exists on Wikimedia Commons
echo "Example 1: Checking if images exist\n";
echo "====================================\n";
$imagesToCheck = ['AwareLogo.png', 'NonExistentImage.png', ''];

foreach ($imagesToCheck as $image) {
    $exists = check_commons_image_exists($image);
    $imageName = $image ?: '(empty)';
    echo "Image '$imageName': " . ($exists ? "EXISTS" : "MISSING") . "\n";
}
echo "\n";

// Example 2: Remove missing images from infobox
echo "Example 2: Remove missing infobox images\n";
echo "=========================================\n";
$infoboxText = <<<'TEXT'
{{Infobox medical condition
|name             = Example Condition
|image            = NonExistentImage.png
|caption          = This image does not exist
|specialty        = [[Cardiology]]
|symptoms         = Various symptoms
}}
TEXT;

echo "Original text:\n$infoboxText\n\n";
$cleaned = remove_missing_infobox_images($infoboxText);
echo "After removing missing images:\n$cleaned\n\n";

// Example 3: Remove missing inline images
echo "Example 3: Remove missing inline images\n";
echo "========================================\n";
$articleText = <<<'TEXT'
This is an example article about a medical condition.

[[File:NonExistentDiagram.png|thumb|A diagram that doesn't exist]]

The condition affects many people worldwide.

[[File:AnotherMissing.jpg|left|200px|This should also be removed]]

For more information, see the references section.
TEXT;

echo "Original text:\n$articleText\n\n";
$cleaned = remove_missing_inline_images($articleText);
echo "After removing missing images:\n$cleaned\n\n";

// Example 4: Remove all missing images (both infobox and inline)
echo "Example 4: Remove all missing images\n";
echo "=====================================\n";
$fullText = <<<'TEXT'
{{Infobox disease
|name             = Heart Disease
|image            = MissingHeartImage.png
|caption          = A missing heart diagram
|specialty        = [[Cardiology]]
}}

Heart disease is a serious condition.

[[File:MissingChart.png|thumb|Statistics chart]]

For treatment options, consult a physician.
TEXT;

echo "Original text:\n$fullText\n\n";
$cleaned = remove_missing_images($fullText);
echo "After removing all missing images:\n$cleaned\n\n";

// Example 5: Handling nested links in captions
echo "Example 5: Nested links in captions\n";
echo "====================================\n";
$textWithNestedLinks = "[[File:Missing.png|thumb|See [[Article]] and [[Other]] for more info]]";
echo "Original: $textWithNestedLinks\n";
$cleaned = remove_missing_inline_images($textWithNestedLinks);
echo "After removal: " . ($cleaned ?: "(empty)") . "\n\n";

// Example 6: Image: prefix (legacy syntax)
echo "Example 6: Legacy Image: prefix\n";
echo "================================\n";
$legacyText = "[[Image:OldStyleMissing.png|thumb|Old style syntax]]";
echo "Original: $legacyText\n";
$cleaned = remove_missing_inline_images($legacyText);
echo "After removal: " . ($cleaned ?: "(empty)") . "\n\n";

echo "Examples complete!\n";
