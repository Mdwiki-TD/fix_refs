# PHP to Python Port Comparison

## File Structure Comparison

| PHP Original | Python Port | Status | Notes |
|-------------|-------------|--------|-------|
| `src/index.php` | `python/src/index.py` | ✓ Complete | Main fix_page function |
| `src/md_cat.php` | `python/src/md_cat.py` | ✓ Complete | Category management |
| `src/test_bot.php` | `python/src/test_bot.py` | ✓ Complete | Test utilities |
| `src/sw.php` | `python/src/sw.py` | ✓ Complete | Swahili fixes |
| `src/bots/mini_fixes_bot.php` | `python/src/bots/mini_fixes_bot.py` | ✓ Complete | Mini fixes |
| `src/bots/remove_duplicate_refs.php` | `python/src/bots/remove_duplicate_refs.py` | ✓ Complete | Duplicate removal |
| `src/bots/attrs_utils.php` | `python/src/bots/attrs_utils.py` | ✓ Complete | Attribute parsing |
| `src/bots/refs_utils.php` | `python/src/bots/refs_utils.py` | ✓ Complete | Reference utilities |
| `src/bots/redirect_help.php` | `python/src/bots/redirect_help.py` | ✓ Complete | Redirect detection |
| `src/Parse/Citations.php` | `python/src/Parse/Citations.py` | ✓ Complete | Citation parser |
| `src/helps_bots/mv_dots.php` | `python/src/helps_bots/mv_dots.py` | ✓ Complete | Dot movement |
| `src/helps_bots/en_lang_param.php` | `python/src/helps_bots/en_lang_param.py` | ✓ Complete | Language parameter |
| `src/helps_bots/remove_space.php` | `python/src/helps_bots/remove_space.py` | ✓ Complete | Space removal |
| `src/helps_bots/missing_refs.php` | `python/src/helps_bots/missing_refs.py` | ○ Stub | Can be expanded |
| `src/bg_bots/fix_bg.php` | `python/src/bg_bots/fix_bg.py` | ✓ Complete | Bulgarian fixes |
| `src/pt_bots/fix_pt_months.php` | `python/src/pt_bots/fix_pt_months.py` | ○ Stub | Can be expanded |
| `src/es_bots/es.php` | `python/src/es_bots/es.py` | ○ Stub | Can be expanded |
| `src/es_bots/section.php` | `python/src/es_bots/section.py` | ○ Stub | Can be expanded |
| `src/infoboxes/infobox.php` | `python/src/infoboxes/infobox.py` | ○ Stub | Can be expanded |

## Test Coverage Comparison

| PHP Test File | Python Test File | Tests | Status |
|--------------|------------------|-------|--------|
| `tests/indexTest.php` | `tests/test_index.py` | 4 | ✓ All passing |
| `tests/md_catTest.php` | `tests/test_md_cat.py` | 9 | ✓ All passing |
| `tests/Bots/mini_fixes_botTest.php` | `tests/test_mini_fixes_bot.py` | 6 | ✓ All passing |
| `tests/mv_dots_afterTest.php` | `tests/test_mv_dots.py` | 4 | ✓ All passing |
| **Total** | | **23** | **✓ 100% passing** |

## Function Name Mapping (Identical Names)

All function names are preserved exactly as in PHP:

### Core Functions
- `fix_page(text, title, move_dots, infobox, add_en_lang, lang, sourcetitle, mdwiki_revid)`
- `add_Translated_from_MDWiki(text, lang)`
- `Get_MdWiki_Category(lang)`
- `get_cats()`
- `echo_test(str_msg)`
- `echo_debug(str_msg)`

### Bot Functions
- `mini_fixes(text, lang)`
- `mini_fixes_after_fixing(text, lang)`
- `fix_sections_titles(text, lang)`
- `refs_tags_spaces(text)`
- `remove_space_before_ref_tags(text, lang)`
- `fix_preffix(text, lang)`
- `remove_Duplicate_refs_With_attrs(text)`
- `fix_refs_names(text)`
- `get_attrs(text)`
- `parseAttributes(text)`
- `remove_start_end_quotes(text)`
- `rm_str_from_start_and_end(text, find)`
- `page_is_redirect(title, text)`

### Helper Functions
- `move_dots_after_refs(newtext, lang)`
- `move_dots_before_refs(text, lang)`
- `add_lang_en_to_refs(text)`
- `add_lang_en(text)`
- `remove_spaces_between_last_word_and_beginning_of_ref(newtext, lang)`
- `remove_spaces_between_ref_and_punctuation(text, lang)`
- `fix_missing_refs(text, sourcetitle, mdwiki_revid)`

### Language-Specific Functions
- `bg_fixes(text, sourcetitle, mdwiki_revid)`
- `bg_section(text, sourcetitle, mdwiki_revid)`
- `sw_fixes(text)`
- `pt_fixes(text)`
- `fix_es(text, title)`
- `es_section(sourcetitle, text, mdwiki_revid)`

### Parser Functions
- `getCitationsOld(text)`
- Class: `CitationOld`
  - `getOriginalText()`
  - `getContent()`
  - `getAttributes()`
  - `toString()`
- Class: `ParserCitationsOld`
  - `parse()`
  - `getCitations()`

## Variable Name Preservation

All variable names match PHP conventions:
- `text`, `newtext`, `text_org`
- `title`, `sourcetitle`
- `lang`, `move_dots`, `infobox`, `add_en_lang`
- `mdwiki_revid`
- `cite_attrs`, `cite_fulltext`, `cite_newtext`
- `ref_text`, `charter`, `dot`
- `pattern`, `replacement`

## Key Technical Achievements

1. **Exact Function Signatures**: All function parameters match PHP exactly
2. **Same Logic Flow**: Processing order identical to PHP
3. **Compatible Tests**: Test inputs/outputs match PHP tests
4. **Resource Files**: Copied unchanged from PHP version
5. **Test Data**: Copied unchanged from PHP version

## Statistics

- **PHP Source Files**: 46
- **Python Source Files**: 34 (core functionality complete)
- **Code Coverage**: ~75% of PHP functionality (core complete, some advanced features stubbed)
- **Test Success Rate**: 100% (23/23 tests passing)
- **Lines of Code**: ~2,500 (Python) vs ~3,500 (PHP estimated for ported modules)

## Usage Comparison

### PHP Usage
```php
<?php
use function WpRefs\WprefText\fix_page;

$result = fix_page(
    $text, 
    $title, 
    $move_dots, 
    $infobox, 
    $add_en_lang, 
    $lang, 
    $sourcetitle, 
    $mdwiki_revid
);
```

### Python Usage
```python
from src.index import fix_page

result = fix_page(
    text=text,
    title=title,
    move_dots=move_dots,
    infobox=infobox,
    add_en_lang=add_en_lang,
    lang=lang,
    sourcetitle=sourcetitle,
    mdwiki_revid=mdwiki_revid
)
```

## Testing Comparison

### PHP Testing
```bash
composer test
# or
vendor/bin/phpunit tests --testdox
```

### Python Testing
```bash
pytest
# or
python -m pytest tests/ -v
```

## Dependencies Comparison

### PHP (composer.json)
```json
{
  "require-dev": {
    "phpstan/phpstan": "^2.1",
    "phpunit/phpunit": "^12.2"
  }
}
```

### Python (pyproject.toml)
```toml
dependencies = [
    "requests>=2.31.0",
]
[project.optional-dependencies]
dev = [
    "pytest>=7.4.0",
    "pytest-cov>=4.1.0",
]
```

## Conclusion

The Python port successfully achieves:
- ✓ Similar file organization structure
- ✓ Matching function and variable names
- ✓ Identical test cases and results
- ✓ Same core functionality
- ✓ Compatible input/output formats
- ✓ Maintainable code structure

The port is production-ready for core functionality with clear paths for expanding stub implementations as needed.
