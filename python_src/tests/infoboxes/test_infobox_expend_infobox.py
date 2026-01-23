"""Tests for infobox expansion (expend_infobox.py)

Converted from tests/infoboxes/infoboxTest.php and tests/infoboxes/infobox2Test.php
"""
import pytest
from src.infobox import expend_infobox as ei_module

expend_new = ei_module.expend_new
make_tempse = ei_module.make_tempse
fix_title_bold = ei_module.fix_title_bold
make_section_0 = ei_module.make_section_0
expend_infobox = ei_module.Expend_Infobox


class TestInfobox:
    """Test cases for infobox expansion"""

    def test_expend_new_simple_template(self):
        """Test expend_new with a simple template"""
        # Test with basic template
        result = expend_new("{{test}}")
        assert result == "{{test}}"

    def test_make_tempse(self):
        """Test make_tempse function"""
        text = """{{Infobox drug
|name=Test
|value=123
}}
Some text {{other template}}"""
        result = make_tempse(text)

        # make_tempse returns a dict mapping template text to template text
        assert isinstance(result, dict)
        assert len(result) > 0

    def test_fix_title_bold(self):
        """Test fix_title_bold function"""
        # The function expects: }'''``''title''``''' pattern (} + 5 quotes + title + 5 quotes)
        text = "}'''''TestTitle'''''Some text"
        result = fix_title_bold(text, "TestTitle")
        # The function should replace the }'''``''TestTitle''``''' pattern
        assert "'''''TestTitle'''''" not in result

    def test_make_section_0(self):
        """Test make_section_0 function"""
        text = "Some text before\n==Section==\nContent after"
        result = make_section_0("Title", text)
        assert result == "Some text before\n"

        # Test without sections
        text2 = "Just some text"
        result2 = make_section_0("Title", text2)
        assert result2 == text2

    def test_expend_infobox_basic(self):
        """Test expend_infobox with basic infobox"""
        text = """{{Infobox drug|name=Test}}
Some content"""
        result = expend_infobox(text, "TestPage", "")
        assert isinstance(result, str)
        assert len(result) > 0

    # Note: Full infoboxTest.php test requires file-based I/O
    # The test_expend_new_FileText would require reading from test data files
    # This is a simplified version - for full testing, create test data files
