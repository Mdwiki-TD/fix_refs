"""
Swahili-specific bot fixes
"""

import re


def sw_fixes(text: str) -> str:
    """Apply Swahili-specific fixes to text

    Fixes section title: == Marejeleo == to == Marejeo ==

    Args:
        text: WikiText content

    Returns:
        Fixed text
    """
    # Replace == Marejeleo == with == Marejeo ==
    pattern = r'(=+)\s*Marejeleo\s*(\1)'
    replacement = r'\1 Marejeo \1'
    text = re.sub(pattern, replacement, text, flags=re.IGNORECASE)

    return text
