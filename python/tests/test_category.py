"""
Test for Parse.Category module

Ported from: tests/Parse/CategoryTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.Parse.Category import get_categories_reg


class TestCategory(MyFunctionTest):
    """Test category parsing functions"""

    def test_get_categories_with_simple_categories(self):
        """Test simple category extraction"""
        text = "This is some text [[Category:Example]] and more text [[Category:Test]]"
        expected = {
            "Example": "[[Category:Example]]",
            "Test": "[[Category:Test]]"
        }

        result = get_categories_reg(text)
        assert expected == result

    def test_get_categories_with_no_categories(self):
        """Test text without categories"""
        text = "This is some text without any categories"
        expected = {}

        result = get_categories_reg(text)
        assert expected == result


    def test_get_categories_with_pipe_separator(self):
        """Test categories with sort keys"""
        text = "Text with [[Category:Example|sort key]] and [[Category:Test|another key]]"
        expected = {
            "Example": "[[Category:Example|sort key]]",
            "Test": "[[Category:Test|another key]]"
        }

        result = get_categories_reg(text)
        assert expected == result

    def test_get_categories_with_spaces(self):
        """Test categories with extra spaces"""
        text = "Text with [[ Category : Example with spaces ]] and [[  Category:Test  ]]"
        expected = {
            "Example with spaces": "[[ Category : Example with spaces ]]",
            "Test": "[[  Category:Test  ]]"
        }

        result = get_categories_reg(text)
        assert expected == result
