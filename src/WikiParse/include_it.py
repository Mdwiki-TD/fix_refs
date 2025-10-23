"""Python port of ``src/WikiParse/include_it.php``.

The PHP version eagerly includes every file in the WikiParse package so
that their symbols are available globally.  The helper :func:`include_all`
recreates that behaviour by importing the Python modules in the same
order (DataModel first, then the rest of ``src``, and finally the
root-level helpers).
"""

from __future__ import annotations

import importlib
import pkgutil
from pathlib import Path
from types import ModuleType
from typing import List


def _import_modules(package: str, directory: Path) -> List[ModuleType]:
    modules: List[ModuleType] = []
    for module in sorted(pkgutil.iter_modules([str(directory)]), key=lambda item: item.name):
        fullname = f"{package}.{module.name}"
        modules.append(importlib.import_module(fullname))
    return modules


def include_all() -> List[ModuleType]:
    """Eagerly import the WikiParse modules.

    The return value mirrors the PHP ``include`` side effect by returning
    the loaded module objects which can be useful for debugging or tests.
    """

    base_package = __package__ or "wprefs.src.WikiParse"
    base_path = Path(__file__).resolve().parent

    imported: List[ModuleType] = []

    # Load the data model classes first.
    data_model_path = base_path / "src" / "DataModel"
    if data_model_path.is_dir():
        imported.extend(_import_modules(f"{base_package}.src.DataModel", data_model_path))

    # Then load the remaining parser helpers under src/.
    src_path = base_path / "src"
    if src_path.is_dir():
        for module in sorted(pkgutil.iter_modules([str(src_path)]), key=lambda item: item.name):
            if module.name == "DataModel":
                continue
            fullname = f"{base_package}.src.{module.name}"
            imported.append(importlib.import_module(fullname))

    # Finally import the modules that live alongside this file.
    for module in sorted(pkgutil.iter_modules([str(base_path)]), key=lambda item: item.name):
        if module.name in {"__init__", "include_it"}:
            continue
        fullname = f"{base_package}.{module.name}"
        imported.append(importlib.import_module(fullname))

    return imported


__all__ = ["include_all"]
