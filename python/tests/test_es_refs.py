"""
Test for es_bots.es_refs module (Spanish reference processing)

Ported from: tests/es_bots/es_refsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.es_bots.es_refs import mv_es_refs


class TestEsRefs(MyFunctionTest):
    """Test Spanish reference processing"""

    def test_mv_es_refs_basic(self):
        """Test basic Spanish ref movement"""
        text = "Text <ref>Reference</ref>"
        result = mv_es_refs(text)
        assert isinstance(result, str)

    def test_mv_es_refs_empty(self):
        """Test with empty text"""
        text = ""
        result = mv_es_refs(text)
        assert result == ""
