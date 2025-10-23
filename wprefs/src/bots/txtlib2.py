"""Template extraction helpers translated from PHP."""

from __future__ import annotations

from typing import Dict, List

from ..WikiParse import Template as template_helpers


def extract_templates_and_params(text: str) -> List[Dict[str, object]]:
    templates = template_helpers.getTemplates(text)
    results: List[Dict[str, object]] = []
    for temp in templates:
        results.append(
            {
                "name": temp.getStripName(),
                "item": temp.getOriginalText(),
                "params": temp.getParameters(),
            }
        )
    return results


__all__ = ["extract_templates_and_params"]
