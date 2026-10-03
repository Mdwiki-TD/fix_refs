<?php

namespace WpRefs\FixPage;

use function WpRefs\Run\fixPgeWithSetting;

/**
 * WARNING / DEPENDENCY NOTICE:
 *
 * The function `\WpRefs\FixPage\fix_page_with_setting`
 * defined or modified here are referenced and used in:
 * https://github.com/Mdwiki-TD/publish/blob/main/src/su/text_edit.php
 *  - ```if (function_exists('\WpRefs\FixPage\fix_page_with_setting')) { ... }```
 * https://github.com/Mdwiki-TD/mdwiki.toolforge.org/blob/main/src/public_html/fixwikirefs/include.php
 *  - ```use function WpRefs\FixPage\fix_page_with_setting;```
 * Any structural or behavioral changes made to this file must be synchronized
 * and reflected in the referenced file to avoid breaking external functionality.
 */

include_once __DIR__ . '/bootstrap.php';

function fix_page_with_setting(
    string $sourcetitle,
    string $title,
    string $text,
    string $lang,
    int|string $mdwikiRevid,
    ?bool $moveDots = null,
    ?bool $expand = null,
    ?bool $addEnLang = null
): string {
    return fixPgeWithSetting(
        $sourcetitle,
        $title,
        $text,
        $lang,
        $mdwikiRevid,
        $moveDots,
        $expand,
        $addEnLang,
    );
}
