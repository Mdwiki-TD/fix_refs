# Project Audit Report

**Project:** Fix Refs (`mdwiki/fixrefs`)
**Repository:** fix_refs_repo
**Audit Date:** 2026-05-27
**Auditor:** Automated Code Audit

---

## Executive Summary

Fix Refs is a PHP library that parses and fixes `<ref>` tags and citation templates in MediaWiki wikitext. It is used by the MDWiki Translation Dashboard to standardize references in Wikipedia articles translated from English into 11+ languages. The system fetches wikitext via the MediaWiki API, applies a sequential pipeline of text transformations (deduplication, missing ref recovery, punctuation normalization, template localization, category management), and returns the cleaned wikitext.

| Attribute | Value |
|-----------|-------|
| Language | PHP 8.2+ |
| Framework | None (pure PHP, PSR-4 autoloaded) |
| Runtime Dependencies | Zero (only `ext-curl`, `ext-json`) |
| Dev Dependencies | PHPStan ^2.1, PHPUnit ^11.5 |
| Source Files | 53 PHP files |
| Lines of Code | ~4,700 |
| Test Files | 30 PHPUnit test classes |
| Static Analysis | PHPStan Level 5 |
| License | GPL-3.0-or-later |
| Deployment Target | Wikimedia Toolforge |

The architecture follows a pipeline pattern: `fix_page()` in `src/fix_src/index.php` chains 11 transformation stages, each calling focused functions from `bots/`, `helps_bots/`, and `lang_bots/` modules. A separate `WikiParse` submodule provides OOP-based wikitext parsing with recursive template extraction.

---

## Project Health Assessment

### Overall Code Quality: 6/10

The codebase is functional and purpose-built. Core transformation logic is correct and well-tested for its primary use cases. However, naming conventions are inconsistent (mixed `snake_case`, `camelCase`, `PascalCase_Snake`), commented-out code persists in production files, and two parallel parsing systems (`Parse/CitationsOld` vs `WikiParse/ParserCitations`) create ambiguity.

### Maintainability: 6/10

Language-specific bots are well-isolated in separate files/directories, making per-language changes safe. The main risk is the growing `if` chain in `fix_page()` -- adding a new language requires modifying this function directly. No abstraction layer (interface, strategy pattern) exists for language-specific processing.

### Scalability: 7/10

The library handles its current scope (~11 languages, single-article processing) well. cURL timeouts (5s) prevent hanging. The recursive template parser has a `$maxDepth = 10` guard. The main scalability concern is repeated full-text parsing -- `getCitationsOld()` is called 5+ times per pipeline run on the same text.

### Security Posture: 4/10

Multiple security gaps exist. CSRF verification is commented out in the POST endpoint. Debug mode (`display_errors`) is activatable via `?test=1` query parameter. No input validation on the `$lang` parameter. The `$title` parameter is not consistently sanitized before use in regex patterns. Hardcoded file paths expose internal server structure.

### Production Readiness: 6.5/10

The system is already deployed and running on Wikimedia Toolforge, processing real Wikipedia articles. It works reliably for its intended use case. However, the security gaps and the critical variable-name bug in `text_post.php` represent risks that should be addressed before broader adoption.

---

## Cross-Project Analysis

### Shared Architectural Patterns

All modules within `src/` share these patterns:

- **Function-based API** -- Public interfaces are namespaced functions, not classes. Example: `WpRefs\WprefText\fix_page()`.
- **String-in / string-out transforms** -- Every bot function takes a wikitext string and returns a modified wikitext string.
- **cURL with User-Agent** -- All HTTP requests use the same User-Agent string (`WikiProjectMed Translation Dashboard/1.0`) and 5-second timeouts.
- **Regex-heavy processing** -- Core logic relies on `preg_replace`, `preg_match_all`, and `str_replace` rather than AST manipulation.

### Repeated Weaknesses

| Weakness | Occurrences | Files Affected |
|----------|-------------|----------------|
| Duplicate `str_starts_with`/`str_ends_with` polyfills | 2 | `refs_utils.php`, `remove_space.php` |
| Duplicate `start_end()` function | 2 | `fix_pt_months.php`, `es_months.php` |
| Commented-out code in production | 6+ | `fix_src/index.php`, `text_post.php`, `en_lang_param.php`, `es_months.php`, `fix_pt_months.php` |
| Debug output controlled by `$_GET['test']` | 4 | `src/index.php`, `src/test.php`, `src/work.php`, `test_bot.php` |
| No input validation on `$lang` | 3 | `work.php`, `wikitext.php`, `missing_refs.php` |

### Common Technical Debt

1. **Glob-based autoloading** -- `include_files.php` manually globs directories to include PHP files, bypassing the PSR-4 autoloader configured in `composer.json`. This is fragile (file load order matters) and adds startup overhead.

2. **Two parsing systems** -- `Parse/Citations.php` (class `CitationOld`, regex-based) and `WikiParse/src/ParserCitations.php` (OOP, uses `ParserTags`) serve the same purpose. Most bot functions still use the older `getCitationsOld()`.

3. **Static global state** -- `ESData` in `es.php` populates public static arrays at include time. This creates implicit coupling and makes isolated testing difficult.

4. **Environment detection via `$_SERVER`** -- Multiple files check `$_SERVER['SERVER_NAME']` to switch between localhost and production URLs. This is fragile and leaks server information.

### Dependency Issues

- **No runtime dependencies** -- This is a strength. Zero supply-chain risk.
- **Dev tooling is current** -- PHPStan 2.1 and PHPUnit 11.5 are recent versions.
- **External API coupling** -- The library depends on Wikipedia API, Wikidata API, and MDWiki internal APIs (`mdwikicx.toolforge.org`, `mdwiki.toolforge.org/api.php`). No circuit-breaker or retry logic exists.

### Integration Concerns

- **`src/index.php`** expects a `header.php` from the MDWiki main repo at `../header.php`. This hard-couples the tool to the parent project's directory structure.
- **`text_post.php`** is the API-style endpoint but has no authentication, no rate limiting, and the CSRF check is disabled.
- **`work.php`** loads settings from a remote API with a local JSON fallback. If the remote API returns malformed data, the fallback path is silent.

---

## Critical Findings

### HIGH -- Functional Bug in `text_post.php`

**File:** `src/text_post.php`, lines 40-54

```php
$newtext = fix_page_with_setting(...);  // line 40: result stored in $newtext
if (trim($new_text) === trim($text)) {  // line 50: reads $new_text (undefined)
    $final_text = 'no changes';
} else {
    $final_text = $new_text;            // line 53: reads $new_text (undefined)
}
```

The result variable is `$newtext` (no underscore) but the comparison and output use `$new_text` (with underscore). Since `$new_text` is never defined, PHP evaluates `trim(null) === trim($text)`, which is almost always `false`. This means the endpoint always outputs an empty string (since `$new_text` is null, `$final_text` becomes null, and the `echo` outputs nothing). **The POST endpoint is effectively broken.**

**Severity:** Critical -- the API-style text processing endpoint does not function.

### HIGH -- CSRF Protection Disabled

**File:** `src/text_post.php`, line 39

```php
// if (verify_csrf_token()) {
```

CSRF verification is commented out. Any external site can submit POST requests to `text_post.php` on behalf of an authenticated user. While the endpoint currently returns `text/plain` (limiting XSS via response), it can be used to trigger arbitrary wikitext processing and potentially abuse the Wikipedia API through the server.

**Severity:** High -- cross-site request forgery is possible on the POST endpoint.

### HIGH -- Debug Mode Exposed to Users

**Files:** `src/index.php:5`, `src/test.php:3`, `src/work.php:5`, `src/fix_src/test_bot.php:16-22`

```php
if (isset($_GET['test']) || (($_SERVER['SERVER_NAME'] ?? '') === 'localhost')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
```

Any user can append `?test=1` to enable full error display, leaking PHP stack traces, file paths, and internal server structure. Additionally, `echo_test()` in `test_bot.php` outputs debug strings when `$_GET['test']` or `$_POST['test']` is set, potentially revealing processing details.

**Severity:** High -- information disclosure to unauthenticated users.

### MEDIUM -- Hardcoded Server Paths

**File:** `src/fix_src/helps_bots/missing_refs.php`, lines 70-72

```php
$path = ($server == "localhost")
    ? "I:/medwiki/new/medwiki.toolforge.org_repo/public_html"
    : "/data/project/mdwikicx/public_html";
```

A Windows-specific absolute path is hardcoded. This will fail on any non-Windows localhost and exposes the developer's local directory structure. The production path (`/data/project/mdwikicx/`) also reveals Toolforge internal paths.

**Severity:** Medium -- portability issue and information disclosure.

### MEDIUM -- No Input Validation on `$lang`

**Files:** `src/work.php:30`, `src/wikibots/wikitext.php:20,60`

The `$lang` parameter from user input is inserted directly into API URLs (`https://{$lang}.wikipedia.org/...`) and file paths without validation. A malicious value like `../../etc` would produce `https://../../etc.wikipedia.org/...` (cURL would handle this safely, but the principle is wrong). No whitelist of supported language codes exists at the entry point.

**Severity:** Medium -- potential for path traversal or SSRF depending on downstream usage.

### MEDIUM -- Repeated Full-Text Parsing

**File:** `src/fix_src/index.php` (pipeline)

During a single `fix_page()` call, the following functions each independently parse all citations from the full text:

1. `fix_missing_refs()` -> `getCitationsOld()` + `get_short_citations()` + `get_full_refs()`
2. `remove_Duplicate_refs_With_attrs()` -> `getCitationsOld()`
3. `add_lang_en_to_refs()` -> `getCitationsOld()`
4. `pt_fixes()` / `fix_es_months_in_refs()` -> `getCitationsOld()`
5. `mv_es_refs()` -> `getCitationsOld()`

For a 50KB article, this means 5+ full regex passes over the same text. Each pass runs `preg_match_all` with a moderately complex pattern.

**Severity:** Medium -- performance impact on large articles.

### LOW -- Inconsistent Error Handling

cURL failures in `get_curl()` (work.php) echo errors to output but continue execution. `json_decode()` return values are checked in some places (`load_settings_new()`) but not others (`find_mdwiki_revid()`). `file_get_contents()` return values are checked for `false` inconsistently.

**Severity:** Low -- silent failures may cause incorrect output without clear error reporting.

---

## Strengths

### 1. Clean WikiParse Module

The `WikiParse/src/DataModel/` classes (`Template`, `Parameters`, `Tag`, `Attribute`) are well-designed value objects with proper encapsulation, getter/setter methods, and `toString()` serialization. `ParserTemplates` correctly implements recursive descent with a depth limit. This module could be extracted as a standalone library.

### 2. Comprehensive Language Support

Spanish support alone includes 50+ parameter mappings and 20+ template name translations. Each language's processing is isolated in its own file, enabling independent development and testing. The idempotency checks (e.g., "is this category already present?") prevent duplicate modifications.

### 3. Zero Runtime Dependencies

The library has no Composer runtime dependencies. It uses only PHP built-in functions (`preg_*`, `str_*`, `curl_*`, `json_*`). This eliminates supply-chain risk and keeps the deployment footprint minimal.

### 4. Test Infrastructure

30 PHPUnit test classes cover the core bot functions. The custom `assertEqualCompare()` method in `MyFunctionTest` catches silent no-op bugs by verifying that functions actually make changes when changes are expected. PHPStan at level 5 provides additional static safety.

### 5. Defensive API Consumption

All HTTP requests include proper User-Agent headers (Wikimedia API compliance), 5-second timeouts, and fallback mechanisms (Action API -> REST API -> local file). The Wikidata category lookup has a local JSON fallback.

### 6. Idempotent Transformations

Functions like `add_Translated_from_MDWiki()`, `bg_section()`, and `es_section()` check whether their target state already exists before modifying text. This makes the pipeline safe to run multiple times on the same article.

---

## Improvement Roadmap

### Immediate Fixes (1-2 days)

| # | Fix | File | Effort |
|---|-----|------|--------|
| 1 | Fix `$new_text` -> `$newtext` variable name bug | `src/text_post.php:50,53` | 5 min |
| 2 | Uncomment and enable `verify_csrf_token()` | `src/text_post.php:39` | 5 min |
| 3 | Gate `display_errors` behind an environment variable (e.g., `FIX_REFS_DEBUG`) instead of `$_GET['test']` | `src/index.php`, `src/test.php`, `src/work.php`, `src/fix_src/test_bot.php` | 30 min |
| 4 | Remove all commented-out code from production files | Multiple | 30 min |
| 5 | Add input length limit (e.g., 1MB) to `text_post.php` | `src/text_post.php` | 15 min |

### Short-term Improvements (1-2 weeks)

| # | Improvement | Impact |
|---|-------------|--------|
| 1 | Replace glob-based `include_files.php` with Composer PSR-4 autoloading | Eliminates fragile load-order dependency |
| 2 | Consolidate duplicate `str_starts_with`/`str_ends_with` into a single `polyfills.php` | Removes code duplication |
| 3 | Add `$lang` whitelist validation in `work.php` (`in_array($lang, ['es','pt','pl','bg','sw','hy','ar','zh','hi','ru','hr'])`) | Prevents invalid input propagation |
| 4 | Extract User-Agent string to a constant in `wikitext.php` | DRY principle |
| 5 | Add PHPStan analysis for `src/` (currently only covers `src/fix_src/`) | Catches bugs in web layer |
| 6 | Validate API responses in `from_api()` before accessing nested keys | Prevents null access on API errors |
| 7 | Replace `$_SERVER['SERVER_NAME']` checks with `getenv('FIX_REFS_ENV')` | Removes server info leak |

### Long-term Strategic Refactoring (1-3 months)

| # | Refactoring | Rationale |
|---|-------------|-----------|
| 1 | **Extract `LanguageFixerInterface`** with per-language implementations. Replace the `if` chain in `fix_page()` with a registry that loads the appropriate fixer based on `$lang`. | Eliminates the growing `if` chain, enables adding languages without modifying core code |
| 2 | **Deprecate `CitationOld`** in favor of `WikiParse/src/ParserCitations`. Migrate all bot functions to use the OOP parser. | Eliminates dual parsing systems |
| 3 | **Parse citations once** at the start of `fix_page()` and pass the result to all bot functions. | Eliminates 5+ redundant full-text regex passes |
| 4 | **Extract `WikiParse` as a standalone Composer package.** It has no dependencies on the rest of fix_src and could be reused. | Promotes reuse, simplifies testing |
| 5 | **Replace `echo_test()`/`echo_debug()`** with PSR-3 logger injection. | Proper logging, configurable output |
| 6 | **Extract `ESData` static arrays** into JSON configuration files. | Removes global state, enables non-PHP tooling |

### Security Hardening Priorities

| Priority | Action |
|----------|--------|
| P0 | Enable CSRF verification on `text_post.php` |
| P0 | Fix the `$new_text`/`$newtext` bug (currently the endpoint is broken, which paradoxically limits exposure) |
| P1 | Replace `$_GET['test']` debug toggle with environment variable |
| P1 | Add `$lang` whitelist validation |
| P1 | Add `$title` sanitization (`preg_quote` or URL-encode consistently) |
| P2 | Add rate limiting for external API calls (Wikipedia, Wikidata) |
| P2 | Add Content-Type and X-Content-Type-Options headers to all responses |
| P2 | Remove hardcoded paths from `missing_refs.php` |
| P3 | Add Content-Security-Policy headers to HTML endpoints |

### DevOps and Testing Recommendations

| Area | Recommendation |
|------|----------------|
| **CI/CD** | Add GitHub Actions workflow running `composer test` on push/PR |
| **Integration Tests** | Add end-to-end test for `fix_page()` with sample wikitext for each language |
| **Web Endpoint Tests** | Add tests for `index.php` and `text_post.php` using PHPUnit's HTTP client or a test server |
| **Mutation Testing** | Add Infection PHP to verify test quality |
| **Code Style** | Add PHP-CS-Fixer with a consistent ruleset (PSR-12) |
| **Static Analysis** | Extend PHPStan to cover `src/` (not just `src/fix_src/`) and raise to level 6 |
| **Dependency Audit** | Add `composer audit` to CI for dev dependency vulnerability scanning |
| **Caching** | Add in-memory cache (static array) for repeated Wikipedia API calls in the same request |

---

## Final Evaluation

| Metric | Score | Notes |
|--------|-------|-------|
| **Overall Project Score** | **6.0 / 10** | Functional and purpose-built, but has a critical bug and security gaps |
| **Risk Level** | **Medium** | The tool processes wikitext; bugs cause formatting issues, not data loss. The broken POST endpoint limits blast radius. |
| **Technical Debt Level** | **Moderate** | Dual parsing systems, glob includes, naming inconsistencies, commented-out code. Manageable with focused effort. |
| **Production Readiness** | **65%** | Already deployed on Toolforge and processing real articles. Needs the immediate fixes applied before broader adoption. |
| **Security Readiness** | **40%** | CSRF disabled, debug mode exposed, no input validation. The tool runs in a constrained Toolforge environment which limits exploit impact. |
| **Test Coverage** | **60%** | 30 test classes cover core bot functions. Missing: web endpoints, CSRF, integration pipeline, API utilities. |

### Recommended Next Steps

1. **Apply the 5 immediate fixes** (estimated: 2 hours). The `$new_text` bug and CSRF disable are the highest priority.
2. **Run the existing test suite** to establish a baseline: `composer test`.
3. **Add 3 integration tests** for `fix_page()` covering Spanish, Portuguese, and Bulgarian -- the most complex language paths.
4. **Replace glob includes with Composer autoloading** -- this is a high-value, low-risk change.
5. **Plan the `LanguageFixerInterface` extraction** as a 2-week sprint when the team has capacity.

---

*Report generated from analysis of 53 PHP source files (~4,700 lines), 30 test classes, and 4 README documentation files.*
