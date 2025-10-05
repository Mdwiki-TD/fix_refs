<?php

namespace WpRefs\MissingRefs;

/*
usage:

use function WpRefs\MissingRefs\fix_missing_refs;

*/

use function WpRefs\TestBot\echo_test;
use function WpRefs\TestBot\echo_debug;
use function WpRefs\Parse\Reg_Citations\getShortCitations;
use function WpRefs\Parse\Reg_Citations\get_full_refs;
use function WpRefs\MdCat\get_url_curl;

function get_full_text_url($sourcetitle, $mdwiki_revid)
{
    // ---
    $path = (($_SERVER["SERVER_NAME"] ?? "localhost") == "localhost")
        ? "http://localhost:9001"
        : "https://mdwikicx.toolforge.org/";
    //---
    if (empty($mdwiki_revid) || $mdwiki_revid == 0) {
        $json_file = "$path/revisions_new/json_data.json";
        // ---
        $data = json_decode(get_url_curl($json_file), true) ?? [];
        // ---
        echo_test("url" . $json_file);
        echo_test("count of data: " . count($data));
        // ---
        $mdwiki_revid = $data[str_replace($sourcetitle, " ", "_")] ?? "";
    };
    // ---
    if (empty($mdwiki_revid)) {
        // ---
        echo_test("empty mdwiki_revid");
        // ---
        return "";
    };
    // ---
    $file = "$path/revisions_new/$mdwiki_revid/wikitext.txt";
    // ---
    echo_test($file);
    // ---
    if (!file_exists($file)) {
        echo_test("file not found: $file");
        return "";
    };
    // ---
    echo_test("url" . $file);
    // ---
    $text = get_url_curl($file);
    // ---
    if (!$text) {
        echo_test("Failed to fetch URL: $file");
        return "";
    }
    // ---
    return $text;
}

function get_full_text($sourcetitle, $mdwiki_revid)
{
    // ---
    $sourcetitle = str_replace(" ", "_", $sourcetitle);
    // ---
    $path = (($_SERVER["SERVER_NAME"] ?? "localhost") == "localhost")
        ? "I:/medwiki/new/medwiki.toolforge.org_repo/public_html"
        : "/data/project/mdwikicx/public_html";
    //---
    if (empty($mdwiki_revid) || $mdwiki_revid == 0) {
        $json_file = "$path/revisions_new/json_data.json";
        // ---
        $data = json_decode(file_get_contents($json_file) ?: "[]", true) ?? [];
        // ---
        echo_test("url" . $json_file);
        echo_test("count of data: " . count($data));
        // ---
        $mdwiki_revid = $data[$sourcetitle] ?? "";
    };
    // ---
    if (empty($mdwiki_revid)) {
        // ---
        echo_test("empty mdwiki_revid, sourcetitle:($sourcetitle)");
        // ---
        return "";
    };
    // ---
    $file = "$path/revisions_new/$mdwiki_revid/wikitext.txt";
    // ---
    echo_test($file);
    // ---
    if (!file_exists($file)) {
        echo_test("file not found: $file");
        return "";
    };
    // ---
    echo_test("url" . $file);
    // ---
    $text = file_get_contents($file) ?: "";
    // ---
    return $text;
}

function refs_expend($short_refs, $text, $alltext)
{
    $refs = get_full_refs($alltext);

    foreach ($short_refs as $cite) {
        $name = $cite["name"];
        $refe = $cite["tag"];
        // ---
        $rr = $refs[$name] ?? false;
        if ($rr) {
            echo_debug("refs_expend: $name");
            // ---
            $text = str_replace($refe, $rr, $text);
        }
    }
    return $text;
}

function find_empty_short($text)
{
    $shorts = getShortCitations($text);
    $fulls = get_full_refs($text);
    $empty_refs = [];
    foreach ($shorts as $cite) {
        $name = $cite["name"];
        // ---
        $rr = $fulls[$name] ?? false;
        if (!$rr) {
            $empty_refs[$name] = $cite;
        }
    }
    // ---
    return $empty_refs;
}

function fix_missing_refs($text, $sourcetitle, $mdwiki_revid)
{
    $empty_short = find_empty_short($text);
    // ---
    echo_debug("empty refs: " . count($empty_short));
    // ---
    if (empty($empty_short)) return $text;
    // ---
    $full_text = get_full_text($sourcetitle, $mdwiki_revid);
    // ---
    if (empty($full_text)) return $text;
    // ---
    $text = refs_expend($empty_short, $text, $full_text);
    // ---
    return $text;
}
