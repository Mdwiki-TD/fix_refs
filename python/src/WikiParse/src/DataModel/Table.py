"""
Table data model

Implemented from: src/WikiParse/src/DataModel/Table.php

Usage:
    from src.WikiParse.src.DataModel.Table import Table
"""


class Table:
    """
    Represents a wiki table
    
    Matches: src/WikiParse/src/DataModel/Table.php
    """
    
    def __init__(self, content: str = "", originalText: str = ""):
        """
        Initialize Table
        
        Args:
            content: Table content
            originalText: Original wikitext
        """
        self.content = content
        self.originalText = originalText
    
    def getContent(self) -> str:
        """Get table content"""
        return self.content
    
    def getOriginalText(self) -> str:
        """Get original text"""
        return self.originalText
    
    def setContent(self, content: str):
        """Set table content"""
        self.content = content
    
    def toString(self) -> str:
        """Convert to string"""
        return self.content
    
    def __str__(self) -> str:
        """String representation"""
        return self.toString()
