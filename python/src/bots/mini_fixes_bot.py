"""
Mini fixes bot module

Usage:
    from src.bots.mini_fixes_bot import (
        mini_fixes, fix_sections_titles, mini_fixes_after_fixing
    )
"""

import re


def fix_sections_titles(text, lang):
    """
    Fix section titles for specific languages
    
    Args:
        text: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    to_replace = {
        "hr": {
            "Reference": "Izvori",
            "References": "Izvori",
        },
        "sw": {
            "Reference": "Marejeo",
            "References": "Marejeo",
            "Marejeleo": "Marejeo"
        },
        "ru": {
            "Reference": "Примечания",
            "References": "Примечания",
            "Ссылки": "Примечания"
        }
    }
    
    if lang not in to_replace:
        return text
    
    for key, value in to_replace[lang].items():
        # Quote the key to avoid regex special characters
        k = re.escape(key)
        
        # Regex pattern explanation:
        # (1) (={1,})   -> capture one or more '=' at the beginning
        # (2) \s*       -> optional spaces
        # (3) $k        -> the key to be replaced
        # (4) [^=]*     -> any extra text (e.g. numbers) except '='
        # (5) \s* \1    -> optional spaces and same '=' count at the end
        pattern = r'(=+)\s*' + k + r'\s*\1'
        
        # Replacement keeps the same '=' count but replaces the key
        replacement = r'\1 ' + value + r' \1'
        
        text = re.sub(pattern, replacement, text, flags=re.IGNORECASE)
    
    return text


def remove_space_before_ref_tags(text, lang):
    """
    Remove spaces before ref tags
    
    Args:
        text: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    for_langs = ["sw", "bn", "ar"]
    
    text = re.sub(r'\s*(\.|,|。|।)\s*<ref', r'\1<ref', text, flags=re.IGNORECASE)
    
    return text


def refs_tags_spaces(text):
    """
    Remove unnecessary spaces between reference tags
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    # </ref> <ref>
    text = re.sub(r'</ref>\s*<ref', '</ref><ref', text)
    
    # <ref name="A Costa"/><ref name=Gaia>
    text = re.sub(r'/>\s*<ref', '/><ref', text)
    
    # </ref><ref name=... | </ref><ref>
    text = text.replace('</ref> <ref', '</ref><ref')
    
    text = text.replace('> <ref', '><ref')
    
    return text


def fix_preffix(text, lang):
    """
    Fix language prefixes in links
    
    Args:
        text: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    # replace [[:{en}: by [[
    text = re.sub(r'\[\[:en:', '[[', text)
    # replace [[:{lang}: by [[
    text = re.sub(r'\[\[:' + re.escape(lang) + ':', '[[', text, flags=re.IGNORECASE)
    
    return text


def mini_fixes_after_fixing(text, lang):
    """
    Apply mini fixes after main processing
    
    Args:
        text: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    # remove empty lines
    text = re.sub(r'^\s*\n', '\n', text, flags=re.MULTILINE)
    
    text = fix_preffix(text, lang)
    
    return text


def mini_fixes(text, lang):
    """
    Apply mini fixes to text
    
    Args:
        text: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    text = refs_tags_spaces(text)
    
    text = fix_sections_titles(text, lang)
    
    text = remove_space_before_ref_tags(text, lang)
    
    return text
