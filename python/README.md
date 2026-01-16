# Fix Refs - Python Version

This is a Python port of the PHP fix_refs library. It processes and fixes references in Wikipedia articles using wikitext format.

## Overview

This Python version mirrors the functionality and structure of the original PHP implementation, maintaining the same:
- File organization
- Function and variable names
- Test cases and expected results

## Project Structure

The project follows the same modular architecture as the PHP version:

### 1. Core Controller
- **Main entry point:** `src/index.py`
- **Main function:** `fix_page()` - coordinates all text processing

### 2. WikiParse Module
- **Located in:** `src/WikiParse/`
- **Responsible for parsing Wikipedia syntax elements**
- **Includes data models and parser classes**

### 3. Bot Module
- **Located in:** `src/bots/`
- **Automated scripts for text fixes**
- **Includes language-specific fixes and utilities**

### 4. Language-Specific Modules
- **Portuguese fixes:** `src/pt_bots/`
- **Spanish fixes:** `src/es_bots/`
- **Bulgarian fixes:** `src/bg_bots/`
- **Swahili fixes:** `src/sw.py`

### 5. Helper Modules
- **Located in:** `src/helps_bots/`
- **Includes utilities for various text processing tasks**

## Installation

```bash
cd python
pip install -e .
```

For development:
```bash
pip install -e ".[dev]"
```

## Running Tests

```bash
cd python
pytest
```

Or with coverage:
```bash
pytest --cov=src --cov-report=html
```

## Usage

```python
from src.index import fix_page

# Process wikitext
result = fix_page(
    text="Your wikitext here",
    title="Article Title",
    move_dots=True,
    infobox=True,
    add_en_lang=True,
    lang="en",
    sourcetitle="",
    mdwiki_revid=0
)
```

## How It Works

The main script processes references in Wikipedia articles by:

1. **Expanding Infobox:** Expands the infobox parameters in the text
2. **Removing Duplicate References:** Removes any duplicate references
3. **Moving Dots:** Moves dots in the text after references
4. **Adding English Language References:** Adds `|language=en` to references
5. **Applying Language-specific Fixes:** Applies fixes based on text language
6. **Adding MDWiki Category:** Adds the MDWiki category if not present

## License

This project is open-source and distributed under the GPL-3.0-or-later License.
