"""
Infobox expansion module

Usage:
    from src.infoboxes.infobox import Expend_Infobox
"""

import re
from src.WikiParse.Template import getTemplates


def fix_title_bold(text, title):
    """
    Fix title bold formatting
    
    Args:
        text: Text to process
        title: Page title
        
    Returns:
        Modified text
    """
    try:
        title2 = re.escape(title)
    except:
        title2 = title
    
    text = re.sub(rf"\}}\s*('''{title2}''')", r"}\n\n\1", text)
    
    return text


def make_section_0(title, newtext):
    """
    Extract section 0 (lead section)
    
    Args:
        title: Page title
        newtext: Text to process
        
    Returns:
        Section 0 text
    """
    section_0 = ""
    
    if "==" in newtext:
        section_0 = newtext.split("==")[0]
    else:
        tagg = f"'''{title}'''1"
        if tagg in newtext:
            section_0 = newtext.split(tagg)[0]
        else:
            section_0 = newtext
    
    return section_0


def Expend_Infobox(text, title, section_0):
    """
    Expand infobox in text
    
    Args:
        text: Text to process
        title: Page title
        section_0: Section 0 text (can be empty)
        
    Returns:
        Modified text
    """
    newtext = text
    
    if not section_0:
        section_0 = make_section_0(title, newtext)
    
    newtext = fix_title_bold(newtext, title)
    
    # Basic implementation - could be expanded with template processing
    return newtext
