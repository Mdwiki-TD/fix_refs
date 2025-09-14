<?php

use App\Tests\MyFunctionTest;
use function WpRefs\Bots\es_months\fix_es_months_in_refs;
use function WpRefs\ES\fix_es;
use function WpRefs\ES\fix_temps;

function fix_temps_wrap($text)
{
    // ---
    $result = fix_temps($text);
    $result = fix_es_months_in_refs($result);
    $result = preg_replace("/\s*=\s*/", "=", $result);
    // ---
    return $result;
}

class esTest extends MyFunctionTest
{
    public function test_fix_temps_and_months_1()
    {
        $old = "{{cite journal|vauthors=Dowd SE|title=Confirmation |journal=Applied |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10|bibcode=1|url-status=dead}}";
        $new = "{{cita publicación|vauthors=Dowd SE|título=Confirmation|journal=Applied|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10|bibcode=1}}";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function test_fix_temps_and_months_2()
    {
        $old = "hi!. <ref name=Doed1998>{{cite journal|vauthors=Dowd SE|title=Confirmation |journal=Applied |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10|bibcode=1}}</ref> dodo";
        $new = "hi!. <ref name=Doed1998>{{cita publicación|vauthors=Dowd SE|título=Confirmation|journal=Applied|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10|bibcode=1}}</ref> dodo";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function test_fix_temps_and_months_3()
    {
        $old = "hi!. <ref name=Doed1998>{{cite journal |vauthors=Dowd SE, Gerba CP, Pepper IL |title=Confirmation of the Human-Pathogenic Microsporidia Enterocytozoon bieneusi, Encephalitozoon intestinalis, and Vittaforma corneae in Water |journal=Applied and Environmental Microbiology |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10.1128/AEM.64.9.3332-3335.1998|bibcode=1998ApEnM..64.3332D }}</ref>yemen";
        $new = "hi!. <ref name=Doed1998>{{cita publicación|vauthors=Dowd SE, Gerba CP, Pepper IL|título=Confirmation of the Human-Pathogenic Microsporidia Enterocytozoon bieneusi, Encephalitozoon intestinalis, and Vittaforma corneae in Water|journal=Applied and Environmental Microbiology|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10.1128/AEM.64.9.3332-3335.1998|bibcode=1998ApEnM..64.3332D}}</ref>yemen";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function test_fix_temps_and_months_4()
    {
        $old = "<ref>{{cite journal|vauthors=Dowd SE|title=Confirmation|url-status=dead}} {{cite web |title=CDC - DPDx - Microsporidiosis |url=https://www.cdc.gov/dpdx/microsporidiosis/index.html |website=www.cdc.gov |accessdate=11 December 2024 |language=en-us |date=29 May 2019}}</ref>";
        $new = "<ref>{{cita publicación|vauthors=Dowd SE|título=Confirmation}} {{cita web|título=CDC - DPDx - Microsporidiosis|url=https://www.cdc.gov/dpdx/microsporidiosis/index.html|sitioweb=www.cdc.gov|fechaacceso=11 de diciembre de 2024|idioma=en-us|fecha=29 de mayo de 2019}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function test_fix_temps_and_months_5()
    {
        $old = "<ref>{{cite journal |date=July 25, 1975}} {{cite journal |date=May 25, 1975}}</ref>";
        $new = "<ref>{{cita publicación|fecha=25 de julio de 1975}} {{cita publicación|fecha=25 de mayo de 1975}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function test_fix_temps_and_months_6()
    {
        $old = "<ref>{{cite web |access-date=10 January 2022 |archive-date=9 January 2021}} {{Webarchive|url=https://web.archive.org/web/20221014134136/https://books.google.ca/books?id=4gznEkPjNJMC&pg=PA640|date=10 December 2022}}</ref>";
        $new = "<ref>{{cita web|fechaacceso=10 de enero de 2022|fechaarchivo=9 de enero de 2021}} {{Webarchive|url=https://web.archive.org/web/20221014134136/https://books.google.ca/books?id=4gznEkPjNJMC&pg=PA640|date=10 de diciembre de 2022}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function test_fix_temps_1()
    {
        $text_input   = file_get_contents(__DIR__ . "/texts/fix_es_1_input.txt");
        $text_output  = file_get_contents(__DIR__ . "/texts/fix_es_1_output.txt");
        // --
        $result = fix_temps($text_input);
        // --
        $this->assertEquals($text_output, $result);
    }

    public function test_fix_es_1()
    {
        $text_input   = file_get_contents(__DIR__ . "/texts/fix_es_1_output.txt");
        $text_output  = file_get_contents(__DIR__ . "/texts/fix_es_2_output.txt");
        // --
        $result = fix_es($text_input);
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $text_output = preg_replace("/\r\n/", "\n", $text_output);
        // --
        $this->assertEquals($text_output, $result);
    }
}
