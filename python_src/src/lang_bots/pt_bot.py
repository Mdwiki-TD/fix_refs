"""
Portuguese-specific bot fixes
"""

import re
from typing import Optional
from ..bots.months import make_date_new_val_pt
from ..parsers.citations import get_citations


def start_end(cite_temp: str) -> bool:
    """Check if string starts with {{ and ends with }}"""
    return cite_temp.startswith("{{") and cite_temp.endswith("}}")


def fix_pt_months_in_texts(temp_text: str) -> str:
    """Translate English months to Portuguese within template parameters

    Args:
        temp_text: Template text (can contain multiple templates)

    Returns:
        Template text with translated months
    """
    new_text = temp_text

    # Find all templates using a simple brace counter approach
    def process_single_template(template_str: str) -> str:
        """Process a single template string"""
        if not (template_str.startswith('{{') and template_str.endswith('}}')):
            return template_str

        # Remove outer braces
        inner = template_str[2:-2]

        # Process each parameter
        new_params = []
        for param in inner.split('|'):
            if '=' in param:
                key, value = param.split('=', 1)
                new_value = make_date_new_val_pt(value)
                if new_value and new_value.strip() != value.strip():
                    new_params.append(f"{key.strip()}={new_value.strip()}")
                else:
                    # Normalize spaces
                    new_params.append(f"{key.strip()}={value.strip()}")
            else:
                new_params.append(param.strip())

        return "{{" + "|".join(new_params) + "}}"

    # Find all {{...}} patterns (simple, non-nested)
    # This regex matches templates that don't contain nested templates
    template_pattern = r'\{\{[^{}]*\}\}'
    matches = list(re.finditer(template_pattern, temp_text))

    # Process templates from end to start (to preserve positions)
    for match in reversed(matches):
        original = match.group(0)
        processed = process_single_template(original)
        new_text = new_text[:match.start()] + processed + new_text[match.end():]

    return new_text


def fix_pt_months_in_refs(text: str) -> str:
    """Translate English months to Portuguese within citations

    Args:
        text: Text containing citations

    Returns:
        Text with translated months in citations
    """
    new_text = text

    # Get all citations
    citations = get_citations(text)

    for citation in citations:
        cite_temp = citation.get_content()

        # Only process if it looks like a template
        if not (cite_temp.startswith("{{") and cite_temp.endswith("}}")):
            continue

        new_temp = fix_pt_months_in_texts(cite_temp)
        if new_temp != cite_temp:
            new_text = new_text.replace(cite_temp, new_temp)

    return new_text


def rm_ref_spaces(newtext: str) -> str:
    """Remove spaces between punctuation and ref tags

    Args:
        newtext: Text to process

    Returns:
        Text with spaces removed
    """
    dot = r"(\.|,|。|।)"
    regline = r"((?:\s*<ref[\s\S]+?(?:<\/ref|\/)>)+)"
    pattern = r'\s*' + dot + r'\s*' + regline
    replacement = r'\1\2'

    newtext = re.sub(pattern, replacement, newtext)

    return newtext


def pt_fixes(text: str) -> str:
    """Apply Portuguese-specific fixes to text

    Args:
        text: WikiText content

    Returns:
        Fixed text
    """
    text = fix_pt_months_in_refs(text)
    text = rm_ref_spaces(text)
    return text
