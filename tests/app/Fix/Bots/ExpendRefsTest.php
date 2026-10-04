<?php

use Tests\MyFunctionTest;
use App\Fix\Bots\ExpendRefs;

class expendRefsTest extends MyFunctionTest
{
    private string $textInput = "";
    private string $textExpected = "";
    private string $refsExpends = "";

    protected function setUp(): void
    {
        $this->textInput    = (string)file_get_contents(__DIR__ . "/fixtures/expend/input.txt");
        $this->textExpected = (string)file_get_contents(__DIR__ . "/fixtures/expend/expected.txt");
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
    public function testRefsExpendWorkWithSimpleCase(): void
    {
        $input = '<ref name="ref1">Full content</ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">Full content</ref> Text <ref name="ref1">Full content</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithNoMatchingRef(): void
    {
        $input = '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>';
        $expected = '<ref name="ref1">Full content</ref> Text <ref name="ref2"/>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithMultipleRefs(): void
    {
        $input = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> Text <ref name="ref1"/> <ref name="ref2"/>';
        $expected = '<ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref> Text <ref name="ref1">Content 1</ref> <ref name="ref2">Content 2</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithAlltextParameter(): void
    {
        $first = 'Text <ref name="ref1"/>';
        $alltext = '<ref name="ref1">Full content</ref>';
        $expected = 'Text <ref name="ref1">Full content</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($first, $alltext));
    }

    public function testRefsExpendWorkWithEmptyInput(): void
    {
        $this->assertEquals("", ExpendRefs::refs_expend_work(""));
    }

    public function testRefsExpendWorkWithNoRefs(): void
    {
        $input = 'No references here';
        $this->assertEquals($input, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkPreservesOriginalFormatting(): void
    {
        $input = '<ref name="ref1">  Full content  </ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">  Full content  </ref> Text <ref name="ref1">  Full content  </ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }

    public function testRefsExpendWorkWithSpecialCharacters(): void
    {
        $input = '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> Text <ref name="ref1"/>';
        $expected = '<ref name="ref1">Content with "quotes" & \'apostrophes\'</ref> Text <ref name="ref1">Content with "quotes" & \'apostrophes\'</ref>';
        $this->assertEquals($expected, ExpendRefs::refs_expend_work($input));
    }
}
