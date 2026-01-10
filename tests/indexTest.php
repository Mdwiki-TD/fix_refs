<?php

use FixRefs\Tests\MyFunctionTest;
use function WpRefs\FixPage\DoChangesToText1;

class indexTest extends MyFunctionTest
{

    private function fix_page_wrap(string $text, string $lang)
    {
        return DoChangesToText1("", "", $text, $lang, 00);
    }

    public function testPart1()
    {
        // $input     = file_get_contents(__DIR__ . "/texts/indexTest/1/input.txt");
        // $expected  = file_get_contents(__DIR__ . "/texts/indexTest/1/expected.txt");
        // --
        $input   = <<<WIKI
            [[Category:Translated from MDWiki]]
            Այս հիվանդության համար բուժում չկա<ref name="Shi2015" />։ Կյանքի վաղ շրջանում սովորաբար անհրաժեշտ է մշտական [[Ախտանշանային բուժում|աջակցող խնամք]] <ref name="NORD2006" />։ Բուժումները կարող են ներառել խոնավեցնող կրեմ, [[Հակաբիոտիկ|հակաբիոտիկներ]], էտրետինատ կամ ռետինոիդներ <ref name="NORD2006" /><ref name="Gli2017" />։ Հիվանդների մոտ կեսը մահանում է առաջին մի քանի ամիսների ընթացքում<ref name="Ah2014">{{Cite journal|last=Ahmed|first=H|last2=OToole|first2=E|date=2014|title=Recent advances in the genetics and management of Harlequin Ichthyosis|journal=Pediatric Dermatology|volume=31|issue=5|pages=539–46|doi=10.1111/pde.12383|pmid=24920541}}</ref>։ սակայն ռետինոիդային բուժումը կարող է մեծացնել գոյատևման հավանականությունը<ref name="Lay2005">{{Cite journal|last=Layton|first=Lt. Jason|date=May 2005|title=A Review of Harlequin Ichthyosis|url=http://connect.springerpub.com/lookup/doi/10.1891/0730-0832.24.3.17|journal=Neonatal Network|language=en|volume=24|issue=3|pages=17–23|doi=10.1891/0730-0832.24.3.17|issn=0730-0832|via=|access-date=2020-01-29|archive-date=2021-08-28|archive-url=https://web.archive.org/web/20210828101317/https://connect.springerpub.com/content/sgrnn/24/3/17|url-status=live}}</ref><ref name="Shi2015" />։ Կյանքի առաջին տարին ողջ մնացած երեխաները հաճախ ունենում են երկարատև խնդիրներ, ինչպիսիք են մաշկի կարմրությունը, հոդերի կոնտրակտուրաները և աճի դանդաղումը<ref name="Gli2017" />։ Այս վիճակը հանդիպում է 300,000 ծնունդներից մոտ 1-ի մոտ<ref name="Ah2014" />։ Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ <ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>
        WIKI;
        $expected   = <<<WIKI
            [[Category:Translated from MDWiki]]
            Այս հիվանդության համար բուժում չկա<ref name="Shi2015" />։ Կյանքի վաղ շրջանում սովորաբար անհրաժեշտ է մշտական [[Ախտանշանային բուժում|աջակցող խնամք]] <ref name="NORD2006" />։ Բուժումները կարող են ներառել խոնավեցնող կրեմ, [[Հակաբիոտիկ|հակաբիոտիկներ]], էտրետինատ կամ ռետինոիդներ <ref name="NORD2006" /><ref name="Gli2017" />։ Հիվանդների մոտ կեսը մահանում է առաջին մի քանի ամիսների ընթացքում<ref name="Ah2014">{{Cite journal|last=Ahmed|first=H|last2=OToole|first2=E|date=2014|title=Recent advances in the genetics and management of Harlequin Ichthyosis|journal=Pediatric Dermatology|volume=31|issue=5|pages=539–46|doi=10.1111/pde.12383|pmid=24920541}}</ref>։ սակայն ռետինոիդային բուժումը կարող է մեծացնել գոյատևման հավանականությունը<ref name="Lay2005">{{Cite journal|last=Layton|first=Lt. Jason|date=May 2005|title=A Review of Harlequin Ichthyosis|url=http://connect.springerpub.com/lookup/doi/10.1891/0730-0832.24.3.17|journal=Neonatal Network|language=en|volume=24|issue=3|pages=17–23|doi=10.1891/0730-0832.24.3.17|issn=0730-0832|via=|access-date=2020-01-29|archive-date=2021-08-28|archive-url=https://web.archive.org/web/20210828101317/https://connect.springerpub.com/content/sgrnn/24/3/17|url-status=live}}</ref><ref name="Shi2015" />։ Կյանքի առաջին տարին ողջ մնացած երեխաները հաճախ ունենում են երկարատև խնդիրներ, ինչպիսիք են մաշկի կարմրությունը, հոդերի կոնտրակտուրաները և աճի դանդաղումը<ref name="Gli2017" />։ Այս վիճակը հանդիպում է 300,000 ծնունդներից մոտ 1-ի մոտ<ref name="Ah2014" />։ Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ <ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>
        WIKI;
        $result = $this->fix_page_wrap($input, 'hy');
        // --
        // $output_file   = __DIR__ . "/texts/indexTest/1/output.txt";
        // file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testPart2()
    {
        $input     = file_get_contents(__DIR__ . "/texts/indexTest/2/input.txt");
        $expected  = file_get_contents(__DIR__ . "/texts/indexTest/2/expected.txt");
        // --
        $result = $this->fix_page_wrap($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/indexTest/2/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testPart3()
    {
        $input = '[[Category:Translated from MDWiki]] ռետինոիդներ։ <ref name="NORD2006" /><ref name="Gli2017" />';
        // ---
        $expected = '[[Category:Translated from MDWiki]] ռետինոիդներ<ref name="NORD2006" /><ref name="Gli2017" />։';
        $this->assertEqualCompare($expected, $input, $this->fix_page_wrap($input, 'hy'));
    }
    public function testPart4()
    {
        $input = '[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ <ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։';
        // ---
        $expected = '[[Category:Translated from MDWiki]] Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ<ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։';
        // ---
        $this->assertEqualCompare($expected, $input, $this->fix_page_wrap($input, 'hy'));
    }
}
