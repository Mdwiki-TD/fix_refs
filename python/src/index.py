"""
Main fix page module

Usage:
    from src.index import fix_page
"""

from src.test_bot import echo_test
from src.infoboxes.infobox import Expend_Infobox
from src.pt_bots.fix_pt_months import pt_fixes
from src.bg_bots.fix_bg import bg_fixes
from src.sw import sw_fixes
from src.es_bots.es import fix_es
from src.es_bots.section import es_section
from src.bots.remove_duplicate_refs import remove_Duplicate_refs_With_attrs
from src.helps_bots.mv_dots import move_dots_after_refs
from src.helps_bots.en_lang_param import add_lang_en_to_refs
from src.md_cat import add_Translated_from_MDWiki
from src.bots.mini_fixes_bot import mini_fixes, mini_fixes_after_fixing
from src.helps_bots.remove_space import (
    remove_spaces_between_last_word_and_beginning_of_ref,
    remove_spaces_between_ref_and_punctuation
)
from src.helps_bots.missing_refs import fix_missing_refs
from src.bots.redirect_help import page_is_redirect


def fix_page(text, title, move_dots, infobox, add_en_lang, lang, sourcetitle, mdwiki_revid):
    """
    Fix page content by applying various fixes
    
    Args:
        text: Page text to process
        title: Page title
        move_dots: Whether to move dots after refs
        infobox: Whether to expand infobox
        add_en_lang: Whether to add English language parameter
        lang: Language code
        sourcetitle: Source title
        mdwiki_revid: MDWiki revision ID
        
    Returns:
        Fixed text or original text if no changes
    """
    text_org = text
    
    if page_is_redirect(title, text):
        return text
    
    # Expand infobox
    if infobox or lang == "es":
        echo_test("Expend_Infobox\n")
        text = Expend_Infobox(text, title, "")
    
    # Apply mini fixes
    text = mini_fixes(text, lang)
    
    # Fix missing refs
    text = fix_missing_refs(text, sourcetitle, mdwiki_revid)
    
    # Remove duplicate refs
    text = remove_Duplicate_refs_With_attrs(text)
    
    # Move dots
    if move_dots:
        echo_test("move_dots\n")
        text = move_dots_after_refs(text, lang)
    
    # Add English language parameter
    if add_en_lang:
        echo_test("add_en_lang\n")
        text = add_lang_en_to_refs(text)
    
    # Language-specific fixes
    if lang == "pt":
        text = pt_fixes(text)
    
    if lang == "bg":
        text = bg_fixes(text, sourcetitle, mdwiki_revid)
    
    if lang == "es":
        text = fix_es(text, title)
        text = es_section(sourcetitle, text, mdwiki_revid)
    
    if lang == 'sw':
        text = sw_fixes(text)
    
    if lang == "hy":
        text = remove_spaces_between_last_word_and_beginning_of_ref(text, "hy")
        text = remove_spaces_between_ref_and_punctuation(text)
    
    # Add MDWiki category
    if lang != "bg":
        text = add_Translated_from_MDWiki(text, lang)
    
    # Apply mini fixes after fixing
    text = mini_fixes_after_fixing(text, lang)
    
    # Return modified text or original if empty
    if text:
        return text
    
    return text_org
