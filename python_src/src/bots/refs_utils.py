"""
Reference utilities for string manipulation
"""


def str_ends_with(string: str, end_string: str) -> bool:
    """Check if string ends with a specific substring

    Args:
        string: String to check
        end_string: Substring to look for at the end

    Returns:
        True if string ends with end_string
    """
    return string.endswith(end_string)


def str_starts_with(text: str, start: str) -> bool:
    """Check if string starts with a specific substring

    Args:
        text: String to check
        start: Substring to look for at the start

    Returns:
        True if string starts with start
    """
    return text.startswith(start)


def rm_str_from_start_and_end(text: str, find: str) -> str:
    """Remove string from both start and end if present

    Args:
        text: Text to modify
        find: String to remove from start and end

    Returns:
        Modified text
    """
    if not find:
        return text

    text = text.strip()

    if str_starts_with(text, find) and str_ends_with(text, find):
        text = text[len(find):-len(find)]

    return text.strip()


def remove_start_end_quotes(text: str) -> str:
    """Normalize quotes around text

    Removes quotes from start and end, then wraps in double quotes

    Args:
        text: Text to normalize

    Returns:
        Text wrapped in double quotes
    """
    text = text.strip()
    text = rm_str_from_start_and_end(text, '"')
    text = rm_str_from_start_and_end(text, "'")

    quote = '"' if '"' not in text else "'"
    return f"{quote}{text}{quote}"
