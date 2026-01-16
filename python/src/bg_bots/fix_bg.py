"""
Bulgarian fixes module

Usage:
    from src.bg_bots.fix_bg import bg_fixes
"""

import re


def bg_section(text, sourcetitle, mdwiki_revid):
    """
    Add Bulgarian translation section
    
    Args:
        text: Text to process
        sourcetitle: Source title
        mdwiki_revid: MDWiki revision ID
        
    Returns:
        Modified text
    """
    # Check if translation template already exists
    if re.search(r'\{\{\s*Превод\s*от\s*\|', text, re.IGNORECASE):
        return text
    
    temp = f"{{{{Превод от|mdwiki|{sourcetitle}|{mdwiki_revid}}}}}\n"
    
    # Add before first category or at end
    match = re.search(r'\[\[(Категория|Category):', text, re.IGNORECASE)
    if match:
        pos = match.start()
        text = text[:pos] + temp + text[pos:]
    else:
        text += "\n" + temp
    
    return text


def bg_fixes(text, sourcetitle, mdwiki_revid):
    """
    Apply Bulgarian-specific fixes
    
    Args:
        text: Text to process
        sourcetitle: Source title
        mdwiki_revid: MDWiki revision ID
        
    Returns:
        Modified text
    """
    text = bg_section(text, sourcetitle, mdwiki_revid)
    
    # Remove [[Category:Translated from MDWiki]]
    text = re.sub(
        r'\[\[\s*(Категория|Category)\s*:\s*Translated from MDWiki\s*\]\]',
        '',
        text,
        flags=re.IGNORECASE
    )
    
    return text
