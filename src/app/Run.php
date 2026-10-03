<?php

namespace WpRefs\Run;

use function WpRefs\Settings\loadSettings;
use function WpRefs\WprefText\fix_page;

function fixPageNoSetting(
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

function fixPgeWithSetting(
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
        $newtext = fixPageNoSetting($text, $title, $lang, $sourcetitle, $mdwikiRevid);
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
