"""High level Spanish fixes translated from PHP."""

from __future__ import annotations

from dataclasses import dataclass, field
from typing import Dict

from ..TestBot import echo_test
from ..WikiParse import Template as template_helpers
from . import es_months
from .es_refs import mv_es_refs


@dataclass
class ESData:
    args_to: Dict[str, str] = field(default_factory=dict)
    refs_temps: Dict[str, str] = field(default_factory=dict)


ES_DATA = ESData(
    args_to={
        "title": "título",
        "website": "sitioweb",
        "access-date": "fechaacceso",
        "accessdate": "fechaacceso",
        "language": "idioma",
        "archive-url": "urlarchivo",
        "archiveurl": "urlarchivo",
        "date": "fecha",
        "archive-date": "fechaarchivo",
        "archivedate": "fechaarchivo",
        "first": "nombre",
        "last": "apellidos",
        "first1": "nombre1",
        "last1": "apellidos1",
        "last2": "apellidos2",
        "first2": "nombre2",
    },
    refs_temps={
        "cite web": "cita web",
        "cite arxiv": "cita arxiv",
        "cite certification": "cita certificación",
        "cite conference": "cita conferencia",
        "cite encyclopedia": "cita enciclopedia",
        "cite interview": "cita entrevista",
        "cite episode": "cita episodio",
        "cite newsgroup": "cita grupo de noticias",
        "cite comic": "cita historieta",
        "cite court": "cita juicio",
        "cite book": "cita libro",
        "cite mailing list": "cita lista de correo",
        "cite map": "cita mapa",
        "cite av media notes": "cita notas audiovisual",
        "cite news": "cita noticia",
        "cite podcast": "cita podcast",
        "cite journal": "cita publicación",
        "citation needed": "cita requerida",
        "cite thesis": "cita tesis",
        "cite tweet": "cita tuit",
        "cite av media": "cita video",
        "cite video game": "cita videojuego",
    },
)

REFS_TEMPS_LOWER = {key.lower(): value for key, value in ES_DATA.refs_temps.items()}


_PARAMS_ES_UP = {
    "nombre1": ["first1", "given1"],
    "enlaceautor1": ["authorlink1", "author1-link", "author-link1"],
    "enlaceautor": ["author-link", "authorlink"],
    "título": ["title"],
    "fechaacceso": ["accessdate"],
    "año": ["year"],
    "fecha": ["date"],
    "editorial": ["publisher"],
    "apellido-editor": ["editor-last", "editor-surname", "editor1-last"],
    "nombre-editor": ["editor-first", "editor-given", "editor1-first", "editor1-given"],
    "enlace-editor": ["editor-link", "editor1-link"],
    "ubicación": ["place", "location"],
    "lugar-publicación": ["publication-place"],
    "fecha-publicación": ["publication-date"],
    "edición": ["edition"],
    "sined": ["noed"],
    "volumen": ["volume"],
    "página": ["page"],
    "páginas": ["pages"],
    "en": ["at"],
    "enlace-pasaje": ["url-pasaje"],
    "idioma": ["language"],
    "título-trad": ["trans_title"],
    "capítulo": ["chapter"],
    "url-capítulo": ["url-chapter"],
    "capítulo-trad": ["trans_chapter"],
    "formato": ["format"],
    "cita": ["quote"],
    "separador": ["separator"],
    "resumen": ["laysummary", "layurl"],
    "fecha-resumen": ["laydate"],
    "apellidos1": ["last1"],
    "apellidos2": ["last2"],
    "nombre2": ["first2", "given2"],
    "enlaceautor2": ["authorlink2", "author2-link", "authorlink2"],
    "apellidos3": ["last3", "surname3", "author3"],
    "nombre3": ["first3", "given3"],
    "enlaceautor3": ["authorlink3", "author3-link", "authorlink3"],
    "apellidos4": ["last4", "surname4", "author4"],
    "nombre4": ["first4", "given4"],
    "enlaceautor4": ["authorlink4", "author4-link", "authorlink4"],
    "apellidos5": ["last5", "surname5", "author5"],
    "nombre5": ["first5", "given5"],
    "enlaceautor5": ["authorlink5", "author5-link", "authorlink5"],
    "apellidos6": ["last6", "surname6", "author6"],
    "nombre6": ["first6", "given6"],
    "enlaceautor6": ["authorlink6", "author6-link", "authorlink6"],
    "apellidos7": ["last7", "surname7", "author7"],
    "nombre7": ["first7", "given7"],
    "enlaceautor7": ["authorlink7", "author7-link", "authorlink7"],
    "apellidos8": ["last8", "surname8", "author8"],
    "nombre8": ["first8", "given8"],
    "enlaceautor8": ["authorlink8", "author8-link", "authorlink8"],
    "apellidos9": ["last9", "surname9", "author9"],
    "nombre9": ["first9", "given9"],
    "enlaceautor9": ["authorlink9", "author9-link", "authorlink9"],
    "separador-nombres": ["author-name-separator"],
    "separador-autores": ["author-separator"],
    "número-autores": ["display-authors"],
    "otros": ["others"],
}


for new_name, old_list in _PARAMS_ES_UP.items():
    for old in old_list:
        ES_DATA.args_to[old] = new_name


def work_one_temp(temp, name: str) -> str:
    temp_name2 = REFS_TEMPS_LOWER.get(name.lower(), name)
    if temp_name2.lower() != name.lower():
        temp.setName(temp_name2)
    temp.changeParametersNames(ES_DATA.args_to)
    temp.deleteParameter("url-status")
    return temp.toString()


def fix_temps(text: str) -> str:
    temps_in = template_helpers.getTemplates(text)
    new_text = text
    for temp in temps_in:
        name = temp.getStripName()
        old_text_template = temp.getOriginalText()
        if name.lower() not in REFS_TEMPS_LOWER:
            continue
        new_text_str = work_one_temp(temp, name)
        new_text = new_text.replace(old_text_template, new_text_str)
    return new_text


def fix_es(text: str, title: str = "") -> str:
    if "#REDIRECCIÓN" in text and title != "test!":
        return text
    if text.count("\n") < 10 and title != "test!":
        echo_test("less than 10 lines\n")
    if "<references />" in text:
        text = text.replace("<references />", "{{listaref}}")
    newtext = text
    newtext = es_months.fix_es_months_in_refs(newtext)
    newtext = fix_temps(newtext)
    newtext = mv_es_refs(newtext)
    return newtext


__all__ = ["fix_es", "ESData", "fix_temps"]
