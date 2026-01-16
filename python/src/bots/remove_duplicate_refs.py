"""
Remove duplicate references module

Usage:
    from src.bots.remove_duplicate_refs import (
        remove_Duplicate_refs_With_attrs, fix_refs_names
    )
"""

import re
from src.bots.attrs_utils import get_attrs
from src.bots.refs_utils import remove_start_end_quotes
from src.Parse.Citations import getCitationsOld
from src.test_bot import echo_debug


def fix_refs_names(text: str) -> str:
    """
    Fix reference names by normalizing attributes
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    new_text = text
    
    citations = getCitationsOld(text)
    
    for citation in citations:
        cite_attrs = citation.getAttributes()
        cite_attrs = cite_attrs.strip() if cite_attrs else ""
        
        if_in = f"<ref {cite_attrs}>"
        
        if if_in not in new_text:
            continue
        
        attrs = get_attrs(cite_attrs)
        
        if not cite_attrs:
            continue
        
        new_cite_attrs = ""
        
        for key, value in attrs.items():
            value2 = remove_start_end_quotes(value)
            new_cite_attrs += f" {key}={value2}"
        
        new_cite_attrs = new_cite_attrs.strip()
        
        cite_newtext = f"<ref {new_cite_attrs}>"
        
        new_text = new_text.replace(if_in, cite_newtext)
    
    return new_text


def remove_Duplicate_refs_With_attrs(text: str) -> str:
    """
    Remove duplicate references with attributes
    
    Args:
        text: Text to process
        
    Returns:
        Modified text with duplicates removed
    """
    new_text = text
    
    refs_to_check = {}
    refs = {}
    
    citations = getCitationsOld(new_text)
    
    numb = 0
    
    for citation in citations:
        cite_fulltext = citation.getOriginalText()
        
        cite_attrs = citation.getAttributes()
        cite_attrs = cite_attrs.strip() if cite_attrs else ""
        
        if not cite_attrs:
            numb += 1
            name = f"autogen_{numb}"
            cite_attrs = f"name='{name}'"
        
        echo_debug(f"\n cite_attrs: (({cite_attrs}))")
        
        cite_newtext = f"<ref {cite_attrs} />"
        
        if cite_attrs in refs:
            new_text = new_text.replace(cite_fulltext, cite_newtext)
        else:
            refs_to_check[cite_newtext] = cite_fulltext
            refs[cite_attrs] = cite_newtext
    
    for key, value in refs_to_check.items():
        if value not in new_text:
            pattern = re.escape(key)
            new_text = re.sub(pattern, value, new_text, count=1)
    
    return new_text
