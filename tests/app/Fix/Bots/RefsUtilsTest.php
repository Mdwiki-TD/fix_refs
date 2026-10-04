<?php

use Tests\MyFunctionTest;
use App\Fix\Bots\RefsUtils;

class refsUtilsTest extends MyFunctionTest
{
    /**
     * @description يضيف علامات اقتباس مزدوجة لنص عادي.
     */
    public function testAddsDoubleQuotesToPlainString(): void
    {
        $this->assertEquals('"value"', RefsUtils::remove_start_end_quotes('value'));
    }

    /**
     * @description يزيل علامات الاقتباس المفردة ويضيف مزدوجة.
     */
    public function testReplacesSingleQuotesWithDoubleQuotes(): void
    {
        $this->assertEquals('"value"', RefsUtils::remove_start_end_quotes("'value'"));
    }

    /**
     * @description يزيل علامات الاقتباس المزدوجة ويضيف مزدوجة مرة أخرى.
     */
    public function testReplacesDoubleQuotesWithDoubleQuotes(): void
    {
        $this->assertEquals('"value"', RefsUtils::remove_start_end_quotes('"value"'));
    }

    /**
     * @description يحيط النص بعلامات اقتباس مفردة إذا كان يحتوي على علامات مزدوجة بالداخل.
     */
    public function testWrapsWithSingleQuotesIfContainsDoubleQuotes(): void
    {
        $this->assertEquals("'val\"ue'", RefsUtils::remove_start_end_quotes('val"ue'));
    }

    /**
     * @description يزيل المسافات الزائدة من البداية والنهاية.
     */
    public function testTrimsWhitespace(): void
    {
        $this->assertEquals('"value"', RefsUtils::remove_start_end_quotes('  value  '));
    }

    /**
     * @description يتعامل مع نص فارغ.
     */
    public function testHandlesEmptyString(): void
    {
        $this->assertEquals('""', RefsUtils::remove_start_end_quotes(''));
    }

    public function testOneQuotesDouble(): void
    {
        $this->assertEquals("'\"value'", RefsUtils::remove_start_end_quotes('  "value '));
    }

    public function testOneQuotesSingle(): void
    {
        $this->assertEquals('"\'value"', RefsUtils::remove_start_end_quotes("  'value "));
    }

    // اختبارات دالة str_ends_with
    public function teststrEndsWith(): void
    {
        $tests = [
            ["string" => "Hello world", "endString" => "world", "expected" => true],
            ["string" => "Hello world", "endString" => "hello", "expected" => false],
            ["string" => "", "endString" => "test", "expected" => false],
            ["string" => "short", "endString" => "longer text", "expected" => false],
            ["string" => "exact", "endString" => "exact", "expected" => true],
            ["string" => "file.txt", "endString" => ".txt", "expected" => true],
            ["string" => "Case", "endString" => "case", "expected" => false]
        ];

        foreach ($tests as $test) {
            $result = str_ends_with($test['string'], $test['endString']);
            $this->assertSame($test['expected'], $result);
        }
    }

    // اختبارات دالة str_starts_with
    public function teststrStartsWith(): void
    {
        $tests = [
            ["text" => "Hello world", "start" => "Hello", "expected" => true],
            ["text" => "Hello world", "start" => "world", "expected" => false],
            ["text" => "", "start" => "test", "expected" => false],
            ["text" => "test", "start" => "", "expected" => true],
            ["text" => "short", "start" => "longer text", "expected" => false],
            ["text" => "exact", "start" => "exact", "expected" => true],
            ["text" => "#tag", "start" => "#", "expected" => true],
            ["text" => "Case", "start" => "case", "expected" => false]
        ];

        foreach ($tests as $test) {
            $result = str_starts_with($test['text'], $test['start']);
            $this->assertSame($test['expected'], $result);
        }
    }

    // اختبارات دالة rm_str_from_start_and_end
    public function testDelStartEnd(): void
    {
        $tests = [
            ["text" => "'quoted text'", "find" => "'", "expected" => "quoted text"],
            ["text" => '"double quoted"', "find" => '"', "expected" => "double quoted"],
            ["text" => "no quotes", "find" => "'", "expected" => "no quotes"],
            ["text" => "'start only", "find" => "'", "expected" => "'start only"],
            ["text" => "end only'", "find" => "'", "expected" => "end only'"],
            ["text" => "  '  spaced  '  ", "find" => "'", "expected" => "spaced"],
            ["text" => "", "find" => "'", "expected" => ""],
            ["text" => "''multiple''", "find" => "'", "expected" => "'multiple'"],
            ["text" => "''", "find" => "'", "expected" => ""]
        ];

        foreach ($tests as $test) {
            $result = RefsUtils::rm_str_from_start_and_end($test['text'], $test['find']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    // اختبارات دالة remove_start_end_quotes
    public function testFixAttrValue(): void
    {
        $tests = [
            ["text" => "value1", "expected" => '"value1"'],
            ["text" => "'value2'", "expected" => '"value2"'],
            ["text" => '"value3"', "expected" => '"value3"'],
            ["text" => '"mixed\'quotes"', "expected" => '"mixed\'quotes"'],
            ["text" => 'value"with"quotes', "expected" => "'value\"with\"quotes'"],
            ["text" => "  spaced  ", "expected" => '"spaced"'],
            ["text" => "val'ue", "expected" => '"val\'ue"']
        ];

        foreach ($tests as $test) {
            $result = RefsUtils::remove_start_end_quotes($test['text']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    public function testFixEmpty(): void
    {
        $this->assertEquals("", "");
    }

    public function testFixOnlyQuotes(): void
    {
        $this->assertEquals('""', RefsUtils::remove_start_end_quotes('""'));
    }

    public function testFixOnlySingleQuotes(): void
    {
        $this->assertEquals('""', RefsUtils::remove_start_end_quotes("''"));
    }

    public function testDelStartEndEmpty(): void
    {
        $result = RefsUtils::rm_str_from_start_and_end('testzz', '');
        $this->assertEqualCompare('testzz', 'testzz', $result);
    }
}
