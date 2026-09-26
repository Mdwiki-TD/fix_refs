<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\ExpendRefs\refs_expend_work;

class expend_refsTest extends MyFunctionTest
{

    private $textInput = "";
    private $textOutput = "";
    private $refsExpends = "";

    protected function setUp(): void
    {
        $this->textInput   = file_get_contents(__DIR__ . "/fixtures/expend_input.txt");
        $this->textOutput  = file_get_contents(__DIR__ . "/fixtures/expend_output.txt");
        $this->refsExpends  = refs_expend_work($this->textInput);
    }

    public function test_input_text_not_empty(): void
    {
        $this->assertNotEmpty($this->textInput, "Input text file is empty!");
    }

    public function test_text_output_not_empty(): void
    {
        $this->assertNotEmpty($this->textOutput, "output file is empty!");
    }

    public function test_not_same(): void
    {
        $this->assertNotEquals($this->textInput, $this->textOutput, "Input and output are the same!");
    }
    public function test_expend_refs_not_empty(): void
    {
        $this->assertNotEmpty($this->refsExpends, "output file is empty!");
    }
    public function test_expend_refs_the_same_as_output(): void
    {
        $this->assertEquals($this->textOutput, $this->refsExpends, "Expend refs not working!");
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
