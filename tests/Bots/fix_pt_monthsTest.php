<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;

use function WpRefs\Bots\FixPtMonth\rm_ref_spaces;
use function WpRefs\Bots\FixPtMonth\fix_pt_months_in_texts;
use function WpRefs\Bots\FixPtMonth\fix_pt_months_in_refs;

class fix_pt_monthsTest extends TestCase
{
    private function assertEqualCompare(string $expected, string $input, string $result)
    {
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }
    public function testTempInWikiTexts()
    {
        $input = 'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}}</ref>
<ref name="AHFS2016">{{Citar web|acessodata=8 December 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 December 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 December 2016}}</ref>';
        $expected = 'test: <ref name="AHFS2016">{{Citar web|titulo=Charcoal, Activated|url=https://www.drugs.com/monograph/charcoal-activated.html|publicado=The American Society of Health-System Pharmacists|acessodata=8 de dezembro 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de dezembro 2016}}</ref>
<ref name="AHFS2016">{{Citar web|acessodata=8 de dezembro 2016|urlmorta=live|arquivourl=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|arquivodata=21 de dezembro 2016}} xxxxxxxxxxxxxxxx {{Webarchive|url=https://web.archive.org/web/20161221011707/https://www.drugs.com/monograph/charcoal-activated.html|date=21 de dezembro 2016}}</ref>';
        $result = fix_pt_months_in_refs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testTempInRef()
    {
        // ("25 de dezembro 2016", make_date_new_val_pt("25 December 2016")
        $input = '<ref name="test" group="notes">{{cite web|date=25  December 2016 |}}</ref>';
        $expected = '<ref name="test" group="notes">{{cite web|date=25 de dezembro 2016|}}</ref>';
        $result = fix_pt_months_in_refs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testTempInTemplates()
    {
        $input = '{{cite web|date=10 January, 2023|}}';
        $expected = '{{cite web|date=10 de janeiro 2023|}}';
        $result = fix_pt_months_in_texts($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testTempInTemplatesMore()
    {
        $input = '{{cite web|date=10 January, 2023|}} {{cite book|time = test|date = 10 de janeiro 2023 }}';
        $expected = '{{cite web|date=10 de janeiro 2023|}} {{cite book|time=test|date=10 de janeiro 2023}}';
        $result = fix_pt_months_in_texts($input);
        $this->assertEqualCompare($expected, $input, $result);
    }

    public function testRemoveSpacesAfterDotBeforeRef()
    {
        $input = "This is a sentence . <ref>Reference</ref>";
        $expected = "This is a sentence.<ref>Reference</ref>";
        $this->assertEqualCompare($expected, $input, rm_ref_spaces($input));
    }

    public function testRemoveSpacesAfterCommaBeforeRef()
    {
        $input = "Hello , <ref>Ref</ref>";
        $expected = "Hello,<ref>Ref</ref>";
        $this->assertEqualCompare($expected, $input, rm_ref_spaces($input));
    }

    public function testMultipleRefsTogether()
    {
        $input = "Sentence . <ref>First</ref> <ref>Second</ref>";
        $expected = "Sentence.<ref>First</ref> <ref>Second</ref>";
        $this->assertEqualCompare($expected, $input, rm_ref_spaces($input));
    }

    public function testNoSpacesShouldRemainUnchanged()
    {
        $input = "Correct.<ref>Already OK</ref>";
        $expected = "Correct.<ref>Already OK</ref>";
        $this->assertEqualCompare($expected, $input, rm_ref_spaces($input));
    }

    public function testNoRefNoChange()
    {
        $input = "This is a sentence . Without ref.";
        $expected = $input; // يجب أن يبقى كما هو
        $this->assertEqualCompare($expected, $input, rm_ref_spaces($input));
    }
}
