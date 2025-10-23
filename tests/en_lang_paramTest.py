from __future__ import annotations

from src.helps_bots.en_lang_param import add_lang_en_to_refs


class en_lang_paramTest:
    def testAddLangEnSimpleRef(self) -> None:
        input_text = "<ref>{{Citar web|Some text}}</ref> {{temp|test=1}}"
        expected = "<ref>{{Citar web|Some text|language=en}}</ref> {{temp|test=1}}"
        assert add_lang_en_to_refs(input_text) == expected

    def testAddLangEnExistingLanguage(self) -> None:
        input_text = "<ref>{{Citar web|Text|language=fr}}</ref>"
        assert add_lang_en_to_refs(input_text) == input_text

    def testAddLangEnEmptyRef(self) -> None:
        input_text = "<ref></ref>"
        assert add_lang_en_to_refs(input_text) == input_text

    def testAddLangEnWithExistingParams(self) -> None:
        input_text = " {{temp|test=1}} <ref>{{Citar web|Text|author=John}}</ref>"
        expected = " {{temp|test=1}} <ref>{{Citar web|Text|author=John|language=en}}</ref>"
        assert add_lang_en_to_refs(input_text) == expected

    def testAddLangMalformedRef(self) -> None:
        input_text = "<ref>{{Citar web|Text|language = }}</ref> {{temp|test=1}}"
        expected = "<ref>{{Citar web|Text|language=en}}</ref> {{temp|test=1}}"
        assert add_lang_en_to_refs(input_text) == expected

    def testAddLangEn(self) -> None:
        input_text = "<ref>{{Citar web|Text|language=ar}}</ref>"
        assert add_lang_en_to_refs(input_text) == input_text

    def testAddLangEnNoChangeNeeded(self) -> None:
        input_text = " {{temp|test=1}} <ref>{{Citar web|Text|language=en}}</ref>"
        assert add_lang_en_to_refs(input_text) == input_text
