"""
ParserTags module

Implemented from: src/WikiParse/src/ParserTags.php

Usage:
    from src.WikiParse.src.ParserTags import ParserTags
"""

import re
from src.WikiParse.src.DataModel.Tag import Tag


class ParserTags:
    """
    Parser for extracting HTML/XML tags from wikitext
    
    Matches: src/WikiParse/src/ParserTags.php
    """
    
    def __init__(self, text: str, tagname: str = "ref"):
        """
        Initialize ParserTags
        
        Args:
            text: Text to parse
            tagname: Tag name to search for (default: "ref")
        """
        self.text = text
        self.tagname = tagname
        self.tags = []
        self.parse()
    
    def parse(self):
        """Parse tags from text"""
        # Pattern for self-closing tags
        self_closing_pattern = f'<{self.tagname}([^/>]*?)/\\s*>'
        self_closing_matches = re.findall(self_closing_pattern, self.text, re.IGNORECASE)
        self_closing_full = re.findall(f'<{self.tagname}[^/>]*?/\\s*>', self.text, re.IGNORECASE)
        
        for i, attrs in enumerate(self_closing_matches):
            original = self_closing_full[i] if i < len(self_closing_full) else ""
            tag = Tag(self.tagname, "", attrs, original, True)
            self.tags.append(tag)
        
        # Pattern for paired tags
        paired_pattern = f'<{self.tagname}([^/>]*?)>(.+?)</{self.tagname}>'
        paired_matches = re.findall(paired_pattern, self.text, re.IGNORECASE | re.DOTALL)
        paired_full = re.findall(f'<{self.tagname}[^/>]*?>.+?</{self.tagname}>', self.text, re.IGNORECASE | re.DOTALL)
        
        for i, (attrs, content) in enumerate(paired_matches):
            original = paired_full[i] if i < len(paired_full) else ""
            tag = Tag(self.tagname, content, attrs, original, False)
            self.tags.append(tag)
    
    def getTags(self) -> list:
        """
        Get parsed tags
        
        Returns:
            List of Tag objects
        """
        return self.tags
