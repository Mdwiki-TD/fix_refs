"""
Template module

Usage:
    from src.WikiParse.Template import getTemplate, getTemplates
"""

from src.WikiParse.src.ParserTemplate import ParserTemplate
from src.WikiParse.src.ParserTemplates import ParserTemplates


def getTemplate(text):
    """
    Get a single template from text
    
    Args:
        text: Text containing template
        
    Returns:
        Template object
    """
    parser = ParserTemplate(text)
    temp = parser.getTemplate()
    return temp


def getTemplates(text):
    """
    Get all templates from text
    
    Args:
        text: Text containing templates
        
    Returns:
        List of Template objects
    """
    if not text:
        return []
    
    parser = ParserTemplates(text)
    temps = parser.getTemplates()
    return temps
