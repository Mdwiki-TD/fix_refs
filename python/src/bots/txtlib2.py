"""
Txtlib2 module - Text utility library

Implemented from: src/bots/txtlib2.php

Contains various text processing utilities
"""

import re


def normalize_whitespace(text: str) -> str:
    """
    Normalize whitespace in text
    
    Args:
        text: Text to process
        
    Returns:
        Text with normalized whitespace
    """
    # Remove multiple spaces
    text = re.sub(r' +', ' ', text)
    # Remove multiple newlines
    text = re.sub(r'\n\n\n+', '\n\n', text)
    return text


def remove_html_comments(text: str) -> str:
    """
    Remove HTML comments from text
    
    Args:
        text: Text to process
        
    Returns:
        Text without HTML comments
    """
    return re.sub(r'<!--.*?-->', '', text, flags=re.DOTALL)


# Additional text utilities can be added as needed
