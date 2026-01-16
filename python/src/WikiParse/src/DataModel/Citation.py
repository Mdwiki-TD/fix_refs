"""
Citation data model

Implemented from: src/WikiParse/src/DataModel/Citation.php

Usage:
    from src.WikiParse.src.DataModel.Citation import Citation
"""


class Citation:
    """
    Represents a citation in wikitext
    
    Matches: src/WikiParse/src/DataModel/Citation.php
    """
    
    def __init__(self, text: str = "", options: str = "", cite_text: str = ""):
        """
        Initialize Citation
        
        Args:
            text: Citation content
            options: Citation attributes
            cite_text: Original citation text
        """
        self.text = text
        self.options = options
        self.cite_text = cite_text
    
    def getOriginalText(self) -> str:
        """Get original citation text"""
        return self.cite_text
    
    def getTemplate(self) -> str:
        """Get template (content)"""
        return self.text
    
    def getContent(self) -> str:
        """Get content"""
        return self.text
    
    def getAttributes(self) -> str:
        """Get attributes"""
        return self.options
    
    def toString(self) -> str:
        """Convert to string"""
        opts = self.options.strip()
        if opts:
            return f"<ref {opts}>{self.text}</ref>"
        return f"<ref>{self.text}</ref>"
    
    def __str__(self) -> str:
        """String representation"""
        return self.toString()
