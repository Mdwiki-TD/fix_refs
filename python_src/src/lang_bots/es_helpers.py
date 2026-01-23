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
                new_value = make_date_new_val_es(value)
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
    # Match refs with content, excluding self-closing refs
    # Use negative lookbehind to exclude refs ending with />
    # Pattern: <ref + attributes (not ending with /) + > + content + </ref>
    citations = re.finditer(r'<ref([^/>]*|[^>]*/[^/>][^>]*)>(.*?)<\/ref>', text, re.IGNORECASE | re.DOTALL)

    numb = 0

    for match in citations:
        full_match = match.group(0)
        cite_attrs = match.group(1)
        cite_contents = match.group(2)

        # Skip self-closing refs like <ref name=foo/> (they end with /> in the opening tag)
        # Check if full_match starts with <ref.../> before any content
        opening_tag = full_match[:full_match.find('>') + 1] if '>' in full_match else full_match[:20]
        if opening_tag.rstrip().endswith('/>'):
            continue

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
        new_text = new_text.replace(full_match, cite_newtext)

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
    # Handle multi-line templates with nested braces by matching until the closing }}
    # that is on its own line or at end of content
    pattern = r'(\{\{\s*(reflist|listaref)\s*\|[^}]*refs\s*=)(.*?)(\}\})'
    match = re.search(pattern, text, re.IGNORECASE | re.DOTALL)

    new_text = text
    temp_already_in = False

    if match:
        template_start = match.group(1)  # {{Reflist|refs=
        refs_content = match.group(3)     # existing refs content
        template_end = match.group(4)     # }}

        # Clean up existing refs content
        refs_content = check_short_refs(refs_content)
        
        # Combine existing refs with new refs
        combined_refs = refs_content.strip() + "\n" + line.strip() if refs_content.strip() else line.strip()
        
        # Replace the template - keep }} on same line as last ref
        new_template = f"{template_start}\n{combined_refs}{template_end}"
        new_text = text[:match.start()] + new_template + text[match.end():]
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
