# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Fix Refs is a Python library for parsing and fixing references in MediaWiki wikitext. It's designed to clean up and normalize citations, categories, and templates on Wikipedia pages across different language editions.

## Common Commands

```bash
# Install package (editable mode)
pip install -e .

# Install with dev dependencies
pip install -e ".[dev]"

# Run all tests
pytest

# Run specific test file
pytest tests/new/test_citations.py

# Run tests with coverage
pytest --cov=src --cov-report=html

# Type checking
mypy src/

# Linting
pylint src/
```

## Architecture

The codebase is organized into several key modules under `src/`:

### Core Entry Point
- `src/core/fix_page.py` - Main `fix_refs()` function that orchestrates all fixes. This is the primary API - it takes wikitext and a language code, then applies a series of transformations.

### Language-Specific Bots (`src/lang_bots/`)
Each language has its own bot module with specific fixes:
- `pl_bot.py` - Polish Wikipedia fixes
- `pt_bot.py` - Portuguese Wikipedia fixes
- `bg_bot.py` - Bulgarian Wikipedia fixes
- `es_bot.py`, `es_helpers.py`, `es_section.py`, `es_data.py` - Spanish Wikipedia fixes
- `sw_bot.py` - Swahili Wikipedia fixes
- `hy_bot.py` - Armenian Wikipedia fixes

These are called conditionally from `fix_page()` based on the `lang` parameter.

### General Bots (`src/bots/`)
Language-agnostic fix operations:
- `remove_duplicate_refs.py` - Removes duplicate reference tags
- `mini_fixes.py` - Small formatting fixes (applied before and after main fixing)
- `move_dots.py` - Moves punctuation marks after reference tags
- `add_lang_en.py` - Adds English language parameter to references
- `expend_refs.py` - Expands reference templates
- `fix_missing_refs.py` - Attempts to recover missing references
- `attrs_utils.py`, `refs_utils.py`, `txtlib2.py` - Utility functions

### Parsers (`src/parsers/`)
- `citations.py` - Parses `<ref>` tags into `Citation` dataclass objects
- `category.py` - Extracts `[[Category:...]]` tags from wikitext
- `template.py` - Template parsing utilities

### Other Modules
- `src/infobox/expend_infobox.py` - Expands infobox templates
- `src/mdwiki/category.py` - Adds "translated from" categories
- `src/utils/debug.py`, `src/utils/http.py` - Utilities

## Processing Pipeline

When `fix_refs(text, lang)` is called, the processing follows this order (from `fix_page.py`):
1. Check for redirect pages (skip if redirect)
2. Apply language-specific pre-fixes (e.g., Polish)
3. Expand infobox if requested
4. Apply mini fixes
5. Fix missing references
6. Remove duplicate references
7. Move dots after references (optional)
8. Add English language parameter (optional)
9. Apply language-specific post-fixes (pt, bg, es, sw, hy)
10. Add "translated from" category
11. Apply final mini fixes

## Testing Structure

Tests are organized under `tests/` by module:
- `tests/Bots/` - General bot function tests
- `tests/Parse/` - Parser tests
- `tests/{lang}_bots/` - Language-specific bot tests (es_bots, pl_bots, etc.)
- `tests/infoboxes/` - Infobox tests
- `tests/new/` - Newer test files

Test fixtures and path configuration are in `tests/conftest.py`.
