"""
InternalLink data model

Implemented from: src/WikiParse/src/DataModel/InternalLink.php

Usage:
    from src.WikiParse.src.DataModel.InternalLink import InternalLink
"""


class InternalLink:
    """
    Represents an internal wiki link (e.g., [[Page|Display text]])
    
    Matches: src/WikiParse/src/DataModel/InternalLink.php
    """
    
    def __init__(self, target: str = "", text: str = "", originalText: str = ""):
        """
        Initialize InternalLink
        
        Args:
            target: Link target page
            text: Link display text  
            originalText: Original wikitext
        """
        self.target = target
        self.text = text
        self.originalText = originalText
    
    def getTarget(self) -> str:
        """Get link target"""
        return self.target
    
    def getText(self) -> str:
        """Get link text"""
        return self.text
    
    def getOriginalText(self) -> str:
        """Get original text"""
        return self.originalText
    
    def setTarget(self, target: str):
        """Set link target"""
        self.target = target
    
    def setText(self, text: str):
        """Set link text"""
        self.text = text
    
    def toString(self) -> str:
        """Convert to string"""
        if self.text and self.text != self.target:
            return f"[[{self.target}|{self.text}]]"
        return f"[[{self.target}]]"
    
    def __str__(self) -> str:
        """String representation"""
        return self.toString()
