"""Infobox expansion helpers ported from PHP."""

from __future__ import annotations

import re

from ..bots import txtlib2
from ..WikiParse.src.ParserTemplate import ParserTemplate


COMMENT_PATTERN = re.compile(
    r"\s*\n*\s*(<!-- (Monoclonal antibody data|External links|Names*|Clinical data|Legal data|Legal status|Pharmacokinetic data|Chemical and physical data|Definition and medical uses|Chemical data|\w+ \w+ data|\w+ \w+ \w+ data|\w+ data|\w+ status|Identifiers) -->)\s*\n*",
    re.IGNORECASE,
)


def do_comments(text: str) -> str:
    def repl(match: re.Match[str]) -> str:
        return f"\n\n{match.group(1).strip()}\n"

    return COMMENT_PATTERN.sub(repl, text)


def expend_new(main_temp: str) -> str:
    main_temp = main_temp.strip()
    parser = ParserTemplate(main_temp)
    temp = parser.getTemplate()
    new_temp = temp.toString(newLine=True, ljust=17)
    new_temp = do_comments(new_temp)
    return new_temp.strip()


def make_tempse(section_0: str) -> dict:
    tempse_by_u = {}
    tempse = {}
    ingr = txtlib2.extract_templates_and_params(section_0)
    for index, temp in enumerate(ingr, start=1):
        params = temp["params"]
        template = temp["item"]
        if len(params) > 4 and f">{template}" not in section_0:
            tempse_by_u[index] = temp
            tempse[index] = len(template)
    return {"tempse_by_u": tempse_by_u, "tempse": tempse}


__all__ = ["expend_new", "make_tempse"]
