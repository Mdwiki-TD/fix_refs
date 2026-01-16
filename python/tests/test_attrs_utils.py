"""
Test for Bots.attrs_utils module

Ported from: tests/Bots/attrs_utilsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.bots.attrs_utils import normalize_attrs


class TestAttrsUtils(MyFunctionTest):
    """Test attribute utilities"""
    
    def test_normalize_attrs_basic(self):
        """Test basic attribute normalization"""
        attrs = 'name="test"'
        result = normalize_attrs(attrs)
        self.assertIsInstance(result, str)
    
    def test_normalize_attrs_empty(self):
        """Test empty attributes"""
        attrs = ''
        result = normalize_attrs(attrs)
        self.assertEqual("", result)
