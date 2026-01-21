"""
Template extraction utilities
"""

import re
from typing import Dict, List, Any


def extract_templates_and_params(text: str) -> List[Dict[str, Any]]:
    """Extract templates and their parameters from text

    Args:
        text: Text containing WikiText templates

    Returns:
        List of dictionaries with name, item, and params
    """
    temps = []

    # Simple regex-based template extraction
    pattern = r'\{([^}:]+?)(\|.*?)?\}\}'
    matches = re.finditer(pattern, text, re.IGNORECASE)

    for match in matches:
        template_text = match.group(0)
        content = match.group(1) if len(match.groups()) > 1 else ""

        template_name = template_text.split('|')[0].strip() if '|' in template_text else template_text.strip()
        template = template_text

        temps.append({
            'name': template_name,
            'item': template,
            'params': template
        })

    return temps


def make_tempse(section_0: str) -> Dict[str, Any]:
    """Create tempse dictionary from section_0 content

    Args:
        section_0: Section content

    Returns:
        Dictionary with tempse and tempse data
    """
    tempse: Dict[str, str] = {}

    pattern = r'\{([^}:]+?)(\|.*?)?\}\}'
    matches = re.finditer(pattern, section_0, re.IGNORECASE)

    for match in matches:
        template_text = match.group(0)
        template_name = template_text.split('|')[0].strip() if '|' in template_text else template_text.strip()
        template = template_text

        tempse[template_name] = template

    return {
        'tempse_by_u': tempse,
        'tempse': tempse
    }
