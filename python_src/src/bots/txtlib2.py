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

    # Extract the main template - look for {{ ... }} that contains the whole text
    # For the test case, the text itself is the template
    if text.strip().startswith('{{') and text.strip().endswith('}}'):
        template_text = text.strip()
    else:
        # Find first template if text doesn't start with one
        match = re.search(r'(\{\{.+?\}\})', text, re.DOTALL)
        if match:
            template_text = match.group(1)
        else:
            return temps

    # Extract template name (everything after {{ up to first | or })
    # Allow spaces in template name (e.g., "Infobox drug")
    # Use greedy match to capture all content up to the final }}
    name_match = re.match(r'\{\{([^|}]+)\|(.*)\}\}', template_text, re.DOTALL)
    if not name_match:
        return temps

    template_name = name_match.group(1).strip()

    # Extract parameters
    params: Dict[str, str] = {}

    # Get content after the template name
    params_content = name_match.group(2) or ''

    # Parse parameters by splitting on | but handle nested templates
    if params_content.strip():
        # Use a state machine to handle nested templates
        parts = []
        current = []
        depth = 0

        for char in params_content:
            if char == '{':
                depth += 1
                current.append(char)
            elif char == '}':
                depth -= 1
                current.append(char)
            elif char == '|' and depth == 0:
                parts.append(''.join(current).strip())
                current = []
            else:
                current.append(char)

        if current:
            parts.append(''.join(current).strip())

        for part in parts:
            if '=' in part:
                key, value = part.split('=', 1)
                params[key.strip()] = value.strip()
            else:
                # Positional parameter
                idx = str(len([k for k in params if k.isdigit()]) + 1)
                params[idx] = part.strip()

    temps.append({
        'name': template_name,
        'item': template_text,
        'params': params
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
