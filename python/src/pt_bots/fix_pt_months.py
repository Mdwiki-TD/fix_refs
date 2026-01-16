"""
Portuguese month fixes module

Usage:
    from src.pt_bots.fix_pt_months import pt_fixes
"""

import re
from src.test_bot import echo_test, echo_debug
from src.Parse.Citations import getCitationsOld
from src.WikiParse.Template import getTemplates
from src.bots.months_new_value import make_date_new_val_pt


def start_end(cite_temp):
    """
    Check if text starts with {{ and ends with }}
    
    Args:
        cite_temp: Text to check
        
    Returns:
        True if starts with {{ and ends with }}
    """
    return cite_temp.startswith("{{") and cite_temp.endswith("}}")


def rm_ref_spaces(newtext):
    """
    Remove spaces around punctuation before refs
    
    Args:
        newtext: Text to process
        
    Returns:
        Modified text
    """
    dot = r"(\.|,|。|।)"
    regline = r"((?:\s*<ref[\s\S]+?(?:</ref|/)>)+)"
    pattern = r"\s*" + dot + r"\s*" + regline
    replacement = r"\1\2"
    newtext = re.sub(pattern, replacement, newtext, flags=re.MULTILINE)
    return newtext


def fix_pt_months_in_texts(temp_text):
    """
    Fix Portuguese months in template text
    
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
        
        params = temp.getParameters()
        
        for key, value in params.items():
            new_value = make_date_new_val_pt(value)
            
            if new_value is not None and new_value.strip() != str(value).strip():
                temp.setParameter(key, new_value)
        
        temp_new = temp.toString()
        new_text = new_text.replace(temp_old, temp_new)
    
    return new_text


def fix_pt_months_in_refs(text):
    """
    Fix Portuguese months in references
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    echo_debug("\n fix_pt_months_in_refs:\n")
    
    new_text = text
    
    citations = getCitationsOld(text)
    
    for citation in citations:
        cite_temp = citation.getContent()
        
        new_temp = fix_pt_months_in_texts(cite_temp)
        
        new_text = new_text.replace(cite_temp, new_temp)
    
    return new_text


def pt_fixes(text):
    """
    Apply Portuguese-specific fixes
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    text = fix_pt_months_in_refs(text)
    text = rm_ref_spaces(text)
    return text
