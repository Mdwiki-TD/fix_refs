from __future__ import annotations

from src.es_bots.section import es_section
from tests.conftest import assert_equal_compare


class esSectionTest:
    def test_text_already_has_traducido_ref(self) -> None:
        text = "Some content\n{{Traducido ref|title|oldid=12345}}\nMore content"
        assert_equal_compare(text, text, es_section("Source Title", text, "12345"))

    def test_text_already_has_traducido_ref_mdwiki(self) -> None:
        text = "Content here\n{{Traducido ref MDWIKI|en|Title|oldid=12345}}\nEnd"
        assert_equal_compare(text, text, es_section("Source Title", text, "12345"))

    def test_add_traducido_ref_after_enlaces_externos(self) -> None:
        text = "Content here\n== Enlaces externos ==\n* [http://example.com Link]\n"
        expected = (
            "Content here\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n* [http://example.com Link]\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "12345"))

    def test_add_enlaces_externos_section_with_traducido_ref(self) -> None:
        text = "Content here\nMore content"
        expected = (
            "Content here\nMore content\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "12345"))

    def test_enlaces_externos_with_extra_spaces(self) -> None:
        text = "Content\n== Enlaces   externos ==\n"
        expected = (
            "Content\n== Enlaces   externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "12345"))

    def test_empty_text(self) -> None:
        text = ""
        expected = (
            "\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=12345|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "12345"))

    def test_traducido_ref_with_spaces(self) -> None:
        text = "{{ Traducido ref | mdwiki | title | oldid=12345 }}"
        expected = "{{Traducido ref MDWiki|en| title | oldid=12345 }}"
        assert_equal_compare(expected, text, es_section("Source Title", text, "12345"))

    def test_es_section_already_has_template(self) -> None:
        old = "Texto con \n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Título|oldid=111|trad=|fecha=2020}} ya incluido."
        assert es_section("Otro título", old, 222) == old

    def test_es_section_with_external_links(self) -> None:
        old = "Intro.\n== Enlaces externos ==\n\n* [http://example.com Ejemplo]"
        new = (
            "Intro.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n\n* [http://example.com Ejemplo]"
        )
        assert es_section("Artículo de prueba", old, 123) == new

    def test_es_section_without_external_links(self) -> None:
        old = "Intro sin sección."
        new = (
            "Intro sin sección.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=321|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert es_section("Artículo de prueba", old, 321) == new

    def test_es_section(self) -> None:
        old = "Intro sin sección.\n== Enlaces externos ==\n"
        new = (
            "Intro sin sección.\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Artículo de prueba|oldid=321|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\n"
        )
        assert es_section("Artículo de prueba", old, 321) == new

    def test_already_contains_traducido_ref_template(self) -> None:
        text = "Some content {{Traducido ref|param=value}} more content"
        assert_equal_compare(text, text, es_section("Source Title", text, "123"))

    def test_add_after_existing_enlaces_externos(self) -> None:
        text = "Content before\n== Enlaces externos ==\nMore content"
        expected = (
            "Content before\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\nMore content"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "123"))

    def test_append_new_section_when_none_exists(self) -> None:
        text = "No external links section here"
        expected = (
            "No external links section here\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "123"))

    def test_empty_text_input(self) -> None:
        text = ""
        expected = (
            "\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "123"))

    def test_multiple_enlaces_externos_sections(self) -> None:
        text = "== Enlaces externos ==\nFirst section\n== Enlaces externos ==\nSecond section"
        expected = (
            "== Enlaces externos ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n\nFirst section\n== Enlaces externos ==\nSecond section"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "123"))

    def testCaseVariationsInSectionHeader(self) -> None:
        text = "== ENLACES EXTERNOS =="
        expected = (
            "== ENLACES EXTERNOS ==\n{{Traducido ref MDWiki|en|Source Title|oldid=123|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert_equal_compare(expected, text, es_section("Source Title", text, "123"))

    def testWhitespaceAroundSectionHeader(self) -> None:
        text = "  ==   Enlaces externos   ==  "
        expected = (
            "  ==   Enlaces externos   ==\n{{Traducido ref MDWiki|en|test!|oldid=520|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n  "
        )
        assert_equal_compare(expected, text, es_section("test!", text, "520"))

    def test_template_case_insensitive_match(self) -> None:
        text = "Something {{traducido REF mdwiki|Title|oldid=100}} end"
        assert es_section("Source Title", text, "100") == text

    def test_section_without_newline_after(self) -> None:
        text = "Intro\n== Enlaces externos =="
        expected = (
            "Intro\n== Enlaces externos ==\n{{Traducido ref MDWiki|en|Test|oldid=200|trad=|fecha={{subst:CURRENTDAY}} de "
            "{{subst:CURRENTMONTHNAME}} de {{subst:CURRENTYEAR}}}}\n"
        )
        assert_equal_compare(expected, text, es_section("Test", text, "200"))

    def test_multiple_templates_already_present(self) -> None:
        text = "{{Traducido ref|one}}\n{{Traducido ref|two}}"
        assert_equal_compare(text, text, es_section("Source Title", text, "300"))
