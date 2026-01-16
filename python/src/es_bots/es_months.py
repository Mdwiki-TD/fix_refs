"""
Spanish months module

Implemented from: src/es_bots/es_months.php

Usage:
    from src.es_bots.es_months import fix_es_months_in_refs, fix_es_months_in_texts
"""

from src.test_bot import echo_debug
from src.Parse.Citations import getCitationsOld
from src.WikiParse.Template import getTemplates
from src.bots.months_new_value import make_date_new_val_es


def start_end(cite_temp: str) -> bool:
    """
    Check if citation starts with {{ and ends with }}
    
    Args:
        cite_temp: Citation text
        
    Returns:
        True if starts with {{ and ends with }}
    """
    return cite_temp.startswith("{{") and cite_temp.endswith("}}")


def fix_es_months_in_texts(temp_text: str) -> str:
    """
    Fix Spanish months in template text
    
    Matches: src/es_bots/es_months.php - fix_es_months_in_texts()
    
    Args:
        temp_text: Text to process
        
    Returns:
        Modified text with Spanish month translations
    """
    new_text = temp_text
    temp_text = temp_text.strip()
    
    temps = getTemplates(temp_text)
    
    for temp in temps:
        temp_old = temp.getOriginalText()
        
        params = temp.getParameters()
        
        for key, value in params.items():
            new_value = make_date_new_val_es(value)
            
            if new_value is not None and new_value.strip() != str(value).strip():
                temp.setParameter(key, new_value)
        
        temp_new = temp.toString()
        new_text = new_text.replace(temp_old, temp_new)
    
    return new_text


def fix_es_months_in_refs(text: str) -> str:
    """
    Fix Spanish months in references
    
    Matches: src/es_bots/es_months.php - fix_es_months_in_refs()
    
    Args:
        text: Text to process
        
    Returns:
        Modified text with Spanish month translations
    """
    echo_debug("\n fix_es_months_in_refs:\n")
    
    new_text = text
    citations = getCitationsOld(text)
    
    for citation in citations:
        cite_temp = citation.getContent()
        new_temp = fix_es_months_in_texts(cite_temp)
        new_text = new_text.replace(cite_temp, new_temp)
    
    return new_text
