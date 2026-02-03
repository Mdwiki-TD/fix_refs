# Fix Images Module

This module provides functionality to check if images exist on Wikimedia Commons and remove missing images from wikitext.

## Features

- **Check image existence**: Verify if an image file exists on Wikimedia Commons using their API
- **Remove missing infobox images**: Automatically remove `|image` and `|caption` fields when the image doesn't exist
- **Remove missing inline images**: Remove `[[File:...]]` or `[[Image:...]]` blocks when the image doesn't exist
- **Handle edge cases**: Properly handles empty values, nested links, whitespace, and special characters

## Installation

The module is automatically loaded through the project's autoloader in `src/include_files.php`.

## Usage

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/work.php';
require_once __DIR__ . '/src/include_files.php';

use function WpRefs\Bots\FixImages\check_commons_image_exists;
use function WpRefs\Bots\FixImages\remove_missing_infobox_images;
use function WpRefs\Bots\FixImages\remove_missing_inline_images;
use function WpRefs\Bots\FixImages\remove_missing_images;
```

## Functions

### `check_commons_image_exists(string $filename): bool`

Checks if an image exists on Wikimedia Commons.

**Parameters:**
- `$filename` - The image filename (with or without `File:` or `Image:` prefix)

**Returns:**
- `true` if the image exists
- `false` if the image doesn't exist or filename is empty
- `true` if API call fails (assumes image exists to avoid false positives)

**Examples:**
```php
check_commons_image_exists('AwareLogo.png');        // true (if exists)
check_commons_image_exists('NonExistent.png');      // false
check_commons_image_exists('');                      // false
check_commons_image_exists('File:Test.png');        // Removes prefix and checks
```

### `remove_missing_infobox_images(string $text): string`

Removes infobox image fields that don't exist on Commons, along with their associated caption fields.

**Handles:**
- `|image = filename.png` → Removed if missing
- `|caption = text` → Removed if corresponding image is removed
- `|image2 = ...`, `|caption2 = ...` → Works with numbered variants
- Empty fields → Always removed

**Example:**
```php
$text = "|name = Disease\n|image = Missing.png\n|caption = Caption\n|specialty = [[Cardiology]]";
$result = remove_missing_infobox_images($text);
// Result: "|name = Disease\n|specialty = [[Cardiology]]"
```

### `remove_missing_inline_images(string $text): string`

Removes inline `[[File:...]]` or `[[Image:...]]` blocks that don't exist on Commons.

**Handles:**
- `[[File:image.png|thumb|caption]]` → Entire block removed if missing
- `[[Image:image.png|...]]` → Also handles legacy `Image:` prefix
- Nested links in captions → `[[File:img.png|See [[Article]]]]` handled correctly
- Multiple images → Each checked independently

**Example:**
```php
$text = "Text [[File:Missing.png|thumb|Caption]] more text";
$result = remove_missing_inline_images($text);
// Result: "Text  more text"
```

### `remove_missing_images(string $text): string`

Main function that removes both infobox and inline missing images.

**Example:**
```php
$text = "{{Infobox\n|image = Missing.png\n|caption = Caption\n}}\n[[File:Missing2.png|thumb|Text]]";
$result = remove_missing_images($text);
// Removes both the infobox image/caption and the inline image
```

## Edge Cases Handled

1. **Empty image fields**: `|image =` → Removed
2. **Whitespace**: `|image =   ` → Treated as empty, removed
3. **Multiple numbered fields**: `image2`, `image3`, etc. → Each checked independently
4. **Nested links**: `[[File:img.png|See [[Article]]]]` → Brackets counted correctly
5. **Legacy syntax**: `[[Image:...]]` → Treated same as `[[File:...]]`
6. **Special characters**: Filenames with special characters → Properly URL-encoded
7. **API failures**: Network errors → Assumes image exists (conservative approach)

## API Details

The module uses the Wikimedia Commons API:

```
https://commons.wikimedia.org/w/api.php?action=query&titles=File:{filename}&format=json
```

The API response is checked for the `"missing"` key in the pages object. If present, the file doesn't exist.

## Testing

Run the test suite:

```bash
phpunit tests/Bots/fix_imagesTest.php --testdox
```

Note: Tests that require API access will be skipped if the Wikimedia Commons API is not reachable.

## Examples

See `examples/fix_images_example.php` for comprehensive usage examples.

## Error Handling

- **Empty filenames**: Return `false` immediately
- **API failures**: Return `true` (assumes image exists to avoid accidentally removing valid images)
- **Malformed wikitext**: Skips malformed image tags, continues processing

## Performance Considerations

- Each unique image filename triggers one API call
- API calls use `@file_get_contents()` with error suppression
- No caching is implemented (can be added in future versions)
- For bulk processing, consider rate limiting to respect Wikimedia's API guidelines

## Future Enhancements

Possible improvements:
- Caching of API results to reduce redundant calls
- Batch API requests for multiple images
- Configuration option to change API failure behavior
- Support for local file checking (non-Commons images)
- Progress reporting for large text processing

## License

This code is part of the fix_refs project and follows the same license terms.
