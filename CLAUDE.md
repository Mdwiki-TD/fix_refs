# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Fix Refs is a PHP library for parsing and fixing references in MediaWiki wikitext. It's used by the MDWiki Translation Dashboard to process and standardize citations across different language versions of Wikipedia.

## Commands

```bash
# Install dependencies
composer install

# Run tests (includes PHPStan static analysis)
composer test

# Run PHPUnit tests only
vendor/bin/phpunit tests --testdox --colors=always

# Run PHPStan static analysis only
vendor/bin/phpstan analyse
```

## Architecture

The codebase follows a modular architecture with the `fix_page()` function in `src/index.php` as the main orchestrator. Processing flows through these stages:

1. **Redirect check** - Skip processing if page is a redirect
2. **Language-specific preprocessing** - Polish (`pl_fixes`)
3. **Infobox expansion** - `Expend_Infobox()`
4. **Mini fixes** - General text corrections via `mini_fixes()`
5. **Missing refs** - Fix missing references
6. **Duplicate removal** - `remove_Duplicate_refs_With_attrs()`
7. **Move dots** - Move punctuation after references
8. **Language parameter** - Add `|language=en` to citations
9. **Language-specific fixes** - Portuguese (`pt_fixes`), Bulgarian (`bg_fixes`), Spanish (`fix_es`), Swahili (`sw_fixes`), Armenian (space fixes)
10. **Category addition** - Add MDWiki translation category
11. **Final cleanup** - `mini_fixes_after_fixing()`

### Key Directories

- `src/WikiParse/` - Parser for MediaWiki syntax (templates, citations, links, tables)
- `src/bots/` - Core text transformation functions
- `src/lang_bots/` - Language-specific bots (`es_bots/`, `pt_bots/`, `pl_bots/`, `bg_bots/`)
- `src/helps_bots/` - Helper utilities for refs, dots, language params
- `src/infoboxes/` - Infobox expansion logic
- `tests/` - PHPUnit tests organized by module

### Namespaces

- `WpRefs\` - Main namespace for src/ (PSR-4 autoloaded)
- `WikiConnect\ParseWiki\` - WikiParse module namespace
- `FixRefs\Tests\` - Test namespace

## Testing

Tests extend `MyFunctionTest` (in `tests/bootstrap.php`) which provides `assertEqualCompare($expected, $input, $result)` - this fails if the function makes no changes when changes are expected.

Test file naming: `{feature}Test.php` (e.g., `mv_dots_afterTest.php`)

## Supported Languages

The tool applies language-specific fixes for: `es` (Spanish), `pt` (Portuguese), `pl` (Polish), `bg` (Bulgarian), `sw` (Swahili), `hy` (Armenian), `ar` (Arabic), `zh` (Chinese), `hi` (Hindi), `ru` (Russian)

## Configuration

Settings are loaded from a remote API with local fallback in `resources/language_settings.json`. Each language has flags for `move_dots`, `expend` (infobox expansion), `add_en_lang`, etc.
