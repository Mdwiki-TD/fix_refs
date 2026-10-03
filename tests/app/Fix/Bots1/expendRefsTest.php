<?php



use WpRefs\Tests\MyFunctionTest;
use App\Fix\Bots\ExpendRefs;

class expendRefsTest extends MyFunctionTest
{

    private $textInput = "";
    private $textExpected = "";
    private $refsExpends = "";

    protected function setUp(): void
    {
        $this->textInput    = file_get_contents(__DIR__ . "/fixtures/expend/input.txt");
        $this->textExpected = file_get_contents(__DIR__ . "/fixtures/expend/expected.txt");
        $this->refsExpends  = ExpendRefs::refs_expend_work($this->textInput);
    }

    public function testInputTextNotEmpty(): void
    {
        $this->assertNotEmpty($this->textInput, "Input text file is empty!");
    }

    public function testTextOutputNotEmpty(): void
    {
        $this->assertNotEmpty($this->textExpected, "output file is empty!");
    }

    public function testNotSame(): void
    {
        $this->assertNotEquals($this->textInput, $this->textExpected, "Input and output are the same!");
    }
    public function testExpendRefsNotEmpty(): void
    {
        $this->assertNotEmpty($this->refsExpends, "output file is empty!");
    }
    public function testExpendRefsTheSameAsOutput(): void
    {
        $this->assertEquals($this->textExpected, $this->refsExpends, "Expend refs not working!");
    }
    // اختبارات إضافية للدالة الرئيسية
    public function testRefsExpendWorkWithSimpleCase()
    {
        $input = '<ref name="ref1">Full content</ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">Full content</ref> Text <ref name="ref1">Full content</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithNoMatchingRef()
    {
        $input = '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>';
        $expected = '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithMultipleRefs()
    {
        $input = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> Text <ref name="ref1"/> <ref name="ref2"/>';
        $expected = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> Text <ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithAlltextParameter()
    {
        $first = 'Text <ref name="ref1"/>';
        $alltext = '<ref name="ref1">Full content</ref>';
        $expected = 'Text <ref name="ref1">Full content</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($first, $alltext));
    }

    public function testRefsExpendWorkWithEmptyInput()
    {
        $this->assertEquals("", ExpendRefs::refs_expend_work(""));
    }

    public function testRefsExpendWorkWithNoRefs()
    {
        $input = 'No references here';
        $this->assertEquals($input, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkPreservesOriginalFormatting()
    {
        $input = '<ref name="ref1">  Full content  </ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">  Full content  </ref> Text <ref name="ref1">  Full content  </ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithSpecialCharacters()
    {
        $input = '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> Text <ref name="ref1">Content with "quotes" & \'apostrophes\'</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }
}
