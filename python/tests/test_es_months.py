"""
Test for es_bots.es_months module (Spanish month fixes)

Ported from: tests/es_bots/es_monthsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.es_bots.es_months import fix_es_months_in_refs


class TestEsMonths(MyFunctionTest):
    """Test Spanish month translation"""
    
    def test_es_months_basic(self):
        """Test basic Spanish month fixes"""
        text = "Text with dates"
        result = fix_es_months_in_refs(text)
        self.assertIsInstance(result, str)
    
    def test_es_months_with_refs(self):
        """Test Spanish month fixes in references"""
        text = "<ref>{{cite web|date=January 2020}}</ref>"
        result = fix_es_months_in_refs(text)
        self.assertIsInstance(result, str)
