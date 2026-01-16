"""
English language parameter module

Usage:
    from src.helps_bots.en_lang_param import add_lang_en_to_refs
"""

import re
from src.test_bot import echo_debug
from src.Parse.Citations import getCitationsOld
from src.WikiParse.Template import getTemplates


def add_lang_en(text):
    """
    Add language=en parameter to references
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    # Match references
    REFS = r"(?is)(?P<pap><ref[^>\/]*>)(?P<ref>.*?</ref>)"
    
    matches = list(re.finditer(REFS, text))
    for match in matches:
        pap = match.group('pap')
        ref = match.group('ref')
        
        if not ref.strip():
            continue
        
        # Check if language parameter already exists
        if re.sub(r'\|\s*language\s*=\s*\w+', '', ref) != ref:
            continue
        
        # Try to add language parameter
        ref2 = re.sub(r'(\|\s*language\s*=\s*)(\|\}\})', r'\1en\2', ref)
        
        if ref2 == ref:
            ref2 = ref.replace("}}</ref>", "|language=en}}</ref>")
        
        if ref2 != ref:
            text = text.replace(pap + ref, pap + ref2)
    
    return text


def add_lang_en_new(temp_text):
    """
    Add language=en to templates in text
    
    Args:
        temp_text: Text to process
        
    Returns:
        Modified text
    """
    new_text = temp_text
    temp_text = temp_text.strip()
    
    temps = getTemplates(temp_text)
    
    for temp in temps:
        temp_old = temp.getOriginalText()
        
        echo_debug(f"temp_old:({temp_old})\n")
        
        params = temp.parameters
        
        language = params.get("language", "")
        
        if language == "":
            params.set("language", "en")
            temp_new = temp.toString()
            new_text = new_text.replace(temp_old, temp_new)
    
    return new_text


def add_lang_en_to_refs(text):
    """
    Add language=en to references
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    return add_lang_en(text)
