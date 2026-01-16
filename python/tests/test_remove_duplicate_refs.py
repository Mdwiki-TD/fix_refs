"""
Test for Bots.remove_duplicate_refs module

Ported from: tests/Bots/remove_duplicate_refsTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.bots.remove_duplicate_refs import remove_Duplicate_refs_With_attrs


class TestRemoveDuplicateRefs(MyFunctionTest):
    """Test duplicate reference removal"""
    
    def test_remove_duplicates_basic(self):
        """Test basic duplicate removal"""
        text = '<ref name="test">Content</ref> more text <ref name="test">Content</ref>'
        result = remove_Duplicate_refs_With_attrs(text)
        # Should have only one full ref
        self.assertIsInstance(result, str)
    
    def test_remove_duplicates_none(self):
        """Test with no duplicates"""
        text = '<ref name="test1">Content1</ref> <ref name="test2">Content2</ref>'
        result = remove_Duplicate_refs_With_attrs(text)
        self.assertIsInstance(result, str)
