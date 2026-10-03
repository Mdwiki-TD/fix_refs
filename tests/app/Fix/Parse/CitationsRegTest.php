<?php



use Tests\MyFunctionTest;

use function App\Fix\Parse\Reg_Citations\get_name;
use function App\Fix\Parse\Reg_Citations\get_regex_citations;
use function App\Fix\Parse\Reg_Citations\get_full_refs;
use function App\Fix\Parse\Reg_Citations\get_short_citations;

class CitationsRegTest extends MyFunctionTest
{

    // اختبارات إضافية للدوال المساعدة
    public function testGetNameWithDoubleQuotes()
    {
        $this->assertEquals("test_name", get_name('name="test_name"'));
    }

    public function testGetNameWithSingleQuotes()
    {
        $this->assertEquals("test_name", get_name("name='test_name'"));
    }

    public function testGetNameWithoutQuotes()
    {
        $this->assertEquals("test_name", get_name("name=test_name"));
    }

    public function testGetNameWithSpaces()
    {
        $this->assertEquals("test name", get_name("name = 'test name'"));
    }

    public function testGetNameEmpty()
    {
        $this->assertEquals("", get_name(""));
        $this->assertEquals("", get_name("other_attr=value"));
    }

    public function testGetRegexCitationsWithMultipleRefs()
    {
        $text = '<ref name="ref1">Content 1</ref> Text <ref name="ref2">Content 2</ref>';
        $citations = get_regex_citations($text);

        $this->assertCount(2, $citations);
        $this->assertEquals("ref1", $citations[0]["name"]);
        $this->assertEquals("Content 1", $citations[0]["content"]);
        $this->assertEquals('<ref name="ref1">Content 1</ref>', $citations[0]["tag"]);
    }

    public function testGetRegexCitationsWithNoRefs()
    {
        $text = 'No references here';
        $citations = get_regex_citations($text);
        $this->assertCount(0, $citations);
    }

    public function testGetFullRefs()
    {
        $text = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>';
        $fullRefs = get_full_refs($text);

        $this->assertCount(2, $fullRefs);
        $this->assertEquals('<ref name="ref1">Content 1</ref>', $fullRefs["ref1"]);
        $this->assertEquals('<ref name="ref2">Content 2</ref>', $fullRefs["ref2"]);
    }

    public function testGetShortCitations()
    {
        $text = '<ref name="ref1"/> Text <ref name="ref2"/>';
        $shortRefs = get_short_citations($text);

        $this->assertCount(2, $shortRefs);
        $this->assertEquals("ref1", $shortRefs[0]["name"]);
        $this->assertEquals('<ref name="ref1"/>', $shortRefs[0]["tag"]);
    }
}
