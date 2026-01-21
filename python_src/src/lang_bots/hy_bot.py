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
    """Remove spaces between last word and beginning of ref

    Args:
        newtext: Text to process
        lang: Language code

    Returns:
        Fixed text
    """
    # Define punctuation marks
    dot = r"\.,。।"

    if lang == "hy":
        dot = r"\.,。։:"

    parts = get_parts(newtext, dot)

    for pair in parts:
        if len(pair) < 2:
            continue

        part, char_ter = pair[0], pair[1]

        # Find all ref tags in the part
        regline = r'((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)'
        last_ref_matches = list(re.finditer(regline, part, re.DOTALL))

        if last_ref_matches:
            # Get the last reference tag
            ref_text = last_ref_matches[-1].group(0)
            end_part = ref_text + char_ter

            if str_ends_with(part, end_part):
                # Remove the ending and clean up
                first_part_clean_end = part[0:-len(end_part)]
                first_part_clean_end = first_part_clean_end.rstrip()

                # Reconstruct with ref text and punctuation attached
                new_part = first_part_clean_end + ref_text.strip() + char_ter
                newtext = newtext.replace(part, new_part)

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
