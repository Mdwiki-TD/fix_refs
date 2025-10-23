"""Python port of ``src/WikiParse/Template.php``.

This module exposes the helper entry points that mirror the PHP helpers
used throughout the project.  The function names intentionally follow the
original camelCase style so that existing call sites can be migrated
without further renaming.
"""

from .src.ParserTemplate import ParserTemplate
from .src.ParserTemplates import ParserTemplates


def getTemplate(text):
    """Return a single :class:`~wprefs.src.WikiParse.src.DataModel.Template`.

    The implementation mirrors ``WikiParse\Template\getTemplate`` by
    instantiating :class:`ParserTemplate` and returning the parsed
    Template instance.
    """

    parser = ParserTemplate(text)
    return parser.getTemplate()


def getTemplates(text):
    """Return all templates found inside *text*.

    The behaviour matches the PHP helper: an empty input yields an empty
    list, otherwise :class:`ParserTemplates` is used to scan the text and
    return the parsed templates.
    """

    if not text:
        return []

    parser = ParserTemplates(text)
    return parser.getTemplates()
