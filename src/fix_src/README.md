# Fix Src - Core Reference Fixing Library

The core PHP library for parsing and fixing MediaWiki references. This is the main processing engine used by the Fix Refs project, autoloaded under the `WpRefs\` namespace via PSR-4.

## Project Overview

`fix_src` contains the complete pipeline for transforming wikitext references. It provides a wikitext parser (`WikiParse`), a set of text transformation bots, language-specific processors, and helper utilities. The main entry point is the `fix_page()` function in `index.php`.

### Main Features

- Recursive wikitext template parser with parameter extraction
- Citation (`<ref>`) tag parsing (both OOP and regex approaches)
- Duplicate reference detection and consolidation
- Short reference expansion (resolving `<ref name="X" />` to full content)
- Punctuation-reference spacing normalization
- `|language=en` parameter injection into citations
- Language-specific citation template translation (EN -> ES/PT/PL/BG/SW)
- Infobox template expansion to multi-line format
- MDWiki category management via Wikidata
- Redirect page detection and skipping

### PHP Version Requirements

- PHP 8.2+ (configured in `composer.json` platform)

### Dependencies

No runtime dependencies. Uses only PHP built-in functions (`preg_*`, `str_*`, `curl_*`, `json_*`).

## Project Structure

```
fix_src/
├── index.php                   # fix_page() - main processing pipeline
├── include_files.php           # Autoloader (glob-based file includes)
├── test_bot.php                # Debug output helpers (echo_test, echo_debug)
├── md_cat.php                  # MDWiki category via Wikidata API
│
├── WikiParse/                  # OOP wikitext parser module
│   ├── Template.php            # Facade: getTemplate(), getTemplates()
│   ├── include_it.php          # WikiParse autoloader
│   └── src/
│       ├── ParserTemplate.php      # Single {{template}} parser
│       ├── ParserTemplates.php     # Multi-template parser (recursive, depth-limited)
│       ├── ParserTags.php          # <tag> parser (full + self-closing)
│       ├── ParserCitations.php     # <ref> tag parser (uses ParserTags)
│       ├── ParserCategories.php    # [[Category:...]] parser
│       ├── ParserInternalLinks.php # [[link]] parser
│       ├── ParserExternalLinks.php # [http://...] parser
│       └── DataModel/
│           ├── Template.php        # Template model (name + Parameters)
│           ├── Parameters.php      # Ordered key-value parameter collection
│           ├── Tag.php             # HTML tag model (name, attrs, content)
│           ├── Attribute.php       # Tag attribute model
│           ├── Citation.php        # Citation data model
│           ├── InternalLink.php    # [[wiki link]] model
│           ├── ExternalLink.php    # [http://...] model
│           └── Table.php           # Table data model
│
├── Parse/                      # Regex-based parsers (supplementary)
│   ├── Citations.php           # CitationOld class + getCitationsOld()
│   ├── Citations_reg.php       # get_full_refs(), get_short_citations()
│   └── Category.php            # get_categories_reg()
│
├── bots/                       # Core text transformation functions
│   ├── mini_fixes_bot.php      # Spacing, section titles, prefix cleanup
│   ├── remove_duplicate_refs.php # Duplicate ref detection/removal
│   ├── expend_refs.php         # Short ref expansion
│   ├── refs_utils.php          # String helpers (str_starts_with, etc.)
│   ├── attrs_utils.php         # HTML attribute parsing
│   ├── months_new_value.php    # Month name translation (PT/ES)
│   ├── redirect_help.php       # Redirect page detection
│   ├── txtlib2.php             # Template extraction helper
│   └── tests/                  # Manual test scripts
│       └── fix_sections.php    # Section title fix test
│
├── helps_bots/                 # Helper utilities
│   ├── mv_dots.php             # Punctuation movement after references
│   ├── en_lang_param.php       # |language=en injection
│   ├── missing_refs.php        # Missing ref recovery from MDWiki source
│   └── remove_space.php        # Reference-punctuation spacing
│
├── infoboxes/                  # Infobox expansion
│   ├── infobox.php             # Main Expend_Infobox() logic
│   └── infobox2.php            # Template formatting helpers
│
└── lang_bots/                  # Language-specific processors
    ├── es_bots/                # Spanish
    │   ├── es.php              # Main fix_es() + template translation tables
    │   ├── es_months.php       # Month name localization
    │   ├── es_refs.php         # Reference restructuring (mv_es_refs)
    │   └── section.php         # Translation attribution template
    ├── pt_bots/                # Portuguese
    │   └── fix_pt_months.php   # Month localization + spacing
    ├── pl_bots/                # Polish
    │   └── fix_pl_infobox.php  # Choroba infobox parameter completion
    ├── bg_bots/                # Bulgarian
    │   └── fix_bg.php          # Превод от template + category
    └── sw_bot.php              # Swahili section title fix
```

## Architecture & Code Quality Review

### Processing Pipeline

The `fix_page()` function in `index.php` executes transformations in this order:

```
Input wikitext
  │
  ├─ 1. page_is_redirect() ──── Skip if redirect
  ├─ 2. pl_fixes() ──────────── Polish infobox params
  ├─ 3. Expend_Infobox() ────── Expand infobox templates
  ├─ 4. mini_fixes() ────────── Spacing, section titles, prefixes
  ├─ 5. fix_missing_refs() ──── Expand short refs from source
  ├─ 6. remove_Duplicate_refs_With_attrs() ── Deduplicate refs
  ├─ 7. move_dots_after_refs() ── Punctuation normalization
  ├─ 8. add_lang_en_to_refs() ── Add |language=en
  ├─ 9. pt_fixes() / bg_fixes() / fix_es() / sw_fixes() ── Language-specific
  ├─ 10. add_Translated_from_MDWiki() ── Category
  └─ 11. mini_fixes_after_fixing() ── Final cleanup
Output wikitext
```

### Design Patterns

| Pattern | Usage |
|---------|-------|
| **Pipeline** | `fix_page()` chains transformations sequentially |
| **Strategy** | Language bots selected by `$lang` parameter |
| **Value Object** | `Template`, `Tag`, `Parameters` are immutable-style data carriers |
| **Facade** | `WikiParse/Template.php` provides `getTemplates()` entry point |
| **Recursive Descent** | `ParserTemplates` uses stack-based recursive parsing |
| **Registry** | `ESData` static class holds translation mapping tables |

### Code Organization

**Strengths:**
- Clear separation between parsing (WikiParse, Parse) and transformation (bots, helps_bots, lang_bots)
- Each language has its own directory/file, enabling independent changes
- Data models in `WikiParse/src/DataModel/` are clean value objects with proper encapsulation

**Concerns:**
- Two parallel parsing systems exist (`Parse/Citations.php` with `CitationOld` and `WikiParse/src/ParserCitations.php`)
- `include_files.php` uses glob includes instead of relying on Composer PSR-4 autoloading
- The `bots/tests/` directory contains manual test scripts that shouldn't be in the library source

### SOLID Principles

- **SRP**: Generally good - each file has a focused purpose. Exception: `es.php` contains both translation tables and transformation logic.
- **OCP**: Poor - adding a language requires modifying `fix_page()` directly. A plugin/strategy pattern would help.
- **LSP**: N/A - minimal inheritance.
- **ISP**: Good - no forced interface implementations.
- **DIP**: Poor - all function calls are direct, no abstractions or injection points.

### Maintainability Rating: 6/10

The modular file structure aids navigation, but the growing `if` chain in `fix_page()` and lack of abstraction layers make extension harder over time.

## Strengths

1. **Clean WikiParse module** - The `DataModel/` classes (`Template`, `Parameters`, `Tag`) are well-designed with proper encapsulation, getter/setter methods, and `toString()` serialization. The recursive `ParserTemplates` correctly handles nested templates.

2. **Comprehensive Spanish support** - `es.php` contains 50+ parameter mappings and 20+ template name translations, covering the full Spanish citation template system.

3. **Idempotent operations** - Functions like `add_Translated_from_MDWiki()` and `bg_section()` check for existing state before modifying, preventing duplicate additions.

4. **Dual parsing strategy** - Regex parsers (`Parse/`) for fast extraction and OOP parsers (`WikiParse/`) for structured manipulation provide flexibility.

5. **Test infrastructure** - The `MyFunctionTest::assertEqualCompare()` method catches silent no-op bugs by verifying that functions actually make changes when expected.

6. **Proper recursive parsing** - `ParserTemplates` uses a stack-based approach with `$maxDepth = 10` to prevent infinite recursion on malformed wikitext.

## Weaknesses

1. **Duplicate utility functions** - `str_starts_with()` and `str_ends_with()` are defined in both `refs_utils.php` and `remove_space.php` with `function_exists` guards. Should be consolidated.

2. **Mixed naming conventions** - Functions use a mix of `snake_case` (`fix_page`), `PascalCase_Snake` (`Expend_Infobox`), `camelCase` (`getCitationsOld`), and `Mixed_Case` (`remove_Duplicate_refs_With_attrs`).

3. **Commented-out code** - `fix_page()` contains commented-out calls (`// $text = fix_refs_names($text);`), and multiple files have debug lines left in.

4. **Static global state** - `ESData` class uses public static arrays populated at include time, creating implicit coupling and making testing harder.

5. **Hardcoded paths** - `missing_refs.php` lines 71-72 contain hardcoded Windows and Toolforge paths:
   ```php
   $path = ($server == "localhost")
       ? "I:/medwiki/new/medwiki.toolforge.org_repo/public_html"
       : "/data/project/mdwikicx/public_html";
   ```

6. **No Composer autoloading for all files** - Despite PSR-4 configuration in `composer.json`, `include_files.php` manually globs and includes files, bypassing the autoloader.

7. **Duplicate `start_end()` function** - Defined identically in both `fix_pt_months.php` and `es_months.php`.

## Critical Issues

### Security

1. **User-controlled debug output** - `test_bot.php` checks `$_POST['test']` and `$_GET['test']` to enable debug output. Any user can trigger verbose output by adding `?test=1` to the URL.

2. **No input sanitization on `$title`** - The `$title` parameter is passed to `preg_replace()` patterns in `fix_title_bold()` without `preg_quote()` being consistently applied (it's wrapped in a try-catch that falls back to the raw title).

3. **Server path disclosure** - `missing_refs.php` echoes file paths via `echo_test()`, which could expose internal server paths when debug mode is active.

### Bugs

4. **`start_end()` function collision** - Both `fix_pt_months.php` (namespace `WpRefs\PT\FixPtMonth`) and `es_months.php` (namespace `WpRefs\EsBots\es_months`) define `start_end()` in their respective namespaces. While namespacing prevents a fatal error, the duplication is a maintenance risk.

### Performance

5. **Repeated full-text parsing** - `getCitationsOld()` is called by multiple functions during a single pipeline run (`remove_Duplicate_refs_With_attrs`, `add_lang_en_to_refs`, `fix_pt_months_in_refs`, `fix_es_months_in_refs`, `mv_es_refs`), each re-parsing the entire text.

6. **Regex on large texts** - Several functions apply `preg_replace` or `preg_match_all` on the full article text multiple times. For large articles, this could be slow.

## Areas That Need Attention

### Missing Validation
- `$lang` is not validated against supported language codes before being used in regex patterns
- `$sourcetitle` is used in file path construction without sanitization
- No maximum text length limit

### Missing Tests
- No integration test for the complete `fix_page()` pipeline
- `infobox2.php` has limited test coverage
- `sw_fixes()` has a single test case
- No tests for edge cases (empty input, malformed wikitext, extremely long articles)

### Technical Debt
- Deprecate `CitationOld` / `ParserCitationsOld` in favor of `WikiParse/src/ParserCitations`
- Remove glob-based includes and rely on Composer autoloading
- Clean up commented-out code
- Extract `ESData` static properties into a configuration file or injected dependency

### Error Handling
- `get_url_curl()` in `md_cat.php` returns empty string on failure without logging
- `file_get_contents()` calls don't consistently check for `false`
- No exception handling around `json_decode()` calls

## Improvement Plan

### Quick Fixes
1. Consolidate `str_starts_with()`/`str_ends_with()` into a single file
2. Remove all commented-out code from production files
3. Remove `bots/tests/` directory (move to `tests/` if needed)
4. Add `preg_quote($title, '/')` consistently in all regex patterns using `$title`

### Medium-term
1. Replace `include_files.php` glob system with Composer PSR-4 autoloading
2. Add a `LanguageFixerInterface` with per-language implementations
3. Parse citations once in `fix_page()` and pass the result to all bot functions
4. Replace `$_SERVER['SERVER_NAME']` checks with environment variables
5. Extract `ESData` mappings into a JSON configuration file

### Long-term
1. Implement a plugin system for language-specific fixes
2. Merge `CitationOld` and `ParserCitations` into a single parser
3. Add PSR-3 logging to replace `echo_test()`/`echo_debug()`
4. Create a `WikitextDocument` class that caches parsed elements
5. Add mutation testing to verify test quality

## Setup & Usage

### As a Composer Dependency

```json
{
    "require": {
        "mdwiki/fixrefs": "*"
    }
}
```

### Standalone Usage

```php
// Include the library
require_once __DIR__ . '/include_files.php';

// Process wikitext
use function WpRefs\WprefText\fix_page;

$result = fix_page(
    $text,          // Raw wikitext
    $title,         // Article title
    true,           // move_dots
    true,           // expand infobox
    true,           // add |language=en
    'es',           // Language code
    'Source Title', // MDWiki source title
    12345           // MDWiki revision ID
);
```

### Using the Parser Directly

```php
use WikiParse\Template\getTemplates;

$templates = getTemplates('{{cite web|url=...|title=...}}');
foreach ($templates as $template) {
    echo $template->getName();           // "cite web"
    echo $template->getParameter('url'); // "..."
    echo $template->toString();          // Serialized template
}
```

### Running Tests

```bash
# From project root
vendor/bin/phpunit tests --testdox --colors=always
vendor/bin/phpstan analyse
```
