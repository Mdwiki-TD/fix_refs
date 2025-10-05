<?php

namespace WpRefs\MdCat;
/*

use function WpRefs\MdCat\add_Translated_from_MDWiki;
use function WpRefs\MdCat\get_url_curl;

*/

use function WpRefs\TestBot\echo_test;



function get_url_curl(string $url): string
{
    $usr_agent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    // curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

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

function get_cats()
{
    $url = "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/Q107014860/sitelinks";
    // ---
    static $json = [];
    // ---
    if (!empty($json)) {
        return $json;
    }
    // ---
    $data = get_url_curl($url);
    // ---
    $json = json_decode($data, true) ?: [];
    // ---
    return $json;
}

function Get_MdWiki_Category($lang)
{
    // ---
    // https://it.wikipedia.org/w/index.php?title=Categoria:Translated_from_MDWiki&action=edit&redlink=1
    $skip_langs = [
        "it"
    ];
    // ---
    if (in_array($lang, $skip_langs)) {
        return "";
    }
    // ---
    $cats = get_cats();
    // ---
    $cat = $cats[$lang . "wiki"]["title"] ?? "Category:Translated from MDWiki";
    // ---
    return $cat;
}

function add_Translated_from_MDWiki($text, $lang)
{
    // ---
    if (preg_match("/:\s*Translated[ _]from[ _]MDWiki\s*\]\]/iu", $text)) {
        return $text;
    };
    // ---
    $cat = Get_MdWiki_Category($lang);
    // ---
    if (!empty($cat) && strpos($text, $cat) === false) {
        $text .= "\n[[$cat]]\n";
    }
    // ---
    return $text;
}
