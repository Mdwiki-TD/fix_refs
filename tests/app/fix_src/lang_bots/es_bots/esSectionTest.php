<?php

use WpRefs\Tests\MyFunctionTest;
use App\Fix\lang_bots\es_bots\Section;

class esSectionTest extends MyFunctionTest
{
    /**
     * Test when text already contains the old template {{Traducido ref|...}}
     */
    public function testTextAlreadyHasTraducidoRef()
    {
        $text = "Some content\n{{Traducido ref|title|oldid=12345}}\nMore content";
        $expected = "Some content\n{{Traducido ref|title|oldid=12345}}\nMore content";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when text already contains the new template {{Traducido ref MDWIKI|...}}
     */
    public function testTextAlreadyHasTraducidoRefMdwiki()
    {
        $text = "Content here\n{{Traducido ref MDWIKI|en|Title|oldid=12345}}\nEnd";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($text, $text, $result);
    }

    /**
     * Test when no template exists and "Enlaces externos" section is present
     */
    public function testAddTraducidoRefAfterEnlacesExternos()
    {
        $text = "Content here\n== Enlaces externos ==\n* [http://example.com Link]\n";
        $expected = "Content here\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n* [http://example.com Link]\n";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when no "Enlaces externos" section exists
     */
    public function testAddEnlacesExternosSectionWithTraducidoRef()
    {
        $text = "Content here\nMore content";
        $expected = "Content here\nMore content\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when "Enlaces externos" contains extra spaces
     */
    public function testEnlacesExternosWithExtraSpaces()
    {
        $text = "Content\n== Enlaces   externos ==\n";
        $expected = "Content\n== Enlaces   externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test with empty text
     */
    public function testEmptyText()
    {
        $text = "";
        $expected = "\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }

    /**
     * Test when template contains extra spaces inside
     */
    public function testTraducidoRefWithSpaces()
    {
        $text = "{{ Traducido ref | mdwiki | title | oldid=12345 }}";
        $expected = "{{Traducido ref MDWiki|en| title | oldid=12345 }}";
        $result = Section::es_section("Source Title", $text, "12345");
        $this->assertEqualCompare($expected, $text, $result);
    }
    public function testEsSectionAlreadyHasTemplate()
    {
        $old = "Texto con \n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Título|oldid=111|trad=|fecha=2020}} ya incluido.";
        $new = $old; // no change
        $this->assertEquals($new, Section::es_section("Otro título", $old, 222));
    }

    public function testEsSectionWithExternalLinks()
    {
        $old = "Intro.\n== Enlaces externos ==\n\n* [http://example.com Ejemplo]";
        $new = "Intro.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n\n* [http://example.com Ejemplo]";
        $this->assertEquals($new, Section::es_section("Artículo de prueba", $old, 123));
    }

    public function testEsSectionWithoutExternalLinks()
    {
        $old = "Intro sin sección.";
        $new = "Intro sin sección.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=321|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $this->assertEquals($new, Section::es_section("Artículo de prueba", $old, 321));
    }

    public function testEsSection()
    {
        $old = "Intro sin sección.\n== Enlaces externos ==\n";
        $new = "Intro sin sección.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=321|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n";
        $this->assertEquals($new, Section::es_section("Artículo de prueba", $old, 321));
    }

    // Test when text already contains Traducido ref template
    public function testAlreadyContainsTraducidoRefTemplate()
    {
        $text = "Some content {{Traducido ref|param=value}} more content";
        $expected = "Some content {{Traducido ref|param=value}} more content";
        $result = Section::es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test adding template after existing "Enlaces externos" section
    public function testAddAfterExistingEnlacesExternos()
    {
        $text = "Content before\n== Enlaces externos ==\nMore content";
        $expected = "Content before\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\nMore content";
        $result = Section::es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test appending new section when none exists
    public function testAppendNewSectionWhenNoneExists()
    {
        $text = "No external links section here";
        $expected = "No external links section here\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = Section::es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with empty text input
    public function testEmptyTextInput()
    {
        $text = "";
        $expected = "\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = Section::es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with multiple "Enlaces externos" sections (should only modify first)
    public function testMultipleEnlacesExternosSections()
    {
        $text = "== Enlaces externos ==\nFirst section\n== Enlaces externos ==\nSecond section";
        $expected = "== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\nFirst section\n== Enlaces externos ==\nSecond section";
        $result = Section::es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with case variations in "Enlaces externos"
    public function testCaseVariationsInSectionHeader()
    {
        $text = "== ENLACES EXTERNOS ==";
        $expected = "== ENLACES EXTERNOS ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = Section::es_section('Source Title', $text, '123');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test with leading/trailing whitespace around section header
    public function testWhitespaceAroundSectionHeader()
    {
        $text = "  ==   Enlaces externos   ==  ";
        $expected = "  ==   Enlaces externos   ==\n{{Traducido ref MDWiki|en|test!|oldid=520|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n  ";
        $result = Section::es_section('test!', $text, '520');
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test when template already exists in lowercase/uppercase variations
    public function testTemplateCaseInsensitiveMatch()
    {
        $text = "Something {{traducido REF mdwiki|Title|oldid=100}} end";
        $result = Section::es_section("Source Title", $text, "100");
        $this->assertEquals($text, $result);
    }

    // Test when "Enlaces externos" exists but has no newline after
    public function testSectionWithoutNewlineAfter()
    {
        $text = "Intro\n== Enlaces externos ==";
        $expected = "Intro\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Test|oldid=200|trad=|fecha={{subst:CURRENTDAY}} de {{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n";
        $result = Section::es_section("Test", $text, "200");
        $this->assertEqualCompare($expected, $text, $result);
    }

    // Test when template already exists multiple times
    public function testMultipleTemplatesAlreadyPresent()
    {
        $text = "{{Traducido ref|one}}\n{{Traducido ref|two}}";
        $expected = "{{Traducido ref|one}}\n{{Traducido ref|two}}";
        $result = Section::es_section("Source Title", $text, "300");
        $this->assertEqualCompare($expected, $text, $result);
    }
}
