# WikiBots - Wikipedia API Utilities

A utility module for fetching wikitext content from Wikipedia articles via the MediaWiki API.

## Project Overview

`wikibots` provides functions to retrieve the raw wikitext of any Wikipedia article by title and language code. It uses two complementary approaches: the MediaWiki Action API (primary) and the REST API (fallback).

### Main Features

- Fetch wikitext from any language edition of Wikipedia
- Dual API strategy: Action API with REST API fallback
- Proper User-Agent header compliance with Wikimedia API etiquette
- Configurable timeouts to prevent hanging requests

### PHP Version Requirements

- PHP 8.2+

### Dependencies

- `ext-curl` - For HTTP requests
- No Composer dependencies

## Project Structure

```
wikibots/
└── wikitext.php    # Wikipedia text retrieval functions
```

### Functions

| Function | Description |
|----------|-------------|
| `get_wikipedia_text($title, $lang)` | Main entry point. Fetches wikitext, tries Action API first, then REST API |
| `from_api($title, $lang)` | Fetches via MediaWiki Action API (`action=query&prop=revisions`) |
| `from_rest($title, $lang)` | Fetches via MediaWiki REST API (`/w/rest.php/v1/page/`) |

### Namespace

`WpRefs\WikiText`

## Architecture & Code Quality Review

### Code Organization

Single-file module with three functions following a clear fallback pattern. The namespace `WpRefs\WikiText` is well-chosen and descriptive.

### Design Patterns

- **Fallback Pattern** - `get_wikipedia_text()` tries `from_api()` first, falls back to `from_rest()`
- **Facade** - `get_wikipedia_text()` hides the dual-API implementation

### SOLID Principles

- **SRP**: Good - the module has a single responsibility (fetching wikitext)
- **OCP**: N/A - not designed for extension
- **DIP**: Low - directly creates cURL handles, no abstraction

### Maintainability Rating: 7/10

Simple, focused, and easy to understand. Limited scope keeps complexity low.

## Strengths

1. **Proper User-Agent** - Uses a descriptive User-Agent string (`WikiProjectMed Translation Dashboard/1.0`) compliant with Wikimedia API policy.

2. **Dual API fallback** - If the Action API fails or returns empty, the REST API is tried automatically.

3. **URL encoding** - `from_rest()` properly encodes `/` in titles with `%2F`.

4. **Timeout protection** - Both `from_api()` and `from_rest()` set `CURLOPT_CONNECTTIMEOUT` and `CURLOPT_TIMEOUT` to 5 seconds.

## Weaknesses

1. **No error handling on API responses** - `from_api()` doesn't check for API error responses (e.g., `{"error": {...}}`). The JSON is parsed and accessed without validation.

2. **Inconsistent HTTP methods** - `from_api()` uses POST while `from_rest()` uses GET. The Action API supports both, but consistency would be clearer.

3. **No caching** - The same article may be fetched multiple times during a pipeline run. No in-memory or file-based caching is implemented.

4. **Silent failures** - Both functions return empty string on failure with no logging or error reporting.

5. **Duplicate User-Agent string** - The same User-Agent string is defined in both `from_api()` and `from_rest()`. It should be a constant.

## Critical Issues

### Security

1. **No input validation on `$title`** - The `$title` parameter is inserted directly into the API URL. While cURL handles most edge cases, a title containing special characters could cause unexpected behavior. `from_rest()` does encode `/` but not other special characters.

2. **No HTTPS enforcement** - URLs are constructed with `https://` which is correct, but there's no validation that the `$lang` parameter is a valid language code (e.g., `../../etc/passwd` as `$lang` would produce `https://../../etc/passwd.wikipedia.org/...`).

### Performance

3. **No connection reuse** - Each call creates a new cURL handle. For batch processing, reusing a handle with `curl_reset()` would be more efficient.

## Areas That Need Attention

### Missing Validation
- `$lang` should be validated as a 2-3 letter language code
- `$title` should be URL-encoded for the REST API path
- API response structure should be validated before accessing nested keys

### Missing Tests
- No unit tests for any of the three functions
- No mocking of cURL for testable HTTP calls

### Error Handling
- API errors (rate limiting, invalid title, missing article) are not distinguished
- cURL errors are silently ignored in `from_rest()` (unlike `from_api()` which at least has the check in the caller)

## Improvement Plan

### Quick Fixes
1. Extract User-Agent string to a constant
2. Validate `$lang` against `/^[a-z]{2,3}$/` before making requests
3. Check for API error responses in `from_api()`

### Medium-term
1. Add an in-memory cache (static array) for repeated requests to the same title
2. Add PSR-3 logger injection for error reporting
3. Standardize on POST for both API endpoints

### Long-term
1. Create an injectable HTTP client interface for testability
2. Add rate limiting (respect `Retry-After` headers)
3. Support batch fetching for multiple titles via `action=query` with multiple `titles` parameter

## Setup & Usage

### Basic Usage

```php
require_once __DIR__ . '/../fix_src/include_files.php';

use function WpRefs\WikiText\get_wikipedia_text;

// Fetch English Wikipedia article
$text = get_wikipedia_text('Rhesus disease', 'en');

// Fetch Japanese Wikipedia article
$text = get_wikipedia_text('利用者:Doc James/Rh血液型不適合', 'ja');

if (empty($text)) {
    echo "Article not found or API error";
} else {
    echo "Got " . strlen($text) . " bytes of wikitext";
}
```

### How It Works

1. `get_wikipedia_text()` calls `from_api()` first
2. `from_api()` sends a POST request to `https://{lang}.wikipedia.org/w/api.php` with:
   - `action=query`
   - `prop=revisions`
   - `rvslots=*`
   - `rvprop=content`
   - `titles={title}`
3. If the response contains wikitext, it's returned
4. If empty, `from_rest()` is called as fallback
5. `from_rest()` sends a GET request to `https://{lang}.wikipedia.org/w/rest.php/v1/page/{title}`
6. The `source` field from the JSON response is returned

### API Rate Limits

The Wikimedia API has a rate limit of approximately 200 requests/second for well-behaved bots. This module does not implement rate limiting, so when processing many articles, consider adding delays between calls.
