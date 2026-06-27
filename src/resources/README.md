# Resources - Data Files

Static data files used by the Fix Refs library as fallbacks when remote APIs are unavailable.

> **Note:** This is not a PHP project. It contains JSON configuration files and cached wikitext revisions.

## Project Structure

```
resources/
├── language_settings.json      # Language configuration fallback
├── mdwiki_categories.json      # MDWiki category name mappings per language
└── revisions/                  # Cached MDWiki revision wikitext
    └── 1469242/
        └── wikitext.txt        # Cached wikitext for revision 1469242
```

## Files

### `language_settings.json`

Fallback configuration for language-specific processing flags. Used when the remote API (`https://mdwiki.toolforge.org/api.php?get=language_settings`) is unavailable.

Loaded by: `src/work.php` -> `load_settings_new()`

Each entry contains:
- `lang_code` - Language code (e.g., `es`, `pt`, `pl`)
- `move_dots` - Whether to move punctuation after references (0/1)
- `expend` - Whether to expand infobox templates (0/1)
- `add_en_lang` - Whether to add `|language=en` to citations (0/1)

### `mdwiki_categories.json`

Mapping of Wikipedia language editions to their localized "Translated from MDWiki" category names. Used when the Wikidata API (`Q107014860` sitelinks) is unavailable.

Loaded by: `src/fix_src/md_cat.php` -> `load_from_local_file()`

### `revisions/`

Directory containing cached wikitext files from MDWiki revisions. Each subdirectory is named by revision ID and contains a `wikitext.txt` file.

Used by: `src/fix_src/helps_bots/missing_refs.php` -> `get_full_text()` for expanding short references when the source wikitext is not available via HTTP.

## Usage

These files are loaded automatically by the library when remote data sources are unavailable. No manual configuration is needed.

To add a new cached revision:

```bash
mkdir -p src/resources/revisions/<revision_id>
echo "wikitext content here" > src/resources/revisions/<revision_id>/wikitext.txt
```
