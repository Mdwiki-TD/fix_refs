<?php



use PHPUnit\Framework\TestCase;
use function WpRefs\ExpendRefs\refs_expend_work;

class expend_refsTest extends TestCase
{

    private $text_input = "";
    private $text_output = "";
    private $refs_expends = "";

    protected function setUp(): void
    {
        $this->text_input   = file_get_contents(__DIR__ . "/texts/expend_input.txt");
        $this->text_output  = file_get_contents(__DIR__ . "/texts/expend_output.txt");
        $this->refs_expends  = refs_expend_work($this->text_input);
    }

    public function test_input_text_not_empty(): void
    {
        $this->assertNotEmpty($this->text_input, "Input text file is empty!");
    }

    public function test_text_output_not_empty(): void
    {
        $this->assertNotEmpty($this->text_output, "output file is empty!");
    }

    public function test_not_same(): void
    {
        $this->assertNotEquals($this->text_input, $this->text_output, "Input and output are the same!");
    }
    public function test_expend_refs_not_empty(): void
    {
        $this->assertNotEmpty($this->refs_expends, "output file is empty!");
    }
    public function test_expend_refs_the_same_as_output(): void
    {
        $this->assertEquals($this->text_output, $this->refs_expends, "Expend refs not working!");
    }
    // اختبارات إضافية للدالة الرئيسية
    public function test_refs_expend_work_with_simple_case()
    {
        $input = '<ref name="ref1">Full content</ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">Full content</ref> Text <ref name="ref1">Full content</ref>';
        $this->assertEquals($expected, refs_expend_work($input));
    }

    public function test_refs_expend_work_with_no_matching_ref()
    {
        $input = '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>';
        $expected = '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>';
        $this->assertEquals($expected, refs_expend_work($input));
    }

    public function test_refs_expend_work_with_multiple_refs()
    {
        $input = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> Text <ref name="ref1"/> <ref name="ref2"/>';
        $expected = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> Text <ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>';
        $this->assertEquals($expected, refs_expend_work($input));
    }

    public function test_refs_expend_work_with_alltext_parameter()
    {
        $first = 'Text <ref name="ref1"/>';
        $alltext = '<ref name="ref1">Full content</ref>';
        $expected = 'Text <ref name="ref1">Full content</ref>';
        $this->assertEquals($expected, refs_expend_work($first, $alltext));
    }

    public function test_refs_expend_work_with_empty_input()
    {
        $this->assertEquals("", refs_expend_work(""));
    }

    public function test_refs_expend_work_with_no_refs()
    {
        $input = 'No references here';
        $this->assertEquals($input, refs_expend_work($input));
    }

    public function test_refs_expend_work_preserves_original_formatting()
    {
        $input = '<ref name="ref1">  Full content  </ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">  Full content  </ref> Text <ref name="ref1">  Full content  </ref>';
        $this->assertEquals($expected, refs_expend_work($input));
    }

    public function test_refs_expend_work_with_special_characters()
    {
        $input = '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> Text <ref name="ref1">Content with "quotes" & \'apostrophes\'</ref>';
        $this->assertEquals($expected, refs_expend_work($input));
    }
}
