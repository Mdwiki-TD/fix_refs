"""
Test for infoboxes module

Ported from: tests/infoboxes/infoboxTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.infoboxes.infobox import make_bold


class TestInfobox(MyFunctionTest):
    """Test infobox processing"""
    
    def test_make_bold_basic(self):
        """Test making title bold"""
        text = "Article about Test"
        title = "Test"
        result = make_bold(text, title)
        self.assertIn("'''", result)
    
    def test_make_bold_already_bold(self):
        """Test with already bold title"""
        text = "Article about '''Test'''"
        title = "Test"
        result = make_bold(text, title)
        self.assertIn("'''", result)
