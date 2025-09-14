<?php



use FixRefs\Tests\MyFunctionTest;

use function WpRefs\MdCat\add_Translated_from_MDWiki;

class md_catTest extends MyFunctionTest
{
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
}
