<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\MoveDots\move_dots_text;
use function WpRefs\MoveDots\add_lang_en;
use function WpRefs\MoveDots\add_lang_en_to_refs;

class mv_dotsTest extends TestCase
{
    // Tests for move_dots_text function
    public function testMoveDotsTextSingleDot()
    {
        $input = "This is a sentence.<ref>Reference 1</ref>";
        $expected = "This is a sentence<ref>Reference 1</ref>.";
        $this->assertEquals($expected, move_dots_text($input, 'en'));
    }

    public function testMoveDotsTextMultipleDots()
    {
        $input = "First sentence. Second sentence.<ref>Reference 1</ref>";
        $expected = "First sentence. Second sentence<ref>Reference 1</ref>.";
        $this->assertEquals($expected, move_dots_text($input, 'en'));
    }

    public function testMoveDotsTextMultipleRefs()
    {
        $input = "Text.<ref>Ref1</ref><ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref><ref>Ref2</ref>.";
        $this->assertEquals($expected, move_dots_text($input, 'en'));
    }

    public function testMoveDotsTextNoDot()
    {
        $input = "Text<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>";
        $this->assertEquals($expected, move_dots_text($input, 'en'));
    }

    public function testMoveDotsTextDifferentPunctuation()
    {
        $input = "Text, <ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>,";
        $this->assertEquals($expected, move_dots_text($input, 'en'));
    }

    // Tests for add_lang_en function
    public function testAddLangEnSimpleRef()
    {
        $input = "<ref>{{Citar web|Some text}}</ref> {{temp|test=1}}";
        $expected = "<ref>{{Citar web|Some text|language=en}}</ref> {{temp|test=1}}";
        $this->assertEquals($expected, add_lang_en($input));
    }

    public function testAddLangEnExistingLanguage()
    {
        $input = "<ref>{{Citar web|Text|language=fr}}</ref>";
        $expected = "<ref>{{Citar web|Text|language=fr}}</ref>";
        $this->assertEquals($expected, add_lang_en($input));
    }

    public function testAddLangEnEmptyRef()
    {
        $input = "<ref></ref>";
        $expected = "<ref></ref>";
        $this->assertEquals($expected, add_lang_en($input));
    }

    public function testAddLangEnWithExistingParams()
    {
        $input = " {{temp|test=1}} <ref>{{Citar web|Text|author=John}}</ref>";
        $expected = " {{temp|test=1}} <ref>{{Citar web|Text|author=John|language=en}}</ref>";
        $this->assertEquals($expected, add_lang_en($input));
    }

    public function testAddLangMalformedRef()
    {
        $input = "<ref>{{Citar web|Text|language = }}</ref> {{temp|test=1}}";
        $expected = "<ref>{{Citar web|Text|language=en}}</ref> {{temp|test=1}}";
        $this->assertEquals($expected, add_lang_en_to_refs($input));
    }

    public function testAddLangEn()
    {
        $input = "<ref>{{Citar web|Text|language=ar}}</ref>";
        $expected = "<ref>{{Citar web|Text|language=ar}}</ref>";
        $this->assertEquals($expected, add_lang_en($input));
    }

    public function testAddLangEnNoChangeNeeded()
    {
        $input = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>";
        $expected = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>";
        $this->assertEquals($expected, add_lang_en($input));
    }
}
