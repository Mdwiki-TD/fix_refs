"""
Attribute utilities module

Usage:
    from src.bots.attrs_utils import parseAttributes, get_attrs
"""

import re


def parseAttributes(text: str) -> dict:
    """
    Parse attributes from text
    
    Args:
        text: Attribute text to parse
        
    Returns:
        Dictionary of attributes
    """
    text = f"<ref {text}>"
    
    attrfind_tolerant = r'''
            ((?<=[\'"\\s\/])[^\s\/>][^\s\/=>]*)             # Attribute name
            (\s*=+\s*                                      # Equals sign(s)
            (
                \'[^\']*\'                                 # Value in single quotes
                |"[^"]*"                                   # Value in double quotes
                |(?![\'"])[^>\s]*                          # Unquoted value
            ))?
            (?:\s|\/(?!>))*                                # Trailing space or slash not followed by >
        '''
    
    attributes_array = {}
    
    matches = re.finditer(attrfind_tolerant, text, re.VERBOSE | re.UNICODE)
    for match in matches:
        attr_name = match.group(1).lower()
        attr_value = match.group(3) if match.group(3) else ""
        attributes_array[attr_name] = attr_value
    
    return attributes_array


def get_attrs(text):
    """
    Get attributes from text
    
    Args:
        text: Text containing attributes
        
    Returns:
        Dictionary of attributes
    """
    text = f"<ref {text}>"
    attrfind_tolerant = r'((?<=[\'"\\s\/])[^\s\/>][^\s\/=>]*)(\s*=+\s*(\'[^\']*\'|"[^"]*"|(?![\'"])[^>\s]*))?(?:\s|\/(?!>))*'
    attrs = {}
    
    matches = re.finditer(attrfind_tolerant, text, re.UNICODE)
    for match in matches:
        attr_name = match.group(1).lower()
        attr_value = match.group(3) if match.group(3) else ""
        attrs[attr_name] = attr_value
    
    return attrs
