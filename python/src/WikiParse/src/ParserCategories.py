"""
ParserCategories module

Implemented from: src/WikiParse/src/ParserCategories.php

Usage:
    from src.WikiParse.src.ParserCategories import ParserCategories
"""

import re


class ParserCategories:
    """
    Parser for extracting categories from wikitext
    
    Matches: src/WikiParse/src/ParserCategories.php
    """
    
    def __init__(self, text: str):
        """
        Initialize ParserCategories
        
        Args:
            text: Text to parse
        """
        self.text = text
        self.categories = []
        self.parse()
    
    def parse(self):
        """Parse categories from text"""
        # Pattern to match category links
        pattern = r'\[\[\s*Category\s*:([^\]]+)\]\]'
        matches = re.findall(pattern, self.text, re.IGNORECASE)
        
        for match in matches:
            # Split on | to get category name and sort key
            parts = match.split('|')
            category_name = parts[0].strip()
            self.categories.append(category_name)
    
    def getCategories(self) -> list:
        """
        Get parsed categories
        
        Returns:
            List of category names
        """
        return self.categories
