<?php

namespace WpRefs\Parse\Citations;

/*
Usage:

use function WpRefs\Parse\Citations\getCitationsOld;

*/

class CitationOld
{
    private string $text;
    private string $options;
    private string $cite_text;
    public function __construct(string $text, string $options = "", string $cite_text = "")
    {
        $this->text = $text;
        $this->options = $options;
        $this->cite_text = $cite_text;
    }
    public function getOriginalText(): string
    {
        return $this->cite_text;
    }
    public function getTemplate(): string
    {
        return $this->text;
    }
    public function getContent(): string
    {
        return $this->text;
    }
    public function getAttributes(): string
    {
        return $this->options;
    }
    public function toString(): string
    {
        return "<ref " . trim($this->options) . ">" . $this->text . "</ref>";
    }
}

class ParserCitationsOld
{
    private string $text;
    private array $citations;
    public function __construct(string $text)
    {
        $this->text = $text;
        $this->parse();
    }
    private function find_sub_citations($string)
    {
        preg_match_all("/<ref([^\/>]*?)>(.+?)<\/ref>/is", $string, $matches);
        return $matches;
    }
    public function parse(): void
    {
        $text_citations = $this->find_sub_citations($this->text);
        $this->citations = [];
        foreach ($text_citations[1] as $key => $text_citation) {
            $_Citation = new CitationOld($text_citations[2][$key], $text_citation, $text_citations[0][$key]);
            $this->citations[] = $_Citation;
        }
    }

    public function getCitations(): array
    {
        return $this->citations;
    }
}

function getCitationsOld($text)
{
    $do = new ParserCitationsOld($text);
    $citations = $do->getCitations();

    return $citations;
}
