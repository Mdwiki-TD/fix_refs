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

function load_from_local_file()
{
    $localFile = dirname(__DIR__) . '/resources/mdwiki_categories.json';
    $decoded = [];
    if (is_file($localFile)) {
        $decoded = json_decode(file_get_contents($localFile) ?: '[]', true);
    }
    return $decoded;
}
function get_cats()
{
    $url = "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/Q107014860/sitelinks";
    static $json = null;

    if (is_array($json)) {
        return $json;
    }

    $data = get_url_curl($url);
    $decoded = json_decode($data, true);

    if (!is_array($decoded) || empty($decoded)) {
        $decoded = load_from_local_file();
    }

    $json = is_array($decoded) ? $decoded : [];

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
