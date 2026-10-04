<?php



use Tests\MyFunctionTest;

use App\Fix\Parse\CitationsReg;

class CitationsRegTest extends MyFunctionTest
{

    // اختبارات إضافية للدوال المساعدة
    public function testGetNameWithDoubleQuotes(): void {
        $this->assertEquals("test_name", CitationsReg::get_name('name="test_name"'));
    }

    public function testGetNameWithSingleQuotes(): void {
        $this->assertEquals("test_name", CitationsReg::get_name("name='test_name'"));
    }

    public function testGetNameWithoutQuotes(): void {
        $this->assertEquals("test_name", CitationsReg::get_name("name=test_name"));
    }

    public function testGetNameWithSpaces(): void {
        $this->assertEquals("test name", CitationsReg::get_name("name = 'test name'"));
    }

    public function testGetNameEmpty(): void {
        $this->assertEquals("", CitationsReg::get_name(""));
        $this->assertEquals("", CitationsReg::get_name("other_attr=value"));
    }

    public function testGetRegexCitationsWithMultipleRefs(): void {
        $text = '<ref name="ref1">Content 1</ref> Text <ref name="ref2">Content 2</ref>';
        $citations = CitationsReg::get_regex_citations($text);

        $this->assertCount(2, $citations);
        $this->assertEquals("ref1", $citations[0]["name"]);
        $this->assertEquals("Content 1", $citations[0]["content"]);
        $this->assertEquals('<ref name="ref1">Content 1</ref>', $citations[0]["tag"]);
    }

    public function testGetRegexCitationsWithNoRefs(): void {
        $text = 'No references here';
        $citations = CitationsReg::get_regex_citations($text);
        $this->assertCount(0, $citations);
    }

    public function testGetFullRefs(): void {
        $text = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>';
        $fullRefs = CitationsReg::get_full_refs($text);

        $this->assertCount(2, $fullRefs);
        $this->assertEquals('<ref name="ref1">Content 1</ref>', $fullRefs["ref1"]);
        $this->assertEquals('<ref name="ref2">Content 2</ref>', $fullRefs["ref2"]);
    }

    public function testGetShortCitations(): void {
        $text = '<ref name="ref1"/> Text <ref name="ref2"/>';
        $shortRefs = CitationsReg::get_short_citations($text);

        $this->assertCount(2, $shortRefs);
        $this->assertEquals("ref1", $shortRefs[0]["name"]);
        $this->assertEquals('<ref name="ref1"/>', $shortRefs[0]["tag"]);
    }
}
