"""Python port of ``src/index.php`` providing the ``fix_page`` helper."""

from __future__ import annotations

from .bots.mini_fixes_bot import mini_fixes, mini_fixes_after_fixing
from .bots.redirect_help import page_is_redirect
from .helps_bots.en_lang_param import add_lang_en_to_refs
from .helps_bots.missing_refs import fix_missing_refs
from .helps_bots.mv_dots import move_dots_after_refs
from .helps_bots.remove_space import (
    remove_spaces_between_last_word_and_beginning_of_ref,
    remove_spaces_between_ref_and_punctuation,
)
from .infoboxes.infobox import Expend_Infobox
from .md_cat import add_Translated_from_MDWiki
from .bots.remove_duplicate_refs import remove_Duplicate_refs_With_attrs
from .pt_bots.fix_pt_months import pt_fixes
from .bg_bots.fix_bg import bg_fixes
from .es_bots.es import fix_es
from .es_bots.section import es_section
from .sw import sw_fixes


def fix_page(
    text: str,
    title: str,
    move_dots: bool,
    infobox: bool,
    add_en_lang: bool,
    lang: str,
    sourcetitle: str,
    mdwiki_revid,
) -> str:
    text_org = text
    if page_is_redirect(title, text):
        return text

    if infobox or lang == "es":
        text = Expend_Infobox(text, title, "")

    text = mini_fixes(text, lang)
    text = fix_missing_refs(text, sourcetitle, mdwiki_revid)
    text = remove_Duplicate_refs_With_attrs(text)

    if move_dots:
        text = move_dots_after_refs(text, lang)

    if add_en_lang:
        text = add_lang_en_to_refs(text)

    if lang == "pt":
        text = pt_fixes(text)

    if lang == "bg":
        text = bg_fixes(text, sourcetitle, mdwiki_revid)

    if lang == "es":
        text = fix_es(text, title)
        text = es_section(sourcetitle, text, mdwiki_revid)

    if lang == "sw":
        text = sw_fixes(text)

    if lang == "hy":
        text = remove_spaces_between_last_word_and_beginning_of_ref(text, "hy")
        text = remove_spaces_between_ref_and_punctuation(text)

    if lang != "bg":
        text = add_Translated_from_MDWiki(text, lang)

    text = mini_fixes_after_fixing(text, lang)

    return text if text else text_org
