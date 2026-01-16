"""
ExternalLink data model

PLACEHOLDER - This module will be implemented to match the functionality of:
src/WikiParse/src/DataModel/ExternalLink.php

Usage:
    from src.WikiParse.src.DataModel.ExternalLink import ExternalLink
"""


class ExternalLink:
    """
    Represents an external link in wikitext
    
    This is a placeholder implementation. The full implementation will match:
    src/WikiParse/src/DataModel/ExternalLink.php
    """
    
    def __init__(self, url: str = "", text: str = ""):
        """
        Initialize ExternalLink
        
        Args:
            url: Link URL
            text: Link text
        """
        # TODO: Implement full ExternalLink class matching PHP version
        self.url = url
        self.text = text
    
    def getUrl(self) -> str:
        """Get URL"""
        return self.url
    
    def getText(self) -> str:
        """Get link text"""
        return self.text
