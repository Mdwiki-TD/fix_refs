<?php

use FixRefs\Tests\MyFunctionTest;
use function WpRefs\EsBots\Section\es_section;

class esSectionTest extends MyFunctionTest
{
    /**
     * Test when text already contains the old template {{Traducido ref|...}}
     */
    public function test_text_already_has_traducido_ref()
    {
        $text = "Some content\n{{Traducido ref|title|oldid=12345}}\nMore content";
        $expected = "Some content\n{{Traducido ref|title|oldid=12345}}\nMore content";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when text already contains the new template {{Traducido ref MDWIKI|...}}
     */
    public function test_text_already_has_traducido_ref_mdwiki()
    {
        $text = "Content here\n{{Traducido ref MDWIKI|en|Title|oldid=12345}}\nEnd";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($text, $text, $result);
    }

    /**
     * Test when no template exists and "Enlaces externos" section is present
     */
    public function test_add_traducido_ref_after_enlaces_externos()
    {
        $text = "Content here\n== Enlaces externos ==\n* [http://example.com Link]\n";
        $expected = "Content here\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n* [http://example.com Link]\n";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when no "Enlaces externos" section exists
     */
    public function test_add_enlaces_externos_section_with_traducido_ref()
    {
        $text = "Content here\nMore content";
        $expected = "Content here\nMore content\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when "Enlaces externos" contains extra spaces
     */
    public function test_enlaces_externos_with_extra_spaces()
    {
        $text = "Content\n== Enlaces   externos ==\n";
        $expected = "Content\n== Enlaces   externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test with empty text
     */
    public function test_empty_text()
    {
        $text = "";
        $expected = "\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when template contains extra spaces inside
     */
    public function test_traducido_ref_with_spaces()
    {
        $text = "{{ Traducido ref | mdwiki | title | oldid=12345 }}";
        $expected = "{{Traducido ref MDWiki|en| title | oldid=12345 }}";
        $result = es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }
    public function test_es_section_already_has_template()
    {
        $old = "Texto con \n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Título|oldid=111|trad=|fecha=2020}} ya incluido.";
        $new = $old; // no change
        $this->assertEquals($new, es_section("Otro título", $old, 222));
    }

    public function test_es_section_with_external_links()
    {
        $old = "Intro.\n== Enlaces externos ==\n\n* [http://example.com Ejemplo]";
        $new = "Intro.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n\n* [http://example.com Ejemplo]";
        $this->assertEquals($new, es_section("Artículo de prueba", $old, 123));
    }

    public function test_es_section_without_external_links()
    {
        $old = "Intro sin sección.";
        $new = "Intro sin sección.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=321|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $this->assertEquals($new, es_section("Artículo de prueba", $old, 321));
    }

    public function test_es_section()
    {
        $old = "Intro sin sección.\n== Enlaces externos ==\n";
        $new = "Intro sin sección.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=321|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n";
        $this->assertEquals($new, es_section("Artículo de prueba", $old, 321));
    }

    // Test when text already contains Traducido ref template
    public function test_already_contains_traducido_ref_template()
    {
        $text = "Some content {{Traducido ref|param=value}} more content";
        $expected = "Some content {{Traducido ref|param=value}} more content";
        $result = es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test adding template after existing "Enlaces externos" section
    public function test_add_after_existing_enlaces_externos()
    {
        $text = "Content before\n== Enlaces externos ==\nMore content";
        $expected = "Content before\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\nMore content";
        $result = es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test appending new section when none exists
    public function test_append_new_section_when_none_exists()
    {
        $text = "No external links section here";
        $expected = "No external links section here\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with empty text input
    public function test_empty_text_input()
    {
        $text = "";
        $expected = "\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with multiple "Enlaces externos" sections (should only modify first)
    public function test_multiple_enlaces_externos_sections()
    {
        $text = "== Enlaces externos ==\nFirst section\n== Enlaces externos ==\nSecond section";
        $expected = "== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\nFirst section\n== Enlaces externos ==\nSecond section";
        $result = es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with case variations in "Enlaces externos"
    public function testCaseVariationsInSectionHeader()
    {
        $text = "== ENLACES EXTERNOS ==";
        $expected = "== ENLACES EXTERNOS ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with leading/trailing whitespace around section header
    public function testWhitespaceAroundSectionHeader()
    {
        $text = "  ==   Enlaces externos   ==  ";
        $expected = "  ==   Enlaces externos   ==\n{{Traducido ref MDWiki|en|test!|oldid=520|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n  ";
        $result = es_section('test!', $text, '520');
        $this->assertEqualCompare($expected, $text, $result);
    }
}
