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

    # Pattern: } followed by title in triple quotes
    pattern = r'\}\s*' + "'" + "'" + "'" + re.escape(title2) + r"'" + "'" + "'" + "'"  # Triple quotes
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
        tagg = "''" + title + "'''1"
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


def expend_new(main_temp: str) -> str:
    """Expand template using simple string manipulation

    Args:
        main_temp: Template text to expand

    Returns:
        Expanded template text
    """
    main_temp = str(main_temp).strip()

    # PHP uses (?R) for recursion - simplify to match any braces
    pattern = r'\{([^{}]*)\}\}'
    match = re.search(pattern, main_temp)

    if match:
        temp_content = match.group(1)
        simple_temp = temp_content

        temp = "{{" + simple_temp + "}}"
        new_temp = do_comments(temp)
        new_temp = new_temp.strip()

        return new_temp

    return main_temp


def make_tempse(section_0: str) -> Dict[str, str]:
    """Create tempse dictionary from section_0 content

    Args:
        section_0: Section content

    Returns:
        Dictionary of templates sorted by length (longest first)
    """
    # Simple regex-based template extraction
    tempse: Dict[str, str] = {}

    # Pattern to match full template
    pattern = r'\{([^{}:]+?)(\|.*?)?\}\}'
    matches = re.finditer(pattern, section_0, re.IGNORECASE)

    for match in matches:
        template_text = match.group(0)
        # Store template for sorting by length
        tempse[template_text] = template_text

    return tempse


def find_max_value_key(dictionary: Dict[str, int]) -> str:
    """Sort dictionary by value in descending order and return first key

    Args:
        dictionary: Dictionary to sort

    Returns:
        First key (corresponding to max value)
    """
    sorted_items = sorted(dictionary.items(), key=lambda x: x[1], reverse=True)
    return sorted_items[0][0] if sorted_items else ""


def make_main_temp(tempse: Dict[str, str]) -> Dict[str, str]:
    """Return the longest template (main infobox)

    Args:
        tempse: Dictionary of templates

    Returns:
        Dictionary with main template
    """
    if len(tempse) == 1:
        return {'name': list(tempse.keys())[0], 'item': list(tempse.values())[0]}

    # Get template with max length
    main_template_name = find_max_value_key({k: len(v) for k, v in tempse.items()})
    main_template = tempse.get(main_template_name, "")

    return {'name': main_template_name, 'item': main_template}


def Expend_Infobox(text: str, title: str, section_0: str = "") -> str:
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

    if not tab:
        return newtext

    main_temp = make_main_temp(tab)

    if not main_temp['name']:
        return newtext

    main_temp_text = main_temp['item']
    new_temp = expend_new(main_temp_text)

    if new_temp != main_temp_text:
        newtext = newtext.replace(main_temp_text, new_temp)
        newtext = fix_title_bold(newtext, title)

    return newtext
