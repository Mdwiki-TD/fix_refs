"""
Spanish helper functions
"""

import re
from ..bots.months import make_date_new_val_es
from ..parsers.citations import get_short_citations


def start_end(cite_temp: str) -> bool:
    """Check if string starts with {{ and ends with }}"""
    return cite_temp.startswith("{{") and cite_temp.endswith("}}")


def fix_es_months_in_texts(temp_text: str) -> str:
    """Translate English months to Spanish within template parameters

    Args:
        temp_text: Template text

    Returns:
        Template text with translated months
    """
    new_text = temp_text
    temp_text = temp_text.strip()

    # Extract template content
    temp_match = re.match(r'\{\{([^}]*)\}\}', temp_text)
    if not temp_match:
        return new_text

    temp_content = temp_match.group(1)

    # Process each parameter
    new_params = []
    for param in temp_content.split('|'):
        if '=' in param:
            key, value = param.split('=', 1)
            new_value = make_date_new_val_es(value)
            if new_value and new_value.strip() != value.strip():
                new_params.append(f"{key}={new_value}")
            else:
                new_params.append(param)
        else:
            new_params.append(param)

    if new_params != temp_content.split('|'):
        new_template = "{{" + "|".join(new_params) + "}}"
        new_text = temp_text.replace(temp_text, new_template)

    return new_text


def fix_es_months_in_refs(text: str) -> str:
    """Translate English months to Spanish within citations

    Args:
        text: Text containing citations

    Returns:
        Text with translated months in citations
    """
    new_text = text

    # Get all citations
    pattern = r'<ref([^>]*?)>(.*?)<\/ref>'
    citations = re.finditer(pattern, text, re.IGNORECASE | re.DOTALL)

    for match in citations:
        cite_attrs = match.group(1)
        cite_temp = match.group(2)

        # Only process if it looks like a template
        if not (cite_temp.startswith("{{") and cite_temp.endswith("}}")):
            continue

        new_temp = fix_es_months_in_texts(cite_temp)
        if new_temp != cite_temp:
            new_text = new_text.replace(f"<ref{cite_attrs}>{cite_temp}</ref>",
                                     f"<ref{cite_attrs}>{new_temp}</ref>")

    return new_text


def get_refs(text: str) -> dict:
    """Extract references from text and create ref mapping

    Args:
        text: Text to process

    Returns:
        Dictionary with 'refs' and 'new_text' keys
    """
    new_text = text
    refs = {}
    citations = re.finditer(r'<ref([^>]*?)>(.*?)<\/ref>', text, re.IGNORECASE | re.DOTALL)

    numb = 0

    for match in citations:
        cite_attrs = match.group(1)
        cite_contents = match.group(2)
        cite_attrs = cite_attrs.strip() if cite_attrs else ""

        # Check if ref already has a name attribute
        has_name = bool(re.search(r'name\s*=', cite_attrs, re.IGNORECASE))

        if not has_name:
            # Generate autogen name for refs without a name
            numb += 1
            name = f"autogen_{numb}"
            cite_attrs = f"name='{name}'"

        refs[cite_attrs] = cite_contents

        # Replace with self-closing ref
        cite_newtext = f"<ref {cite_attrs} />"
        new_text = new_text.replace(match.group(0), cite_newtext)

    return {"refs": refs, "new_text": new_text}


def check_short_refs(line: str) -> str:
    """Remove short citations from line

    Args:
        line: Text line

    Returns:
        Line with short citations removed
    """
    shorts = get_short_citations(line)
    for cite in shorts:
        line = line.replace(cite.get_original_text(), "")

    # Remove multiple newlines
    line = re.sub(r'\n+', '\n', line)
    return line


def make_line(refs: dict) -> str:
    """Create reference line from refs dict

    Args:
        refs: Dictionary of ref attributes to content

    Returns:
        Formatted reference lines
    """
    line = "\n"

    for name, ref in refs.items():
        la = f'<ref {name.strip()}>{ref}</ref>\n'
        line += la

    line = line.strip()
    return line


def add_line_to_temp(line: str, text: str) -> str:
    """Add reference line to ref template

    Args:
        line: Reference lines to add
        text: Text containing templates

    Returns:
        Text with references added to template
    """
    # Find templates like {{reflist|...}} or {{listaref|...}}
    pattern = r'\{\{\s*(reflist|listaref)[^}]*\}\}'
    match = re.search(pattern, text, re.IGNORECASE)

    new_text = text
    temp_already_in = False

    if match:
        temp_text = match.group(0)
        # Extract refs parameter if exists
        refs_match = re.search(r'refs\s*=\s*(.*?)\s*(?:\||\}\})', temp_text, re.IGNORECASE)

        if refs_match:
            # Update refs parameter
            refn_param = refs_match.group(1)
            refn_param = check_short_refs(refn_param)
            line = refn_param.strip() + "\n" + line.strip()

            # Replace in the full text, not just temp_text
            new_temp_text = temp_text.replace(refs_match.group(0), f"refs={line.strip()}\n")
            new_text = text.replace(temp_text, new_temp_text)
            temp_already_in = True

    # If no template found, add section
    if not temp_already_in:
        section_ref = "\n== Referencias ==\n{{listaref|refs=\n" + line.strip() + "\n}}"
        new_text += section_ref

    return new_text


def mv_es_refs(text: str) -> str:
    """Move references to {{listaref}} template

    Args:
        text: Text to process

    Returns:
        Text with references moved to listaref template
    """
    if not text:
        return text

    refs = get_refs(text)
    new_lines = make_line(refs['refs'])
    new_text = refs['new_text']
    new_text = add_line_to_temp(new_lines, new_text)

    return new_text
