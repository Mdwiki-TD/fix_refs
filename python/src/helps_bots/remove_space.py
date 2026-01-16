"""
Remove space module

Usage:
    from src.helps_bots.remove_space import (
        remove_spaces_between_last_word_and_beginning_of_ref,
        remove_spaces_between_ref_and_punctuation
    )
"""

import re
from src.test_bot import echo_debug


def match_it(text, charters):
    """
    Match punctuation at end of text after ref tags
    
    Args:
        text: Text to check
        charters: Punctuation characters to look for
        
    Returns:
        Matched punctuation or None
    """
    pattern = r'(<\/ref>|/>)\s*([' + re.escape(charters) + r']\s*)$'
    match = re.search(pattern, text)
    if match:
        return match.group(2)
    return None


def get_parts(newtext, charters):
    """
    Get parts of text that end with refs and punctuation
    
    Args:
        newtext: Text to process
        charters: Punctuation characters
        
    Returns:
        List of (part, charter) tuples
    """
    matches = newtext.split("\n\n")
    
    if len(matches) == 1:
        matches = newtext.split("\r\n\r\n")
    
    echo_debug(f"count(matches)={len(matches)}\n")
    
    new_parts = []
    
    for p in matches:
        chart = match_it(p, charters)
        if chart:
            new_parts.append((p, chart))
    
    echo_debug(f"count(new_parts)={len(new_parts)}\n")
    
    return new_parts


def remove_spaces_between_last_word_and_beginning_of_ref(newtext, lang):
    """
    Remove spaces between last word and beginning of ref
    
    Args:
        newtext: Text to process
        lang: Language code
        
    Returns:
        Modified text
    """
    # Define punctuation marks
    dot = r".,。।"
    
    if lang == "hy":
        dot = r".,。।։:"
    
    parts = get_parts(newtext, dot)
    
    for part, charter in parts:
        echo_debug(f"charter={charter}\n")
        
        regline = r'((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)'
        
        last_ref_matches = re.findall(regline, part, re.DOTALL)
        
        echo_debug(f"count(last_ref)={len(last_ref_matches)}\n")
        
        if last_ref_matches:
            ref_text = last_ref_matches[-1]
            end_part = ref_text + charter
            
            if part.endswith(end_part):
                echo_debug("endswith\n")
                
                first_part_clean_end = part[:-len(end_part)]
                first_part_clean_end = first_part_clean_end.rstrip()
                
                new_part = first_part_clean_end + ref_text.strip() + charter
                
                newtext = newtext.replace(part, new_part)
    
    return newtext


def remove_spaces_between_ref_and_punctuation(text, lang=None):
    """
    Remove spaces between ref and punctuation
    
    Args:
        text: Text to process
        lang: Language code (optional)
        
    Returns:
        Modified text
    """
    # Use a superset of punctuation across supported languages
    dots = ".,。։।:"
    cls = re.escape(dots)
    
    # </ref> : to </ref>:
    # Keep punctuation right after <ref ... /> with no space
    text = re.sub(r'(<ref[^>]*/>)\s*([' + cls + '])', r'\1\2', text)
    
    # Normalize endings: </ref> followed by any punctuation remains attached
    text = re.sub(r'</ref>\s*([' + cls + '])', r'</ref>\1', text)
    
    return text
