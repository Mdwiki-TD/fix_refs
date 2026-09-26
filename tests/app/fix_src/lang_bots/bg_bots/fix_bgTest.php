<?php

use FixRefs\Tests\MyFunctionTest;
use function WpRefs\BG\bg_fixes;
use function WpRefs\BG\bg_section;

class fix_bgTest extends MyFunctionTest
{
    // =========================================================================
    // Tests for bg_section() function
    // =========================================================================

    public function testBgSectionWithExistingTranslationTemplate()
    {
        // Text already has translation template - should return text as is
        $text = "Some text here\n{{Превод от|mdwiki|Example|123456}}\n[[Категория:Test]]";
        $result = bg_section($text, "Example", "123456");
        $this->assertEqualCompare($text, $text, $result);
    }

    public function testBgSectionAddTemplateBeforeFirstBulgarianCategory()
    {
        // Text has Bulgarian category - should add template before first category
        $text = "Regular text\n[[Категория:Test]]\n[[Category:Another]]";
        $expected = "Regular text\n{{Превод от|mdwiki|TestTitle|789}}\n[[Категория:Test]]\n[[Category:Another]]";
        $result = bg_section($text, "TestTitle", "789");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgSectionAddTemplateBeforeFirstEnglishCategory()
    {
        // Text has English category - should add template before first category
        $text = "Some text\n[[Category:English]]\n[[Категория:Bulgarian]]";
        $expected = "Some text\n{{Превод от|mdwiki|EnglishTitle|111}}\n[[Category:English]]\n[[Категория:Bulgarian]]";
        $result = bg_section($text, "EnglishTitle", "111");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgSectionWithoutCategories()
    {
        // Text has no categories - should add template at the end
        $text = "Text without categories";
        $expected = "Text without categories\n{{Превод от|mdwiki|Test|222}}\n";
        $result = bg_section($text, "Test", "222");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgSectionCaseInsensitiveTemplateDetection()
    {
        // Text has translation template with different cases - should be detected
        $text1 = "{{Превод от|mdwiki|Test|123}}";
        $text2 = "{{ превод от |mdwiki|Test|123}}";
        $text3 = "{{ПРЕВОД ОТ|mdwiki|Test|123}}";

        $result1 = bg_section($text1, "NewTitle", "456");
        $result2 = bg_section($text2, "NewTitle", "456");
        $result3 = bg_section($text3, "NewTitle", "456");

        $this->assertEqualCompare($text1, $text1, $result1);
        $this->assertEqualCompare($text2, $text2, $result2);
        $this->assertEqualCompare($text3, $text3, $result3);
    }

    public function testBgSectionWithMultipleCategories()
    {
        // Text with multiple categories - should add template before first category
        $text = "Text\n[[Category:First]]\n[[Категория:Second]]\n[[Category:Third]]";
        $expected = "Text\n{{Превод от|mdwiki|MultiCat|333}}\n[[Category:First]]\n[[Категория:Second]]\n[[Category:Third]]";
        $result = bg_section($text, "MultiCat", "333");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgSectionTemplateAddedAtCorrectPosition()
    {
        // Template should be inserted exactly before the first category match
        $text = "Line 1\nLine 2\n[[Category:Test]]\nLine 4";
        $expected = "Line 1\nLine 2\n{{Превод от|mdwiki|Position|444}}\n[[Category:Test]]\nLine 4";
        $result = bg_section($text, "Position", "444");
        $this->assertEqualCompare($expected, $text, $result);
    }

    // =========================================================================
    // Tests for bg_fixes() function
    // =========================================================================

    public function testBgFixesRemoveTranslatedCategoryAndAddTemplate()
    {
        // Should remove translated category and add translation template
        $text = "Test text\n[[Category:Translated from MDWiki]]\n[[Категория:Test]]";
        $expected = "Test text\n{{Превод от|mdwiki|TestTitle|444}}\n\n[[Категория:Test]]";
        $result = bg_fixes($text, "TestTitle", "444");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesWithExistingTranslationTemplate()
    {
        // Text has existing template - should keep it and remove translated category
        $text = "Text\n{{Превод от|mdwiki|Existing|555}}\n[[Category:Translated from MDWiki]]\n[[Категория:Test]]";
        $expected = "Text\n{{Превод от|mdwiki|Existing|555}}\n\n[[Категория:Test]]";
        $result = bg_fixes($text, "NewTitle", "666");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesCaseInsensitiveTranslatedCategoryRemoval()
    {
        // Should remove translated category with different cases
        $text1 = "[[Category:Translated from MDWiki]]\n[[Категория:Test]]";
        $text2 = "[[категория:translated from mdwiki]]\n[[Категория:Test]]";
        $text3 = "[[CATEGORY:TRANSLATED FROM MDWIKI]]\n[[Категория:Test]]";

        $expected = "{{Превод от|mdwiki|Test|777}}\n\n[[Категория:Test]]";

        $result1 = bg_fixes($text1, "Test", "777");
        $result2 = bg_fixes($text2, "Test", "777");
        $result3 = bg_fixes($text3, "Test", "777");

        $this->assertEqualCompare($expected, $text1, $result1);
        $this->assertEqualCompare($expected, $text2, $result2);
        $this->assertEqualCompare($expected, $text3, $result3);
    }

    public function testBgFixesWithoutTranslatedCategory()
    {
        // Text without translated category - only add translation template if needed
        $text = "Regular text\n[[Категория:Test]]";
        $expected = "Regular text\n{{Превод от|mdwiki|Simple|888}}\n[[Категория:Test]]";
        $result = bg_fixes($text, "Simple", "888");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesEmptyText()
    {
        // Empty text
        $text = "";
        $expected = "\n{{Превод от|mdwiki|Empty|999}}\n";
        $result = bg_fixes($text, "Empty", "999");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesOnlyTranslatedCategory()
    {
        // Text contains only translated category
        $text = "[[Category:Translated from MDWiki]]";
        $expected = "{{Превод от|mdwiki|OnlyTranslated|1010}}\n";
        $result = bg_fixes($text, "OnlyTranslated", "1010");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesTranslatedCategoryWithoutOtherCategories()
    {
        // Text with translated category but no other categories
        $text = "Regular text\n[[Category:Translated from MDWiki]]";
        $expected = "Regular text\n{{Превод от|mdwiki|NoOtherCats|1111}}\n";
        $result = bg_fixes($text, "NoOtherCats", "1111");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesWithWhitespaceInCategory()
    {
        // Category with extra whitespace should still be removed
        $text = "Text\n[[Category:  Translated from MDWiki  ]]\n[[Категория:Test]]";
        $expected = "Text\n{{Превод от|mdwiki|Whitespace|1212}}\n\n[[Категория:Test]]";
        $result = bg_fixes($text, "Whitespace", "1212");
        $this->assertEqualCompare($expected, $text, $result);
    }

    // =========================================================================
    // Edge cases and special scenarios
    // =========================================================================

    public function testBgSectionSpecialCharactersInParameters()
    {
        // Special characters in title and revid
        $text = "[[Category:Test]]";
        $expected = "{{Превод от|mdwiki|Title with spaces & special|rev-123}}\n[[Category:Test]]";
        $result = bg_section($text, "Title with spaces & special", "rev-123");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgSectionMultipleLineText()
    {
        // Multi-line text with categories
        $text = "Line 1\n\nLine 3\n\n[[Category:First]]\nLine 6\n[[Категория:Second]]";
        $expected = "Line 1\n\nLine 3\n\n{{Превод от|mdwiki|MultiLine|1313}}\n[[Category:First]]\nLine 6\n[[Категория:Second]]";
        $result = bg_section($text, "MultiLine", "1313");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesMultipleTranslatedCategories()
    {
        // Text with multiple translated categories
        $text = "Text\n[[Category:Translated from MDWiki]]\n[[категория:translated from mdwiki]]\n[[Category:Test]]";
        $expected = "Text\n{{Превод от|mdwiki|Multiple|1414}}\n\n\n[[Category:Test]]";
        $result = bg_fixes($text, "Multiple", "1414");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgFixesMixedContent()
    {
        // Complex text with mixed content
        $text = "Introduction\n{{Some other template}}\n[[Category:Translated from MDWiki]]\n\nContent here\n[[Категория:Main]]\n[[Category:Secondary]]";
        $expected = "Introduction\n{{Some other template}}\n{{Превод от|mdwiki|Mixed|1515}}\n\n\nContent here\n[[Категория:Main]]\n[[Category:Secondary]]";
        $result = bg_fixes($text, "Mixed", "1515");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function testBgSectionUnicodeSupport()
    {
        // Test Unicode support in category names
        $text = "Text with Unicode\n[[Категория:Тест]]\n[[Category:Test]]";
        $expected = "Text with Unicode\n{{Превод от|mdwiki|Unicode|1616}}\n[[Категория:Тест]]\n[[Category:Test]]";
        $result = bg_section($text, "Unicode", "1616");
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function test_already_has_template()
    {
        $text = "Some intro\n{{Превод от|mdwiki|TestTitle|12345}}\n[[Категория:Drugs]]";
        $result = bg_section($text, "Naproxen", 1468415);
        $this->assertEqualCompare($text, $text, $result);
    }

    public function test_add_before_bg_category()
    {
        $text = "Intro text\n[[Категория:Medicine]]";
        $expected = "Intro text\n{{Превод от|mdwiki|Naproxen|1468415}}\n[[Категория:Medicine]]";
        $result = bg_section($text, "Naproxen", 1468415);
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function test_add_before_en_category()
    {
        $text = "Intro text\n[[Category:Medicine]]";
        $expected = "Intro text\n{{Превод от|mdwiki|Naproxen|1468415}}\n[[Category:Medicine]]";
        $result = bg_section($text, "Naproxen", 1468415);
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function test_add_at_end_if_no_category()
    {
        $text = "Some intro\nSome content";
        $expected = "Some intro\nSome content\n{{Превод от|mdwiki|Naproxen|1468415}}\n";
        $result = bg_section($text, "Naproxen", 1468415);
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function test_add_before_first_of_multiple_categories()
    {
        $text = "Intro text\n[[Категория:First]]\n[[Category:Second]]";
        $expected = "Intro text\n{{Превод от|mdwiki|Naproxen|1468415}}\n[[Категория:First]]\n[[Category:Second]]";
        $result = bg_section($text, "Naproxen", 1468415);
        $this->assertEqualCompare($expected, $text, $result);
    }

    public function test_empty_text()
    {
        $text = "";
        $expected = "\n{{Превод от|mdwiki|Naproxen|1468415}}\n";
        $result = bg_section($text, "Naproxen", 1468415);
        $this->assertEqualCompare($expected, $text, $result);
    }
}
