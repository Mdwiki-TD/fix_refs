<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\MovesDots\move_dots_after_refs;

class mv_dots_afterTest extends TestCase
{
    private function assertEqualCompare(string $expected, string $input, string $result)
    {
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }
    // Tests for move_dots_after_refs function
    public function testMoveDotsAfterSingleDot()
    {
        $input = "This is a sentence。<ref>Reference 1</ref>";
        $expected = "This is a sentence<ref>Reference 1</ref>。";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterMultipleDots()
    {
        $input = "First sentence. Second sentence.<ref>Reference 1</ref>";
        $expected = "First sentence. Second sentence<ref>Reference 1</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterMultipleRefs()
    {
        $input = "Text।<ref>Ref1</ref><ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref><ref>Ref2</ref>।";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterNoDot()
    {
        $input = "Text<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDifferentPunctuation()
    {
        $input = "Text, <ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>,";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterHy()
    {
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />';
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ<ref name="Os2018" /><ref name="Li2018" />։ Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում<ref name="Luc2021" />։ Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name="Os2018" />։';
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'hy'));
    }
}
