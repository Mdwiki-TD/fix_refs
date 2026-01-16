"""
WikiParse include module

Implemented from: src/WikiParse/include_it.php

This file loads all WikiParse modules for convenience.
"""

# Import all WikiParse modules
from src.WikiParse.Template import getTemplate, getTemplates
from src.WikiParse.src.ParserTemplate import ParserTemplate
from src.WikiParse.src.ParserTemplates import ParserTemplates
from src.WikiParse.src.DataModel.Template import Template
from src.WikiParse.src.DataModel.Parameters import Parameters

__all__ = [
    'getTemplate',
    'getTemplates',
    'ParserTemplate',
    'ParserTemplates',
    'Template',
    'Parameters'
]
