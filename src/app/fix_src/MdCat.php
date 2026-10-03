<?php

namespace App\fix_src;

class MdCat
{
    public static function get_url_curl(string $url): string
    {
        $usrAgent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $usrAgent);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $output = curl_exec($ch);
        if ($output === false) {
            DebugHelper::echo_test("<br>cURL Error: " . curl_error($ch) . "<br>$url");
        }

        curl_close($ch);

        return $output;
    }

    public static function load_from_local_file()
    {
        $localFile = dirname(__DIR__) . '/resources/mdwiki_categories.json';
        if (!is_file($localFile)) {
            return [];
        }

        $content = file_get_contents($localFile);
        if ($content === false || $content === '') {
            return [];
        }

        return json_decode($content, true) ?: [];
    }

    public static function get_cats()
    {
        $url = "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/Q107014860/sitelinks";
        static $json = null;

        if (is_array($json)) {
            return $json;
        }

        $data = self::get_url_curl($url);
        $decoded = json_decode($data, true);

        if (!is_array($decoded) || empty($decoded)) {
            $decoded = self::load_from_local_file();
        }

        $json = is_array($decoded) ? $decoded : [];

        return $json;
    }

    public static function Get_MdWiki_Category($lang)
    {
        $skipLangs = [
            "it"
        ];
        if (in_array($lang, $skipLangs)) {
            return "";
        }
        $cats = self::get_cats();
        $cat = $cats[$lang . "wiki"]["title"] ?? "Category:Translated from MDWiki";
        return $cat;
    }

    public static function add_Translated_from_MDWiki($text, $lang)
    {
        if (preg_match("/:\s*Translated[ _]from[ _]MDWiki\s*\]\]/iu", $text)) {
            return $text;
        }
        $cat = self::Get_MdWiki_Category($lang);
        if (!empty($cat) && strpos($text, $cat) === false) {
            $text .= "\n[[$cat]]\n";
        }
        return $text;
    }
}
