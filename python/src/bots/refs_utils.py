"""
Reference utilities module

Usage:
    from src.bots.refs_utils import (
        str_ends_with, str_starts_with, 
        rm_str_from_start_and_end, remove_start_end_quotes
    )
"""


def str_ends_with(string: str, end_string: str) -> bool:
    """
    Check if string ends with end_string
    
    Args:
        string: String to check
        end_string: Ending to look for
        
    Returns:
        True if string ends with end_string
    """
    return string.endswith(end_string)


def str_starts_with(text: str, start: str) -> bool:
    """
    Check if text starts with start
    
    Args:
        text: Text to check
        start: Starting to look for
        
    Returns:
        True if text starts with start
    """
    return text.startswith(start)


def rm_str_from_start_and_end(text: str, find: str) -> str:
    """
    Remove string from start and end if present on both sides
    
    Args:
        text: Text to process
        find: String to remove
        
    Returns:
        Modified text
    """
    if not find:
        return text
    
    text = text.strip()
    
    if text.startswith(find) and text.endswith(find):
        text = text[len(find):-len(find)]
    
    return text.strip()


def remove_start_end_quotes(text: str) -> str:
    """
    Remove quotes from start and end of text
    
    Args:
        text: Text to process
        
    Returns:
        Text with quotes normalized
    """
    text = text.strip()
    
    text = rm_str_from_start_and_end(text, '"')
    text = rm_str_from_start_and_end(text, "'")
    
    # Choose quote type based on content
    quote = '"' if '"' not in text else "'"
    
    return f"{quote}{text}{quote}"
