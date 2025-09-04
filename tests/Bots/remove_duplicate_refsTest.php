<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;

use function WpRefs\DelDuplicateRefs\remove_Duplicate_refs;
use function WpRefs\DelDuplicateRefs\fix_refs_names;


class remove_duplicate_refsTest extends TestCase
{
    // اختبارات دالة fix_refs_names
    public function testFixRefsNames()
    {
        $tests = [
            // Case: Reference without attributes
            [
                "input" => "<ref>Simple reference</ref>",
                "expected" => "<ref>Simple reference</ref>"
            ],
            // Case: Reference with name attribute in double quotes
            [
                "input" => '<ref name="te1">Reference</ref>',
                "expected" => "<ref name=\"te1\">Reference</ref>"
            ],
            // Case: Reference with name attribute in single quotes
            [
                "input" => "<ref name='te2'>Reference</ref>",
                "expected" => '<ref name="te2">Reference</ref>'
            ],
            // Case: Reference with multiple attributes
            [
                "input" => '<ref name="te3" group="notes">Reference</ref>',
                "expected" => "<ref name=\"te3\" group=\"notes\">Reference</ref>"
            ],
            // Case: Reference with attribute without value
            [
                "input" => '<ref name>Reference</ref>',
                "expected" => "<ref name=\"\">Reference</ref>"
            ],
            // Case: Reference with attribute containing internal quotes
            [
                "input" => '<ref name="test\'quote">Reference</ref>',
                "expected" => "<ref name=\"test'quote\">Reference</ref>"
            ],
            // Case: Multiple references
            [
                "input" => '<ref name="a">Ref1</ref> <ref name=\'b\'>Ref2</ref>',
                "expected" => "<ref name=\"a\">Ref1</ref> <ref name=\"b\">Ref2</ref>"
            ],
            // Case: Reference with extra spaces in attributes
            // [ "input" => '<ref  name  =  "te4"  >Reference</ref>', "expected" => '<ref name="te4">Reference</ref>' ],
            // Case: Reference with mixed quote types
            [
                "input" => '<ref name="te5" group=\'notes\'>Reference</ref>',
                "expected" => "<ref name=\"te5\" group=\"notes\">Reference</ref>"
            ]
        ];

        foreach ($tests as $test) {
            $result = fix_refs_names($test['input']);
            $this->assertEquals($test['expected'], $result);
        }
    }

    // اختبارات دالة remove_Duplicate_refs
    public function testRemoveDuplicateRefs()
    {
        $tests = [
            // Case: Single reference without name
            [
                "input" => "<ref>Reference without name1</ref>",
                "expected" => '<ref>Reference without name1</ref>'
            ],
            // Case: Two references with same name
            [
                "input" => '<ref name="test">Refs</ref> <ref name="test">Refs</ref>',
                "expected" => '<ref name="test">Refs</ref> <ref name="test" />'
            ],
            // Case: Different references
            [
                "input" => '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>',
                "expected" => '<ref name="a">Ref1</ref> <ref name="b">Ref2</ref>'
            ],
            // Case: References with multiple attributes
            [
                "input" => '<ref name="test" group="notes">Refs2</ref> <ref name="test" group="notes">Refs2</ref>',
                "expected" => '<ref name="test" group="notes">Refs2</ref> <ref name="test" group="notes" />'
            ],
        ];

        foreach ($tests as $test) {
            $result = remove_Duplicate_refs($test['input']);
            $this->assertEquals($test['expected'], $result);
        }
    }
    // اختبارات دالة remove_Duplicate_refs
    public function _testRemoveIdenticalRefs()
    {
        $tests = [
            // Case: References with different attribute order
            [
                "input" => '<ref name="test" group="notes">Ref</ref> <ref group="notes" name="test">Ref</ref>',
                "expected" => '<ref name="test" group="notes">Ref</ref> <ref name="test" group="notes" />'
            ],
            // Case: Mixed references with and without names
            [
                "input" => '<ref name="named">Named</ref> <ref>Unnamed</ref> <ref>Unnamed</ref>',
                "expected" => '<ref name="named">Named</ref> <ref name="autogen_1">Unnamed</ref> <ref name="autogen_1"/>'
            ],
            // Case: References with group attribute
            [
                "input" => '<ref group="notes">Refs3</ref> <ref group="notes">Refs3</ref>',
                "expected" => '<ref name="autogen_1" group="notes">Refs3</ref> <ref name="autogen_1" group="notes" />'
            ],
            // Case: Multiple references with duplicates
            [
                "input" => '<ref name="x">A</ref> <ref name="x">A</ref> <ref>B</ref> <ref>B</ref>',
                "expected" => '<ref name="x">A</ref> <ref name="x"/> <ref name="autogen_1">B</ref> <ref name="autogen_1" />'
            ],
            // Case: Two identical references without name
            [
                "input" => "<ref>Identical reference</ref> <ref>Identical reference</ref>",
                "expected" => '<ref name="autogen_1">Identical reference</ref> <ref name="autogen_1" />'
            ],
            // Case: Reference without name followed by identical content
            [
                "input" => "<ref>Refs4</ref> <ref>Refs4</ref>",
                "expected" => '<ref name="autogen_1">Refs4</ref> <ref name="autogen_1" />'
            ]
        ];

        foreach ($tests as $test) {
            $result = remove_Duplicate_refs($test['input']);
            $this->assertEquals($test['expected'], $result);
        }
    }
}
