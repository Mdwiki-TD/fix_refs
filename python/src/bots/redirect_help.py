"""
Redirect helper module

Usage:
    from src.bots.redirect_help import page_is_redirect
"""

import re


def page_is_redirect(title, text):
    """
    Check if page is a redirect
    
    Args:
        title: Page title
        text: Page text
        
    Returns:
        True if page is redirect
    """
    # Check for redirect patterns
    if re.match(r'^#(пренасочване|redirect)', text, re.IGNORECASE):
        return True
    
    return False
