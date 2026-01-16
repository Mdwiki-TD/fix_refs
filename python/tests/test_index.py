"""
Test index module

Matches tests from indexTest.php
"""

import pytest
import os
from tests.bootstrap import MyFunctionTest


# Import after adding to path
from src.index import fix_page


class TestIndex(MyFunctionTest):
    """Tests for main fix_page function"""
    
    def fix_page_wrap(self, text: str, lang: str):
        """
        Wrapper for fix_page with default parameters
        
        Args:
            text: Text to process
            lang: Language code
            
        Returns:
            Fixed text
        """
        return fix_page(
            text=text,
            title="",
            move_dots=True,
            infobox=False,
            add_en_lang=False,
            lang=lang,
            sourcetitle="",
            mdwiki_revid=0
        )
    
    def test_part1(self):
        """Test part 1 from indexTest"""
        test_dir = os.path.join(os.path.dirname(__file__), "texts/indexTest/1")
        
        with open(os.path.join(test_dir, "input.txt"), 'r', encoding='utf-8') as f:
            input_text = f.read()
        
        with open(os.path.join(test_dir, "expected.txt"), 'r', encoding='utf-8') as f:
            expected = f.read()
        
        result = self.fix_page_wrap(input_text, 'hy')
        
        # Write output for debugging
        output_file = os.path.join(test_dir, "output.txt")
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write(result)
        
        self.assertEqualCompare(expected, input_text, result)
    
    def test_part2(self):
        """Test part 2 from indexTest"""
        test_dir = os.path.join(os.path.dirname(__file__), "texts/indexTest/2")
        
        with open(os.path.join(test_dir, "input.txt"), 'r', encoding='utf-8') as f:
            input_text = f.read()
        
        with open(os.path.join(test_dir, "expected.txt"), 'r', encoding='utf-8') as f:
            expected = f.read()
        
        result = self.fix_page_wrap(input_text, 'hy')
        
        # Write output for debugging
        output_file = os.path.join(test_dir, "output.txt")
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write(result)
        
        self.assertEqualCompare(expected, input_text, result)
    
    def test_part3(self):
        """Test part 3 from indexTest"""
        input_text = '[[Category:Translated from MDWiki]] ռետինոիդներ։ <ref name="NORD2006" /><ref name="Gli2017" />'
        expected = '[[Category:Translated from MDWiki]] ռետինոիդներ<ref name="NORD2006" /><ref name="Gli2017" />։'
        
        result = self.fix_page_wrap(input_text, 'hy')
        
        self.assertEqualCompare(expected, input_text, result)
    
    def test_part4(self):
        """Test part 4 from indexTest"""
        input_text = '[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ <ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։'
        expected = '[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ<ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։'
        
        result = self.fix_page_wrap(input_text, 'hy')
        
        self.assertEqualCompare(expected, input_text, result)
