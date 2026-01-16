"""
Citations regex parser module

Implemented from: src/Parse/Citations_reg.php

Usage:
    from src.Parse.Citations_reg import get_name, get_Reg_Citations, get_full_refs, getShortCitations
"""

import re


def get_name(options: str) -> str:
    """
    Get the name attribute from citation options
    
    Matches: src/Parse/Citations_reg.php - get_name()
    
    Args:
        options: The citation options string to extract name from
        
    Returns:
        The extracted name or empty string if not found
    """
    if not options or not options.strip():
        return ""
    
    # Pattern to extract name attribute
    pattern = r'name\s*=\s*["\']?([^>"\'\s]*)["\']?\s*'
    match = re.search(pattern, options, re.IGNORECASE)
    
    if not match:
        return ""
    
    name = match.group(1).strip()
    return name


def get_Reg_Citations(text: str) -> list:
    """
    Get citations using regex
    
    Matches: src/Parse/Citations_reg.php - get_Reg_Citations()
    
    Args:
        text: Text to parse
        
    Returns:
        List of citation dictionaries with keys: content, tag, name, options
    """
    pattern = r'<ref([^/>]*?)>(.+?)</ref>'
    matches = re.findall(pattern, text, re.IGNORECASE | re.DOTALL)
    full_matches = re.findall(r'<ref[^/>]*?>.+?</ref>', text, re.IGNORECASE | re.DOTALL)
    
    citations = []
    
    for i, (citation_options, content) in enumerate(matches):
        ref_tag = full_matches[i] if i < len(full_matches) else ""
        options = citation_options
        citation = {
            "content": content,
            "tag": ref_tag,
            "name": get_name(options),
            "options": options
        }
        citations.append(citation)
    
    return citations


def get_full_refs(text: str) -> dict:
    """
    Get full references from text
    
    Matches: src/Parse/Citations_reg.php - get_full_refs()
    
    Args:
        text: Text to parse
        
    Returns:
        Dictionary mapping reference names to their full tags
    """
    full = {}
    citations = get_Reg_Citations(text)
    
    for cite in citations:
        name = cite["name"]
        ref = cite["tag"]
        full[name] = ref
    
    return full


def getShortCitations(text: str) -> list:
    """
    Get short citations (self-closing ref tags) from text
    
    Matches: src/Parse/Citations_reg.php - getShortCitations()
    
    Args:
        text: Text to parse
        
    Returns:
        List of short citation dictionaries
    """
    pattern = r'<ref ([^/>]*?)/\s*>'
    matches = re.findall(pattern, text, re.IGNORECASE | re.DOTALL)
    full_matches = re.findall(r'<ref [^/>]*?/\s*>', text, re.IGNORECASE | re.DOTALL)
    
    citations = []
    
    for i, citation_options in enumerate(matches):
        ref_tag = full_matches[i] if i < len(full_matches) else ""
        options = citation_options
        citation = {
            "content": "",
            "tag": ref_tag,
            "name": get_name(options),
            "options": options
        }
        citations.append(citation)
    
    return citations
