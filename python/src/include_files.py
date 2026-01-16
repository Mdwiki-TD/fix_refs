"""
Include files module

Implemented from: src/include_files.php

This file loads all necessary modules for the main application.
"""

# Core modules
from src.index import fix_page
from src.md_cat import add_Translated_from_MDWiki, get_cats
from src.test_bot import echo_test, echo_debug

# Bot modules
from src.bots.mini_fixes_bot import mini_fixes, mini_fixes_after_fixing
from src.bots.remove_duplicate_refs import remove_Duplicate_refs_With_attrs
from src.bots.redirect_help import page_is_redirect

# Helper modules
from src.helps_bots.mv_dots import move_dots_after_refs
from src.helps_bots.en_lang_param import add_lang_en_to_refs
from src.helps_bots.remove_space import (
    remove_spaces_between_last_word_and_beginning_of_ref,
    remove_spaces_between_ref_and_punctuation
)

# Language-specific modules
from src.pt_bots.fix_pt_months import pt_fixes
from src.es_bots.es import fix_es
from src.es_bots.section import es_section
from src.bg_bots.fix_bg import bg_fixes
from src.sw import sw_fixes

# WikiParse
from src.WikiParse.Template import getTemplate, getTemplates

# Parse modules
from src.Parse.Citations import getCitationsOld

__all__ = [
    'fix_page',
    'add_Translated_from_MDWiki',
    'get_cats',
    'echo_test',
    'echo_debug',
    'mini_fixes',
    'mini_fixes_after_fixing',
    'remove_Duplicate_refs_With_attrs',
    'page_is_redirect',
    'move_dots_after_refs',
    'add_lang_en_to_refs',
    'remove_spaces_between_last_word_and_beginning_of_ref',
    'remove_spaces_between_ref_and_punctuation',
    'pt_fixes',
    'fix_es',
    'es_section',
    'bg_fixes',
    'sw_fixes',
    'getTemplate',
    'getTemplates',
    'getCitationsOld'
]
