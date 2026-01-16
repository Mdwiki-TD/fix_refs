"""
ParserExternalLinks module

Implemented from: src/WikiParse/src/ParserExternalLinks.php

Usage:
    from src.WikiParse.src.ParserExternalLinks import ParserExternalLinks
"""

import re
from src.WikiParse.src.DataModel.ExternalLink import ExternalLink


class ParserExternalLinks:
    """
    Parser for extracting external links from wikitext
    
    Matches: src/WikiParse/src/ParserExternalLinks.php
    """
    
    def __init__(self, text: str):
        """
        Initialize ParserExternalLinks
        
        Args:
            text: Text to parse
        """
        self.text = text
        self.links = []
        self.parse()
    
    def parse(self):
        """Parse external links from text"""
        # Pattern for external links [http://url text]
        pattern = r'\[([^\s\]]+)(?:\s+([^\]]+))?\]'
        matches = re.findall(pattern, self.text)
        full_matches = re.findall(r'\[[^\s\]]+(?:\s+[^\]]+)?\]', self.text)
        
        for i, match in enumerate(matches):
            url = match[0] if match else ""
            text = match[1] if len(match) > 1 else ""
            original = full_matches[i] if i < len(full_matches) else ""
            
            # Only process if it looks like a URL
            if url.startswith(('http://', 'https://', 'ftp://', '//')):
                link = ExternalLink(url, text, original)
                self.links.append(link)
    
    def getLinks(self) -> list:
        """
        Get parsed external links
        
        Returns:
            List of ExternalLink objects
        """
        return self.links
