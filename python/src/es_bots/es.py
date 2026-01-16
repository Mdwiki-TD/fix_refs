"""
Spanish fixes module

Usage:
    from src.es_bots.es import fix_es
"""

import re
from src.test_bot import echo_test
from src.WikiParse.Template import getTemplates


class ESData:
    """Spanish translation data"""
    refs_temps = {
        "cite web": "cita web",
        "cite arxiv": "cita arxiv",
        "cite book": "cita libro",
        "cite journal": "cita publicación",
        "cite news": "cita noticia",
        "citation needed": "cita requerida",
    }
    
    args_to = {
        "title": "título",
        "website": "sitioweb",
        "access-date": "fechaacceso",
        "accessdate": "fechaacceso",
        "language": "idioma",
        "archive-url": "urlarchivo",
        "archiveurl": "urlarchivo",
        "date": "fecha",
        "archive-date": "fechaarchivo",
        "archivedate": "fechaarchivo",
        "first": "nombre",
        "last": "apellidos",
        "first1": "nombre1",
        "last1": "apellidos1",
        "last2": "apellidos2",
        "first2": "nombre2",
    }


def work_one_temp(temp, name):
    """
    Work on one template
    
    Args:
        temp: Template object
        name: Template name
        
    Returns:
        Modified template string
    """
    temp_name2 = ESData.refs_temps.get(name, name)
    
    if temp_name2.lower() != name.lower():
        temp.setName(temp_name2)
    
    temp.changeParametersNames(ESData.args_to)
    temp.deleteParameter("url-status")
    
    new_text_str = temp.toString()
    
    return new_text_str


def fix_temps(text):
    """
    Fix templates in text
    
    Args:
        text: Text to process
        
    Returns:
        Modified text
    """
    temps_in = getTemplates(text)
    new_text = text
    
    for temp in temps_in:
        name = temp.getStripName()
        old_text_template = temp.getOriginalText()
        
        if name not in ESData.refs_temps and name not in ESData.refs_temps.values():
            continue
        
        new_text_str = work_one_temp(temp, name)
        new_text = new_text.replace(old_text_template, new_text_str)
    
    return new_text


def fix_es(text, title=""):
    """
    Apply Spanish-specific fixes
    
    Args:
        text: Text to process
        title: Page title
        
    Returns:
        Modified text
    """
    # Check for "#REDIRECCIÓN"
    if "#REDIRECCIÓN" in text and title != "test!":
        return text
    
    # Check if the text has fewer than 10 lines
    if text.count("\n") < 10 and title != "test!":
        echo_test("less than 10 lines\n")
    
    # Replace <references /> with {{listaref}}
    if "<references />" in text:
        text = text.replace("<references />", "{{listaref}}")
    
    # Apply transformations
    newtext = text
    newtext = fix_temps(newtext)
    
    return newtext
