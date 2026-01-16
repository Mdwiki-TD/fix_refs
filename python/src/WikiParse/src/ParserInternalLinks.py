"""
ParserInternalLinks module

Implemented from: src/WikiParse/src/ParserInternalLinks.php

Usage:
    from src.WikiParse.src.ParserInternalLinks import ParserInternalLinks
"""

import re
from src.WikiParse.src.DataModel.InternalLink import InternalLink


class ParserInternalLinks:
    """
    Parser for extracting internal links from wikitext
    
    Matches: src/WikiParse/src/ParserInternalLinks.php
    """
    
    def __init__(self, text: str):
        """
        Initialize ParserInternalLinks
        
        Args:
            text: Text to parse
        """
        self.text = text
        self.links = []
        self.parse()
    
    def parse(self):
        """Parse internal links from text"""
        # Pattern for internal links [[Page|Text]] or [[Page]]
        pattern = r'\[\[([^\]|]+)(?:\|([^\]]+))?\]\]'
        matches = re.findall(pattern, self.text)
        full_matches = re.findall(r'\[\[[^\]|]+(?:\|[^\]]+)?\]\]', self.text)
        
        for i, match in enumerate(matches):
            target = match[0].strip() if match else ""
            text = match[1].strip() if len(match) > 1 and match[1] else target
            original = full_matches[i] if i < len(full_matches) else ""
            
            # Skip category and file links
            if not target.lower().startswith(('category:', 'file:', 'image:')):
                link = InternalLink(target, text, original)
                self.links.append(link)
    
    def getLinks(self) -> list:
        """
        Get parsed internal links
        
        Returns:
            List of InternalLink objects
        """
        return self.links
