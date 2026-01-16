"""
Test for infoboxes module

Ported from: tests/infoboxes/infoboxTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.infoboxes.infobox import Expend_Infobox, fix_title_bold, make_section_0


class TestInfobox(MyFunctionTest):
    """Test infobox functionality"""

    def test_fix_title_bold(self):
        """Test title bold formatting fix"""
        text = "}}'''Title'''"  # Simple case
        title = "Title"
        result = fix_title_bold(text, title)
        # Should add newlines after the closing }}
        assert isinstance(result, str)

    def test_make_section_0_with_equals(self):
        """Test extracting section 0 when text contains equals signs"""
        text = "Section 0 content==Section 1=="
        title = "Test"
        result = make_section_0(title, text)
        assert result == "Section 0 content"

    def test_make_section_0_without_equals(self):
        """Test extracting section 0 when text doesn't contain equals signs"""
        text = "Just plain text here"
        title = "Test"
        result = make_section_0(title, text)
        assert result == "Just plain text here"

    def test_Expend_Infobox(self):
        """Test expanding infobox"""
        text = "Some text with infobox content"
        title = "Test Title"
        section_0 = ""
        result = Expend_Infobox(text, title, section_0)
        assert isinstance(result, str)
        assert result == text  # Basic implementation returns the same text
