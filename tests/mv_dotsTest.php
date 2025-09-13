<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\MovesDots\move_dots_text;

class mv_dotsTest extends TestCase
{
    // Tests for move_dots_text function
    public function testMoveDotsTextSingleDot()
    {
        $input = "This is a sentence。<ref>Reference 1</ref>";
        $expected = "This is a sentence<ref>Reference 1</ref>。";
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
        $input = "Text।<ref>Ref1</ref><ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref><ref>Ref2</ref>।";
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
    public function testMoveDotsTextHy()
    {
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />';
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ<ref name="Os2018" /><ref name="Li2018" />։ Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում<ref name="Luc2021" />։ Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name="Os2018" />։';
        $this->assertEquals($expected, move_dots_text($input, 'hy'));
    }
}
