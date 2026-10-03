<?php

namespace App\Fix\WikiParse\src\DataModel;

class Citation
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
