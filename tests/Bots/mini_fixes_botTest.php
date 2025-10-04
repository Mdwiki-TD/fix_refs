<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Bots\Mini\remove_space_before_ref_tags;
use function WpRefs\Bots\Mini\fix_sections_titles;
use function WpRefs\Bots\Mini\refs_tags_spaces;
use function WpRefs\Bots\Mini\fix_preffix;

class mini_fixes_botTest extends MyFunctionTest
{
    public function testSectionsTitles()
    {
        $texts = [
            [
                "lang" => "ru",
                "old" => "== Ссылки  ==\n====Ссылки====\n\n== Примечания 3 ==",
                "new" => "== Примечания ==\n==== Примечания ====\n\n== Примечания 3 =="
            ],
            [
                "lang" => "sw",
                "old" => "== Marejeleo 1 ==\n\n====Marejeleo====\n\n=== Marejeleo ===",
                "new" => "== Marejeleo 1 ==\n\n==== Marejeo ====\n\n=== Marejeo ==="
            ],
        ];

        foreach ($texts as $tab) {
            $lang = $tab['lang'];
            $text = $tab['old'];
            $new  = $tab['new'];
            // ---
            $new_text = fix_sections_titles($text, $lang);
            // ---
            $this->assertEqualCompare($new, $text, $new_text);
        }
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
}
