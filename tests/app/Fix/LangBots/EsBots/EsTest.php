<?php

use Tests\MyFunctionTest;
use App\Fix\LangBots\EsBots\EsMonths;
use App\Fix\LangBots\EsBots\Es;


function fix_temps_wrap(string $text): string
{
    $result = Es::fix_temps($text);
    $result = EsMonths::fix_es_months_in_refs($result);
    $result = (string)preg_replace("/\s*=\s*/", "=", $result);

    return $result;
}

class esTest extends MyFunctionTest
{
    public function testFixTempsAndMonths_1(): void  {
        $old = "{{cite journal|vauthors=Dowd SE|title=Confirmation |journal=Applied |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10|bibcode=1|url-status=dead}}";
        $new = "{{cita publicación|vauthors=Dowd SE|título=Confirmation|journal=Applied|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10|bibcode=1}}";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function testFixTempsAndMonths_2(): void  {
        $old = "hi!. <ref name=Doed1998>{{cite journal|vauthors=Dowd SE|title=Confirmation |journal=Applied |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10|bibcode=1}}</ref> dodo";
        $new = "hi!. <ref name=Doed1998>{{cita publicación|vauthors=Dowd SE|título=Confirmation|journal=Applied|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10|bibcode=1}}</ref> dodo";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function testFixTempsAndMonths_3(): void  {
        $old = "hi!. <ref name=Doed1998>{{cite journal |vauthors=Dowd SE, Gerba CP, Pepper IL |title=Confirmation of the Human-Pathogenic Microsporidia Enterocytozoon bieneusi, Encephalitozoon intestinalis, and Vittaforma corneae in Water |journal=Applied and Environmental Microbiology |volume=64 |issue=9 |pages=3332–5 |year=1998 |pmid=9726879 |pmc=106729 |doi=10.1128/AEM.64.9.3332-3335.1998|bibcode=1998ApEnM..64.3332D }}</ref>yemen";
        $new = "hi!. <ref name=Doed1998>{{cita publicación|vauthors=Dowd SE, Gerba CP, Pepper IL|título=Confirmation of the Human-Pathogenic Microsporidia Enterocytozoon bieneusi, Encephalitozoon intestinalis, and Vittaforma corneae in Water|journal=Applied and Environmental Microbiology|volumen=64|issue=9|páginas=3332–5|año=1998|pmid=9726879|pmc=106729|doi=10.1128/AEM.64.9.3332-3335.1998|bibcode=1998ApEnM..64.3332D}}</ref>yemen";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function testFixTempsAndMonths_4(): void  {
        $old = "<ref>{{cite journal|vauthors=Dowd SE|title=Confirmation|url-status=dead}} {{cite web |title=CDC - DPDx - Microsporidiosis |url=https://www.cdc.gov/dpdx/microsporidiosis/index.html |website=www.cdc.gov |accessdate=11 December 2024 |language=en-us |date=29 May 2019}}</ref>";
        $new = "<ref>{{cita publicación|vauthors=Dowd SE|título=Confirmation}} {{cita web|título=CDC - DPDx - Microsporidiosis|url=https://www.cdc.gov/dpdx/microsporidiosis/index.html|sitioweb=www.cdc.gov|fechaacceso=11 de diciembre de 2024|idioma=en-us|fecha=29 de mayo de 2019}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function testFixTempsAndMonths_5(): void  {
        $old = "<ref>{{cite journal |date=July 25, 1975}} {{cite journal |date=May 25, 1975}}</ref>";
        $new = "<ref>{{cita publicación|fecha=25 de julio de 1975}} {{cita publicación|fecha=25 de mayo de 1975}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function testFixTempsAndMonths_6(): void  {
        $old = "<ref>{{cite web |access-date=10 January 2022 |archive-date=9 January 2021}} {{Webarchive|url=https://web.archive.org/web/20221014134136/https://books.google.ca/books?id=4gznEkPjNJMC&pg=PA640|date=10 December 2022}}</ref>";
        $new = "<ref>{{cita web|fechaacceso=10 de enero de 2022|fechaarchivo=9 de enero de 2021}} {{Webarchive|url=https://web.archive.org/web/20221014134136/https://books.google.ca/books?id=4gznEkPjNJMC&pg=PA640|date=10 de diciembre de 2022}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function testFixTemps_1(): void  {
        $textInput   = (string)file_get_contents(__DIR__ . "/fixtures/3/input.txt");
        $expected  = (string)file_get_contents(__DIR__ . "/fixtures/3/expected.txt");
        // --
        $result = Es::fix_temps($textInput);
        // --
        $this->assertEquals($expected, $result);
    }

    public function testFixEs_1(): void  {
        $textInput   = (string)file_get_contents(__DIR__ . "/fixtures/2/input.txt");
        $expected  = (string)file_get_contents(__DIR__ . "/fixtures/2/expected.txt");
        // --
        $result = Es::fix_es($textInput);
        // --
        $fixedFile = __DIR__ . "/fixtures/2/output.txt";
        file_put_contents($fixedFile, $result);
        // --
        $result = (string)preg_replace("/\r\n/", "\n", $result);
        $expected = (string)preg_replace("/\r\n/", "\n", $expected);
        // --
        $this->assertEquals($expected, $result);
    }

    public function testFixTempsAndMonthsWithMonth(): void  {
        $old = "<ref>{{cite journal|title=Study|journal=Nature|date=January 2005|pages=20–25}}</ref>";
        $new = "<ref>{{cita publicación|título=Study|journal=Nature|fecha=enero de 2005|páginas=20–25}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function testFixTempsAndMonthsDifferentTemplate(): void  {
        $old = "{{cite book|title=Medical Book|year=2010|pages=100–105}}";
        $new = "{{cita libro|título=Medical Book|año=2010|páginas=100–105}}";
        $this->assertEquals($new, fix_temps_wrap($old));
    }

    public function testFixTempsAndMonthsNoChange(): void  {
        $old = "Texto sin plantillas ni meses en inglés.";
        $new = "Texto sin plantillas ni meses en inglés.";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function testFixTempsInsideRef(): void  {
        $old = "<ref name=OI2018>{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}}</ref>";
        $new = "<ref name=OI2018>{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function testFixTempsWithWebarchiveTemp(): void  {
        $old = "<ref name=OI2018>{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}} {{Webarchive|url=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|date=19 de diciembre de 2019}}</ref>";
        $new = "<ref name=OI2018>{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}} {{Webarchive|url=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|date=19 de diciembre de 2019}}</ref>";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
    public function testFixTempsAlone(): void  {
        $old = "{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}}";
        $new = "{{cita web|título=Shoulder Trauma (Fractures and Dislocations)|url=https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/|sitioweb=OrthoInfo - AAOS|fechaacceso=7 de noviembre de 2018|fechaarchivo=19 de diciembre de 2019|urlarchivo=https://web.archive.org/web/20191219132225/https://orthoinfo.aaos.org/en/diseases--conditions/shoulder-trauma-fractures-and-dislocations/}}";
        $this->assertEquals($new, fix_temps_wrap($old));
    }
}
