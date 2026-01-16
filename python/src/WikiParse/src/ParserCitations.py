"""
ParserCitations module

Implemented from: src/WikiParse/src/ParserCitations.php

Usage:
    from src.WikiParse.src.ParserCitations import ParserCitations
"""

import re
from src.WikiParse.src.DataModel.Citation import Citation


class ParserCitations:
    """
    Parser for extracting citations from wikitext
    
    Matches: src/WikiParse/src/ParserCitations.php
    """
    
    def __init__(self, text: str):
        """
        Initialize ParserCitations
        
        Args:
            text: Text to parse
        """
        self.text = text
        self.citations = []
        self.parse()
    
    def parse(self):
        """Parse citations from text"""
        # Pattern for full ref tags
        pattern = r'<ref([^/>]*?)>(.+?)</ref>'
        matches = re.findall(pattern, self.text, re.IGNORECASE | re.DOTALL)
        full_matches = re.findall(r'<ref[^/>]*?>.+?</ref>', self.text, re.IGNORECASE | re.DOTALL)
        
        for i, (options, content) in enumerate(matches):
            cite_text = full_matches[i] if i < len(full_matches) else ""
            citation = Citation(content, options, cite_text)
            self.citations.append(citation)
    
    def getCitations(self) -> list:
        """
        Get parsed citations
        
        Returns:
            List of Citation objects
        """
        return self.citations
