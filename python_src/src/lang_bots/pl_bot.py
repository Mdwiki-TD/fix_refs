"""
Polish-specific bot fixes
"""

import re


def add_missing_params_to_choroba_infobox(text: str) -> str:
    """Add missing medical code parameters to Choroba infobox templates

    Args:
        text: WikiText content

    Returns:
        Text with missing parameters added
    """
    new_text = text

    # Parameters to add if missing
    params_to_add = [
        "nazwa naukowa",
        "ICD11",
        "ICD11 nazwa",
        "ICD10",
        "ICD10 nazwa",
        "DSM-5",
        "DSM-5 nazwa",
        "DSM-IV",
        "DSM-IV nazwa",
        "ICDO",
        "DiseasesDB",
        "OMIM",
        "MedlinePlus",
        "MeshID",
        "commons",
    ]

    # Find all Choroba infobox templates (case-insensitive)
    # Match {{Choroba infobox|...}}
    pattern = r'\{\{\s*Choroba\s+infobox\s*(\|.*?)?\}\}'
    matches = list(re.finditer(pattern, text, re.IGNORECASE | re.DOTALL))

    for match in matches:
        full_template = match.group(0)
        template_content = match.group(1) if len(match.groups()) > 1 else ""

        # Extract existing parameters
        existing_params = set()
        if template_content:
            for param in template_content.split('|'):
                param = param.strip()
                if '=' in param:
                    existing_params.add(param.split('=')[0].strip().lower())

        # Add missing parameters one by one
        new_template = full_template
        for param_name in params_to_add:
            if param_name.lower() not in existing_params:
                # Add parameter before closing braces
                if '|' in new_template:
                    # Insert before }}
                    new_template = new_template.replace('}}', f'| {param_name}= }}')
                else:
                    new_template = new_template.replace('}}', f'| {param_name}= }}')

                # Update existing params set
                existing_params.add(param_name.lower())

        new_text = new_text.replace(full_template, new_template)

    return new_text


def pl_fixes(text: str) -> str:
    """Apply Polish-specific fixes to text

    Args:
        text: WikiText content

    Returns:
        Fixed text
    """
    text = add_missing_params_to_choroba_infobox(text)
    return text
