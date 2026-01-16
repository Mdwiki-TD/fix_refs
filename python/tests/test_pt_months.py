"""
Test for pt_bots.fix_pt_months module (Portuguese month fixes)

Ported from: tests/pt_bots/pt_monthsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.pt_bots.fix_pt_months import pt_fixes


class TestPtMonths(MyFunctionTest):
    """Test Portuguese month translation"""
    
    def test_pt_month_translation(self):
        """Test Portuguese month fixes"""
        text = "Text with dates"
        result = pt_fixes(text)
        self.assertIsInstance(result, str)
    
    def test_pt_fixes_with_refs(self):
        """Test Portuguese fixes with references"""
        text = "Text <ref>Reference with January</ref>"
        result = pt_fixes(text)
        self.assertIsInstance(result, str)
