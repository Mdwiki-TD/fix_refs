<?php



use PHPUnit\Framework\TestCase;
use function WpRefs\EnLangParam\add_lang_en;
use function WpRefs\EnLangParam\add_lang_en_to_refs;

class en_lang_paramTest extends TestCase
{
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
