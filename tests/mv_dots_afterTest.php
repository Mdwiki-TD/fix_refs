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

    // Additional test cases

    public function testMoveDotsAfterWithWhitespace()
    {
        $input = "Text.  <ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterSelfClosingRef()
    {
        $input = "Text.<ref name=\"ref1\" />";
        $expected = "Text<ref name=\"ref1\" />.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterMultipleRefsWithWhitespace()
    {
        $input = "Text. <ref>Ref1</ref> <ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref> <ref>Ref2</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterNotBeforeRefs()
    {
        $input = "This is a sentence. This is another sentence<ref>Reference</ref>";
        $expected = "This is a sentence. This is another sentence<ref>Reference</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterMultiplePunctuation()
    {
        $input = "Text.,<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>.,";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterEmptyText()
    {
        $input = "";
        $expected = "";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterNoReferences()
    {
        $input = "This is a sentence.";
        $expected = "This is a sentence.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterComplexRefs()
    {
        $input = "Text.<ref name=\"ref1\" group=\"group1\">Reference content</ref>";
        $expected = "Text<ref name=\"ref1\" group=\"group1\">Reference content</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterNestedTags()
    {
        $input = "Text.<ref>Reference with <i>italic</i> text</ref>";
        $expected = "Text<ref>Reference with <i>italic</i> text</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterChinesePunctuation()
    {
        $input = "这是句子。<ref>参考文献1</ref>";
        $expected = "这是句子<ref>参考文献1</ref>。";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDevanagariPunctuation()
    {
        $input = "यह वाक्य है।<ref>संदर्भ 1</ref>";
        $expected = "यह वाक्य है<ref>संदर्भ 1</ref>।";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterArmenianMultiplePunctuation()
    {
        $input = "Տեքստ.,<ref>Հղում</ref>";
        $expected = "Տեքստ<ref>Հղում</ref>.,";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'hy'));
    }

    public function testMoveDotsAtEndOfText()
    {
        $input = "Text.<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterMultipleInstances()
    {
        $input = "First sentence.<ref>Ref1</ref> Second sentence.<ref>Ref2</ref>";
        $expected = "First sentence<ref>Ref1</ref>. Second sentence<ref>Ref2</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function _testMoveDotsAfterMultilineText()
    {
        $input = "This is a sentence.\n<ref>Reference</ref>";
        $expected = "This is a sentence<ref>Reference</ref>.\n";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterOnlyPunctuation()
    {
        $input = ".<ref>Reference</ref>";
        $expected = "<ref>Reference</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }
    public function testMoveDotsAfterDotWithSpace()
    {
        $input = "Text. <ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterCommaWithSpace()
    {
        $input = "Text, <ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>,";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotNoSpace()
    {
        $input = "Text.<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterCommaNoSpace()
    {
        $input = "Text,<ref>Reference</ref>";
        $expected = "Text<ref>Reference</ref>,";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotWithMultipleRefs()
    {
        $input = "Text.<ref>Ref1</ref><ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref><ref>Ref2</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotWithMultipleRefsAndSpaces()
    {
        $input = "Text. <ref>Ref1</ref> <ref>Ref2</ref>";
        $expected = "Text<ref>Ref1</ref> <ref>Ref2</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotWithMultipleRefsAndSpacesAndText()
    {
        $input = "Text. Some text <ref>Ref1</ref> More text <ref>Ref2</ref>";
        $expected = "Text. Some text <ref>Ref1</ref> More text <ref>Ref2</ref>";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotWithMultipleRefsAndSpacesAndTextAndDot()
    {
        $input = "Text. Some text. <ref>Ref1</ref> More text. <ref>Ref2</ref>";
        $expected = "Text. Some text<ref>Ref1</ref>. More text<ref>Ref2</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotWithMultipleRefsAndSpacesAndTextAndDotAndComma()
    {
        $input = "Text, Some text. <ref>Ref1</ref> More text, <ref>Ref2</ref>";
        $expected = "Text, Some text<ref>Ref1</ref>. More text<ref>Ref2</ref>,";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }

    public function testMoveDotsAfterDotWithMultipleRefsAndSpacesAndTextAndDotAndCommaAndDot()
    {
        $input = "Text. Some text, <ref>Ref1</ref> More text. <ref>Ref2</ref>";
        $expected = "Text. Some text<ref>Ref1</ref>, More text<ref>Ref2</ref>.";
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'en'));
    }
    public function testMoveDotsAfterHy()
    {
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />\n\n== test ==';
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ<ref name="Os2018" /><ref name="Li2018" />։ Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում<ref name="Luc2021" />։ Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name="Os2018" />։\n\n== test ==';
        $this->assertEqualCompare($expected, $input, move_dots_after_refs($input, 'hy'));
    }
}
