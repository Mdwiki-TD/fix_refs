<?php

namespace WikiConnect\ParseWiki\DataModel;

class Citation
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
