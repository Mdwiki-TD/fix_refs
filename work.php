<?php

namespace WpRefs\FixPage;

if (isset($_GET['test']) || (($_SERVER['SERVER_NAME'] ?? '') === 'localhost')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
/*
usage:

use function WpRefs\FixPage\fix_page_here;
use function WpRefs\FixPage\DoChangesToText1;
// $text = DoChangesToText1($sourcetitle, $text, $lang, $mdwiki_revid);
*/

include_once __DIR__ . '/src/include_files.php';

use function WpRefs\WprefText\fix_page;
use function WpRefs\TestBot\echo_test;

function get_curl(string $url): string
{
    $usr_agent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $usr_agent);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $output = curl_exec($ch);
    if ($output === FALSE) {
        echo_test("<br>cURL Error: " . curl_error($ch) . "<br>$url");
    }

    curl_close($ch);

    return $output;
}

function json_load_file($filename)
{
    if (!is_file($filename)) {
        return [];
    }

    $content = file_get_contents($filename);
    if ($content === false || $content === '') {
        return [];
    }

    return json_decode($content, true) ?: [];
}

function load_settings_new()
{
    // ---
    $url = "http://localhost:9001/api.php?get=language_settings";
    // ---
    if (($_SERVER['SERVER_NAME'] ?? '') === 'mdwiki.toolforge.org') {
        $url = "https://mdwiki.toolforge.org/api.php?get=language_settings";
        $data = get_curl($url);
    } else {
        $data = file_get_contents($url);
    }
    // ---
    if (!$data) {
        $localFile = __DIR__ . '/resources/language_settings.json';
        $data = is_file($localFile) ? file_get_contents($localFile) : '';
    }
    // ---
    $json = json_decode($data ?: '[]', true);
    if (!is_array($json)) {
        $json = ['results' => []];
    }
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

function fix_page_here($text, $title, $langcode, $sourcetitle, $mdwiki_revid)
{
    $setting = load_settings_new();
    // ---
    $lang_default = isset($setting[$langcode]) ? $setting[$langcode] : [];
    // ---
    // if (empty($lang_default)) { echo 'no settings for ' . $langcode; };
    // ---
    // var_export($lang_default);
    // ---
    $move_dots = isset($lang_default['move_dots']) && $lang_default['move_dots'] == 1;
    $expand = (isset($lang_default['expend']) && $lang_default['expend'] == 1) || true;
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
    $newtext = fix_page_here($text, $title, $lang, $sourcetitle, $mdwiki_revid);
    // ---
    if (empty($newtext)) {
        $newtext = $text;
    }
    // ---
    return $newtext;
}
