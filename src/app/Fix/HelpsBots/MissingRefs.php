<?php

namespace App\Fix\HelpsBots;

use App\Logger;
use App\Fix\MdCat;
use App\Fix\Parse\CitationsReg;

class MissingRefs
{
    public static function get_full_text_url($sourcetitle, $mdwikiRevid)
    {
        $server = $_SERVER["SERVER_NAME"] ?? "localhost";
        $serverPath = ($server == "localhost")
            ? "http://localhost:9001"
            : "https://mdwikicx.toolforge.org";

        if (empty($mdwikiRevid) || $mdwikiRevid == 0) {
            $jsonFile = "$serverPath/revisions_new1/json_data.json";
            $data = json_decode(MdCat::get_url_curl($jsonFile), true) ?? [];
            Logger::debug("url" . $jsonFile);
            Logger::debug("count of data: " . count($data));
            $mdwikiRevid = $data[str_replace(" ", "_", $sourcetitle)] ?? "";
        }

        if (empty($mdwikiRevid)) {
            Logger::debug("empty mdwiki_revid");
            return "";
        }

        $fullUrl = "$serverPath/revisions_new1/$mdwikiRevid/wikitext.txt";
        Logger::debug("url" . $fullUrl);
        $text = MdCat::get_url_curl($fullUrl);
        if (!$text) {
            Logger::debug("Failed to fetch URL: $fullUrl");
            return "";
        }

        return $text;
    }

    public static function find_mdwiki_revid($sourcetitle, $jsonFile)
    {
        if (!is_file($jsonFile)) {
            return "";
        }
        $content = file_get_contents($jsonFile);
        $data = json_decode($content, true) ?? [];
        Logger::debug("url" . $jsonFile);
        Logger::debug("count of data: " . count($data));
        $mdwikiRevid = $data[$sourcetitle] ?? "";
        return $mdwikiRevid;
    }

    public static function get_full_text($sourcetitle, $mdwikiRevid)
    {
        $sourcetitle = str_replace(" ", "_", $sourcetitle);
        $revisionsDir = getenv('REVISIONS_DIR') ?: ($_ENV['REVISIONS_DIR'] ?? null);
        if (!$revisionsDir) {
            $home = getenv('HOME') ?: ($_ENV['HOME'] ?? '');
            $revisionsDir = $home ? $home . '/public_html/revisions_new1' : dirname(__DIR__) . '/revisions_new1';
        }
        $jsonFile = "$revisionsDir/json_data.json";
        if (empty($mdwikiRevid) || $mdwikiRevid == 0) {
            $mdwikiRevid = self::find_mdwiki_revid($sourcetitle, $jsonFile);
        }
        if (empty($mdwikiRevid)) {
            Logger::debug("empty mdwiki_revid, sourcetitle:($sourcetitle)");
            return "";
        }
        $file = "$revisionsDir/$mdwikiRevid/wikitext.txt";
        if (!file_exists($file)) {
            $file = dirname(__DIR__, 2) . "/resources/revisions/$mdwikiRevid/wikitext.txt";
        }
        Logger::debug($file);
        if (!file_exists($file)) {
            Logger::debug("file not found: $file");
            return "";
        }
        Logger::debug("url" . $file);
        $text = file_get_contents($file) ?: "";
        return $text;
    }

    public static function refs_expend($shortRefs, $text, $alltext)
    {
        $refs = CitationsReg::get_full_refs($alltext);

        foreach ($shortRefs as $cite) {
            $name = $cite["name"];
            $refe = $cite["tag"];
            $rr = $refs[$name] ?? false;
            if ($rr) {
                Logger::debug("refs_expend: $name");
                $text = str_replace($refe, $rr, $text);
            }
        }
        return $text;
    }

    public static function find_empty_short($text)
    {
        $shorts = CitationsReg::get_short_citations($text);
        $fulls = CitationsReg::get_full_refs($text);
        $emptyRefs = [];
        foreach ($shorts as $cite) {
            $name = $cite["name"];
            $rr = $fulls[$name] ?? false;
            if (!$rr) {
                $emptyRefs[$name] = $cite;
            }
        }
        return $emptyRefs;
    }

    public static function fix_missing_refs($text, $sourcetitle, $mdwikiRevid)
    {
        $emptyShort = self::find_empty_short($text);
        Logger::debug("empty refs: " . count($emptyShort));
        if (empty($emptyShort)) return $text;
        $fullText = self::get_full_text($sourcetitle, $mdwikiRevid);
        if (empty($fullText)) return $text;
        $text = self::refs_expend($emptyShort, $text, $fullText);
        return $text;
    }
}
