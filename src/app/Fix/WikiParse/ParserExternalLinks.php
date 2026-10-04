<?php

namespace App\Fix\WikiParse;

use App\Fix\WikiParse\DataModel\ExternalLink;

/**
 * Class ParserExternalLinks
 * @package App\Fix\WikiParse
 */
class ParserExternalLinks
{
    /**
     * Text to parse
     * @var string
     */
    private string $text;

    /**
     * Array of ExternalLink objects
     * @var ExternalLink[]
     */
    private array $links = [];

    /**
     * ParserExternalLinks constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
        $this->parse();
    }

    /**
     * Find all external links in the given text
     * @param string $string
     * @return array<int, mixed>
     */
    private function find_sub_links(string $string): array
    {
        preg_match_all("/\[(https?:\/\/\S+)(.*?)\]/su", $string, $matches);
        return $matches;
    }

    /**
     * Parse the text for external links
     */
    public function parse(): void
    {
        $textLinks = $this->find_sub_links($this->text);
        $this->links = [];
        if (isset($textLinks[1]) && is_array($textLinks[1])) {
            foreach ($textLinks[1] as $key => $textLink) {
                $_ExternalLinks = new ExternalLink((string)$textLink, trim((string)($textLinks[2][$key] ?? '')));
                $this->links[] = $_ExternalLinks;
            }
        }
    }

    /**
     * Get all external links found in the text
     * @return ExternalLink[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }
}
