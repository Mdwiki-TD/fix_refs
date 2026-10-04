<?php

namespace App\Fix;

use App\Logger;

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
            Logger::debug("<br>cURL Error: " . curl_error($ch) . "<br>$url");
            $output = '';
        }

        curl_close($ch);

        return $output;
    }

    /**
     * @return array<string, mixed>
     */
    public static function load_from_local_file(): array
    {
        $localFile = dirname(__DIR__) . '/resources/mdwiki_categories.json';
        if (!is_file($localFile)) {
            return [];
        }

        $content = file_get_contents($localFile);
        if ($content === false || $content === '') {
            return [];
        }

        /** @var array<string, mixed>|null $decoded */
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function get_cats(): array
    {
        $url = "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/Q107014860/sitelinks";
        /** @var array<string, mixed>|null $json */
        static $json = null;

        if ($json !== null) {
            return $json;
        }

        $data = self::get_url_curl($url);
        $decoded = json_decode($data, true);

        if (!is_array($decoded) || empty($decoded)) {
            $decoded = self::load_from_local_file();
        }

        $json = $decoded;

        return $json;
    }

    public static function Get_MdWiki_Category(string $lang): string
    {
        // https://it.wikipedia.org/w/index.php?title=Categoria:Translated_from_MDWiki&action=edit&redlink=1
        $skipLangs = [
            "it"
        ];

        if (in_array($lang, $skipLangs)) {
            return "";
        }

        $cats = self::get_cats();

        $cat = $cats[$lang . "wiki"]["title"] ?? "Category:Translated from MDWiki";

        return is_string($cat) ? $cat : "Category:Translated from MDWiki";
    }

    public static function add_Translated_from_MDWiki(string $text, string $lang): string
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
