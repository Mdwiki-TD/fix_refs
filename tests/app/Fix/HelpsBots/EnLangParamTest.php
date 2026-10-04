<?php



use Tests\MyFunctionTest;
use App\Fix\HelpsBots\EnLangParam;

class enLangParamTest extends MyFunctionTest
{
    // Tests for addLangEnToRefs function
    public function testAddLangEnSimpleRef(): void
    {
        $input = "<ref>{{Citar web|Some text}}</ref> {{temp|test=1}}";
        $expected = "<ref>{{Citar web|Some text|language=en}}</ref> {{temp|test=1}}";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }

    public function testAddLangEnExistingLanguage(): void
    {
        $input = "<ref>{{Citar web|Text|language=fr}}</ref>";
        $expected = "<ref>{{Citar web|Text|language=fr}}</ref>";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }

    public function testAddLangEnEmptyRef(): void
    {
        $input = "<ref></ref>";
        $expected = "<ref></ref>";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }

    public function testAddLangEnWithExistingParams(): void
    {
        $input = " {{temp|test=1}} <ref>{{Citar web|Text|author=John}}</ref>";
        $expected = " {{temp|test=1}} <ref>{{Citar web|Text|author=John|language=en}}</ref>";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }

    public function testAddLangMalformedRef(): void
    {
        $input = "<ref>{{Citar web|Text|language = }}</ref> {{temp|test=1}}";
        $expected = "<ref>{{Citar web|Text|language=en}}</ref> {{temp|test=1}}";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }

    public function testAddLangEn(): void
    {
        $input = "<ref>{{Citar web|Text|language=ar}}</ref>";
        $expected = "<ref>{{Citar web|Text|language=ar}}</ref>";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }

    public function testAddLangEnNoChangeNeeded(): void
    {
        $input = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>";
        $expected = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>";
        $this->assertEquals($expected, EnLangParam::addLangEnToRefs($input));
    }
}
