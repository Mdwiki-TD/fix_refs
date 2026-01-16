"""
Citations parser module

Usage:
    from src.Parse.Citations import getCitationsOld
"""

import re


class CitationOld:
    """Citation object representing a single reference"""
    
    def __init__(self, text: str, options: str = "", cite_text: str = ""):
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
    
    def getContent(self) -> str:
        """Get citation content"""
        return self.text
    
    def getAttributes(self) -> str:
        """Get citation attributes"""
        return self.options
    
    def toString(self) -> str:
        """Convert citation to string"""
        return f"<ref {self.options.strip()}>{self.text}</ref>"


class ParserCitationsOld:
    """Parser for extracting citations from wikitext"""
    
    def __init__(self, text: str):
        """
        Initialize parser
        
        Args:
            text: Wikitext to parse
        """
        self.text = text
        self.citations = []
        self.parse()
    
    def find_sub_citations(self, string):
        """
        Find citation patterns in string
        
        Args:
            string: String to search
            
        Returns:
            Match object with groups
        """
        pattern = r"<ref([^\/>]*?)>(.+?)<\/ref>"
        matches = re.findall(pattern, string, re.IGNORECASE | re.DOTALL)
        
        # Restructure to match PHP's preg_match_all structure
        if matches:
            options = [m[0] for m in matches]
            content = [m[1] for m in matches]
            # Find full matches
            full_matches = re.findall(r"<ref[^\/>]*?>.*?<\/ref>", string, re.IGNORECASE | re.DOTALL)
            return [full_matches, options, content]
        return [[], [], []]
    
    def parse(self):
        """Parse text and extract citations"""
        text_citations = self.find_sub_citations(self.text)
        self.citations = []
        
        if len(text_citations[1]) > 0:
            for key in range(len(text_citations[1])):
                citation = CitationOld(
                    text_citations[2][key],
                    text_citations[1][key],
                    text_citations[0][key]
                )
                self.citations.append(citation)
    
    def getCitations(self) -> list:
        """
        Get parsed citations
        
        Returns:
            List of CitationOld objects
        """
        return self.citations


def getCitationsOld(text):
    """
    Get citations from text
    
    Args:
        text: Text to parse
        
    Returns:
        List of CitationOld objects
    """
    parser = ParserCitationsOld(text)
    citations = parser.getCitations()
    
    return citations
