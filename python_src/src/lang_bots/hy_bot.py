"""
Armenian-specific bot fixes
"""

import re
from typing import Optional


def str_ends_with(string: str, end_string: str) -> bool:
    """Check if string ends with substring"""
    return string.endswith(end_string)


def str_starts_with(text: str, start: str) -> bool:
    """Check if string starts with substring"""
    return text.startswith(start)


def remove_spaces_between_ref_and_punctuation(text: str, lang: Optional[str] = None) -> str:
    """Remove spaces between ref tags and punctuation

    Args:
        text: WikiText content
        lang: Language code

    Returns:
        Text with spaces removed
    """
    # Use superset of punctuation across supported languages
    dots = ".,。։।:"
    cls = re.escape(dots)

    # Keep punctuation right after <ref ... /> with no space
    # Pattern: <ref[^>]*/>\s*[punctuation]
    text = re.sub(r'(<ref[^>]*\/>)\s*([' + cls + r'])', r'\1\2', text)

    # Normalize endings: </ref> followed by any punctuation remains attached
    # Pattern: </ref>\s*[punctuation]
    text = re.sub(r'<\/ref>\s*([' + cls + r'])', r'</ref>\1', text)

    return text


def get_parts(newtext: str, charters: str) -> list:
    """Split text by double newlines and find parts ending with punctuation

    Args:
        newtext: Text to process
        charters: Punctuation characters to match

    Returns:
        List of [part, char] pairs
    """
    matches = newtext.split("\n\n")

    if len(matches) == 1:
        matches = newtext.split("\r\n\r\n")

    new_parts = []

    for part in matches:
        # Match </ref> or /> followed by punctuation at end
        pattern = r'(?:<\/ref>|\/>)\s*([' + re.escape(charters) + r'])\s*$'
        match = re.search(pattern, part, re.MULTILINE)
        if match:
            new_parts.append([part, match.group(1)])

    return new_parts


def remove_spaces_between_last_word_and_beginning_of_ref(newtext: str, lang: str) -> str:
    """Remove spaces around refs at the end of paragraphs/text

    This function processes each paragraph (split by double newlines) and removes
    spaces around refs that end the paragraph followed by punctuation.

    Args:
        newtext: Text to process
        lang: Language code

    Returns:
        Fixed text
    """
    # Define punctuation marks based on language
    if lang == "hy":
        dots = r".,。։:"
    else:
        dots = r".,।"

    # Split by double newlines to get paragraphs
    # If no double newlines, treat entire text as one paragraph
    paragraphs = newtext.split("\n\n")
    if len(paragraphs) == 1:
        paragraphs = newtext.split("\r\n\r\n")

    result_paragraphs = []
    for para in paragraphs:
        # Check if paragraph ends with refs followed by punctuation
        # Pattern: word + optional space + one or more refs + optional space + punctuation at end
        end_pattern = r'(\S)(\s+)((?:<ref[^>]*(?:/\s*>|>[^<]*</ref>))+)(\s*)([' + re.escape(dots) + r'])\s*$'
        match = re.search(end_pattern, para)

        if match:
            before_space = match.group(1)  # last non-space char before refs
            space_before = match.group(2)  # space(s) before refs
            refs = match.group(3)          # refs
            space_after = match.group(4)   # space(s) after refs (before punctuation)
            punct = match.group(5)         # punctuation

            # Remove spaces: before refs and between refs and punctuation
            new_end = before_space + refs + punct
            para = para[:match.start()] + new_end

        result_paragraphs.append(para)

    return "\n\n".join(result_paragraphs)

    return newtext


def hy_fixes(text: str) -> str:
    """Apply Armenian-specific fixes to text

    Args:
        text: WikiText content

    Returns:
        Fixed text
    """
    text = remove_spaces_between_last_word_and_beginning_of_ref(text, "hy")
    text = remove_spaces_between_ref_and_punctuation(text, "hy")
    return text
