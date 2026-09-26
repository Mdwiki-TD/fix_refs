<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Parse\Category\get_categories_reg;

class CategoryTest extends MyFunctionTest
{
    public function test_get_categories_with_simple_categories()
    {
        $text = "This is some text [[Category:Example]] and more text [[Category:Test]]";
        $expected = [
            "Example" => "[[Category:Example]]",
            "Test" => "[[Category:Test]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_no_categories()
    {
        $text = "This is some text without any categories";
        $expected = [];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_pipe_separator()
    {
        $text = "Text with [[Category:Example|sort key]] and [[Category:Test|another key]]";
        $expected = [
            "Example" => "[[Category:Example|sort key]]",
            "Test" => "[[Category:Test|another key]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_spaces()
    {
        $text = "Text with [[ Category : Example with spaces ]] and [[  Category:Test  ]]";
        $expected = [
            "Example with spaces" => "[[ Category : Example with spaces ]]",
            "Test" => "[[  Category:Test  ]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_special_characters()
    {
        $text = "Text with [[Category:Example & Test]] and [[Category:Something (else)]]";
        $expected = [
            "Example & Test" => "[[Category:Example & Test]]",
            "Something (else)" => "[[Category:Something (else)]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_duplicate_categories()
    {
        $text = "Text with [[Category:Example]] and more [[Category:Example]]";
        $expected = [
            "Example" => "[[Category:Example]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_multiline_text()
    {
        $text = "Start of text\n[[Category:First category]]\nMiddle of text\n[[Category:Second category]]\nEnd of text";
        $expected = [
            "First category" => "[[Category:First category]]",
            "Second category" => "[[Category:Second category]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_empty_input()
    {
        $text = "";
        $expected = [];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_multiple_pipes()
    {
        $text = "Text with [[Category:Example|sort|key]] and [[Category:Test]]";
        $expected = [
            "Example" => "[[Category:Example|sort|key]]",
            "Test" => "[[Category:Test]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_unicode_characters()
    {
        $text = "Text with [[Category:مثال]] and [[Category:測試]]";
        $expected = [
            "مثال" => "[[Category:مثال]]",
            "測試" => "[[Category:測試]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_mixed_case()
    {
        $text = "Text with [[category:example]] and [[CATEGORY:TEST]]";
        $expected = [
            "example" => "[[category:example]]",
            "TEST" => "[[CATEGORY:TEST]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }

    public function test_get_categories_with_templates_inside()
    {
        $text = "Text with [[Category:Example{{template}}]] and [[Category:Test]]";
        $expected = [
            "Example{{template}}" => "[[Category:Example{{template}}]]",
            "Test" => "[[Category:Test]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }


    /**
     * @test
     * @description يختبر استخراج تصنيف واحد من النص.
     */
    public function test_get_single_category()
    {
        $text = "Some text here [[Category:PHP]] more text.";
        $expected = [
            "PHP" => "[[Category:PHP]]"
        ];
        $this->assertEquals($expected, get_categories_reg($text));
    }

    /**
     * @test
     * @description يختبر استخراج عدة تصنيفات من النص.
     */
    public function test_get_multiple_categories()
    {
        $text = "[[Category:Programming]] and [[Category:Web development]].";
        $expected = [
            "Programming" => "[[Category:Programming]]",
            "Web development" => "[[Category:Web development]]"
        ];
        $this->assertEquals($expected, get_categories_reg($text));
    }

    /**
     * @test
     * @description يختبر نصًا لا يحتوي على أي تصنيفات.
     */
    public function test_no_categories_found()
    {
        $text = "This is a text with no categories.";
        $this->assertEmpty(get_categories_reg($text));
    }

    /**
     * @test
     * @description يختبر وجود مسافات إضافية حول اسم التصنيف.
     */
    public function test_category_with_extra_whitespace()
    {
        $text = "[[Category:  Test Category  ]]";
        $expected = [
            "Test Category" => "[[Category:  Test Category  ]]"
        ];
        $this->assertEquals($expected, get_categories_reg($text));
    }

    /**
     * @test
     * @description يختبر اختلاف حالة الأحرف في كلمة "Category".
     */
    public function test_case_insensitive_category_tag()
    {
        $text = "[[category:Case Insensitive]]";
        $expected = [
            "Case Insensitive" => "[[category:Case Insensitive]]"
        ];
        $this->assertEquals($expected, get_categories_reg($text));
    }

    /**
     * @test
     * @description يختبر التصنيفات التي تحتوي على مفتاح فرز (sort key).
     */
    public function test_category_with_sort_key()
    {
        $text = "[[Category:Musicians|Beatles]]";
        $expected = [
            "Musicians" => "[[Category:Musicians|Beatles]]"
        ];
        $this->assertEquals($expected, get_categories_reg($text));
    }

    /**
     * @test
     * @description يختبر وجود عدة تصنيفات مع مفاتيح فرز ومسافات.
     */
    public function test_mixed_and_complex_categories()
    {
        $text = "A complex text [[Category:Software|S]] and another one [[  category :  Databases  ]].";
        $expected = [
            "Software" => "[[Category:Software|S]]",
            "Databases" => "[[  category :  Databases  ]]"
        ];
        $this->assertEquals($expected, get_categories_reg($text));
    }

    public function test_get_categories_with_nested_brackets()
    {
        $text = "Text with [[category:Example {{nested}} | {{!}} ]] and [[CategorY:Test]]";
        $expected = [
            "Example {{nested}}" => "[[category:Example {{nested}} | {{!}} ]]",
            "Test" => "[[CategorY:Test]]"
        ];

        $result = get_categories_reg($text);
        $this->assertEquals($expected, $result);
    }
}
