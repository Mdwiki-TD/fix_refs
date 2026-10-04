<?php

namespace App\Fix\Parse;

use App\Fix\WikiParse\DataModel\Citation;

/**
 * Class Citations
 *
 * Parses text to extract citations from wikitext.
 *
 */
class Citations
{
    /**
     * @var string The text to parse for citations.
     */
    private string $text;

    /**
     * @var Citation[] Array of extracted citations.
     */
    private array $citations = [];

    /**
     * Citations constructor.
     *
     * @param string $text The text to parse.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
        $this->parse();
    }

    /**
     * @param string $string
     * @return array<int, mixed>
     */
    private function find_sub_citations(string $string): array
    {
        preg_match_all("/<ref([^\/>]*?)>(.+?)<\/ref>/isu", $string, $matches);
        return $matches;
    }

    /**
     * Parse the text for <ref> tags using ParserTags and store them.
     *
     * @return void
     */
    public function parse(): void
    {
        $textCitations = $this->find_sub_citations($this->text);
        $this->citations = [];
        if (isset($textCitations[1]) && is_array($textCitations[1])) {
            foreach ($textCitations[1] as $key => $textCitation) {
                $_Citation = new Citation((string)($textCitations[2][$key] ?? ''), (string)$textCitation, (string)($textCitations[0][$key] ?? ''));
                $this->citations[] = $_Citation;
            }
        }
    }

    /**
     * Get all citations found in the text.
     *
     * @return Citation[] Array of Citation objects.
     */
    public function getCitations(): array
    {
        return $this->citations;
    }

    /**
     * @param string $text
     * @return Citation[]
     */
    public static function getCitationsOld(string $text): array
    {
        return (new self($text))->getCitations();
    }
}
