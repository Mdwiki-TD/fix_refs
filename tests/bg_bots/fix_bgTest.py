from __future__ import annotations

from src.bg_bots.fix_bg import bg_fixes, bg_section
from tests.conftest import assert_equal_compare


class fix_bgTest:
    def testBgSectionWithExistingTranslationTemplate(self) -> None:
        text = "Some text here\n{{Превод от|mdwiki|Example|123456}}\n[[Категория:Test]]"
        assert_equal_compare(text, text, bg_section(text, "Example", "123456"))

    def testBgSectionAddTemplateBeforeFirstBulgarianCategory(self) -> None:
        text = "Regular text\n[[Категория:Test]]\n[[Category:Another]]"
        expected = "Regular text\n{{Превод от|mdwiki|TestTitle|789}}\n[[Категория:Test]]\n[[Category:Another]]"
        assert_equal_compare(expected, text, bg_section(text, "TestTitle", "789"))

    def testBgSectionAddTemplateBeforeFirstEnglishCategory(self) -> None:
        text = "Some text\n[[Category:English]]\n[[Категория:Bulgarian]]"
        expected = "Some text\n{{Превод от|mdwiki|EnglishTitle|111}}\n[[Category:English]]\n[[Категория:Bulgarian]]"
        assert_equal_compare(expected, text, bg_section(text, "EnglishTitle", "111"))

    def testBgSectionWithoutCategories(self) -> None:
        text = "Text without categories"
        expected = "Text without categories\n{{Превод от|mdwiki|Test|222}}\n"
        assert_equal_compare(expected, text, bg_section(text, "Test", "222"))

    def testBgSectionCaseInsensitiveTemplateDetection(self) -> None:
        texts = [
            "{{Превод от|mdwiki|Test|123}}",
            "{{ превод от |mdwiki|Test|123}}",
            "{{ПРЕВОД ОТ|mdwiki|Test|123}}",
        ]
        for text in texts:
            assert_equal_compare(text, text, bg_section(text, "NewTitle", "456"))

    def testBgSectionWithMultipleCategories(self) -> None:
        text = "Text\n[[Category:First]]\n[[Категория:Second]]\n[[Category:Third]]"
        expected = (
            "Text\n{{Превод от|mdwiki|MultiCat|333}}\n[[Category:First]]\n[[Категория:Second]]\n[[Category:Third]]"
        )
        assert_equal_compare(expected, text, bg_section(text, "MultiCat", "333"))

    def testBgSectionTemplateAddedAtCorrectPosition(self) -> None:
        text = "Line 1\nLine 2\n[[Category:Test]]\nLine 4"
        expected = "Line 1\nLine 2\n{{Превод от|mdwiki|Position|444}}\n[[Category:Test]]\nLine 4"
        assert_equal_compare(expected, text, bg_section(text, "Position", "444"))

    def testBgFixesRemoveTranslatedCategoryAndAddTemplate(self) -> None:
        text = "Test text\n[[Category:Translated from MDWiki]]\n[[Категория:Test]]"
        expected = "Test text\n{{Превод от|mdwiki|TestTitle|444}}\n\n[[Категория:Test]]"
        assert_equal_compare(expected, text, bg_fixes(text, "TestTitle", "444"))

    def testBgFixesWithExistingTranslationTemplate(self) -> None:
        text = "Text\n{{Превод от|mdwiki|Existing|555}}\n[[Category:Translated from MDWiki]]\n[[Категория:Test]]"
        expected = "Text\n{{Превод от|mdwiki|Existing|555}}\n\n[[Категория:Test]]"
        assert_equal_compare(expected, text, bg_fixes(text, "NewTitle", "666"))

    def testBgFixesCaseInsensitiveTranslatedCategoryRemoval(self) -> None:
        texts = [
            "[[Category:Translated from MDWiki]]\n[[Категория:Test]]",
            "[[категория:translated from mdwiki]]\n[[Категория:Test]]",
            "[[CATEGORY:TRANSLATED FROM MDWIKI]]\n[[Категория:Test]]",
        ]
        expected = "{{Превод от|mdwiki|Test|777}}\n\n[[Категория:Test]]"
        for text in texts:
            assert_equal_compare(expected, text, bg_fixes(text, "Test", "777"))

    def testBgFixesWithoutTranslatedCategory(self) -> None:
        text = "Regular text\n[[Категория:Test]]"
        expected = "Regular text\n{{Превод от|mdwiki|Simple|888}}\n[[Категория:Test]]"
        assert_equal_compare(expected, text, bg_fixes(text, "Simple", "888"))

    def testBgFixesEmptyText(self) -> None:
        text = ""
        expected = "\n{{Превод от|mdwiki|Empty|999}}\n"
        assert_equal_compare(expected, text, bg_fixes(text, "Empty", "999"))

    def testBgFixesOnlyTranslatedCategory(self) -> None:
        text = "[[Category:Translated from MDWiki]]"
        expected = "{{Превод от|mdwiki|OnlyTranslated|1010}}\n"
        assert_equal_compare(expected, text, bg_fixes(text, "OnlyTranslated", "1010"))

    def testBgFixesTranslatedCategoryWithoutOtherCategories(self) -> None:
        text = "Regular text\n[[Category:Translated from MDWiki]]"
        expected = "Regular text\n{{Превод от|mdwiki|NoOtherCats|1111}}\n"
        assert_equal_compare(expected, text, bg_fixes(text, "NoOtherCats", "1111"))

    def testBgFixesWithWhitespaceInCategory(self) -> None:
        text = "Text\n[[Category:  Translated from MDWiki  ]]\n[[Категория:Test]]"
        expected = "Text\n{{Превод от|mdwiki|Whitespace|1212}}\n\n[[Категория:Test]]"
        assert_equal_compare(expected, text, bg_fixes(text, "Whitespace", "1212"))

    def testBgSectionSpecialCharactersInParameters(self) -> None:
        text = "[[Category:Test]]"
        expected = "{{Превод от|mdwiki|Title with spaces & special|rev-123}}\n[[Category:Test]]"
        assert_equal_compare(expected, text, bg_section(text, "Title with spaces & special", "rev-123"))

    def testBgSectionMultipleLineText(self) -> None:
        text = "Line 1\n\nLine 3\n\n[[Category:First]]\nLine 6\n[[Категория:Second]]"
        expected = (
            "Line 1\n\nLine 3\n\n{{Превод от|mdwiki|MultiLine|1313}}\n[[Category:First]]\nLine 6\n[[Категория:Second]]"
        )
        assert_equal_compare(expected, text, bg_section(text, "MultiLine", "1313"))

    def testBgFixesMultipleTranslatedCategories(self) -> None:
        text = (
            "Text\n[[Category:Translated from MDWiki]]\n[[категория:translated from mdwiki]]\n[[Category:Test]]"
        )
        expected = "Text\n{{Превод от|mdwiki|Multiple|1414}}\n\n\n[[Category:Test]]"
        assert_equal_compare(expected, text, bg_fixes(text, "Multiple", "1414"))

    def testBgFixesMixedContent(self) -> None:
        text = (
            "Introduction\n{{Some other template}}\n[[Category:Translated from MDWiki]]\n\nContent here\n[[Категория:Main]]\n[[Category:Secondary]]"
        )
        expected = (
            "Introduction\n{{Some other template}}\n{{Превод от|mdwiki|Mixed|1515}}\n\n\nContent here\n[[Категория:Main]]\n[[Category:Secondary]]"
        )
        assert_equal_compare(expected, text, bg_fixes(text, "Mixed", "1515"))

    def testBgSectionUnicodeSupport(self) -> None:
        text = "Text with Unicode\n[[Категория:Тест]]\n[[Category:Test]]"
        expected = "Text with Unicode\n{{Превод от|mdwiki|Unicode|1616}}\n[[Категория:Тест]]\n[[Category:Test]]"
        assert_equal_compare(expected, text, bg_section(text, "Unicode", "1616"))

    def test_already_has_template(self) -> None:
        text = "Some intro\n{{Превод от|mdwiki|TestTitle|12345}}\n[[Категория:Drugs]]"
        assert_equal_compare(text, text, bg_section(text, "Naproxen", 1468415))

    def test_add_before_bg_category(self) -> None:
        text = "Intro text\n[[Категория:Medicine]]"
        expected = "Intro text\n{{Превод от|mdwiki|Naproxen|1468415}}\n[[Категория:Medicine]]"
        assert_equal_compare(expected, text, bg_section(text, "Naproxen", 1468415))

    def test_add_before_en_category(self) -> None:
        text = "Intro text\n[[Category:Medicine]]"
        expected = "Intro text\n{{Превод от|mdwiki|Naproxen|1468415}}\n[[Category:Medicine]]"
        assert_equal_compare(expected, text, bg_section(text, "Naproxen", 1468415))

    def test_add_at_end_if_no_category(self) -> None:
        text = "Some intro\nSome content"
        expected = "Some intro\nSome content\n{{Превод от|mdwiki|Naproxen|1468415}}\n"
        assert_equal_compare(expected, text, bg_section(text, "Naproxen", 1468415))

    def test_add_before_first_of_multiple_categories(self) -> None:
        text = "Intro text\n[[Категория:First]]\n[[Category:Second]]"
        expected = (
            "Intro text\n{{Превод от|mdwiki|Naproxen|1468415}}\n[[Категория:First]]\n[[Category:Second]]"
        )
        assert_equal_compare(expected, text, bg_section(text, "Naproxen", 1468415))

    def test_empty_text(self) -> None:
        text = ""
        expected = "\n{{Превод от|mdwiki|Naproxen|1468415}}\n"
        assert_equal_compare(expected, text, bg_section(text, "Naproxen", 1468415))
