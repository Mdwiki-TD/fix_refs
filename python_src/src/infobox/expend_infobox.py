"""
Infobox expansion functions
"""

import re
from typing import Dict, List, Any


def fix_title_bold(text: str, title: str) -> str:
    """Fix title formatting - wrap title in bold quotes

    Args:
        text: Text containing title
        title: Title to fix

    Returns:
        Text with title fixed
    """
    try:
        title2 = "''" + title + "''"
    except Exception:
        title2 = title

    pattern = r'\}\s*\'\'\'\'' + re.escape(title2) + r'\'\'\'\''  # Escaped triple quote
    text = re.sub(pattern, r'}\n\n1', text)

    return text


def make_section_0(title: str, newtext: str) -> str:
    """Extract section_0 template content from text

    Args:
        title: Title to search for
        newtext: Text to search in

    Returns:
        Section_0 content
    """
    section_0 = ""

    if "==" in newtext:
        section_0 = newtext.split("==")[0]
    else:
        tagg = f"''{title}'''1"
        if tagg in newtext:
            section_0 = newtext.split(tagg)[0]
        else:
            section_0 = newtext

    return section_0


def do_comments(text: str) -> str:
    """Remove specific comment patterns from text

    Args:
        text: Text to process

    Returns:
        Text with comments removed
    """
    pattern = r'\s*\n\s*(<!-- (Monoclonal antibody data|External links|Names\*Clinical data|Legal data|Legal status|Pharmacokinetic data|Chemical and physical data|Definition and medical uses|Chemical data|\\w+ \\w+ data|\\w+ status|Identifiers) -->)\s*\n*'
    matches = re.findall(pattern, text)

    for match in matches:
        match = match.strip()
        text = text.replace(match, f"\n\n{match}\n")

    return text


def expend_new(main_temp: Any) -> str:
    """Expand template using simple string manipulation

    Args:
        main_temp: Template text to expand

    Returns:
        Expanded template text
    """
    main_temp = str(main_temp).strip()

    pattern = r'\{((?:[^{}]++|(?R))*)\}\}'
    match = re.search(pattern, main_temp)

    if match:
        temp_content = match.group(1)
        simple_temp = temp_content

        temp = f"{{simple_temp}}"
        new_temp = do_comments(temp)
        new_temp = new_temp.strip()

        return new_temp

    return main_temp


def make_tempse(section_0: str):
    """Create tempse dictionary from section_0 content

    Args:
        section_0: Section content

    Returns:
        Dictionary with tempse and tempse data
    """
    # Simple regex-based template extraction
    tempse = []
    tempse_u = {}

    pattern = r'\{([^}:]+?)(\|.*?)?\}\}'
    matches = re.finditer(pattern, section_0, re.IGNORECASE)
    tempse_by_u = 0
    for match in matches:
        template_text = match.group(0)
        content = match.group(1) if len(match.groups()) > 1 else ""

        template_name = template_text.split('|')[0].strip() if '|' in template_text else template_text.strip()
        template = template_text

        tempse_by_u = len(template)
        tempse_u[template_name] = template

        tempse.append({
            'name': template_name,
            'item': template,
            'params': template
        })

    return {
        'tempse_by_u': tempse_by_u,
        'tempse': tempse
    }


def expend_infobox(text: str, title: str, section_0: str = "") -> str:
    """Expand infobox by extracting parameters from section_0 template

    Args:
        text: Text to process
        title: Page title
        section_0: Section_0 template content (can be empty)

    Returns:
        Text with expanded infobox
    """
    newtext = text

    if not section_0:
        section_0 = make_section_0(title, newtext)

    tab = make_tempse(section_0)

    if not tab['tempse']:
        return newtext

    main_temp = None

    for temp in tab['tempse']:
        temp_text = temp['item']
        template_name = temp['name'].lower()
        template = temp['params']

        # Check if this looks like an infobox template
        if 'infobox' in template_name:
            main_temp = temp
            break

    if main_temp:
        new_temp = expend_new(main_temp['item'])

        if new_temp != main_temp['item']:
            newtext = newtext.replace(main_temp['item'], new_temp)

            # Fix title formatting
            newtext = fix_title_bold(newtext, title)

    return newtext
