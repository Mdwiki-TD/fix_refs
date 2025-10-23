"""Python port of ``src/Parse/Category.php``."""

from __future__ import annotations

import re
from typing import Dict


def get_categories_reg(text: str) -> Dict[str, str]:
    """Return a mapping of category names to their original wikitext.

    The PHP implementation scans a wikitext snippet for ``[[Category:...]]``
    entries.  The same behaviour is mirrored here by iterating over all
    matches of the case-insensitive regular expression and storing the
    stripped category name as the key while preserving the full matched
    markup as the value.
    """

    categories: Dict[str, str] = {}
    pattern = re.compile(r"\[\[\s*Category\s*:([^\]\]]+?)\]\]", re.IGNORECASE | re.DOTALL)

    for match in pattern.finditer(text):
        category_content = match.group(1)
        parts = category_content.split("|", 1)
        category_name = parts[0].strip()
        if category_name:
            categories[category_name] = match.group(0)

    return categories
