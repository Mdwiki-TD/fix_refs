<?php

namespace WpRefs\FixPage;

/**
 * WARNING / DEPENDENCY NOTICE:
 *
 * The functions `\WpRefs\FixPage\DoChangesToText1` and `\WpRefs\FixPage\fix_page_with_setting`
 * defined or modified here are referenced and used in:
 * https://github.com/Mdwiki-TD/publish/blob/main/src/su/text_edit.php
 *
 * E.g.,
 * if (function_exists('\WpRefs\FixPage\fix_page_with_setting')) { ... }
 * if (function_exists('\WpRefs\FixPage\DoChangesToText1')) { ... }
 *
 * Any structural or behavioral changes made to this file must be synchronized
 * and reflected in the referenced file to avoid breaking external functionality.
 */

use function WpRefs\Settings\loadSettings;
use function WpRefs\WprefText\fix_page;

include_once __DIR__ . '/include.php';

function fix_page_no_setting(
    string $text,
    string $title,
    string $langcode,
    string $sourcetitle,
    int|string $mdwikiRevid
): string {
    $setting = loadSettings();
    $langDefault = isset($setting[$langcode]) && is_array($setting[$langcode])
        ? $setting[$langcode]
        : [];

    $moveDots = isset($langDefault['move_dots']) && (int)$langDefault['move_dots'] === 1;
    $expand = true; // (isset($langDefault['expend']) && (int)$langDefault['expend'] === 1);

    $addEnLang = isset($langDefault['add_en_lang']) && (int)$langDefault['add_en_lang'] === 1;

    $processedText = fix_page(
        $text,
        $title,
        $moveDots,
        $expand,
        $addEnLang,
        $langcode,
        $sourcetitle,
        $mdwikiRevid,
    );

    return (string)$processedText;
}

function DoChangesToText1(
    string $sourcetitle,
    string $title,
    string $text,
    string $lang,
    int|string $mdwikiRevid
): string {
    $newtext = fix_page_no_setting($text, $title, $lang, $sourcetitle, $mdwikiRevid);

    if (empty($newtext)) {
        $newtext = $text;
    }

    return $newtext;
}

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
    if ($moveDots === null && $expand === null && $addEnLang === null) {
        $newtext = fix_page_no_setting($text, $title, $lang, $sourcetitle, $mdwikiRevid);
    } else {
        $newtext = fix_page(
            $text,
            $title,
            $moveDots,
            $expand,
            $addEnLang,
            $lang,
            $sourcetitle,
            $mdwikiRevid,
        );
    }

    if (empty($newtext)) {
        $newtext = $text;
    }

    return (string)$newtext;
}
