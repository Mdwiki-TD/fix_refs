# Wikipedia Reference Fixer

## Overview
This project is a PHP-based tool designed to process and correct Wikipedia article references in wikitext format. It applies a series of text fixes to page content, using a modular architecture that includes parsing, automation, and configuration management components.

## Project Structure

### 1. Core Controller
- **Main entry point:** `index.php` (root directory)
- **Additional controller logic:** `src/index.php`
- **Reads configuration:** `fixwikirefs.json`
- **Coordinates execution:** Calls `fix_page_here` function

### 2. WikiParse Module
- **Responsible for parsing Wikipedia syntax elements**
- **Located in:** `src/WikiParse`
- **Submodules:**
  - **Parsing logic:** `src/WikiParse/parsewiki`
  - **Data models:** `src/WikiParse/parsewiki/DataModel`
    - `Citation.php`
    - `ExternalLink.php`
    - `InternalLink.php`
    - `Table.php`
    - `Template.php`
- **Specific parsing files:**
  - `src/WikiParse/Category.php`
  - `src/WikiParse/Citations.php`
  - `src/WikiParse/Citations_reg.php`
  - `src/WikiParse/Template.php`
  - `src/WikiParse/include_it.php`
  - Various parser files under `src/WikiParse/parsewiki`

### 3. Bot Module
- **Automated scripts for text fixes**
- **Located in:** `src/bots`
- **Includes:**
  - `es_months.php`
  - `es_refs.php`
  - `expend_refs.php`
  - `fix_pt_months.php`
  - `remove_duplicate_refs.php`
  - `txtlib2.php`

### 4. Additional Utility Scripts
- **Provides auxiliary functionalities**
- **Located in:** `src/`
- **Includes:**
  - `infobox.php`, `infobox2.php`
  - `md_cat.php`
  - `mv_dots.php`
  - `sw.php`
  - `es.php`, `include_files.php`, `test_bot.php`

### 5. Supporting CI/CD and External Integration
- **Continuous Integration workflows**
- **Located in:** `.github/workflows`
- **Includes:** `update.yaml`

## System Architecture
### Workflow
1. **Initialization**
   - The **Core Controller** (`index.php`) loads the configuration (`fixwikirefs.json`).
   - Calls `fix_page_here` function.
2. **Parsing & Data Modeling**
   - The **WikiParse Module** identifies elements such as citations, links, and templates.
   - Uses **DataModel** classes (`Citation.php`, `ExternalLink.php`, etc.) to structure parsed data.
3. **Automated Fixes**
   - **Bot scripts** (`src/bots`) apply language-specific rules and remove duplicate references.
4. **Final Output**
   - The **fixed text** is returned to the main script.

## Design Principles
- **Modular Structure:** Clear separation between core processing, parsing, and bot functions.
- **Data-Driven Processing:** Configuration-driven behavior through `fixwikirefs.json`.
- **Layered Approach:** Input (config), parsing (WikiParse), fixes (bots), and output.

## How to Use
1. Clone the repository.
2. Ensure PHP is installed.
3. Configure `fixwikirefs.json`.
4. Run `php index.php` to start the processing.

## Contributing
- Fork the repository and create a feature branch.
- Submit a pull request with detailed changes.
- Follow coding standards for PHP.

## License
This project is open-source and distributed under the [MIT License](LICENSE).

# Diagram 
