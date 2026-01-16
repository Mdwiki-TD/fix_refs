"""
Attribute data model

Implemented from: src/WikiParse/src/DataModel/Attribute.php

Usage:
    from src.WikiParse.src.DataModel.Attribute import Attribute
"""

import re


class Attribute:
    """
    Represents attributes in wikitext tags
    
    Matches: src/WikiParse/src/DataModel/Attribute.php
    """
    
    def __init__(self, content: str = ""):
        """
        Initialize Attribute
        
        Args:
            content: Attribute content string
        """
        self.content = content
        self.attributes_array = {}
        self.parseAttributes()
    
    def setContent(self, content: str):
        """Set content and reparse attributes"""
        self.content = content
        self.parseAttributes()
    
    def parseAttributes(self):
        """Parse attributes from content string"""
        text = f"<ref {self.content}>"
        
        # Regex pattern for parsing attributes
        pattern = r'''
            ((?<=[\'"\s\/])[^\s\/>][^\s\/=>]*)         # Attribute name
            (\s*=+\s*                                  # Equals sign(s)
            (
                \'[^\']*\'                             # Value in single quotes
                |"[^"]*"                               # Value in double quotes
                |(?![\'"])[^>\s]*                      # Unquoted value
            ))?
            (?:\s|\/(?!>))*                            # Trailing space or slash not followed by >
        '''
        
        self.attributes_array = {}
        
        matches = re.findall(pattern, text, re.VERBOSE)
        for match in matches:
            attr_name = match[0].lower()
            attr_value = match[2] if len(match) > 2 else ""
            self.attributes_array[attr_name] = attr_value
    
    def getAttributesArray(self) -> dict:
        """Get parsed attributes as dictionary"""
        return self.attributes_array
    
    def has(self, key: str) -> bool:
        """Check if attribute exists"""
        return key in self.attributes_array
    
    def get(self, key: str, default: str = "") -> str:
        """Get attribute value"""
        return self.attributes_array.get(key, default)
    
    def set(self, key: str, value: str):
        """Set attribute value"""
        self.attributes_array[key] = value
    
    def delete(self, key: str):
        """Delete attribute"""
        if key in self.attributes_array:
            del self.attributes_array[key]
    
    def toString(self, addQuotes: bool = False) -> str:
        """
        Convert attributes to string
        
        Args:
            addQuotes: Whether to add quotes to values
            
        Returns:
            Attributes as string
        """
        result = []
        
        for key, value in self.attributes_array.items():
            if not value:
                result.append(key)
                continue
            
            if addQuotes:
                # Remove existing quotes
                if len(value) > 1:
                    q = value[0] if value else ''
                    if (q == '"' or q == "'") and value.endswith(q):
                        value = value[1:-1]
                value = f'"{value}"'
            
            result.append(f'{key}={value}')
        
        return ' '.join(result)
    
    def __str__(self) -> str:
        """String representation"""
        return self.toString()
