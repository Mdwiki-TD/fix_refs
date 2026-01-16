"""
Move dots module

Usage:
    from src.helps_bots.mv_dots import move_dots_after_refs
"""

import re


def move_dots_before_refs(text: str, lang: str) -> str:
    """
    Move dots before references
    
    Args:
        text: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    # Define punctuation marks based on language
    punctuation = r'\.,،'
    
    # Pattern to match references followed by punctuation
    pattern = r'((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)([' + punctuation + ']+)'
    
    def replace_func(match):
        # Handle multiple dots by replacing with a single dot
        punct = match.group(2)
        if punct.count('.') > 1:
            punct = '.'
        return punct + ' ' + match.group(1).strip()
    
    result = re.sub(pattern, replace_func, text, flags=re.DOTALL)
    
    return result


def move_dots_after_refs(newtext, lang):
    """
    Move dots after references
    
    Args:
        newtext: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    dot = r"\.,。।"
    
    if lang == "hy":
        dot = r"\.,。։।:"
    
    regline = r"((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)"
    
    pattern = r"([" + dot + r"]+)\s*" + regline
    replacement = r"\2\1"
    
    newtext = re.sub(pattern, replacement, newtext, flags=re.MULTILINE)
    
    return newtext
