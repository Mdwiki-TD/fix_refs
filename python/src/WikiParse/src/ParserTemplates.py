"""
ParserTemplates class

Parse a text and extract all Templates.
"""

import re
from src.WikiParse.src.DataModel.Template import Template
from src.WikiParse.src.ParserTemplate import ParserTemplate


class ParserTemplates:
    """
    Parse a text and extract all Templates.
    """
    
    def __init__(self, text):
        """
        Initialize ParserTemplates
        
        Args:
            text: The text to parse
        """
        self.text = text
        self.templates = []
        self.maxDepth = 10
        self.parse()
    
    def find_sub_templates(self, string):
        """
        Find all templates in the given string
        
        The regex pattern is a recursive pattern that matches templates
        with any level of nesting.
        
        Args:
            string: The string to search for templates
            
        Returns:
            List of matches
        """
        pattern = r'\{{2}(((?>[^\{\}]+)|(?R))*)\}{2}'
        # Python doesn't support recursive regex like PHP, so use a simpler approach
        pattern = r'\{\{((?:[^{}]|(?:\{[^{]|\}[^}]))*)\}\}'
        
        matches = []
        depth = 0
        start = -1
        
        i = 0
        while i < len(string):
            if i < len(string) - 1 and string[i:i+2] == '{{':
                if depth == 0:
                    start = i
                depth += 1
                i += 2
            elif i < len(string) - 1 and string[i:i+2] == '}}':
                depth -= 1
                if depth == 0 and start != -1:
                    full_match = string[start:i+2]
                    inner_match = string[start+2:i]
                    matches.append([full_match, inner_match])
                    start = -1
                i += 2
            else:
                i += 1
        
        return matches
    
    def parse(self):
        """
        Parse a text and extract all Templates
        
        This function parses the text and extracts all templates.
        Then it calls itself recursively for each template found.
        """
        stack = [{'text': self.text, 'depth': 0}]
        
        while stack:
            current = stack.pop()
            currentText = current['text']
            currentDepth = current['depth']
            
            if currentDepth >= self.maxDepth:
                continue
            
            text_templates = self.find_sub_templates(currentText)
            
            for match in text_templates:
                template_full = match[0]  # Including brackets
                template_inner = match[1]  # Content only
                
                parser = ParserTemplate(template_full)
                self.templates.append(parser.getTemplate())
                
                # Add the inner template to the stack for later parsing
                stack.append({
                    'text': template_inner,
                    'depth': currentDepth + 1
                })
    
    def getTemplates(self):
        """
        Get the templates found in the text
        
        Returns:
            List of Template objects
        """
        return self.templates
