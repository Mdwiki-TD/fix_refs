"""
ExternalLink data model

Implemented from: src/WikiParse/src/DataModel/ExternalLink.php

Usage:
    from src.WikiParse.src.DataModel.ExternalLink import ExternalLink
"""


class ExternalLink:
    """
    Represents an external link in wikitext (e.g., [http://example.com Example])
    
    Matches: src/WikiParse/src/DataModel/ExternalLink.php
    """
    
    def __init__(self, url: str = "", text: str = "", originalText: str = ""):
        """
        Initialize ExternalLink
        
        Args:
            url: Link URL
            text: Link display text
            originalText: Original wikitext
        """
        self.url = url
        self.text = text
        self.originalText = originalText
    
    def getUrl(self) -> str:
        """Get URL"""
        return self.url
    
    def getText(self) -> str:
        """Get link text"""
        return self.text
    
    def getOriginalText(self) -> str:
        """Get original text"""
        return self.originalText
    
    def setUrl(self, url: str):
        """Set URL"""
        self.url = url
    
    def setText(self, text: str):
        """Set link text"""
        self.text = text
    
    def toString(self) -> str:
        """Convert to string"""
        if self.text:
            return f"[{self.url} {self.text}]"
        return f"[{self.url}]"
    
    def __str__(self) -> str:
        """String representation"""
        return self.toString()
