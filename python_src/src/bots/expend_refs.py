"""
Reference expansion utilities
"""

import re
from typing import Dict, List, Any


def refs_expend_work(first: str, alltext: str) -> str:
    """Expand references in first text using full refs from alltext

    Args:
        first: Text with self-closing refs like <ref name="x"/>
        alltext: Text with full refs

    Returns:
        Text with self-closing refs replaced by full refs
    """
    if not alltext:
        return first

    # Pattern to match self-closing refs
    pattern = r'<ref\s+([^>\/]*?)\s*/>'

    # Build mapping from alltext
    refs_map: Dict[str, str] = {}

    for match in re.finditer(pattern, alltext):
        attrs = match.group(1)
        if attrs:
            name_match = re.search(r'name\s*=\s*["\']([^"\']+)["\']', attrs, re.IGNORECASE)
            if name_match:
                name = name_match.group(1)
                full_ref_pattern = f'<ref\\s+{re.escape(attrs)}\\s*>(.*?)</ref>'
                full_match = re.search(full_ref_pattern, alltext, re.DOTALL | re.IGNORECASE)
                if full_match:
                    refs_map[name] = full_match.group(0)

    if not refs_map:
        return first

    result = first

    for match in re.finditer(pattern, result):
        attrs = match.group(1)

        if attrs:
            name_match = re.search(r'name\s*=\s*["\']([^"\']+)["\']', attrs, re.IGNORECASE)
            if name_match:
                name = name_match.group(1)
                if name in refs_map:
                    result = result.replace(match.group(0), refs_map[name])

    return result
