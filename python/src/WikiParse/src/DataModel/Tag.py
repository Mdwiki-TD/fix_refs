"""
Tag data model

Implemented from: src/WikiParse/src/DataModel/Tag.php

Usage:
    from src.WikiParse.src.DataModel.Tag import Tag
"""

from src.WikiParse.src.DataModel.Attribute import Attribute


class Tag:
    """
    Represents an HTML/XML tag in wikitext
    
    Matches: src/WikiParse/src/DataModel/Tag.php
    """
    
    def __init__(self, tagname: str = "", content: str = "", attributes: str = "", 
                 originalText: str = "", selfClosing: bool = False):
        """
        Initialize Tag
        
        Args:
            tagname: Tag name
            content: Tag content
            attributes: Tag attributes
            originalText: Original text
            selfClosing: Whether tag is self-closing
        """
        self.tagname = tagname
        self.content = content
        self.attributes = attributes
        self.originalText = originalText
        self.selfClosing = selfClosing
        self.attrs = Attribute(self.attributes)
    
    def getName(self) -> str:
        """Get tag name"""
        return self.tagname
    
    def getOriginalText(self) -> str:
        """Get original text"""
        return self.originalText
    
    def getContent(self) -> str:
        """Get tag content"""
        return self.content
    
    def getAttributes(self) -> str:
        """Get tag attributes string"""
        return self.attributes
    
    def setName(self, tagname: str):
        """Set tag name"""
        self.tagname = tagname
    
    def setContent(self, content: str):
        """Set tag content"""
        self.content = content
    
    def toString(self) -> str:
        """
        Convert tag to string
        
        Returns:
            Tag as string
        """
        if self.selfClosing:
            return f"<{self.tagname} {self.attrs.toString()} />"
        else:
            return f"<{self.tagname} {self.attrs.toString()}>{self.content}</{self.tagname}>"
    
    def __str__(self) -> str:
        """String representation"""
        return self.toString()
