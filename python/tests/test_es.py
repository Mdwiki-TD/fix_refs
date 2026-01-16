"""
Test for es_bots module (Spanish fixes)

Ported from: tests/es_bots/esTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.es_bots.es import fix_es


class TestEs(MyFunctionTest):
    """Test Spanish language fixes"""
    
    def test_es_fixes_basic(self):
        """Test basic Spanish fixes"""
        text = "Article text"
        result = fix_es(text)
        self.assertIsInstance(result, str)
    
    def test_es_fixes_with_templates(self):
        """Test Spanish template translation"""
        text = "{{cite web|title=Test}}"
        result = fix_es(text)
        # Should translate cite web to cita web
        self.assertIn("cita", result.lower())
