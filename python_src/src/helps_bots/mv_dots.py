"""
Move dots utilities for references
"""

import re
from typing import Optional


def move_dots_before_refs(text: str, lang: Optional[str] = None) -> str:
    """Move dots before references to after references

    Args:
        text: Text containing references and punctuation
        lang: Language code

    Returns:
        Text with dots moved
    """
    dot = r"\.,。।"

    if lang == "hy":
        dot = r"\.,。։।"

    # Pattern to match dots before refs
    regline = r"((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)"
    pattern = rf"([{dot}]+)\s*{regline}"
    replacement = r"\2\1"

    text = re.sub(pattern, replacement, text, flags=re.MULTILINE)

    return text


def move_dots_after_refs(text: str, lang: Optional[str] = None) -> str:
    """Move dots after references (keep punctuation after closing tags)

    Args:
        text: Text containing references and punctuation
        lang: Language code

    Returns:
        Text with dots positioned after refs
    """
    dot = r"\.,。।"

    if lang == "hy":
        dot = r"\.,。։:"

    regline = r"((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)"
    pattern = rf"({regline}+)([{dot}]+)"
    replacement = r"\2\1"

    text = re.sub(pattern, replacement, text, flags=re.MULTILINE)

    return text
