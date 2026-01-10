<?php


use FixRefs\Tests\MyFunctionTest;
use function WpRefs\RemoveSpace\remove_spaces_between_last_word_and_beginning_of_ref;

class remove_spaceTest extends MyFunctionTest
{

    public function testRemoveSpaceEnd1stFile()
    {
        $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/1/input.txt");
        $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/1/expected.txt");
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/remove_space_texts/1/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd2ndFile()
    {
        $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/2/input.txt");
        $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/2/expected.txt");
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $output_file   = __DIR__ . "/texts/remove_space_texts/2/output.txt";
        file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd3rdFile()
    {
        $input   = <<<WIKI
            Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] test0 <ref name="Os2018" /><ref>{{ref
            |<!!>
            }}</ref>։ test1 <ref name="Os2018" /><ref>{{ref
            |<!!>
            }}</ref>։
        WIKI;
        $expected   = <<<WIKI
            Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] test0 <ref name="Os2018" /><ref>{{ref
            |<!!>
            }}</ref>։ test1<ref name="Os2018" /><ref>{{ref
            |<!!>
            }}</ref>։
        WIKI;
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd4thFile()
    {
        $input   = <<<WIKI
            Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />
        WIKI;
        $expected   = <<<WIKI
            Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից ։ <ref name="Os2018" />
        WIKI;
        // --
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd5thFile()
    {
        // $input   = file_get_contents(__DIR__ . "/texts/remove_space_texts/5/input.txt");
        // $expected   = file_get_contents(__DIR__ . "/texts/remove_space_texts/5/expected.txt");
        // --
        $input   = <<<WIKI
            Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] կողմից <ref name="Os2018" /><ref>{{ref
            |<!!>
            }}</ref>։ կողմից <ref name="Os2018" /><ref>{{ref
            |zz
            }}</ref>։

            == test ==
        WIKI;
        $expected   = <<<WIKI
            Article text <ref>{{Citar web|Text|author=John|language=en}}</ref> [[Հիպոկրատ|Հիպոկրատի]] կողմից <ref name="Os2018" /><ref>{{ref
            |<!!>
            }}</ref>։ կողմից<ref name="Os2018" /><ref>{{ref
            |zz
            }}</ref>։

            == test ==
        WIKI;
        $result = remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy');
        // --
        // $output_file   = __DIR__ . "/texts/remove_space_texts/5/output.txt";
        // file_put_contents($output_file, $result);
        // --
        $this->assertEqualCompare($expected, $input, $result);
    }
    public function testRemoveSpaceEnd1()
    {
        $input = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից  <ref name="Os2018" />։';
        // ---
        $expected = 'Հետծննդյան հոգեբանական խանգարումը հանդիպում է 1000 ծննդաբերությունից 1-2-ի մոտ։ <ref name="Os2018" /><ref name="Li2018" /> Տարբեր [[Մշակույթ|մշակույթներում]] և [[Դասակարգային կառուցվածք|սոցիալական դասերում]] գները նման են թվում։ <ref name="Luc2021" /> Ավելի հաճախ այն հանդիպում է հայտնի կամ նոր սկսվող երկբևեռ խանգարման համատեքստում, որը հայտնի է որպես հետծննդյան երկբևեռ խանգարում : <ref name="Luc2021" /> Այս վիճակը նկարագրվել է դեռևս մ.թ.ա. 400 թվականից [[Հիպոկրատ|Հիպոկրատի]] կողմից<ref name="Os2018" />։';
        $this->assertEqualCompare($expected, $input, remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy'));
    }
    public function testPart3()
    {
        $input = 'Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ  <ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։';
        // ---
        $expected = 'Այն առաջին անգամ արձանագրվել է 1750 թվականին Ամերիկայում գտնվող քահանա Օլիվեր Հարթի օրագրային գրառման մեջ<ref name="Sc2011">{{Cite book|last=Schachner|first=Lawrence A.|last2=Hansen|first2=Ronald C.|title=Pediatric Dermatology E-Book|date=2011|publisher=Elsevier Health Sciences|isbn=978-0723436652|page=598|url=https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|language=en|url-status=live|archive-url=https://web.archive.org/web/20171105195802/https://books.google.com/books?id=tAlGLYplkacC&pg=PA598|archive-date=November 5, 2017}}</ref>։';
        // ---
        $this->assertEqualCompare($expected, $input, remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy'));
    }
    public function testPart4()
    {
        $input = 'Գոյություն ունի լյարդի ճարպային հիվանդության երկու տեսակ՝ ոչ ալկոհոլային ճարպային լյարդի հիվանդություն (ՈԱՃՀ) և ալկոհոլային լյարդի հիվանդություն <ref name="NIH2016" />։ ՈԱՃՀՀ-ն բաղկացած է պարզ ճարպային լյարդից և ոչ ալկոհոլային ստեատոհեպատիտից (ՈԱՃՀ): <ref name="AFP2013">{{Cite journal|last=Iser|first=D|last2=Ryan|first2=M|title=Fatty liver disease—a practical guide for GPs.|journal=Australian Family Physician|date=July 2013|volume=42|issue=7|pages=444–7|pmid=23826593}}</ref><ref name="NIH2016" /> Հիմնական ռիսկերից են [[Էթիլ սպիրտ|ալկոհոլը]], [[Տիպ 2 շաքարային դիաբետ|2-րդ տիպի շաքարախտը]] և [[Ճարպակալում|ճարպակալումը]] <ref name="Ant2019" /><ref name="NIH2016" />։ Այլ ռիսկի գործոններից են որոշակի դեղամիջոցները, ինչպիսիք են [[Գլյուկոկորտիկոիդներ|գլյուկոկորտիկոիդները]] և [[Հեպատիտ C|հեպատիտ C-ն]] : <ref name="NIH2016" /> Անհասկանալի է, թե ինչու են ոչ ալկոհոլային ճարպային լյարդի հիվանդություն ունեցող որոշ մարդիկ զարգացնում պարզ ճարպային լյարդ, իսկ մյուսները՝ ոչ ալկոհոլային հեպատիտ: <ref name="NIH2016" /> Ախտորոշումը հիմնված է [[Անամնեզ|բժշկական պատմության]] վրա, որը հաստատվում է արյան անալիզներով, բժշկական պատկերագրական հետազոտություններով և երբեմն լյարդի բիոպսիայով <ref name="NIH2016" />։';
        // ---
        $expected = 'Գոյություն ունի լյարդի ճարպային հիվանդության երկու տեսակ՝ ոչ ալկոհոլային ճարպային լյարդի հիվանդություն (ՈԱՃՀ) և ալկոհոլային լյարդի հիվանդություն <ref name="NIH2016" />։ ՈԱՃՀՀ-ն բաղկացած է պարզ ճարպային լյարդից և ոչ ալկոհոլային ստեատոհեպատիտից (ՈԱՃՀ): <ref name="AFP2013">{{Cite journal|last=Iser|first=D|last2=Ryan|first2=M|title=Fatty liver disease—a practical guide for GPs.|journal=Australian Family Physician|date=July 2013|volume=42|issue=7|pages=444–7|pmid=23826593}}</ref><ref name="NIH2016" /> Հիմնական ռիսկերից են [[Էթիլ սպիրտ|ալկոհոլը]], [[Տիպ 2 շաքարային դիաբետ|2-րդ տիպի շաքարախտը]] և [[Ճարպակալում|ճարպակալումը]] <ref name="Ant2019" /><ref name="NIH2016" />։ Այլ ռիսկի գործոններից են որոշակի դեղամիջոցները, ինչպիսիք են [[Գլյուկոկորտիկոիդներ|գլյուկոկորտիկոիդները]] և [[Հեպատիտ C|հեպատիտ C-ն]] : <ref name="NIH2016" /> Անհասկանալի է, թե ինչու են ոչ ալկոհոլային ճարպային լյարդի հիվանդություն ունեցող որոշ մարդիկ զարգացնում պարզ ճարպային լյարդ, իսկ մյուսները՝ ոչ ալկոհոլային հեպատիտ: <ref name="NIH2016" /> Ախտորոշումը հիմնված է [[Անամնեզ|բժշկական պատմության]] վրա, որը հաստատվում է արյան անալիզներով, բժշկական պատկերագրական հետազոտություններով և երբեմն լյարդի բիոպսիայով<ref name="NIH2016" />։';
        // ---
        $this->assertEqualCompare($expected, $input, remove_spaces_between_last_word_and_beginning_of_ref($input, 'hy'));
    }
}
