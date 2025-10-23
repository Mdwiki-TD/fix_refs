"""Python port of ``src/WikiParse/src/ParserCategories.php``."""

from __future__ import annotations

import hashlib
import re
from typing import Dict


class ParserCategories:
    def __init__(self, text: str, namespace: str = "") -> None:
        self.text = text
        self.namespace = namespace if namespace else "Category"
        self.categories: Dict[str, str] = {}
        self.parse()

    def parse(self) -> None:
        pattern = re.compile(r"\[\[\s*" + re.escape(self.namespace) + r"\s*:\s*([^\]|]+)(?:\|[^\]]*)?\s*\]\]", re.UNICODE)
        categories: Dict[str, str] = {}
        for match in pattern.finditer(self.text):
            category = match.group(1).strip()
            key = hashlib.md5(category.encode("utf-8")).hexdigest()
            categories[key] = category
        if categories:
            self.categories = categories

    def getCategories(self) -> Dict[str, str]:
        return dict(self.categories)
