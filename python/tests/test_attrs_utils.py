"""
Test for Bots.attrs_utils module

Ported from: tests/Bots/attrs_utilsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.bots.attrs_utils import parseAttributes, get_attrs


class TestAttrsUtils(MyFunctionTest):
    """Test attribute utilities"""

    def test_parse_attributes_basic(self):
        """Test basic attribute parsing"""
        text = 'name="test" value="example"'
        result = parseAttributes(text)
        expected = {'name': 'test', 'value': 'example'}
        assert result == expected

    def test_get_attrs_basic(self):
        """Test basic attribute getting"""
        text = 'name="test" value="example"'
        result = get_attrs(text)
        expected = {'name': 'test', 'value': 'example'}
        assert result == expected

    def test_parse_attributes_empty(self):
        """Test parsing empty attributes"""
        text = ''
        result = parseAttributes(text)
        # Should contain ref as a key since the function prepends <ref
        assert isinstance(result, dict)

    def test_get_attrs_empty(self):
        """Test getting empty attributes"""
        text = ''
        result = get_attrs(text)
        assert isinstance(result, dict)
