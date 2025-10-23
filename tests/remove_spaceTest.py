from __future__ import annotations

from __future__ import annotations

from pathlib import Path

from src.helps_bots.remove_space import remove_spaces_between_last_word_and_beginning_of_ref
from tests.conftest import assert_equal_compare


FIXTURES_ROOT = Path(__file__).parent / "texts" / "remove_space_texts"


class remove_spaceTest:
    def _run_case(self, case: str) -> None:
        case_dir = FIXTURES_ROOT / case
        input_text = (case_dir / "input.txt").read_text(encoding="utf-8")
        expected = (case_dir / "expected.txt").read_text(encoding="utf-8")
        result = remove_spaces_between_last_word_and_beginning_of_ref(input_text, "hy")
        (case_dir / "output.txt").write_text(result, encoding="utf-8")
        assert_equal_compare(expected, input_text, result)

    def testRemoveSpaceEnd1stFile(self) -> None:
        self._run_case("1")

    def testRemoveSpaceEnd2ndFile(self) -> None:
        self._run_case("2")

    def testRemoveSpaceEnd3rdFile(self) -> None:
        self._run_case("3")

    def testRemoveSpaceEnd4thFile(self) -> None:
        self._run_case("4")

    def testRemoveSpaceEnd5thFile(self) -> None:
        self._run_case("5")

    def testRemoveSpaceEnd1(self) -> None:
        input_text = (
            "Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ "
            "<ref name=\"Os2018\" /><ref name=\"Li2018\" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|"
            "սոցիալական դասերում]] գները նման են թվում։ <ref name=\"Luc2021\" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող"
            " երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name=\"Luc2021\" /> Այս"
            " վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից  <ref name=\"Os2018\" />։"
        )
        expected = (
            "Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ "
            "<ref name=\"Os2018\" /><ref name=\"Li2018\" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|"
            "սոցիալական դասերում]] գները նման են թվում։ <ref name=\"Luc2021\" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող"
            " երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name=\"Luc2021\" /> Այս"
            " վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name=\"Os2018\" />։"
        )
        assert_equal_compare(
            expected,
            input_text,
            remove_spaces_between_last_word_and_beginning_of_ref(input_text, "hy"),
        )

    def testPart3(self) -> None:
        input_text = (
            "Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ  "
            "<ref name=\"Sc2011\">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric"
            " Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|"
            "language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|"
            "archive-date=November 5, 2017}}</ref>։"
        )
        expected = (
            "Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ"
            "<ref name=\"Sc2011\">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology"
            " E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|"
            "language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|"
            "archive-date=November 5, 2017}}</ref>։"
        )
        assert_equal_compare(
            expected,
            input_text,
            remove_spaces_between_last_word_and_beginning_of_ref(input_text, "hy"),
        )

    def testPart4(self) -> None:
        input_text = (
            "Գոյություն ունի լյարդի ճարպային հիվանդության երկու տեսակ՝ ոչ ալկոհոլային ճարպային լյարդի հիվանդություն (ՈԱՃՀ) և "
            "ալկոհոլային լյարդի հիվանդություն <ref name=\"NIH2016\" />։ ՈԱՃՀՀ-ն բաղկացած է պարզ ճարպային լյարդից և ոչ ալկոհոլային"
            " ստեատոհեպատիտից (ՈԱՃՀ): <ref name=\"AFP2013\">{{Cite journal|last=Iser|first=D|last2=Ryan|first2=M|title=Fatty liver"
            " disease—a practical guide for GPs.|journal=Australian Family Physician|date=July 2013|volume=42|issue=7|pages=444–7|"
            "pmid=23826593}}</ref><ref name=\"NIH2016\" /> Հիմնական ռիսկերից են [[Էթիլ սպիրտ|ալկոհոլը]], [[Տիպ 2 շաքարային դիաբետ|"
            "2-րդ տիպի շաքարախտը]] և [[Ճարպակալում|ճարպակալումը]] <ref name=\"Ant2019\" /><ref name=\"NIH2016\" />։ Այլ ռիսկի գործոններից"
            " են որոշակի դեղամիջոցները, ինչպիսիք են [[Գլյուկոկորտիկոիդներ|գլյուկոկորտիկոիդները]] և [[Հեպատիտ C|հեպատիտ C-ն]] :"
            " <ref name=\"NIH2016\" /> Անհասկանալի է, թե ինչու են ոչ ալկոհոլային ճարպային լյարդի հիվանդություն ունեցող որոշ մարդիկ"
            " զարգացնում պարզ ճարպային լյարդ, իսկ մյուսները՝ ոչ ալկոհոլային հեպատիտ: <ref name=\"NIH2016\" /> Ախտորոշումը հիմնված է"
            " [[Անամնեզ|բժշկական պատմության]] վրա, որը հաստատվում է արյան անալիզներով, բժշկական պատկերագրական հետազոտություններով և"
            " երբեմն լյարդի բիոպսիայով <ref name=\"NIH2016\" />։"
        )
        expected = (
            "Գոյություն ունի լյարդի ճարպային հիվանդության երկու տեսակ՝ ոչ ալկոհոլային ճարպային լյարդի հիվանդություն (ՈԱՃՀ) և "
            "ալկոհոլային լյարդի հիվանդություն <ref name=\"NIH2016\" />։ ՈԱՃՀՀ-ն բաղկացած է պարզ ճարպային լյարդից և ոչ ալկոհոլային"
            " ստեատոհեպատիտից (ՈԱՃՀ): <ref name=\"AFP2013\">{{Cite journal|last=Iser|first=D|last2=Ryan|first2=M|title=Fatty liver"
            " disease—a practical guide for GPs.|journal=Australian Family Physician|date=July 2013|volume=42|issue=7|pages=444–7|"
            "pmid=23826593}}</ref><ref name=\"NIH2016\" /> Հիմնական ռիսկերից են [[Էթիլ սպիրտ|ալկոհոլը]], [[Տիպ 2 շաքարային դիաբետ|"
            "2-րդ տիպի շաքարախտը]] և [[Ճարպակալում|ճարպակալումը]] <ref name=\"Ant2019\" /><ref name=\"NIH2016\" />։ Այլ ռիսկի գործոններից"
            " են որոշակի դեղամիջոցները, ինչպիսիք են [[Գլյուկոկորտիկոիդներ|գլյուկոկորտիկոիդները]] և [[Հեպատիտ C|հեպատիտ C-ն]] :"
            " <ref name=\"NIH2016\" /> Անհասկանալի է, թե ինչու են ոչ ալկոհոլային ճարպային լյարդի հիվանդություն ունեցող որոշ մարդիկ"
            " զարգացնում պարզ ճարպային լյարդ, իսկ մյուսները՝ ոչ ալկոհոլային հեպատիտ: <ref name=\"NIH2016\" /> Ախտորոշումը հիմնված է"
            " [[Անամնեզ|բժշկական պատմության]] վրա, որը հաստատվում է արյան անալիզներով, բժշկական պատկերագրական հետազոտություններով և"
            " երբեմն լյարդի բիոպսիայով<ref name=\"NIH2016\" />։"
        )
        assert_equal_compare(
            expected,
            input_text,
            remove_spaces_between_last_word_and_beginning_of_ref(input_text, "hy"),
        )
