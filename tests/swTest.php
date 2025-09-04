<?php

include_once __DIR__ . '/../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\SW\sw_fixes;


class swTest extends TestCase
{
    public function test_fix_temps_and_months_1()
    {
        $this->assertEquals("== Marejeo ==", sw_fixes("== Marejeleo =="));
    }

    public function test_fix_temps_and_months_2()
    {
        $this->assertEquals("== Marejeo ==", sw_fixes("==Marejeleo=="));
    }

    public function test_Extra_spaces_around_the_word()
    {
        $this->assertEquals("==== Marejeo ====", sw_fixes("====   Marejeleo   ===="));
    }

    public function test_Case_insensitivity_mixed()
    {
        $this->assertEquals("====== Marejeo ======", sw_fixes("====== MaReJeLeO ======"));
    }

    public function test_additional_text()
    {
        $this->assertEquals("== Marejeleo na Maoni ==", sw_fixes("== Marejeleo na Maoni =="));
    }

    public function testSwFixes()
    {
        $tests = [
            // Case 3: Case insensitivity (lowercase)
            [
                "input" => "=== marejeleo ===",
                "expected" => "=== Marejeo ==="
            ],
            // Case 5: Multiple occurrences
            [
                "input" => "== Marejeleo ==\nSome text\n== marejeleo ==",
                "expected" => "== Marejeo ==\nSome text\n== Marejeo =="
            ],
            // Case 6: In the middle of content
            [
                "input" => "This is a section: == Marejeleo ==",
                "expected" => "This is a section: == Marejeo =="
            ],
            // Case 7: Similar word that should NOT be replaced
            [
                "input" => "== MarejeleoMengine ==",
                "expected" => "== MarejeleoMengine =="
            ],
            // Case 9: Different word that should NOT be replaced
            [
                "input" => "=== Viungo ===",
                "expected" => "=== Viungo ==="
            ],
            // Case 10: Empty string
            [
                "input" => "",
                "expected" => ""
            ],
            // Case 11: No matching pattern
            [
                "input" => "This is just regular text",
                "expected" => "This is just regular text"
            ],
            // Case 12: Multiple spaces between equals and word
            [
                "input" => "==     Marejeleo     ==",
                "expected" => "== Marejeo =="
            ],
            // Case 13: Tabs instead of spaces
            [
                "input" => "==\tMarejeleo\t==",
                "expected" => "== Marejeo =="
            ],
            // Case 14: Mixed whitespace
            [
                "input" => "== \t \n Marejeleo \n \t ==",
                "expected" => "== Marejeo =="
            ],
            // Case 15: Multiple occurrences in one line
            [
                "input" => "== Marejeleo == and == marejeleo ==",
                "expected" => "== Marejeo == and == Marejeo =="
            ]
        ];

        foreach ($tests as $test) {
            $result = sw_fixes($test['input']);
            $this->assertEquals($test['expected'], $result);
        }
    }
}
