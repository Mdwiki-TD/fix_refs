<?php

use Tests\MyFunctionTest;

use App\Fix\MdCat;

class mdCatTest extends MyFunctionTest
{
    public function testEquals(): void
    {
        $text = "[[Kategorija:Translated from MDWiki]]";

        $result = MdCat::addTranslatedFromMdwiki($text, "hr");

        $this->assertEquals($text, $result);
    }

    public function testSkipLangsIt(): void
    {
        $text = "This is a sample text";
        $result = MdCat::addTranslatedFromMdwiki($text, "it");
        $this->assertEquals($text, $result);
    }

    // Test case: Appends category when conditions are met
    public function testAppendsCategoryWhenConditionsMet(): void
    {
        $text = "This is a sample text";
        $result = MdCat::addTranslatedFromMdwiki($text, "fr");

        $expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n";
        $this->assertEquals($expected, $result);
    }

    // Test case: Doesn't append when category is empty
    public function testDoesNotAppendWhenCategoryEmpty(): void
    {
        $text = "This is a sample text";
        $result = MdCat::addTranslatedFromMdwiki($text, "fr");
        $expected = "This is a sample text\n[[Catégorie:Traduit de MDWiki]]\n";
        $this->assertEquals($expected, $result);
    }

    // Test case: Doesn't append when category already exists
    public function testDoesNotAppendWhenCategoryExists(): void
    {
        $category = "[[Category:Translated from MDWiki (de)]]";
        $text = "This is a sample text\n" . $category;

        $result = MdCat::addTranslatedFromMdwiki($text, "de");

        $this->assertEquals($text, $result);
    }

    // Test case: Doesn't append when fallback category exists
    public function testDoesNotAppendWhenFallbackCategoryExists(): void
    {
        $text = "This is a sample text\n[[Category:Translated from MDWiki]]";

        $result = MdCat::addTranslatedFromMdwiki($text, "es");

        $this->assertEquals($text, $result);
    }

    // Test case: Appends when similar but different category exists
    public function testAppendsWhenSimilarCategoryExists(): void
    {
        $text = "This is a sample text\n[[Category:Translated from MDWiki]]\n";

        $result = MdCat::addTranslatedFromMdwiki($text, "ja");

        $this->assertEquals($text, $result);
    }

    // Test case: Handles multiple newlines correctly
    public function testHandlesMultipleNewlines(): void
    {
        $text = "This is a sample text\n\n";

        $result = MdCat::addTranslatedFromMdwiki($text, "ru");

        $expected = "This is a sample text\n\n\n[[Категория:Статьи, переведённые с MDWiki]]\n";
        $this->assertEquals($expected, $result);
    }

    public function testLangs(): void
    {
        $langs = [
            "ur" => "زمرہ:ایم ڈی وکی سے ترجمہ شدہ",
        ];
        foreach ($langs as $lang => $cat) {
            $textNoCat = "This is a sample text\n\n";
            $expected = "{$textNoCat}\n[[{$cat}]]\n";
            $result = MdCat::addTranslatedFromMdwiki($textNoCat, $lang);
            $this->assertEqualCompare($expected, $textNoCat, $result);

            $textWithCat = "This is a sample text\n\n[[{$cat}]]\n";
            $result = MdCat::addTranslatedFromMdwiki($textWithCat, $lang);
            $this->assertEquals($textWithCat, $result);

            $textWithCat2 = "This is a sample text\n\n[[category:Translated_from_MDWiki]]\n";
            $result = MdCat::addTranslatedFromMdwiki($textWithCat2, $lang);
            $this->assertEquals($textWithCat2, $result);

        }
    }
}
