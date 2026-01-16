"""
Test for remove_space module

Ported from: tests/remove_spaceTest.php
"""

from tests.bootstrap import MyFunctionTest
from src.helps_bots.remove_space import remove_spaces_between_last_word_and_beginning_of_ref
import os


class TestRemoveSpace(MyFunctionTest):
    """Test remove_space functions"""
    
    def test_remove_space_end_1st_file(self):
        """Test with first text file"""
        base_dir = os.path.dirname(__file__)
        input_file = os.path.join(base_dir, "texts/remove_space_texts/1/input.txt")
        expected_file = os.path.join(base_dir, "texts/remove_space_texts/1/expected.txt")
        
        with open(input_file, 'r', encoding='utf-8') as f:
            input_text = f.read()
        with open(expected_file, 'r', encoding='utf-8') as f:
            expected = f.read()
        
        result = remove_spaces_between_last_word_and_beginning_of_ref(input_text, 'hy')
        
        # Write output file
        output_file = os.path.join(base_dir, "texts/remove_space_texts/1/output.txt")
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write(result)
        
        self.assertEqualCompare(expected, input_text, result)
    
    def test_remove_space_end_2nd_file(self):
        """Test with second text file"""
        base_dir = os.path.dirname(__file__)
        input_file = os.path.join(base_dir, "texts/remove_space_texts/2/input.txt")
        expected_file = os.path.join(base_dir, "texts/remove_space_texts/2/expected.txt")
        
        with open(input_file, 'r', encoding='utf-8') as f:
            input_text = f.read()
        with open(expected_file, 'r', encoding='utf-8') as f:
            expected = f.read()
        
        result = remove_spaces_between_last_word_and_beginning_of_ref(input_text, 'hy')
        
        # Write output file
        output_file = os.path.join(base_dir, "texts/remove_space_texts/2/output.txt")
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write(result)
        
        self.assertEqualCompare(expected, input_text, result)
    
    def test_remove_space_end_3rd_file(self):
        """Test with inline text"""
        input_text = """Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] test0 <ref name="Os2018" /><ref>{{ref
|<!!>
}}</ref>։ test1 <ref name="Os2018" /><ref>{{ref
|<!!>
}}</ref>։"""
        
        expected = """Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] test0 <ref name="Os2018" /><ref>{{ref
|<!!>
}}</ref>։ test1<ref name="Os2018" /><ref>{{ref
|<!!>
}}</ref>։"""
        
        result = remove_spaces_between_last_word_and_beginning_of_ref(input_text, 'hy')
        self.assertEqualCompare(expected, input_text, result)
