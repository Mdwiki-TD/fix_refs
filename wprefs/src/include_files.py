"""Python analogue of ``src/include_files.php``.

The PHP version eagerly ``include``\ s a large list of helper modules so
that functions become available in the global namespace.  In Python the
same behaviour can be emulated by importing the packages dynamically.

The :func:`include_all` helper below keeps the original load order to
avoid circular dependencies and side effects.
"""

from __future__ import annotations

import importlib
import pkgutil
from pathlib import Path
from types import ModuleType
from typing import Iterable, List

from .WikiParse import include_it as wikparse_include


def _iter_modules(path: Path) -> Iterable[str]:
    for module in sorted(pkgutil.iter_modules([str(path)]), key=lambda item: item.name):
        yield module.name


def include_all() -> List[ModuleType]:
    """Import every helper package and return the loaded modules."""

    base_package = __package__ or "wprefs.src"
    base_path = Path(__file__).resolve().parent

    imported: List[ModuleType] = []

    # ``test_bot`` must be imported first because many helpers depend on it.
    imported.append(importlib.import_module(f"{base_package}.test_bot"))

    # Load the WikiParse helpers.
    imported.extend(wikparse_include.include_all())

    def _import_group(group: str) -> None:
        package = f"{base_package}.{group}"
        directory = base_path / group
        if not directory.is_dir():
            return
        for name in _iter_modules(directory):
            imported.append(importlib.import_module(f"{package}.{name}"))

    for group in [
        "helps_bots",
        "infoboxes",
        "Parse",
        "bots",
        "es_bots",
        "pt_bots",
        "bg_bots",
    ]:
        _import_group(group)

    for module in ["sw", "md_cat", "index"]:
        imported.append(importlib.import_module(f"{base_package}.{module}"))

    return imported


__all__ = ["include_all"]
