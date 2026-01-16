"""
Category parser module

Implemented from: src/Parse/Category.php

Usage:
    from src.Parse.Category import get_categories_reg
"""

import re


def get_categories_reg(text: str) -> dict:
    """
    Get categories from text using regex
    
    Matches: src/Parse/Category.php - get_categories_reg()
    
    Args:
        text: Text to parse for categories
        
    Returns:
        Dictionary mapping category names to their full match strings
    """
    categories = {}
    
    # Pattern to match category links
    pattern = r'\[\[\s*Category\s*:([^\]\]]+?)\]\]'
    
    matches = re.findall(pattern, text, re.IGNORECASE | re.DOTALL)
    full_matches = re.findall(r'\[\[\s*Category\s*:[^\]\]]+?\]\]', text, re.IGNORECASE | re.DOTALL)
    
    if matches:
        for i, category_content in enumerate(matches):
            # Split on "|" to get just the category name
            parts = category_content.split('|')
            category_name = parts[0].strip()
            
            # Use full match as value
            if i < len(full_matches):
                categories[category_name] = full_matches[i]
    
    return categories
