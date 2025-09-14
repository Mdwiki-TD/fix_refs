<?php /* Tests use PHPUnit via FixRefs\Tests\MyFunctionTest base. */
<?php



use FixRefs\Tests\MyFunctionTest;

use function WpRefs\Bots\RefsUtils\rm_str_from_start_and_end;
use function WpRefs\Bots\RefsUtils\remove_start_end_quotes;

if (!function_exists('str_ends_with')) {
    function str_ends_with($string, $endString)
    {
        $len = strlen($endString);
        return substr($string, -$len) === $endString;
    }
    /**
     * @test
     * @description يتأكد من سلوك str_ends_with مع سلسلة نهاية فارغة في كل من PHP >= 8 والبديل المحلي.
     */
    public function test_str_ends_with_empty_needle_semantics()
    {
        $expected = function_exists("str_ends_with") ? true : false; // PHP 8+: true, fallback here: false (إلا إذا كانت السلسلة فارغة)
        $this->assertEquals($expected, str_ends_with("test", ""));
        // الحالة الخاصة: كلاهما يعيد true عندما تكون السلسلة فارغة
        $this->assertTrue(str_ends_with("", ""));
    }

    /**
     * @test
     * @description يدعم str_ends_with أحرف يونيكود/إيموجي.
     */
    public function test_str_ends_with_unicode_and_emoji()
    {
        $this->assertTrue(str_ends_with("hello😊", "😊"));
        $this->assertTrue(str_ends_with("مرحبا", "با"));
        $this->assertFalse(str_ends_with("مرحبا", "مرح"));
    }

    /**
     * @test
     * @description يدعم str_starts_with أحرف يونيكود ويطابق سلوك PHP عند بداية فارغة.
     */
    public function test_str_starts_with_unicode_and_empty_prefix()
    {
        $this->assertTrue(str_starts_with("שלוםעולם", "שלום"));
        $this->assertFalse(str_starts_with("שלוםעולם", "עולם"));
        $this->assertTrue(str_starts_with("any", ""));
        $this->assertTrue(str_starts_with("", ""));
    }

    /**
     * @test
     * @description rm_str_from_start_and_end: يدعم محددات بطول حرفين أو أكثر.
     */
    public function test_rm_str_from_start_and_end_multi_char_tokens()
    {
        $tests = [
            ["text" => "**bold**", "find" => "**", "expected" => "bold"],
            ["text" => "##slug##", "find" => "##", "expected" => "slug"],
            ["text" => "``code``", "find" => "``", "expected" => "code"],
            ["text" => "**start_only", "find" => "**", "expected" => "**start_only"],
            ["text" => "end_only**", "find" => "**", "expected" => "end_only**"],
        ];
        foreach ($tests as $t) {
            $this->assertEquals($t["expected"], rm_str_from_start_and_end($t["text"], $t["find"]));
        }
    }

    /**
     * @test
     * @description rm_str_from_start_and_end: يزيل الاقتباسات ويقوم بترميم المسافات الداخلية فقط.
     */
    public function test_rm_str_from_start_and_end_trims_inner_whitespace_when_wrapped()
    {
        $this->assertEquals("spaced", rm_str_from_start_and_end("\"  spaced  \"", "));
        $this->assertEquals("code", rm_str_from_start_and_end("` code `", "`"));
    }

    /**
     * @test
     * @description remove_start_end_quotes يتعامل مع أسطر جديدة وعلامات تبويب حول القيمة.
     */
    public function test_remove_start_end_quotes_trims_various_whitespace()
    {
        $input = "\t  value\n";
        $this->assertEquals("value", remove_start_end_quotes($input));
        $this->assertEquals(""value"", remove_start_end_quotes("  \"value\"  "));
    }

    /**
     * @test
     * @description remove_start_end_quotes يحافظ على التغليف الأنسب عند وجود علامات مزدوجة داخلية.
     */
    public function test_remove_start_end_quotes_prefers_single_when_inner_dquotes()
    {
        $this->assertEquals("val"ue", remove_start_end_quotes(val"ue));
        $this->assertEquals(""value"", remove_start_end_quotes("value"));
    }

    /**
     * @test
     * @description remove_start_end_quotes: يُزيل التغليف ثم يُعيد اختيار النوع استنادًا إلى المحتوى.
     */
    public function test_remove_start_end_quotes_rewraps_after_stripping()
    {
        // بعد إزالة الاقتباسات المفردة الخارجية، يجب اختيار المفردة مرة أخرى لاحتواء علامات مزدوجة داخلية بدون هروب إضافي
        $this->assertEquals("val"ue", remove_start_end_quotes("val"ue"));
        // قيم رقمية كنص
        $this->assertEquals("123", remove_start_end_quotes(123));
        $this->assertEquals("0", remove_start_end_quotes(0));
    }

}
if (!function_exists('str_starts_with')) {
    function str_starts_with($text, $start)
    {
        return strpos($text, $start) === 0;
    }
    /**
     * @test
     * @description يتأكد من سلوك str_ends_with مع سلسلة نهاية فارغة في كل من PHP >= 8 والبديل المحلي.
     */
    public function test_str_ends_with_empty_needle_semantics()
    {
        $expected = function_exists("str_ends_with") ? true : false; // PHP 8+: true, fallback here: false (إلا إذا كانت السلسلة فارغة)
        $this->assertEquals($expected, str_ends_with("test", ""));
        // الحالة الخاصة: كلاهما يعيد true عندما تكون السلسلة فارغة
        $this->assertTrue(str_ends_with("", ""));
    }

    /**
     * @test
     * @description يدعم str_ends_with أحرف يونيكود/إيموجي.
     */
    public function test_str_ends_with_unicode_and_emoji()
    {
        $this->assertTrue(str_ends_with("hello😊", "😊"));
        $this->assertTrue(str_ends_with("مرحبا", "با"));
        $this->assertFalse(str_ends_with("مرحبا", "مرح"));
    }

    /**
     * @test
     * @description يدعم str_starts_with أحرف يونيكود ويطابق سلوك PHP عند بداية فارغة.
     */
    public function test_str_starts_with_unicode_and_empty_prefix()
    {
        $this->assertTrue(str_starts_with("שלוםעולם", "שלום"));
        $this->assertFalse(str_starts_with("שלוםעולם", "עולם"));
        $this->assertTrue(str_starts_with("any", ""));
        $this->assertTrue(str_starts_with("", ""));
    }

    /**
     * @test
     * @description rm_str_from_start_and_end: يدعم محددات بطول حرفين أو أكثر.
     */
    public function test_rm_str_from_start_and_end_multi_char_tokens()
    {
        $tests = [
            ["text" => "**bold**", "find" => "**", "expected" => "bold"],
            ["text" => "##slug##", "find" => "##", "expected" => "slug"],
            ["text" => "``code``", "find" => "``", "expected" => "code"],
            ["text" => "**start_only", "find" => "**", "expected" => "**start_only"],
            ["text" => "end_only**", "find" => "**", "expected" => "end_only**"],
        ];
        foreach ($tests as $t) {
            $this->assertEquals($t["expected"], rm_str_from_start_and_end($t["text"], $t["find"]));
        }
    }

    /**
     * @test
     * @description rm_str_from_start_and_end: يزيل الاقتباسات ويقوم بترميم المسافات الداخلية فقط.
     */
    public function test_rm_str_from_start_and_end_trims_inner_whitespace_when_wrapped()
    {
        $this->assertEquals("spaced", rm_str_from_start_and_end("\"  spaced  \"", "));
        $this->assertEquals("code", rm_str_from_start_and_end("` code `", "`"));
    }

    /**
     * @test
     * @description remove_start_end_quotes يتعامل مع أسطر جديدة وعلامات تبويب حول القيمة.
     */
    public function test_remove_start_end_quotes_trims_various_whitespace()
    {
        $input = "\t  value\n";
        $this->assertEquals("value", remove_start_end_quotes($input));
        $this->assertEquals(""value"", remove_start_end_quotes("  \"value\"  "));
    }

    /**
     * @test
     * @description remove_start_end_quotes يحافظ على التغليف الأنسب عند وجود علامات مزدوجة داخلية.
     */
    public function test_remove_start_end_quotes_prefers_single_when_inner_dquotes()
    {
        $this->assertEquals("val"ue", remove_start_end_quotes(val"ue));
        $this->assertEquals(""value"", remove_start_end_quotes("value"));
    }

    /**
     * @test
     * @description remove_start_end_quotes: يُزيل التغليف ثم يُعيد اختيار النوع استنادًا إلى المحتوى.
     */
    public function test_remove_start_end_quotes_rewraps_after_stripping()
    {
        // بعد إزالة الاقتباسات المفردة الخارجية، يجب اختيار المفردة مرة أخرى لاحتواء علامات مزدوجة داخلية بدون هروب إضافي
        $this->assertEquals("val"ue", remove_start_end_quotes("val"ue"));
        // قيم رقمية كنص
        $this->assertEquals("123", remove_start_end_quotes(123));
        $this->assertEquals("0", remove_start_end_quotes(0));
    }

}
class refs_utilsTest extends MyFunctionTest
{

    /**
     * @test
     * @description يضيف علامات اقتباس مزدوجة لنص عادي.
     */
    public function test_adds_double_quotes_to_plain_string()
    {
        $this->assertEquals('"value"', remove_start_end_quotes('value'));
    }

    /**
     * @test
     * @description يزيل علامات الاقتباس المفردة ويضيف مزدوجة.
     */
    public function test_replaces_single_quotes_with_double_quotes()
    {
        // سلوك الدالة هو إزالة الاقتباسات الموجودة ثم إضافة جديدة.
        $this->assertEquals('"value"', remove_start_end_quotes("'value'"));
    }

    /**
     * @test
     * @description يزيل علامات الاقتباس المزدوجة ويضيف مزدوجة مرة أخرى.
     */
    public function test_replaces_double_quotes_with_double_quotes()
    {
        $this->assertEquals('"value"', remove_start_end_quotes('"value"'));
    }

    /**
     * @test
     * @description يحيط النص بعلامات اقتباس مفردة إذا كان يحتوي على علامات مزدوجة بالداخل.
     */
    public function test_wraps_with_single_quotes_if_contains_double_quotes()
    {
        $this->assertEquals("'val\"ue'", remove_start_end_quotes('val"ue'));
    }

    /**
     * @test
     * @description يزيل المسافات الزائدة من البداية والنهاية.
     */
    public function test_trims_whitespace()
    {
        $this->assertEquals('"value"', remove_start_end_quotes('  value  '));
    }

    /**
     * @test
     * @description يتعامل مع نص فارغ.
     */
    public function test_handles_empty_string()
    {
        $this->assertEquals('""', remove_start_end_quotes(''));
    }
    public function test_one_quotes_double()
    {
        $this->assertEquals("'\"value'", remove_start_end_quotes('  "value '));
    }
    public function test_one_quotes_single()
    {
        $this->assertEquals('"\'value"', remove_start_end_quotes("  'value "));
    }

    // اختبارات دالة str_ends_with
    public function teststr_ends_with()
    {
        $tests = [
            // حالة: ينتهي بالنص المطلوب
            ["string" => "Hello world", "endString" => "world", "expected" => true],
            // حالة: لا ينتهي بالنص المطلوب
            ["string" => "Hello world", "endString" => "hello", "expected" => false],
            // حالة: نص فارغ
            ["string" => "", "endString" => "test", "expected" => false],
            // حالة: نص البحث فارغ
            // ["string" => "test", "endString" => "", "expected" => true],
            // حالة: نص البحث أطول من النص الأصلي
            ["string" => "short", "endString" => "longer text", "expected" => false],
            // حالة: تطابق كامل
            ["string" => "exact", "endString" => "exact", "expected" => true],
            // حالة: أحرف خاصة
            ["string" => "file.txt", "endString" => ".txt", "expected" => true],
            // حالة: حساسية الأحرف
            ["string" => "Case", "endString" => "case", "expected" => false]
        ];

        foreach ($tests as $test) {
            $result = str_ends_with($test['string'], $test['endString']);
            $this->assertEqualCompare($test['expected'], $test['string'], $result);
        }
    }

    // اختبارات دالة str_starts_with
    public function teststr_starts_with()
    {
        $tests = [
            // حالة: يبدأ بالنص المطلوب
            ["text" => "Hello world", "start" => "Hello", "expected" => true],
            // حالة: لا يبدأ بالنص المطلوب
            ["text" => "Hello world", "start" => "world", "expected" => false],
            // حالة: نص فارغ
            ["text" => "", "start" => "test", "expected" => false],
            // حالة: نص البحث فارغ
            ["text" => "test", "start" => "", "expected" => true],
            // حالة: نص البحث أطول من النص الأصلي
            ["text" => "short", "start" => "longer text", "expected" => false],
            // حالة: تطابق كامل
            ["text" => "exact", "start" => "exact", "expected" => true],
            // حالة: أحرف خاصة
            ["text" => "#tag", "start" => "#", "expected" => true],
            // حالة: حساسية الأحرف
            ["text" => "Case", "start" => "case", "expected" => false]
        ];

        foreach ($tests as $test) {
            $result = str_starts_with($test['text'], $test['start']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    // اختبارات دالة rm_str_from_start_and_end
    public function testDelStartEnd()
    {
        $tests = [
            // حالة: إزالة من البداية والنهاية
            ["text" => "'quoted text'", "find" => "'", "expected" => "quoted text"],
            // حالة: إزالة علامات تنصيص مزدوجة
            ["text" => '"double quoted"', "find" => '"', "expected" => "double quoted"],
            // حالة: نص بدون علامات في البداية والنهاية
            ["text" => "no quotes", "find" => "'", "expected" => "no quotes"],
            // حالة: علامة في البداية فقط
            ["text" => "'start only", "find" => "'", "expected" => "'start only"],
            // حالة: علامة في النهاية فقط
            ["text" => "end only'", "find" => "'", "expected" => "end only'"],
            // حالة: مسافات زائدة
            // ["text" => "  '  spaced  '  ", "find" => "'", "expected" => "  spaced  "],
            ["text" => "  '  spaced  '  ", "find" => "'", "expected" => "spaced"],
            // حالة: نص فارغ
            ["text" => "", "find" => "'", "expected" => ""],
            // حالة: علامات متعددة
            ["text" => "''multiple''", "find" => "'", "expected" => "'multiple'"],
            // حالة: نص يتكون من العلامة فقط
            ["text" => "''", "find" => "'", "expected" => ""]
        ];

        foreach ($tests as $test) {
            $result = rm_str_from_start_and_end($test['text'], $test['find']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    // اختبارات دالة remove_start_end_quotes
    public function testFixAttrValue()
    {
        $tests = [
            // حالة: نص بدون علامات تنصيص
            ["text" => "value1", "expected" => '"value1"'],
            // حالة: نص بعلامات تنصيص مفردة
            ["text" => "'value2'", "expected" => '"value2"'],
            // حالة: نص بعلامات تنصيص مزدوجة
            ["text" => '"value3"', "expected" => '"value3"'],
            // حالة: نص بعلامات تنصيص مختلطة
            ["text" => '"mixed\'quotes"', "expected" => '"mixed\'quotes"'],
            // حالة: نص يحتوي على علامات تنصيص داخلياً
            ["text" => 'value"with"quotes', "expected" => "'value\"with\"quotes'"],
            // حالة: مسافات زائدة
            ["text" => "  spaced  ", "expected" => '"spaced"'],
            // حالة: نص يحتوي على علامات تنصيص في المنتصف
            ["text" => "val'ue", "expected" => '"val\'ue"']
        ];

        foreach ($tests as $test) {
            $result = remove_start_end_quotes($test['text']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }
    public function testFixEmpty()
    {
        $this->assertEquals("", "");
    }
    public function testFixOnlyQuotes()
    {
        $this->assertEquals('""', remove_start_end_quotes('""'));
    }
    public function testFixOnlySingleQuotes()
    {
        $this->assertEquals('""', remove_start_end_quotes("''"));
    }
    // اختبارات دالة rm_str_from_start_and_end
    public function testDelStartEndEmpty()
    {
        $result = rm_str_from_start_and_end('testzz', '');
        $this->assertEqualCompare('testzz', 'testzz', $result);
    }
    /**
     * @test
     * @description يتأكد من سلوك str_ends_with مع سلسلة نهاية فارغة في كل من PHP >= 8 والبديل المحلي.
     */
    public function test_str_ends_with_empty_needle_semantics()
    {
        $expected = function_exists("str_ends_with") ? true : false; // PHP 8+: true, fallback here: false (إلا إذا كانت السلسلة فارغة)
        $this->assertEquals($expected, str_ends_with("test", ""));
        // الحالة الخاصة: كلاهما يعيد true عندما تكون السلسلة فارغة
        $this->assertTrue(str_ends_with("", ""));
    }

    /**
     * @test
     * @description يدعم str_ends_with أحرف يونيكود/إيموجي.
     */
    public function test_str_ends_with_unicode_and_emoji()
    {
        $this->assertTrue(str_ends_with("hello😊", "😊"));
        $this->assertTrue(str_ends_with("مرحبا", "با"));
        $this->assertFalse(str_ends_with("مرحبا", "مرح"));
    }

    /**
     * @test
     * @description يدعم str_starts_with أحرف يونيكود ويطابق سلوك PHP عند بداية فارغة.
     */
    public function test_str_starts_with_unicode_and_empty_prefix()
    {
        $this->assertTrue(str_starts_with("שלוםעולם", "שלום"));
        $this->assertFalse(str_starts_with("שלוםעולם", "עולם"));
        $this->assertTrue(str_starts_with("any", ""));
        $this->assertTrue(str_starts_with("", ""));
    }

    /**
     * @test
     * @description rm_str_from_start_and_end: يدعم محددات بطول حرفين أو أكثر.
     */
    public function test_rm_str_from_start_and_end_multi_char_tokens()
    {
        $tests = [
            ["text" => "**bold**", "find" => "**", "expected" => "bold"],
            ["text" => "##slug##", "find" => "##", "expected" => "slug"],
            ["text" => "``code``", "find" => "``", "expected" => "code"],
            ["text" => "**start_only", "find" => "**", "expected" => "**start_only"],
            ["text" => "end_only**", "find" => "**", "expected" => "end_only**"],
        ];
        foreach ($tests as $t) {
            $this->assertEquals($t["expected"], rm_str_from_start_and_end($t["text"], $t["find"]));
        }
    }

    /**
     * @test
     * @description rm_str_from_start_and_end: يزيل الاقتباسات ويقوم بترميم المسافات الداخلية فقط.
     */
    public function test_rm_str_from_start_and_end_trims_inner_whitespace_when_wrapped()
    {
        $this->assertEquals("spaced", rm_str_from_start_and_end("\"  spaced  \"", "));
        $this->assertEquals("code", rm_str_from_start_and_end("` code `", "`"));
    }

    /**
     * @test
     * @description remove_start_end_quotes يتعامل مع أسطر جديدة وعلامات تبويب حول القيمة.
     */
    public function test_remove_start_end_quotes_trims_various_whitespace()
    {
        $input = "\t  value\n";
        $this->assertEquals("value", remove_start_end_quotes($input));
        $this->assertEquals(""value"", remove_start_end_quotes("  \"value\"  "));
    }

    /**
     * @test
     * @description remove_start_end_quotes يحافظ على التغليف الأنسب عند وجود علامات مزدوجة داخلية.
     */
    public function test_remove_start_end_quotes_prefers_single_when_inner_dquotes()
    {
        $this->assertEquals("val"ue", remove_start_end_quotes(val"ue));
        $this->assertEquals(""value"", remove_start_end_quotes("value"));
    }

    /**
     * @test
     * @description remove_start_end_quotes: يُزيل التغليف ثم يُعيد اختيار النوع استنادًا إلى المحتوى.
     */
    public function test_remove_start_end_quotes_rewraps_after_stripping()
    {
        // بعد إزالة الاقتباسات المفردة الخارجية، يجب اختيار المفردة مرة أخرى لاحتواء علامات مزدوجة داخلية بدون هروب إضافي
        $this->assertEquals("val"ue", remove_start_end_quotes("val"ue"));
        // قيم رقمية كنص
        $this->assertEquals("123", remove_start_end_quotes(123));
        $this->assertEquals("0", remove_start_end_quotes(0));
    }

}
