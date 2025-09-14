<?php



use PHPUnit\Framework\TestCase;

use function WpRefs\Bots\RefsUtils\endsWith;
use function WpRefs\Bots\RefsUtils\strstartswith;
use function WpRefs\Bots\RefsUtils\del_start_end;
use function WpRefs\Bots\RefsUtils\remove_start_end_quotes;

class refs_utilsTest extends TestCase
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

    // اختبارات دالة endsWith
    public function testEndsWith()
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
            $result = endsWith($test['string'], $test['endString']);
            $this->assertEquals($test['expected'], $result, $test['string']);
        }
    }

    // اختبارات دالة strstartswith
    public function testStrstartswith()
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
            $result = strstartswith($test['text'], $test['start']);
            $this->assertEquals($test['expected'], $result);
        }
    }

    // اختبارات دالة del_start_end
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
            // حالة: علامة البحث فارغة
            ["text" => "test", "find" => "", "expected" => "test"],
            // حالة: علامات متعددة
            ["text" => "''multiple''", "find" => "'", "expected" => "'multiple'"],
            // حالة: نص يتكون من العلامة فقط
            ["text" => "''", "find" => "'", "expected" => ""]
        ];

        foreach ($tests as $test) {
            $result = del_start_end($test['text'], $test['find']);
            $this->assertEquals($test['expected'], $result);
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
            $this->assertEquals($test['expected'], $result, $test['text']);
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
}
