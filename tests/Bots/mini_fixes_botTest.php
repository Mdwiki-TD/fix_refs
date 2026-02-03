<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Bots\Mini\remove_space_before_ref_tags;
use function WpRefs\Bots\Mini\fix_sections_titles;
use function WpRefs\Bots\Mini\refs_tags_spaces;
use function WpRefs\Bots\Mini\fix_preffix;
use function WpRefs\Bots\Mini\remove_template_rtt_links;

class mini_fixes_botTest extends MyFunctionTest
{

    public function testSectionsTitlesRu()
    {
        $texts = [
            [
                "old" => "== Ссылки  ==\n====Ссылки====\n\n== Примечания 3 ==",
                "new" => "== Примечания ==\n==== Примечания ====\n\n== Примечания 3 =="
            ],
            [
                "old" => "== Ссылки  ==\n====Ссылки====\n\n== Примечания 3 ==",
                "new" => "== Примечания ==\n==== Примечания ====\n\n== Примечания 3 =="
            ]
        ];

        foreach ($texts as $tab) {
            $text = $tab['old'];
            $new  = $tab['new'];
            // ---
            $new_text = fix_sections_titles($text, "ru");
            // ---
            $this->assertEqualCompare($new, $text, $new_text);
        }
    }

    public function testSectionsTitleshr()
    {
        $texts = [
            [
                "old" => "== Reference  ==",
                "new" => "== Izvori =="
            ],
            [
                "old" => "== Reference  ==\n\n====References====\n\n== References 3 ==",
                "new" => "== Izvori ==\n\n==== Izvori ====\n\n== References 3 =="
            ]
        ];

        foreach ($texts as $tab) {
            $text = $tab['old'];
            $new  = $tab['new'];
            // ---
            $new_text = fix_sections_titles($text, "hr");
            // ---
            $this->assertEqualCompare($new, $text, $new_text);
        }
    }
    public function testSectionsTitlesSw()
    {
        $text = "== Marejeleo 1 ==\n\n====Marejeleo====\n\n=== Marejeleo ===";
        $new  = "== Marejeleo 1 ==\n\n==== Marejeo ====\n\n=== Marejeo ===";
        // ---
        $new_text = fix_sections_titles($text, "sw");
        // ---
        $this->assertEqualCompare($new, $text, $new_text);
    }

    // اختبارات دالة remove_space_before_ref_tags
    public function testRemoveSpaceBeforeRefTags()
    {
        $tests = [
            // حالة: مسافة قبل <ref> بعد نقطة
            [
                "text" => "جملة. <ref>مرجع</ref>",
                "lang" => "ar",
                "expected" => "جملة.<ref>مرجع</ref>"
            ],
            // حالة: مسافة قبل <ref> بعد فاصلة
            [
                "text" => "جملة, <ref>مرجع</ref>",
                "lang" => "ar",
                "expected" => "جملة,<ref>مرجع</ref>"
            ],
            // حالة: مسافات متعددة
            [
                "text" => "جملة.   <ref>مرجع</ref>",
                "lang" => "sw",
                "expected" => "جملة.<ref>مرجع</ref>"
            ],
            // حالة: بدون مسافات (لا يجب أن يتغير النص)
            [
                "text" => "جملة.<ref>مرجع</ref>",
                "lang" => "bn",
                "expected" => "جملة.<ref>مرجع</ref>"
            ],
            // حالة: علامات ترقيم مختلفة
            [
                "text" => "جملة। <ref>مرجع</ref>",
                "lang" => "ar",
                "expected" => "جملة।<ref>مرجع</ref>"
            ],
            // حالة: نص بدون مراجع
            [
                "text" => "نص عادي بدون مراجع",
                "lang" => "ar",
                "expected" => "نص عادي بدون مراجع"
            ]
        ];

        foreach ($tests as $test) {
            $result = remove_space_before_ref_tags($test['text'], $test['lang']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    // اختبارات دالة refs_tags_spaces
    public function testRefsTagsSpaces()
    {
        $tests = [
            // حالة: مسافة بين </ref> و <ref>
            [
                "text" => "نص</ref> <ref>مرجع</ref>",
                "expected" => "نص</ref><ref>مرجع</ref>"
            ],
            // حالة: مسافات متعددة
            [
                "text" => "نص</ref>   <ref>مرجع</ref>",
                "expected" => "نص</ref><ref>مرجع</ref>"
            ],
            // حالة: وسم مغلق ذاتيًا
            [
                "text" => 'نص<ref name="test"/> <ref>مرجع</ref>',
                "expected" => 'نص<ref name="test"/><ref>مرجع</ref>'
            ],
            // حالة: مسافة بعد وسم الإغلاق
            [
                "text" => "نص> <ref>مرجع</ref>",
                "expected" => "نص><ref>مرجع</ref>"
            ],
            // حالة: نص بدون مراجع متجاورة
            [
                "text" => "نص عادي <ref>مرجع</ref> ونص آخر",
                "expected" => "نص عادي <ref>مرجع</ref> ونص آخر"
            ],
            // حالة: مراجع متعددة متجاورة
            [
                "text" => "</ref> <ref name=A/> <ref name=B>",
                "expected" => "</ref><ref name=A/><ref name=B>"
            ]
        ];

        foreach ($tests as $test) {
            $result = refs_tags_spaces($test['text']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    // اختبارات دالة fix_preffix
    public function testFixPreffix()
    {
        $tests = [
            // حالة: رابط باللغة الإنجليزية
            [
                "text" => "[[:en:Example|مثال]]",
                "lang" => "ar",
                "expected" => "[[Example|مثال]]"
            ],
            // حالة: رابط بنفس لغة النص
            [
                "text" => "[[:ar:مثال|نص]]",
                "lang" => "ar",
                "expected" => "[[مثال|نص]]"
            ],
            // حالة: رابط بلغة أخرى (لا يجب أن يتغير)
            [
                "text" => "[[:fr:Exemple|نص]]",
                "lang" => "ar",
                "expected" => "[[:fr:Exemple|نص]]"
            ],
            // حالة: روابط متعددة
            [
                "text" => "[[:en:Page1|نص1]] و [[:ar:صفحة|نص2]]",
                "lang" => "ar",
                "expected" => "[[Page1|نص1]] و [[صفحة|نص2]]"
            ],
            // حالة: رابط بدون نص بديل
            [
                "text" => "[[:en:Example]]",
                "lang" => "ar",
                "expected" => "[[Example]]"
            ],
            // حالة: نص بدون روابط
            [
                "text" => "نص عادي بدون روابط",
                "lang" => "ar",
                "expected" => "نص عادي بدون روابط"
            ]
        ];

        foreach ($tests as $test) {
            $result = fix_preffix($test['text'], $test['lang']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }

    // Test for remove_template_rtt_links
    public function testRemoveTemplateRttLinks()
    {
        $tests = [
            // Basic case: Template:RTT with Sinhala text
            [
                "text" => "[[Template:RTT|සැකිල්ල:RTT]]",
                "expected" => ""
            ],
            // Case: Template:RTT with different second part
            [
                "text" => "[[Template:RTT|xyz]]",
                "expected" => ""
            ],
            // Case: Template:RTT within text
            [
                "text" => "Some text [[Template:RTT|සැකිල්ල:RTT]] more text",
                "expected" => "Some text  more text"
            ],
            // Case: Multiple Template:RTT links
            [
                "text" => "[[Template:RTT|A]] and [[Template:RTT|B]]",
                "expected" => " and "
            ],
            // Case: Template:RTT with special characters
            [
                "text" => "[[Template:RTT|සැකිල්ල:RTT විස්තරය]]",
                "expected" => ""
            ],
            // Case: Text without Template:RTT (should not change)
            [
                "text" => "Normal text without template",
                "expected" => "Normal text without template"
            ],
            // Case: Template:RTT with spaces
            [
                "text" => "[[Template:RTT|Text with spaces]]",
                "expected" => ""
            ]
        ];

        foreach ($tests as $test) {
            $result = remove_template_rtt_links($test['text']);
            $this->assertEqualCompare($test['expected'], $test['text'], $result);
        }
    }
}
