<?php

namespace WpRefs\FixPage;
/*
usage:

use function WpRefs\FixPage\fix_page_here;
use function WpRefs\FixPage\DoChangesToText1;
// $text = DoChangesToText1($sourcetitle, $text, $lang, $mdwiki_revid);
*/

include_once __DIR__ . '/src/include_files.php';

use function WpRefs\WprefText\fix_page;

function json_load_file($filename)
{
    $content = file_get_contents($filename);
    return json_decode($content, true);
}

function load_settings_new()
{
    $url = "https://mdwiki.toolforge.org/api.php?get=language_settings";
    // ---
    $json = file_get_contents($url);
    // ---
    $json = json_decode($json, true);
    // ---
    $data = $json['results'] ?? [];
    // ---
    $new = [];
    // ---
    foreach ($data as $key => $value) {
        $new[$value['lang_code']] = $value;
    }
    // ---
    return $new;
}

$setting = load_settings_new();

function fix_page_here($text, $title, $langcode, $sourcetitle, $mdwiki_revid)
{
    global $setting;
    // ---
    $lang_default = isset($setting[$langcode]) ? $setting[$langcode] : [];
    // ---
    // var_export($lang_default);
    // ---
    $move_dots = isset($lang_default['move_dots']) && $lang_default['move_dots'] == 1;
    $expand = isset($lang_default['expend']) && $lang_default['expend'] == 1;
    $add_en_lang = isset($lang_default['add_en_lang']) && $lang_default['add_en_lang'] == 1;
    // ---
    $text = fix_page($text, $title, $move_dots, $expand, $add_en_lang, $langcode, $sourcetitle, $mdwiki_revid);
    // ---
    return $text;
}
// $text = DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid);

function DoChangesToText1($sourcetitle, $title, $text, $lang, $mdwiki_revid)
{
    // ---
    $text = fix_page_here($text, $title, $lang, $sourcetitle, $mdwiki_revid);
    // ---
    return $text;
}
