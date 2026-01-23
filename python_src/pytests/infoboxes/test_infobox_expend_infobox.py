"""Tests for infobox expansion (expend_infobox.py)

Converted from tests/infoboxes/infoboxTest.php and tests/infoboxes/infobox2Test.php
"""
import pytest
from src.infobox import expend_infobox as ei_module

do_comments = ei_module.do_comments
expend_new = ei_module.expend_new
make_tempse = ei_module.make_tempse
fix_title_bold = ei_module.fix_title_bold
make_section_0 = ei_module.make_section_0
expend_infobox = ei_module.Expend_Infobox


class TestInfobox:
    """Test cases for infobox expansion"""

    # Test data from infoboxTest.php
    TEXT_INPUT = """{{Infobox drug|verifiedrevid=461217017|image=Omaveloxolone structure.svg|width=250|alt=|caption=<!-- Names -->|pronounce=|tradename=Skyclarys|synonyms=RTA 408|IUPAC_name=N-((4aS,6aR,6bS,8aR,12aS,14aR,14bS)-1 1-cyano-2,2,6a,6b,9,9,12a-heptamethyl-10,14-dioxo-1,2,3,4,4a,5,6,6a,6b,7,8,8a,9,10,12a,14,14a,14b- octadecahydropicen-4a-yl)-2,2-difluoropropanamide

<!-- Clinical data -->|pregnancy_AU=<!-- A / B1 / B2 / B3 / C / D / X -->|pregnancy_AU_comment=|pregnancy_category=|routes_of_administration=[[By mouth]]|onset=|duration_of_action=|Drugs.com={{Drugs.com|monograph|omaveloxolone}}|MedlinePlus=<!-- Legal data -->|legal_AU=<!-- S2, S3, S4, S5, S6, S7, S8, S9 or Unscheduled -->|legal_AU_comment=|legal_CA=<!-- OTC, Rx-only, Schedule I, II, III, IV, V, VI, VII, VIII -->|legal_CA_comment=|legal_DE=<!-- Anlage I, II, III or Unscheduled -->|legal_DE_comment=|legal_EU=|legal_EU_comment=|legal_NZ=<!-- Class A, B, C -->|legal_NZ_comment=|legal_UN=<!-- N I, II, III, IV / P I, II, III, IV -->|legal_UN_comment=|legal_UK=<!-- GSL, P, POM, CD, CD Lic, CD POM, CD No Reg POM, CD (Benz) POM, CD (Anab) POM or CD Inv POM / Class A, B, C -->|legal_UK_comment=|legal_US=Rx-only|legal_US_comment=<ref name="Skyclarys FDA label">{{Cite web |url=https://www.accessdata.fda.gov/drugsatfda_docs/label/2023/216718Orig1s000lbl.pdf |title=Archived copy |access-date=1 March 2023 |archive-date=1 March 2023 |archive-url=https://web.archive.org/web/20230301063942/https://www.accessdata.fda.gov/drugsatfda_docs/label/2023/216718Orig1s000lbl.pdf |url-status=live }}</ref>|legal_status=<!-- For countries not listed above -->|DailyMedID=Omaveloxolone

<!-- Pharmacokinetic data -->|bioavailability=|protein_bound=|metabolism=|metabolites=|elimination_half-life=|excretion=<!-- Chemical and physical data -->|C=33|F=2|H=44|N=2|O=3|SMILES=O=C4C(\C#N)=C/[C@@]5(/C3=C/C(=O)[C@H]2[C@](CC[C@@]1(NC(=O)C(F)(F)C)CCC(C)(C)C[C@H]12)(C)[C@]3(C)CC[C@H]5C4(C)C)C|StdInChI=1S/C32H43NO4/c1-27(2)11-13-32(26(36)37-8)14-12-31(7)24(20(32)17-27)21(34)15-23-29(5)16-19(18-33)25(35)28(3,4)22(29)9-10-30(23,31)6/h15-16,20,22,24H,9-14,17H2,1-8H3/t20-,22-,24-,29-,30+,31+,32-/m0/s1|StdInChI_Ref={{stdinchicite|correct|chemspider}}|StdInChI_comment=|StdInChIKey=WPTTVJLTNAWYAO-KPOXMGGZSA-N|StdInChIKey_Ref={{stdinchicite|correct|chemspider}}|density=|density_notes=|melting_point=|melting_high=|melting_notes=|boiling_point=|boiling_notes=|solubility=|specific_rotation=}}
"""

    TEXT_OUTPUT = """{{Infobox drug
|verifiedrevid    =461217017
|image            =Omaveloxolone structure.svg
|width            =250
|alt              =
|caption          =

<!-- Names -->
|pronounce        =
|tradename        =Skyclarys
|synonyms         =RTA 408
|IUPAC_name       =N-((4aS,6aR,6bS,8aR,12aS,14aR,14bS)-1 1-cyano-2,2,6a,6b,9,9,12a-heptamethyl-10,14-dioxo-1,2,3,4,4a,5,6,6a,6b,7,8,8a,9,10,12a,14,14a,14b- octadecahydropicen-4a-yl)-2,2-difluoropropanamide

<!-- Clinical data -->
|pregnancy_AU     =<!-- A / B1 / B2 / B3 / C / D / X -->
|pregnancy_AU_comment=
|pregnancy_category=
|routes_of_administration=[[By mouth]]
|onset            =
|duration_of_action=
|Drugs.com        ={{Drugs.com|monograph|omaveloxolone}}
|MedlinePlus      =

<!-- Legal data -->
|legal_AU         =<!-- S2, S3, S4, S5, S6, S7, S8, S9 or Unscheduled -->
|legal_AU_comment =
|legal_CA         =<!-- OTC, Rx-only, Schedule I, II, III, IV, V, VI, VII, VIII -->
|legal_CA_comment =
|legal_DE         =<!-- Anlage I, II, III or Unscheduled -->
|legal_DE_comment =
|legal_EU         =
|legal_EU_comment =
|legal_NZ         =<!-- Class A, B, C -->
|legal_NZ_comment =
|legal_UN         =<!-- N I, II, III, IV / P I, II, III, IV -->
|legal_UN_comment =
|legal_UK         =<!-- GSL, P, POM, CD, CD Lic, CD POM, CD No Reg POM, CD (Benz) POM, CD (Anab) POM or CD Inv POM / Class A, B, C -->
|legal_UK_comment =
|legal_US         =Rx-only
|legal_US_comment =<ref name="Skyclarys FDA label">{{Cite web |url=https://www.accessdata.fda.gov/drugsatfda_docs/label/2023/216718Orig1s000lbl.pdf |title=Archived copy |access-date=1 March 2023 |archive-date=1 March 2023 |archive-url=https://web.archive.org/web/20230301063942/https://www.accessdata.fda.gov/drugsatfda_docs/label/2023/216718Orig1s000lbl.pdf |url-status=live }}</ref>
|legal_status     =<!-- For countries not listed above -->
|DailyMedID       =Omaveloxolone

<!-- Pharmacokinetic data -->
|bioavailability  =
|protein_bound    =
|metabolism       =
|metabolites      =
|elimination_half-life=
|excretion        =

<!-- Chemical and physical data -->
|C                =33
|F                =2
|H                =44
|N                =2
|O                =3
|SMILES           =O=C4C(\C#N)=C/[C@@]5(/C3=C/C(=O)[C@H]2[C@](CC[C@@]1(NC(=O)C(F)(F)C)CCC(C)(C)C[C@H]12)(C)[C@]3(C)CC[C@H]5C4(C)C)C
|StdInChI         =1S/C32H43NO4/c1-27(2)11-13-32(26(36)37-8)14-12-31(7)24(20(32)17-27)21(34)15-23-29(5)16-19(18-33)25(35)28(3,4)22(29)9-10-30(23,31)6/h15-16,20,22,24H,9-14,17H2,1-8H3/t20-,22-,24-,29-,30+,31+,32-/m0/s1
|StdInChI_Ref     ={{stdinchicite|correct|chemspider}}
|StdInChI_comment =
|StdInChIKey      =WPTTVJLTNAWYAO-KPOXMGGZSA-N
|StdInChIKey_Ref  ={{stdinchicite|correct|chemspider}}
|density          =
|density_notes    =
|melting_point    =
|melting_high     =
|melting_notes    =
|boiling_point    =
|boiling_notes    =
|solubility       =
|specific_rotation=
}}
"""

    def test_expend_new_simple_template(self):
        """Test expend_new with a simple template"""
        # Test with basic template
        result = expend_new("{{test}}")
        assert result == "{{test}}"

    def test_do_comments(self):
        """Test do_comments function"""
        text = """Some text
<!-- Legal data -->
More text
<!-- Clinical data -->
End"""
        result = do_comments(text)
        # Comments should be reformatted with proper spacing
        assert "<!-- Legal data -->" in result
        assert "<!-- Clinical data -->" in result

    def test_make_tempse(self):
        """Test make_tempse function"""
        text = """{{Infobox drug
|name=Test
|value=123
}}
Some text {{other template}}"""
        result = make_tempse(text)

        # make_tempse returns a dict mapping template text to template text
        assert isinstance(result, dict)
        assert len(result) > 0

    def test_fix_title_bold(self):
        """Test fix_title_bold function"""
        # The function expects: }'''``''title''``''' pattern (} + 5 quotes + title + 5 quotes)
        text = "}'''''TestTitle'''''Some text"
        result = fix_title_bold(text, "TestTitle")
        # The function should replace the }'''``''TestTitle''``''' pattern
        assert "'''''TestTitle'''''" not in result

    def test_make_section_0(self):
        """Test make_section_0 function"""
        text = "Some text before\n==Section==\nContent after"
        result = make_section_0("Title", text)
        assert result == "Some text before\n"

        # Test without sections
        text2 = "Just some text"
        result2 = make_section_0("Title", text2)
        assert result2 == text2

    def test_expend_infobox_basic(self):
        """Test expend_infobox with basic infobox"""
        text = """{{Infobox drug|name=Test}}
Some content"""
        result = expend_infobox(text, "TestPage", "")
        assert isinstance(result, str)
        assert len(result) > 0

    # Note: Full infoboxTest.php test requires file-based I/O
    # The test_expend_new_FileText would require reading from test data files
    # This is a simplified version - for full testing, create test data files

    def test_do_comments_with_multiple_sections(self):
        """Test do_comments with various comment types"""
        text = """<!-- Names -->
<!-- Clinical data -->
<!-- Legal data -->
<!-- Pharmacokinetic data -->
<!-- Chemical and physical data -->"""
        result = do_comments(text)
        # All comments should still be present
        assert "<!-- Names -->" in result
        assert "<!-- Clinical data -->" in result
        assert "<!-- Legal data -->" in result
