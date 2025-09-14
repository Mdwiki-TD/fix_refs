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

function get_full_text($sourcetitle, $mdwiki_revid)
{
    // ---
    $path = "/data/project/mdwikicx";
    // ---
    if (substr(__DIR__, 0, 2) == 'I:') {
        $path = "I:/medwiki/new/medwiki.toolforge.org_repo";
    };
    // ---
    if (empty($mdwiki_revid) || $mdwiki_revid == 0) {
        $json_file = "$path/public_html/revisions_new/json_data.json";
        // ---
        $data = json_decode(file_get_contents($json_file), true) ?? [];
        // ---
        $mdwiki_revid = $data[$sourcetitle] ?? "";
    };
    // ---
    if (empty($mdwiki_revid)) {
        return "";
    };
    // ---
    $file = "$path/public_html/revisions_new/$mdwiki_revid/wikitext.txt";
    // ---
    echo_test($file);
    // ---
    if (!file_exists($file)) {
        echo_debug("file not found: $file");
        return "";
    };
    // ---
    $text = file_get_contents($file) ?? "";
    // ---
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
