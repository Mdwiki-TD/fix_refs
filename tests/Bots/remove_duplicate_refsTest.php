<?php



use PHPUnit\Framework\TestCase;

use function WpRefs\DelDuplicateRefs\remove_Duplicate_refs_With_attrs;
use function WpRefs\DelDuplicateRefs\fix_refs_names;


class remove_duplicate_refsTest extends TestCase
{
    private function assertEqualCompare(string $expected, string $input, string $result)
    {
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }

    // اختبارات دالة fix_refs_names
    public function testFixRefsNames()
    {
        $tests = [
            // Case: Reference without attributes
            [
                "input" => "<ref>Simple reference</ref>",
                "expected" => "<ref>Simple reference</ref>"
            ],
            // Case: Reference with name attribute in double quotes
            [
                "input" => '<ref name="te1">Reference</ref>',
                "expected" => "<ref name=\"te1\">Reference</ref>"
            ],
            // Case: Reference with name attribute in single quotes
            [
                "input" => "<ref name='te2'>Reference</ref>",
                "expected" => '<ref name="te2">Reference</ref>'
            ],
            // Case: Reference with multiple attributes
            [
                "input" => '<ref name="te3" group="notes">Reference</ref>',
                "expected" => "<ref name=\"te3\" group=\"notes\">Reference</ref>"
            ],
            // Case: Reference with attribute without value
            [
                "input" => '<ref name>Reference</ref>',
                "expected" => "<ref name=\"\">Reference</ref>"
            ],
            // Case: Reference with attribute containing internal quotes
            [
                "input" => '<ref name="test\'quote">Reference</ref>',
                "expected" => "<ref name=\"test'quote\">Reference</ref>"
            ],
            // Case: Multiple references
            [
                "input" => '<ref name="a">Ref1</ref> <ref name=\'b\'>Ref2</ref>',
                "expected" => "<ref name=\"a\">Ref1</ref> <ref name=\"b\">Ref2</ref>"
            ],
            // Case: Reference with extra spaces in attributes
            // [ "input" => '<ref  name  =  "te4"  >Reference</ref>', "expected" => '<ref name="te4">Reference</ref>' ],
            // Case: Reference with mixed quote types
            [
                "input" => '<ref name="te5" group=\'notes\'>Reference</ref>',
                "expected" => "<ref name=\"te5\" group=\"notes\">Reference</ref>"
            ]
        ];

        foreach ($tests as $test) {
            $result = fix_refs_names($test['input']);
            $this->assertEqualCompare($test['expected'], $test['input'], $result);
        }
    }

    // اختبارات دالة remove_Duplicate_refs_With_attrs
    public function testRemoveDuplicateRefs()
    {
        $tests = [
            [
                "input" => 'test <ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule}}</ref> adv <ref name="PI2023" /> 205<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule}} any test</ref>',
                "expected" => 'test <ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule}}</ref> adv <ref name="PI2023" /> 205<ref name="PI2023" />'
            ],
            [
                "input" => 'test <ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule}}</ref> adv 205<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule}} any test</ref>',
                "expected" => 'test <ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule}}</ref> adv 205<ref name="PI2023" />'
            ],
            // Case: Single reference without name
            [
                "input" => "<ref>Reference without name1</ref>",
                "expected" => '<ref>Reference without name1</ref>'
            ],
            // Case: Two references with same name
            [
                "input" => '<ref name="test">Refs</ref> <ref name="test">Refs</ref>',
                "expected" => '<ref name="test">Refs</ref> <ref name="test" />'
            ],
            // Case: Different references
            [
                "input" => '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>',
                "expected" => '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>'
            ],
            // Case: References with multiple attributes
            [
                "input" => '<ref name="test" group="notes">Refs2</ref> <ref name="test" group="notes">Refs2</ref>',
                "expected" => '<ref name="test" group="notes">Refs2</ref> <ref name="test" group="notes" />'
            ],
        ];

        foreach ($tests as $test) {
            $result = remove_Duplicate_refs_With_attrs($test['input']);
            $this->assertEqualCompare($test['expected'], $test['input'], $result);
        }
    }
    public function testRemoveGroupRefsDiff()
    {
        $input = '<ref name="test" group="notes">Ref</ref> <ref name="test" group="notes">Ref</ref>';
        $expected = '<ref name="test" group="notes">Ref</ref> <ref name="test" group="notes" />';
        $result = remove_Duplicate_refs_With_attrs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveMedRefs()
    {
        $input = '<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule|url=https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|website=dailymed.nlm.nih.gov|access-date=24 May 2023|archive-date=1 July 2023|archive-url=https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|url-status=live}}</ref> ଅତିକମରେ ୧୬ ବର୍ଷ ବୟସରେ ଏହା ବ୍ୟବହୃତ ହୁଏ ।<ref name="PI2023" /> ଏହା ପାଟିରେ ଦିଆଯାଏ ।<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule|url=https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|website=dailymed.nlm.nih.gov|access-date=24 May 2023|archive-date=1 July 2023|archive-url=https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|url-status=live}}<cite class="citation web cs1" data-ve-ignore="true">[https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e "DailyMed - SKYCLARYS- omaveloxolone capsule"]. \'\'dailymed.nlm.nih.gov\'\'. [https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e Archived] from the original on 1 July 2023<span class="reference-accessdate">. Retrieved <span class="nowrap">24 May</span> 2023</span>.</cite></ref>';
        $expected = '<ref name="PI2023">{{Cite web|title=DailyMed - SKYCLARYS- omaveloxolone capsule|url=https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|website=dailymed.nlm.nih.gov|access-date=24 May 2023|archive-date=1 July 2023|archive-url=https://web.archive.org/web/20230701174203/https://dailymed.nlm.nih.gov/dailymed/drugInfo.cfm?setid=f1a1100e-8318-1596-e053-2995a90a533e|url-status=live}}</ref> ଅତିକମରେ ୧୬ ବର୍ଷ ବୟସରେ ଏହା ବ୍ୟବହୃତ ହୁଏ ।<ref name="PI2023" /> ଏହା ପାଟିରେ ଦିଆଯାଏ ।<ref name="PI2023" />';
        $result = remove_Duplicate_refs_With_attrs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function _testRemoveGroupRefs()
    {
        $input = '<ref name="test" group="notes">Ref</ref> <ref group="notes" name="test">Ref</ref>';
        $expected = '<ref name="test" group="notes">Ref</ref> <ref group="notes" name="test" />';
        $result = remove_Duplicate_refs_With_attrs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveIdenticalRefs_WithGroupAttribute()
    {
        $input = '<ref group="notes">Refs3</ref> <ref group="notes">Refs3</ref>';
        // $expected = '<ref name="autogen_1" group="notes">Refs3</ref> <ref name="autogen_1" group="notes" />';
        $expected = '<ref group="notes">Refs3</ref> <ref group="notes" />';
        $result = remove_Duplicate_refs_With_attrs($input);
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testFileText()
    {
        $text_input   = file_get_contents(__DIR__ . "/texts/del_dup_input.txt");
        $text_output  = file_get_contents(__DIR__ . "/texts/del_dup_output.txt");
        // --
        $result = remove_Duplicate_refs_With_attrs($text_input);
        // --
        $this->assertEqualCompare($text_output, $text_input, $result);
    }
}
