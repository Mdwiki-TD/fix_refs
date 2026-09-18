[![Ask DeepWiki](https://deepwiki.com/badge.svg)](https://deepwiki.com/Mdwiki-TD/fix_refs)

# Fix Refs

A PHP library for parsing and fixing references in MediaWiki wikitext. Used by the [MDWiki Translation Dashboard](https://mdwiki.toolforge.org/) to process and standardize citations across different language versions of Wikipedia.

## Project Overview

Fix Refs automates the cleanup and standardization of `<ref>` tags, citation templates, and related wikitext structures in Wikipedia articles that have been translated through the MDWiki project. It handles language-specific citation formats, duplicate reference removal, missing reference recovery, infobox expansion, and punctuation normalization.

### Main Features

-   **Reference Deduplication** - Detects and removes duplicate `<ref>` tags, consolidating them with `name` attributes
-   **Missing Reference Recovery** - Expands short/self-closing `<ref name="..." />` tags by fetching full reference content from the source MDWiki revision
-   **Citation Template Localization** - Translates English citation templates (e.g., `{{cite web}}`) to language-specific equivalents (e.g., `{{cita web}}` for Spanish)
-   **Parameter Renaming** - Maps English citation parameters to localized names (e.g., `title` -> `título`, `access-date` -> `fechaacceso`)
-   **Month Localization** - Converts English month names in citation dates to Portuguese and Spanish
-   **Punctuation Normalization** - Moves trailing punctuation (`.`, `,`, `。`, `।`) after reference tags to follow MediaWiki conventions
-   **Language Parameter Injection** - Adds `|language=en` to citation templates that lack a language parameter
-   **Infobox Expansion** - Reformats compact infobox templates into multi-line readable format
-   **Category Management** - Adds `[[Category:Translated from MDWiki]]` (or localized equivalent) to translated articles
-   **Section Title Translation** - Localizes "References" section headings for Croatian, Swahili, and Russian
-   **CSRF Protection** - Token-based form protection for the web interface

### Supported Languages

| Code | Language   | Specific Fixes                                                                |
| ---- | ---------- | ----------------------------------------------------------------------------- |
| `es` | Spanish    | Template/parameter translation, month localization, ref section restructuring |
| `pt` | Portuguese | Month localization, reference spacing                                         |
| `pl` | Polish     | Infobox parameter completion for `Choroba infobox`                            |
| `bg` | Bulgarian  | Translation attribution template (`Превод от`)                                |
| `sw` | Swahili    | Section title correction                                                      |
| `hy` | Armenian   | Reference-punctuation spacing                                                 |
| `ar` | Arabic     | Reference spacing                                                             |
| `zh` | Chinese    | Punctuation-aware dot moving                                                  |
| `hi` | Hindi      | Punctuation-aware dot moving                                                  |
| `ru` | Russian    | Section title translation                                                     |
| `hr` | Croatian   | Section title translation                                                     |

### Frameworks & Technologies

-   **PHP 8.2+** (platform target in `composer.json`)
-   **No framework** - Pure PHP with PSR-4 autoloading
-   **MediaWiki API** - Fetches wikitext via Action API and REST API
-   **cURL** - HTTP requests to Wikipedia and Wikidata APIs
-   **Bootstrap** - Web UI styling (loaded from external MDWiki header)

### Dependencies

| Package           | Version | Type | Purpose         |
| ----------------- | ------- | ---- | --------------- |
| `phpstan/phpstan` | ^2.1    | dev  | Static analysis |
| `phpunit/phpunit` | ^11.5   | dev  | Unit testing    |

No runtime dependencies - the library is self-contained.

## Project Structure

```
fix_refs_repo/
├── src/                          # Application source code
│   ├── index.php                 # Web UI entry point (form + result display)
│   ├── work.php                  # Core orchestrator: settings loading, cURL, fix_page_with_setting()
│   ├── text_post.php             # POST handler for API-style text processing
│   ├── test.php                  # Test form UI for manual testing
│   ├── csrf.php                  # CSRF token generation and verification
│   ├── fix_src/                  # Core library (PSR-4: WpRefs\)
│   │   ├── index.php             # fix_page() - main processing pipeline
│   │   ├── include_files.php     # Autoloader via glob includes
│   │   ├── test_bot.php          # Debug/test output helpers
│   │   ├── md_cat.php            # MDWiki category management (Wikidata integration)
│   │   ├── WikiParse/            # MediaWiki wikitext parser module
│   │   │   ├── Template.php      # getTemplate()/getTemplates() facade
│   │   │   ├── include_it.php    # WikiParse autoloader
│   │   │   └── src/              # Parser classes and data models
│   │   │       ├── ParserTemplate.php    # Single template parser
│   │   │       ├── ParserTemplates.php   # Multi-template parser (recursive)
│   │   │       ├── ParserTags.php        # HTML/XML tag parser
│   │   │       ├── ParserCitations.php   # Citation (<ref>) parser
│   │   │       ├── ParserCategories.php  # Category link parser
│   │   │       ├── ParserInternalLinks.php
│   │   │       ├── ParserExternalLinks.php
│   │   │       └── DataModel/            # Value objects
│   │   │           ├── Template.php      # Template model with Parameters
│   │   │           ├── Parameters.php    # Key-value parameter collection
│   │   │           ├── Tag.php           # HTML tag model
│   │   │           ├── Attribute.php     # Tag attribute model
│   │   │           ├── Citation.php      # Citation model
│   │   │           ├── InternalLink.php
│   │   │           ├── ExternalLink.php
│   │   │           └── Table.php
│   │   ├── Parse/                # Regex-based parsers (legacy/supplementary)
│   │   │   ├── Citations.php     # CitationOld parser (regex-based)
│   │   │   ├── Citations_reg.php # Short/full ref extraction by name
│   │   │   └── Category.php      # Category regex parser
│   │   ├── bots/                 # Core text transformation functions
│   │   │   ├── mini_fixes_bot.php        # Spacing, section titles, prefix cleanup
│   │   │   ├── remove_duplicate_refs.php # Duplicate ref detection/removal
│   │   │   ├── expend_refs.php           # Short ref expansion
│   │   │   ├── refs_utils.php            # String helpers (str_starts_with, etc.)
│   │   │   ├── attrs_utils.php           # HTML attribute parsing
│   │   │   ├── months_new_value.php      # Month name translation (PT/ES)
│   │   │   ├── redirect_help.php         # Redirect page detection
│   │   │   └── txtlib2.php               # Template extraction helper
│   │   ├── helps_bots/           # Helper utilities
│   │   │   ├── mv_dots.php       # Punctuation-after-reference movement
│   │   │   ├── en_lang_param.php # |language=en injection
│   │   │   ├── missing_refs.php  # Missing ref recovery from source
│   │   │   └── remove_space.php  # Reference-punctuation spacing
│   │   ├── infoboxes/            # Infobox expansion
│   │   │   ├── infobox.php       # Main infobox expansion logic
│   │   │   └── infobox2.php      # Template formatting helpers
│   │   └── lang_bots/            # Language-specific processing
│   │       ├── es_bots/          # Spanish: template translation, months, refs, sections
│   │       ├── pt_bots/          # Portuguese: month localization
│   │       ├── pl_bots/          # Polish: infobox parameter completion
│   │       ├── bg_bots/          # Bulgarian: translation attribution
│   │       └── sw_bot.php        # Swahili: section title fix
│   ├── wikibots/                 # Wikipedia API utilities
│   │   └── wikitext.php          # Fetch wikitext via Action API / REST API
│   └── resources/                # Local data files
│       ├── language_settings.json # Language configuration fallback
│       ├── mdwiki_categories.json # Category name mappings
│       └── revisions/            # Cached MDWiki revision wikitext
├── tests/                        # PHPUnit test suite
│   ├── bootstrap.php             # Test bootstrap with MyFunctionTest base class
│   ├── Bots/                     # Bot function tests
│   ├── Parse/                    # Parser tests
│   ├── es_bots/                  # Spanish bot tests
│   ├── pt_bots/                  # Portuguese bot tests
│   ├── pl_bots/                  # Polish bot tests
│   ├── bg_bots/                  # Bulgarian bot tests
│   ├── helps_bots/               # Helper bot tests
│   └── infoboxes/                # Infobox tests
├── composer.json                 # Composer configuration
├── phpunit.xml                   # PHPUnit configuration
├── phpstan.neon                  # PHPStan configuration
└── CLAUDE.md                     # AI assistant instructions
```

### Application Layers

1. **Web Layer** (`src/index.php`, `src/text_post.php`, `src/test.php`) - HTML forms and POST handlers
2. **Orchestration Layer** (`src/work.php`) - Settings loading, environment detection, entry points
3. **Pipeline Layer** (`src/fix_src/index.php`) - Sequential processing pipeline in `fix_page()`
4. **Bot Layer** (`src/fix_src/bots/`, `src/fix_src/helps_bots/`) - Individual text transformations
5. **Language Layer** (`src/fix_src/lang_bots/`) - Language-specific transformations
6. **Parser Layer** (`src/fix_src/WikiParse/`, `src/fix_src/Parse/`) - Wikitext parsing
7. **Data Layer** (`src/fix_src/WikiParse/src/DataModel/`) - Value objects and models
8. **API Layer** (`src/wikibots/`) - Wikipedia/Wikidata API communication

## Architecture & Code Quality Review

### Code Organization

The project follows a modular architecture with clear separation between parsing, transformation, and language-specific logic. The `fix_page()` function in `src/fix_src/index.php` serves as the main pipeline orchestrator, calling functions in a defined sequence.

### Design Patterns

-   **Pipeline Pattern** - `fix_page()` chains transformations sequentially
-   **Strategy Pattern** - Language-specific bots are selected based on `$lang` parameter
-   **Data Model / Value Object** - `Template`, `Tag`, `Parameters` encapsulate parsed structures
-   **Facade** - `WikiParse/Template.php` provides simple `getTemplates()` entry point
-   **Static Registry** - `ESData` class holds translation mappings as static properties

### SOLID Principles Compliance

| Principle | Assessment                                                                                            |
| --------- | ----------------------------------------------------------------------------------------------------- |
| **S**RP   | Moderate - Most functions have single responsibilities, but some files mix parsing and transformation |
| **O**CP   | Low - Adding a new language requires modifying `fix_page()` directly with new `if` branches           |
| **L**SP   | N/A - Minimal inheritance hierarchy                                                                   |
| **I**SP   | Good - Interfaces are minimal (no forced implementations)                                             |
| **D**IP   | Low - Direct function calls, no dependency injection or abstractions                                  |

### Maintainability

-   **Good**: Each language bot is in its own file/directory, making language-specific changes isolated
-   **Good**: The WikiParse module is well-structured with proper data models
-   **Concern**: The `include_files.php` uses glob-based includes rather than Composer autoloading for all files
-   **Concern**: The `fix_page()` function has a growing list of language-specific `if` blocks

### Readability

-   **Good**: Function names are descriptive (e.g., `remove_Duplicate_refs_With_attrs`, `move_dots_after_refs`)
-   **Good**: Arabic comments provide context for bilingual developers
-   **Concern**: Inconsistent naming conventions (camelCase, snake_case, PascalCase mixed)
-   **Concern**: Some commented-out code remains in production files

### Scalability

-   The current architecture works well for the existing set of ~11 languages
-   Adding more languages requires: creating a new lang_bot file, adding `if` block to `fix_page()`, and updating settings
-   The cURL-based API calls have 5-second timeouts, which is reasonable for the use case

### Dependency Management

-   Minimal dependencies (only dev tools) reduces supply chain risk
-   No runtime Composer dependencies means zero autoload overhead for the library itself
-   The glob-based include system in `include_files.php` bypasses Composer autoloading

## Strengths

1. **Well-structured WikiParse module** - Clean OOP with proper encapsulation in `DataModel/` classes. The `Template`, `Parameters`, and `Tag` classes provide a solid foundation for wikitext manipulation.

2. **Comprehensive language support** - Each language has dedicated, isolated processing logic with proper locale-specific mappings (Spanish has ~50+ parameter translations).

3. **Robust citation parsing** - Two complementary approaches: regex-based (`Parse/Citations_reg.php`) for speed and OOP-based (`WikiParse/`) for structured access.

4. **Test coverage** - 30+ test files covering most bot functions, with a custom `assertEqualCompare()` that catches no-op failures.

5. **Defensive API calls** - cURL calls have timeouts, user-agent strings, and fallback mechanisms (API -> REST -> local file).

6. **CSRF protection** - Proper single-use token generation with `random_bytes(32)`.

7. **Idempotent processing** - Functions check if changes are already applied before modifying text (e.g., category already exists, template already translated).

8. **Wikidata integration** - Category names are fetched from Wikidata with local JSON fallback, keeping mappings up-to-date.

## Weaknesses

1. **Glob-based autoloading** - `include_files.php` uses `glob()` to include all PHP files rather than relying on Composer PSR-4 autoloading. This is fragile and slower.

2. **Mixed parsing approaches** - Two parallel parsing systems (`Parse/Citations.php` with `CitationOld` class and `WikiParse/src/ParserCitations.php`) create confusion about which to use.

3. **Inconsistent naming** - Mixed conventions: `Expend_Infobox` (Pascal+snake), `fix_page` (snake), `getCitationsOld` (camel), `remove_Duplicate_refs_With_attrs` (mixed).

4. **Commented-out code** - Multiple files contain commented-out code blocks (e.g., `// $text = fix_refs_names($text);` in `fix_src/index.php`).

5. **Global state in ESData** - `ESData` uses public static properties populated at file include time, creating implicit coupling.

6. **Duplicate `str_starts_with`/`str_ends_with`** - Polyfill functions are defined in both `refs_utils.php` and `remove_space.php` with `function_exists` guards.

7. **No input validation on web endpoints** - `text_post.php` does minimal validation; `$lang` and `$title` are not sanitized against injection.

8. **Hardcoded server paths** - `missing_refs.php` contains hardcoded Windows path (`I:/medwiki/new/...`) and Toolforge path.

## Critical Issues

### Security

1. **Variable name mismatch in text_post.php (BUG)** - Line 50 uses `$new_text` but the result is stored in `$newtext` (no underscore). This causes the comparison to always fail and the output to always say "no changes" even when changes exist. **Severity: High - functional bug.**

2. **Commented-out CSRF verification** - In `text_post.php` line 39, the CSRF check is commented out (`// if (verify_csrf_token())`). POST requests are processed without CSRF validation. **Severity: Medium.**

3. **No XSS protection on text output** - `text_post.php` line 62-64 outputs `$final_text` as `text/plain` without escaping. While the Content-Type header mitigates browser rendering, the variable `$new_text` (which is undefined) could leak error details in debug mode.

4. **Debug mode enabled by user input** - `index.php` and `test.php` enable `display_errors` when `$_GET['test']` is set, which can leak stack traces and file paths to users. **Severity: Low-Medium.**

5. **No rate limiting** - The `get_curl()` and `from_api()` functions make external HTTP requests without rate limiting, which could be abused or trigger Wikipedia API blocks.

### Performance

6. **Regex complexity** - The recursive regex in `ParserTemplates::find_sub_templates()` (`(?R)`) can be slow on deeply nested templates. The `$maxDepth = 10` limit helps but doesn't prevent exponential backtracking on malformed input.

7. **Repeated parsing** - `getCitationsOld()` is called multiple times during a single `fix_page()` invocation (by different bot functions), re-parsing the same text each time.

## Areas That Need Attention

### Missing Validation

-   `$lang` parameter is not validated against a whitelist of supported languages
-   `$title` is not sanitized before being used in regex patterns (`preg_quote` is used in some places but not all)
-   No length limits on input text

### Missing Tests

-   No integration tests for the full `fix_page()` pipeline
-   No tests for the web endpoints (`index.php`, `text_post.php`)
-   No tests for CSRF module
-   No tests for `wikibots/wikitext.php` (API calls)
-   `sw_bot.php` has minimal test coverage

### Outdated Patterns

-   `include_files.php` should be replaced with Composer autoloading
-   `CitationOld` class should be deprecated in favor of `ParserCitations`
-   The `$_SERVER['SERVER_NAME']` check for environment detection should use environment variables

### Error Handling

-   cURL errors in `get_curl()` are echoed to output but not properly handled
-   `json_decode` failures are not logged
-   File operations (`file_get_contents`) don't check for `false` returns consistently

### Documentation

-   No API documentation for the public functions
-   No changelog
-   Arabic comments are helpful for bilingual teams but could benefit from English translations

## Improvement Plan

### Quick Fixes (1-2 days)

1. Fix the `$new_text` vs `$newtext` variable name bug in `text_post.php`
2. Uncomment and enable CSRF verification in `text_post.php`
3. Remove or gate debug mode (`display_errors`) behind an environment variable instead of `$_GET['test']`
4. Remove commented-out code from production files
5. Add input length limits to web endpoints

### Medium-term Improvements (1-2 weeks)

1. Replace glob-based includes with proper Composer PSR-4 autoloading
2. Consolidate duplicate `str_starts_with`/`str_ends_with` polyfills into a single location
3. Add `$lang` whitelist validation in `work.php`
4. Cache parsed citations to avoid repeated parsing in `fix_page()`
5. Add integration tests for the full pipeline
6. Standardize naming conventions across the codebase

### Long-term Refactoring (1-2 months)

1. Extract language handling into a plugin/strategy pattern - create a `LanguageFixerInterface` with implementations per language, eliminating the `if` chain in `fix_page()`
2. Depare `CitationOld` in favor of `ParserCitations` from WikiParse
3. Add a proper dependency injection container or at minimum constructor-based DI
4. Create an abstraction for HTTP requests (injectable client for testing)
5. Add PHPDoc `@throws` annotations and proper exception handling
6. Implement proper logging (PSR-3) instead of `echo_test()`

### Security Hardening

1. Validate and sanitize all `$_POST` inputs against expected formats
2. Replace `$_SERVER['SERVER_NAME']` checks with environment variables
3. Add rate limiting for external API calls
4. Implement Content-Security-Policy headers on web endpoints
5. Add input text length limits (e.g., 1MB max)

### Performance Optimization

1. Parse citations once and pass the result to all bot functions
2. Use `str_contains()` (PHP 8.0+) instead of `strpos() !== false` for readability
3. Consider precompiling regex patterns that are reused across calls
4. Add opcache recommendations for production deployment

## Comprehensive Review

| Metric                   | Score      | Notes                                                                                         |
| ------------------------ | ---------- | --------------------------------------------------------------------------------------------- |
| **Overall Rating**       | 6.5/10     | Functional and well-tested for its purpose, but has code quality issues                       |
| **Production Readiness** | 7/10       | Already in production on Toolforge; works reliably for its use case                           |
| **Security Score**       | 5/10       | CSRF partially implemented, no input validation, debug mode exposed                           |
| **Technical Debt**       | 6/10       | Moderate - mixed parsing systems, naming inconsistencies, glob includes                       |
| **Maintainability**      | 6/10       | Good module isolation but growing `if` chain and no DI                                        |
| **Risk Assessment**      | Low-Medium | The tool processes wikitext text transformations; bugs cause formatting issues, not data loss |

## Setup & Usage

### Installation

```bash
# Clone the repository
git clone https://github.com/Mdwiki-TD/fix_refs.git
cd fix_refs

# Install dependencies
composer install
```

### Environment Setup

This project is designed to run on [Wikimedia Toolforge](https://wikitech.wikimedia.org/wiki/Help:Toolforge). For local development:

1. Ensure PHP 8.2+ is installed with the `curl` and `json` extensions
2. The web interface expects a `header.php` file from the MDWiki main repo at `../header.php` (or the hardcoded path). For standalone testing, the tool works without it.

### Local Development

```bash
# Start a local PHP server
php -S localhost:8080 -t src/

# Access the web interface
# http://localhost:8080/index.php
# http://localhost:8080/test.php (test form with sample data)
```

### Testing

```bash
# Run all tests (PHPStan + PHPUnit)
composer test

# Run PHPUnit tests only
vendor/bin/phpunit tests --testdox --colors=always

# Run PHPStan static analysis only
vendor/bin/phpstan analyse
```

### API Usage

The library can be used programmatically:

```php
require_once 'src/fix_src/include_files.php';

use function WpRefs\FixPage\fix_page_with_setting;

$text = "... your wikitext here ...";
$result = fix_page_with_setting(
    'Source Title',  // $sourcetitle (MDWiki source article)
    'Target Title',  // $title (target Wikipedia article)
    $text,           // $text (wikitext to fix)
    'es',            // $lang (language code)
    12345,           // $mdwiki_revid (MDWiki revision ID)
    true,            // $move_dots (move punctuation after refs)
    true,            // $expand (expand infobox)
    true             // $add_en_lang (add |language=en)
);
```

### Configuration

Language settings are loaded from a remote API with local fallback:

-   **Remote**: `https://mdwiki.toolforge.org/api.php?get=language_settings`
-   **Local fallback**: `src/resources/language_settings.json`

Each language entry controls:

-   `move_dots` - Whether to move punctuation after references
-   `expend` - Whether to expand infobox templates
-   `add_en_lang` - Whether to add `|language=en` to citations

## End points

| Endpoint         | Method | Description                                     |
| ---------------- | ------ | ----------------------------------------------- |
| `/`              | GET    | Main entry - web form for fixing references     |
| `/`              | POST   | Process a Wikipedia article by title & language |
| `/text_post.php` | POST   | Process raw wikitext (API-style)                |
| `/test.php`      | GET    | Test form with pre-filled sample data           |

### Deployment

The project is deployed on Wikimedia Toolforge. The `src/` directory is the web root. See [End points](#end-points) for available routes.
