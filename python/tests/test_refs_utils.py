"""
Test for Bots.refs_utils module

Ported from: tests/Bots/refs_utilsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.bots.refs_utils import fix_ref_attrs


class TestRefsUtils(MyFunctionTest):
    """Test reference utilities"""
    
    def test_fix_ref_attrs_basic(self):
        """Test basic ref attribute fixing"""
        text = '<ref name="test">Content</ref>'
        result = fix_ref_attrs(text)
        self.assertIsInstance(result, str)
