<?php



use FixRefs\Tests\MyFunctionTest;

use function WpRefs\Parse\Reg_Citations\get_name;
use function WpRefs\Parse\Reg_Citations\get_regex_citations;
use function WpRefs\Parse\Reg_Citations\get_full_refs;
use function WpRefs\Parse\Reg_Citations\get_short_citations;

class CitationsRegTest extends MyFunctionTest
{

    // اختبارات إضافية للدوال المساعدة
    public function test_get_name_with_double_quotes()
    {
        $this->assertEquals("test_name", get_name('name="test_name"'));
    }

    public function test_get_name_with_single_quotes()
    {
        $this->assertEquals("test_name", get_name("name='test_name'"));
    }

    public function test_get_name_without_quotes()
    {
        $this->assertEquals("test_name", get_name("name=test_name"));
    }

    public function test_get_name_with_spaces()
    {
        $this->assertEquals("test name", get_name("name = 'test name'"));
    }

    public function test_get_name_empty()
    {
        $this->assertEquals("", get_name(""));
        $this->assertEquals("", get_name("other_attr=value"));
    }

    public function test_get_regex_citations_with_multiple_refs()
    {
        $text = '<ref name="ref1">Content 1</ref> Text <ref name="ref2">Content 2</ref>';
        $citations = get_regex_citations($text);

        $this->assertCount(2, $citations);
        $this->assertEquals("ref1", $citations[0]["name"]);
        $this->assertEquals("Content 1", $citations[0]["content"]);
        $this->assertEquals('<ref name="ref1">Content 1</ref>', $citations[0]["tag"]);
    }

    public function test_get_regex_citations_with_no_refs()
    {
        $text = 'No references here';
        $citations = get_regex_citations($text);
        $this->assertCount(0, $citations);
    }

    public function test_get_full_refs()
    {
        $text = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>';
        $fullRefs = get_full_refs($text);

        $this->assertCount(2, $fullRefs);
        $this->assertEquals('<ref name="ref1">Content 1</ref>', $fullRefs["ref1"]);
        $this->assertEquals('<ref name="ref2">Content 2</ref>', $fullRefs["ref2"]);
    }

    public function test_get_short_citations()
    {
        $text = '<ref name="ref1"/> Text <ref name="ref2"/>';
        $shortRefs = get_short_citations($text);

        $this->assertCount(2, $shortRefs);
        $this->assertEquals("ref1", $shortRefs[0]["name"]);
        $this->assertEquals('<ref name="ref1"/>', $shortRefs[0]["tag"]);
    }
}
