from __future__ import annotations

import pytest

from src.helps_bots import missing_refs
from tests.conftest import assert_equal_compare


@pytest.fixture(autouse=True)
def stub_full_text(monkeypatch: pytest.MonkeyPatch) -> None:
    content = (
        "Accreta, <ref name='Stat2020'/> increta, percreta"
        "<ref name=\"Stat2020\">{{cite journal |last1=Shepherd |first1=Alexa M. |last2=Mahdy |first2=Heba |title=Placenta Accreta "
        "|journal=StatPearls |date=2020 |pmid=33085435 |url=https://pubmed.ncbi.nlm.nih.gov/33085435/ |publisher=StatPearls Publishing "
        "|access-date=2020-10-23 |archive-date=2021-08-28 |archive-url=https://web.archive.org/web/20210828225644/https://pubmed.ncbi.nlm.nih.gov/33085435/ "
        "|url-status=live }} {{Webarchive|url=https://web.archive.org/web/20210828225644/https://pubmed.ncbi.nlm.nih.gov/33085435/ |date=2021-08-28 }}</ref>"
    )
    monkeypatch.setattr(missing_refs, "get_full_text", lambda *args, **kwargs: content)


class missing_refsTest:
    def testPart1(self) -> None:
        input_text = "Accreta, <ref name='Stat2020'/> increta, percreta<ref name=\"Stat2020\"/>"
        expected = (
            "Accreta, <ref name='Stat2020'/> increta, percreta"
            "<ref name=Stat2020>{{cite journal |last1=Shepherd |first1=Alexa M. |last2=Mahdy |first2=Heba |title=Placenta Accreta "
            "|journal=StatPearls |date=2020 |pmid=33085435 |url=https://pubmed.ncbi.nlm.nih.gov/33085435/ |publisher=StatPearls Publishing "
            "|access-date=2020-10-23 |archive-date=2021-08-28 |archive-url=https://web.archive.org/web/20210828225644/https://pubmed.ncbi.nlm.nih.gov/33085435/ "
            "|url-status=live }} {{Webarchive|url=https://web.archive.org/web/20210828225644/https://pubmed.ncbi.nlm.nih.gov/33085435/ |date=2021-08-28 }}</ref>"
        )
        result = missing_refs.fix_missing_refs(input_text, "", 1469242)
        assert_equal_compare(expected, input_text, result)
