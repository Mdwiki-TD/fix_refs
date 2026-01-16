"""
Month new value module

Usage:
    from src.bots.months_new_value import make_date_new_val_pt, make_date_new_val_es
"""

import re


def new_date(val, lang='pt'):
    """
    Convert English month dates to localized format
    
    Args:
        val: Date string to convert
        lang: Language code ('pt' or 'es')
        
    Returns:
        Converted date string
    """
    # Define month translations per language
    months_translations = {
        'pt': {
            "January": "janeiro",
            "February": "fevereiro",
            "March": "março",
            "April": "abril",
            "May": "maio",
            "June": "junho",
            "July": "julho",
            "August": "agosto",
            "September": "setembro",
            "October": "outubro",
            "November": "novembro",
            "December": "dezembro",
        },
        'es': {
            "January": "enero",
            "February": "febrero",
            "March": "marzo",
            "April": "abril",
            "May": "mayo",
            "June": "junio",
            "July": "julio",
            "August": "agosto",
            "September": "septiembre",
            "October": "octubre",
            "November": "noviembre",
            "December": "diciembre",
        },
    }
    
    # Ensure language exists
    if lang not in months_translations:
        return val.strip()
    
    months_lower = {k.lower(): v for k, v in months_translations[lang].items()}
    
    month_part = r"(?P<m>January|February|March|April|May|June|July|August|September|October|November|December)"
    patterns = [
        rf"^(?:(?P<d>\d{{1,2}})\s+)?{month_part},?\s+(?P<y>\d{{4}})$",
        rf"^{month_part}\s+(?P<d>\d{{1,2}}),?\s+(?P<y>\d{{4}})$",
    ]
    
    for pattern in patterns:
        match = re.match(pattern, val.strip(), re.IGNORECASE)
        if match:
            day = match.group('d')
            month = match.group('m').lower()
            year = match.group('y')
            translated_month = months_lower.get(month, "")
            
            if translated_month:
                # Build result depending on language
                if lang == 'es':
                    return f"{day} de {translated_month} de {year}".strip() if day else f"{translated_month} de {year}".strip()
                # no de before year in pt
                return f"{day} de {translated_month} {year}".strip() if day else f"{translated_month} {year}".strip()
    
    return val.strip()


def make_date_new_val_pt(val):
    """
    Convert English month dates to Portuguese format
    
    Args:
        val: Date string to convert
        
    Returns:
        Converted date string
    """
    return new_date(val, 'pt')


def make_date_new_val_es(val):
    """
    Convert English month dates to Spanish format
    
    Args:
        val: Date string to convert
        
    Returns:
        Converted date string
    """
    return new_date(val, 'es')
