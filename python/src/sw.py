"""
Swahili fixes module

Usage:
    from src.sw import sw_fixes
"""

import re


def sw_fixes(text):
    """
    Apply Swahili-specific fixes
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    # find == Marejeleo == replace by == Marejeo ==
    text = re.sub(r'(=+)\s*Marejeleo\s*(\1)', r'\1 Marejeo \1', text, flags=re.IGNORECASE)
    
    return text
