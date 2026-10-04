<?php

namespace App\Fix\HelpsBots;

use App\Logger;
use App\Fix\Parse\CitationsReg;

class MissingRefs
{
    private string $text;
    private string $sourcetitle;
    private int|string $mdwikiRevid;

    public function __construct(string $text, string $sourcetitle, int|string $mdwikiRevid)
    {
        $this->text = $text;
        $this->sourcetitle = str_replace(" ", "_", $sourcetitle);
        $this->mdwikiRevid = $mdwikiRevid;
    }

    private function find_mdwiki_revid(string $jsonFile): string
    {
        if (!is_file($jsonFile)) {
            Logger::debug("jsonFile not found: $jsonFile");
            return "";
        }

        $content = file_get_contents($jsonFile);
        /** @var array<string, mixed> $data */
        $data = ($content !== false) ? (json_decode($content, true) ?? []) : [];

        Logger::debug("url" . $jsonFile);
        Logger::debug("count of data: " . count($data));

        $mdwikiRevid = is_scalar($data[$this->sourcetitle] ?? '')
            ? (string)$data[$this->sourcetitle]
            : "";

        return $mdwikiRevid;
    }

    private function resolve_mdwiki_revid(string $revisionsDir): string
    {
        if (empty($this->mdwikiRevid)) {
            $jsonFile = "$revisionsDir/json_data.json";
            $this->mdwikiRevid = $this->find_mdwiki_revid($jsonFile);
        }

        return (string)$this->mdwikiRevid;
    }

    private function get_full_text(): string
    {
        $revisionsDir = getenv('REVISIONS_DIR') ?: ($_ENV['REVISIONS_DIR'] ?? null);
        if (!$revisionsDir) {
            $home = getenv('HOME') ?: ($_ENV['HOME'] ?? '');
            $revisionsDir = $home ? $home . '/public_html/revisions_new1' : dirname(__DIR__) . '/revisions_new1';
        }

        $revid = $this->resolve_mdwiki_revid($revisionsDir);
        if (empty($revid)) {
            Logger::debug("empty mdwiki_revid, sourcetitle:({$this->sourcetitle})");
            return "";
        }

        $file = "$revisionsDir/{$revid}/wikitext.txt";
        if (!file_exists($file)) {
            Logger::debug("wikitext file not found: $file");
            $file = dirname(__DIR__, 2) . "/resources/revisions/{$revid}/wikitext.txt";
        }

        Logger::debug($file);
        if (!file_exists($file)) {
            Logger::debug("file not found: $file");
            return "";
        }

        Logger::debug("url" . $file);

        return file_get_contents($file) ?: "";
    }

    /**
     * @param array<int|string, array{name: string, tag: string}> $shortRefs
     * @param string $alltext
     * @return string
     */
    private function refs_expend(array $shortRefs, string $alltext): string
    {
        $refs = CitationsReg::get_full_refs($alltext);

        foreach ($shortRefs as $cite) {
            $name = $cite["name"];
            $refe = $cite["tag"];
            $rr = $refs[$name] ?? false;
            if ($rr) {
                Logger::debug("refs_expend: $name");
                $this->text = str_replace($refe, $rr, $this->text);
            }
        }
        return $this->text;
    }

    /**
     * @return array<string, array{content: string, tag: string, name: string, options: string}>
     */
    private function find_empty_short(): array
    {
        $shorts = CitationsReg::get_short_citations($this->text);
        $fulls = CitationsReg::get_full_refs($this->text);
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

    public function fix_missing_refs(): string
    {
        $emptyShort = $this->find_empty_short();
        Logger::debug("empty refs: " . count($emptyShort));
        if (empty($emptyShort)) {
            return $this->text;
        }

        $fullText = $this->get_full_text();

        if (empty($fullText)) {
            return $this->text;
        }

        $this->text = $this->refs_expend($emptyShort, $fullText);
        return $this->text;
    }
}
