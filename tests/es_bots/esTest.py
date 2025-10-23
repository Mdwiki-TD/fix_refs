from __future__ import annotations

import re
from pathlib import Path

from src.es_bots.es import fix_es, fix_temps
from src.es_bots.es_months import fix_es_months_in_refs


def fix_temps_wrap(text: str) -> str:
    result = fix_temps(text)
    result = fix_es_months_in_refs(result)
    return re.sub(r"\s*=\s*", "=", result)


FIXTURES_DIR = Path(__file__).parent / "texts"


class esTest:
    def test_fix_temps_and_months_1(self) -> None:
        old = (
            "{{cite journal|vauthors=Dowd SE|title=Confirmation |journal=Applied |volume=64 |issue=9 |pages=3332–5 |year=1998 "
            "|pmid=9726879 |pmc=106729 |doi=10|bibcode=1|url-status=dead}}"
        )
        new = (
            "{{cita publicación|vauthors=Dowd SE|título=Confirmation|journal=Applied|volumen=64|issue=9|páginas=3332–5|año=1998|"
            "pmid=9726879|pmc=106729|doi=10|bibcode=1}}"
        )
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_2(self) -> None:
        old = (
            "hi!. <ref name=Doed1998>{{cite journal|vauthors=Dowd SE|title=Confirmation |journal=Applied |volume=64 |issue=9|pages="
            "3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10|bibcode=1}}</ref> dodo"
        )
        new = (
            "hi!. <ref name=Doed1998>{{cita publicación|vauthors=Dowd SE|título=Confirmation|journal=Applied|volumen=64|issue=9|"
            "páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10|bibcode=1}}</ref> dodo"
        )
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_3(self) -> None:
        old = (
            "hi!. <ref name=Doed1998>{{cite journal |vauthors=Dowd SE, Gerba CP, Pepper IL |title=Confirmation of the Human-"
            "Pathogenic Microsporidia Enterocytozoon bieneusi, Encephalitozoon intestinalis, and Vittaforma corneae in Water |"
            "journal=Applied and Environmental Microbiology |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |"
            "doi=10.1128/AEM.64.9.3332-3335.1998|bibcode=1998ApEnM..64.3332D }}</ref>yemen"
        )
        new = (
            "hi!. <ref name=Doed1998>{{cita publicación|vauthors=Dowd SE, Gerba CP, Pepper IL|título=Confirmation of the Human-"
            "Pathogenic Microsporidia Enterocytozoon bieneusi, Encephalitozoon intestinalis, and Vittaforma corneae in Water|"
            "journal=Applied and Environmental Microbiology|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|"
            "doi=10.1128/AEM.64.9.3332-3335.1998|bibcode=1998ApEnM..64.3332D}}</ref>yemen"
        )
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_4(self) -> None:
        old = (
            "<ref>{{cite journal|vauthors=Dowd SE|title=Confirmation|url-status=dead}} {{cite web |title=CDC - DPDx - Microsporidiosis"
            " |url=https://www.cdc.gov/dpdx/microsporidiosis/index.html |website=www.cdc.gov |accessdate=11 December 2024 |language=en-us "
            "|date=29 May 2019}}</ref>"
        )
        new = (
            "<ref>{{cita publicación|vauthors=Dowd SE|título=Confirmation}} {{cita web|título=CDC - DPDx - Microsporidiosis|url="
            "https://www.cdc.gov/dpdx/microsporidiosis/index.html|sitioweb=www.cdc.gov|fechaacceso=11 de diciembre de 2024|idioma=en-us|"
            "fecha=29 de mayo de 2019}}</ref>"
        )
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_5(self) -> None:
        old = "<ref>{{cite journal |date=July 25, 1975}} {{cite journal |date=May 25, 1975}}</ref>"
        new = "<ref>{{cita publicación|fecha=25 de julio de 1975}} {{cita publicación|fecha=25 de mayo de 1975}}</ref>"
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_6(self) -> None:
        old = (
            "<ref>{{cite web |access-date=10 January 2022 |archive-date=9 January 2021}} {{Webarchive|url=https://web.archive.org/web/20221014134136/https://books.google.ca/books?id=4gznEkPjNJMC&pg=PA640|date=10 December 2022}}</ref>"
        )
        new = (
            "<ref>{{cita web|fechaacceso=10 de enero de 2022|fechaarchivo=9 de enero de 2021}} {{Webarchive|url=https://web.archive.org/web/20221014134136/https://books.google.ca/books?id=4gznEkPjNJMC&pg=PA640|date=10 de diciembre de 2022}}</ref>"
        )
        assert fix_temps_wrap(old) == new

    def test_fix_temps_1(self) -> None:
        text_input = (FIXTURES_DIR / "fix_es_1_input.txt").read_text(encoding="utf-8")
        text_output = (FIXTURES_DIR / "fix_es_1_output.txt").read_text(encoding="utf-8")
        assert fix_temps(text_input) == text_output

    def test_fix_es_1(self) -> None:
        text_input = (FIXTURES_DIR / "fix_es_1_output.txt").read_text(encoding="utf-8")
        text_output = (FIXTURES_DIR / "fix_es_2_output.txt").read_text(encoding="utf-8")
        result = fix_es(text_input)
        (FIXTURES_DIR / "fix_es_1_output_fixed.txt").write_text(result, encoding="utf-8")
        assert text_output.replace("\r\n", "\n") == result.replace("\r\n", "\n")

    def test_fix_temps_and_months_with_month(self) -> None:
        old = "<ref>{{cite journal|title=Study|journal=Nature|date=January 2005|pages=20–25}}</ref>"
        new = "<ref>{{cita publicación|título=Study|journal=Nature|fecha=enero de 2005|páginas=20–25}}</ref>"
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_different_template(self) -> None:
        old = "{{cite book|title=Medical Book|year=2010|pages=100–105}}"
        new = "{{cita libro|título=Medical Book|año=2010|páginas=100–105}}"
        assert fix_temps_wrap(old) == new

    def test_fix_temps_and_months_no_change(self) -> None:
        text = "Texto sin plantillas ni meses en inglés."
        assert fix_temps_wrap(text) == text

    def test_fix_temps_inside_ref(self) -> None:
        old = (
            "<ref name=OI2018>{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}}</ref>"
        )
        assert fix_temps_wrap(old) == old

    def test_fix_temps_with_Webarchive_temp(self) -> None:
        old = (
            "<ref name=OI2018>{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}} {{Webarchive|url=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|date=19 de diciembre de 2019}}</ref>"
        )
        assert fix_temps_wrap(old) == old

    def test_fix_temps_alone(self) -> None:
        old = (
            "{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}}"
        )
        assert fix_temps_wrap(old) == old
