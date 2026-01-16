"""
Test for Parse.Citations_reg module

Ported from: tests/Parse/Citations_regTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.Parse.Citations_reg import get_name, get_Reg_Citations, get_full_refs, getShortCitations


class TestCitationsReg(MyFunctionTest):
    """Test regex-based citation parsing"""
    
    def test_get_name_simple(self):
        """Test extracting name attribute"""
        options = 'name="test"'
        result = get_name(options)
        self.assertEqual("test", result)
    
    def test_get_name_with_spaces(self):
        """Test name attribute with spaces"""
        options = 'name = "my name"'
        result = get_name(options)
        self.assertEqual("my", result)
    
    def test_get_name_empty(self):
        """Test empty options"""
        options = ''
        result = get_name(options)
        self.assertEqual("", result)
    
    def test_get_reg_citations(self):
        """Test getting regular citations"""
        text = '<ref name="test">Content here</ref>'
        result = get_Reg_Citations(text)
        
        self.assertEqual(1, len(result))
        self.assertEqual("Content here", result[0]["content"])
        self.assertEqual("test", result[0]["name"])
    
    def test_get_short_citations(self):
        """Test getting self-closing citations"""
        text = '<ref name="test" />'
        result = getShortCitations(text)
        
        self.assertEqual(1, len(result))
        self.assertEqual("", result[0]["content"])
        self.assertEqual("test", result[0]["name"])
    
    def test_get_full_refs(self):
        """Test getting full refs dict"""
        text = '<ref name="ref1">Content 1</ref><ref name="ref2">Content 2</ref>'
        result = get_full_refs(text)
        
        self.assertIn("ref1", result)
        self.assertIn("ref2", result)
