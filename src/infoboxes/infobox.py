"""Infobox expansion entry points translated from PHP."""

from __future__ import annotations

import re

from . import infobox2


def find_max_value_key(dictionary: dict) -> str:
    if not dictionary:
        return ""
    return max(dictionary, key=dictionary.get)


def make_main_temp(tempse_by_u: dict, tempse: dict) -> dict:
    if len(tempse_by_u) == 1:
        return next(iter(tempse_by_u.values()))
    u2 = find_max_value_key(tempse)
    return tempse_by_u.get(u2, {})


def make_section_0(title: str, newtext: str) -> str:
    if "==" in newtext:
        return newtext.split("==", 1)[0]
    tagg = "'''" + title + "'''1"
    if tagg in newtext:
        return newtext.split(tagg, 1)[0]
    return newtext


def fix_title_bold(text: str, title: str) -> str:
    try:
        title2 = re.escape(title)
    except re.error:
        title2 = title
    return re.sub(rf"\}}\s*('''{title2}''')", r"}}\n\n\1", text)


def Expend_Infobox(text: str, title: str, section_0: str | None) -> str:
    newtext = text
    if not section_0:
        section_0 = make_section_0(title, newtext)
    newtext = fix_title_bold(newtext, title)
    section_0 = fix_title_bold(section_0, title)
    tab = infobox2.make_tempse(section_0)
    tempse_by_u = tab.get("tempse_by_u", {})
    tempse = tab.get("tempse", {})
    main_temp = make_main_temp(tempse_by_u, tempse)
    if main_temp:
        main_temp_text = main_temp.get("item", "")
        new_temp = infobox2.expend_new(main_temp_text)
        if new_temp != main_temp_text:
            newtext = newtext.replace(main_temp_text, new_temp)
            newtext = newtext.replace(new_temp + "'''", new_temp + "\n'''")
    return newtext


__all__ = ["Expend_Infobox"]
