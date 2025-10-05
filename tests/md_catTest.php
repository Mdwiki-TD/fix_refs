<?php

use FixRefs\Tests\MyFunctionTest;

use function WpRefs\MdCat\add_Translated_from_MDWiki;

class md_catTest extends MyFunctionTest
{
    public function testEquals()
    {
        $text = "[[Kategorija:Translated from MDWiki]]";

        $result = add_Translated_from_MDWiki($text, "hr");

        $this->assertEquals($text, $result);
    }

    public function testSkipLangsIt()
    {
        $text = "This is a sample text";
        $result = add_Translated_from_MDWiki($text, "it");
        $this->assertEquals($text, $result);
    }

    // Test case: Appends category when conditions are met
    public function testAppendsCategoryWhenConditionsMet()
    {
        $text = "This is a sample text";
        $result = add_Translated_from_MDWiki($text, "fr");

        $expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n";
        $this->assertEquals($expected, $result);
    }

    // Test case: Doesn't append when category is empty
    public function testDoesNotAppendWhenCategoryEmpty()
    {
        $text = "This is a sample text";
        $result = add_Translated_from_MDWiki($text, "fr");
        $expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n";
        $this->assertEquals($expected, $result);
    }

    // Test case: Doesn't append when category already exists
    public function testDoesNotAppendWhenCategoryExists()
    {
        $category = "[[Category:Translated from MDWiki (de)]]";
        $text = "This is a sample text\n" . $category;

        $result = add_Translated_from_MDWiki($text, "de");

        $this->assertEquals($text, $result);
    }

    // Test case: Doesn't append when fallback category exists
    public function testDoesNotAppendWhenFallbackCategoryExists()
    {
        $text = "This is a sample text\n[[Category:Translated from MDWiki]]";

        $result = add_Translated_from_MDWiki($text, "es");

        $this->assertEquals($text, $result);
    }

    // Test case: Appends when similar but different category exists
    public function testAppendsWhenSimilarCategoryExists()
    {
        $text = "This is a sample text\n[[Category:Translated from MDWiki]]\n";

        $result = add_Translated_from_MDWiki($text, "ja");

        $this->assertEquals($text, $result);
    }

    // Test case: Handles multiple newlines correctly
    public function testHandlesMultipleNewlines()
    {
        $text = "This is a sample text\n\n";

        $result = add_Translated_from_MDWiki($text, "ru");

        $expected = "This is a sample text\n\n\n[[Категория:Статьи, переведённые с MDWiki]]\n";
        $this->assertEquals($expected, $result);
    }

    public function testLangs()
    {
        $langs = [
            "ur" => "زمرہ:ایم ڈی وکی سے ترجمہ شدہ",
        ];
        foreach ($langs as $lang => $cat) {
            $text_no_cat = "This is a sample text\n\n";
            $expected = "{$text_no_cat}\n[[{$cat}]]\n";
            $result = add_Translated_from_MDWiki($text_no_cat, $lang);
            $this->assertEqualCompare($expected, $text_no_cat, $result);
            // ---
            $text_with_cat = "This is a sample text\n\n[[{$cat}]]\n";
            $result = add_Translated_from_MDWiki($text_with_cat, $lang);
            $this->assertEquals($text_with_cat, $result);
            // ---
            $text_with_cat2 = "This is a sample text\n\n[[category:Translated_from_MDWiki]]\n";
            $result = add_Translated_from_MDWiki($text_with_cat2, $lang);
            $this->assertEquals($text_with_cat2, $result);
            // ---
        }
    }
}
