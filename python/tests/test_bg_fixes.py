"""
Test for bg_bots.fix_bg module (Bulgarian fixes)

Ported from: tests/bg_bots/fix_bgTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.bg_bots.fix_bg import bg_fixes


class TestBgFixes(MyFunctionTest):
    """Test Bulgarian language fixes"""
    
    def test_bg_fixes_basic(self):
        """Test basic Bulgarian fixes"""
        text = "== Източници =="
        result = bg_fixes(text)
        self.assertIsInstance(result, str)
    
    def test_bg_fixes_with_translate_section(self):
        """Test Bulgarian fixes with translation"""
        text = "Article text"
        result = bg_fixes(text)
        self.assertIsInstance(result, str)
