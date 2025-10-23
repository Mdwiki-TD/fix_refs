from __future__ import annotations

from src.Parse.Category import get_categories_reg


class CategoryTest:
    def test_get_categories_with_simple_categories(self) -> None:
        text = "This is some text [[Category:Example]] and more text [[Category:Test]]"
        expected = {"Example": "[[Category:Example]]", "Test": "[[Category:Test]]"}
        assert get_categories_reg(text) == expected

    def test_get_categories_with_no_categories(self) -> None:
        assert get_categories_reg("This is some text without any categories") == {}

    def test_get_categories_with_pipe_separator(self) -> None:
        text = "Text with [[Category:Example|sort key]] and [[Category:Test|another key]]"
        expected = {
            "Example": "[[Category:Example|sort key]]",
            "Test": "[[Category:Test|another key]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_categories_with_spaces(self) -> None:
        text = "Text with [[ Category : Example with spaces ]] and [[  Category:Test  ]]"
        expected = {
            "Example with spaces": "[[ Category : Example with spaces ]]",
            "Test": "[[  Category:Test  ]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_categories_with_special_characters(self) -> None:
        text = "Text with [[Category:Example & Test]] and [[Category:Something (else)]]"
        expected = {
            "Example & Test": "[[Category:Example & Test]]",
            "Something (else)": "[[Category:Something (else)]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_categories_with_duplicate_categories(self) -> None:
        text = "Text with [[Category:Example]] and more [[Category:Example]]"
        assert get_categories_reg(text) == {"Example": "[[Category:Example]]"}

    def test_get_categories_with_multiline_text(self) -> None:
        text = (
            "Start of text\n[[Category:First category]]\nMiddle of text\n"
            "[[Category:Second category]]\nEnd of text"
        )
        expected = {
            "First category": "[[Category:First category]]",
            "Second category": "[[Category:Second category]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_categories_with_empty_input(self) -> None:
        assert get_categories_reg("") == {}

    def test_get_categories_with_multiple_pipes(self) -> None:
        text = "Text with [[Category:Example|sort|key]] and [[Category:Test]]"
        expected = {
            "Example": "[[Category:Example|sort|key]]",
            "Test": "[[Category:Test]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_categories_with_unicode_characters(self) -> None:
        text = "Text with [[Category:مثال]] and [[Category:測試]]"
        expected = {"مثال": "[[Category:مثال]]", "測試": "[[Category:測試]]"}
        assert get_categories_reg(text) == expected

    def test_get_categories_with_mixed_case(self) -> None:
        text = "Text with [[category:example]] and [[CATEGORY:TEST]]"
        expected = {"example": "[[category:example]]", "TEST": "[[CATEGORY:TEST]]"}
        assert get_categories_reg(text) == expected

    def test_get_categories_with_templates_inside(self) -> None:
        text = "Text with [[Category:Example{{template}}]] and [[Category:Test]]"
        expected = {
            "Example{{template}}": "[[Category:Example{{template}}]]",
            "Test": "[[Category:Test]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_single_category(self) -> None:
        assert get_categories_reg("Some text here [[Category:PHP]] more text.") == {
            "PHP": "[[Category:PHP]]"
        }

    def test_get_multiple_categories(self) -> None:
        assert get_categories_reg("[[Category:Programming]] and [[Category:Web development]].") == {
            "Programming": "[[Category:Programming]]",
            "Web development": "[[Category:Web development]]",
        }

    def test_no_categories_found(self) -> None:
        assert get_categories_reg("This is a text with no categories.") == {}

    def test_category_with_extra_whitespace(self) -> None:
        assert get_categories_reg("[[Category:  Test Category  ]]") == {
            "Test Category": "[[Category:  Test Category  ]]"
        }

    def test_case_insensitive_category_tag(self) -> None:
        assert get_categories_reg("[[category:Case Insensitive]]") == {
            "Case Insensitive": "[[category:Case Insensitive]]"
        }

    def test_category_with_sort_key(self) -> None:
        assert get_categories_reg("[[Category:Musicians|Beatles]]") == {
            "Musicians": "[[Category:Musicians|Beatles]]"
        }

    def test_mixed_and_complex_categories(self) -> None:
        text = "A complex text [[Category:Software|S]] and another one [[  category :  Databases  ]]."
        expected = {
            "Software": "[[Category:Software|S]]",
            "Databases": "[[  category :  Databases  ]]",
        }
        assert get_categories_reg(text) == expected

    def test_get_categories_with_nested_brackets(self) -> None:
        text = "Text with [[category:Example {{nested}} | {{!}} ]] and [[CategorY:Test]]"
        expected = {
            "Example {{nested}}": "[[category:Example {{nested}} | {{!}} ]]",
            "Test": "[[CategorY:Test]]",
        }
        assert get_categories_reg(text) == expected
