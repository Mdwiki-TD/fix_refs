<?php

namespace App\Fix\Parse\Citations;



class CitationOld
{
    private string $text;
    private string $options;
    private string $citeText;
    public function __construct(string $text, string $options = "", string $citeText = "")
    {
        $this->text = $text;
        $this->options = $options;
        $this->citeText = $citeText;
    }
    public function getOriginalText(): string
    {
        return $this->citeText;
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
        preg_match_all("/<ref([^\/>]*?)>(.+?)<\/ref>/isu", $string, $matches);
        return $matches;
    }
    public function parse(): void
    {
        $textCitations = $this->find_sub_citations($this->text);
        $this->citations = [];
        foreach ($textCitations[1] as $key => $textCitation) {
            $_Citation = new CitationOld($textCitations[2][$key], $textCitation, $textCitations[0][$key]);
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
