<?php

use FixRefs\Tests\MyFunctionTest;

class TestPhpMdTest extends MyFunctionTest
{
    private string $testPhpMdFile = __DIR__ . '/../test.php.md';
    private string $testPhpMdContent;

    protected function setUp(): void
    {
        $this->testPhpMdContent = file_get_contents($this->testPhpMdFile);
    }

    /**
     * Test that test.php.md file exists
     */
    public function testTestPhpMdFileExists()
    {
        $this->assertFileExists($this->testPhpMdFile, 'test.php.md file should exist');
    }

    /**
     * Test that test.php.md is readable and not empty
     */
    public function testTestPhpMdIsNotEmpty()
    {
        $this->assertNotEmpty($this->testPhpMdContent, 'test.php.md should not be empty');
        $this->assertGreaterThan(500, strlen($this->testPhpMdContent),
            'test.php.md should have substantial content');
    }

    /**
     * Test that test.php.md contains reference tags
     */
    public function testTestPhpMdContainsReferenceTags()
    {
        $this->assertStringContainsString('<ref', $this->testPhpMdContent,
            'test.php.md should contain <ref tags');
        $this->assertStringContainsString('</ref>', $this->testPhpMdContent,
            'test.php.md should contain closing </ref> tags');
    }

    /**
     * Test that test.php.md contains named references
     */
    public function testTestPhpMdContainsNamedReferences()
    {
        $this->assertStringContainsString('name="NHS2023"', $this->testPhpMdContent,
            'test.php.md should contain named reference NHS2023');
        $this->assertStringContainsString('name="Jack2021"', $this->testPhpMdContent,
            'test.php.md should contain named reference Jack2021');
        $this->assertStringContainsString('name="NHS2023Sym"', $this->testPhpMdContent,
            'test.php.md should contain named reference NHS2023Sym');
    }

    /**
     * Test that test.php.md contains self-closing named references
     */
    public function testTestPhpMdContainsSelfClosingNamedReferences()
    {
        $this->assertStringContainsString('<ref name="NHS2023" />', $this->testPhpMdContent,
            'test.php.md should contain self-closing named references');
    }

    /**
     * Test that test.php.md contains Cite web templates
     */
    public function testTestPhpMdContainsCiteWebTemplates()
    {
        $this->assertStringContainsString('{{Cite web', $this->testPhpMdContent,
            'test.php.md should contain {{Cite web templates');
    }

    /**
     * Test that test.php.md contains Cite journal templates
     */
    public function testTestPhpMdContainsCiteJournalTemplates()
    {
        $this->assertStringContainsString('{{Cite journal', $this->testPhpMdContent,
            'test.php.md should contain {{Cite journal templates');
    }

    /**
     * Test that test.php.md contains Infobox template
     */
    public function testTestPhpMdContainsInfoboxTemplate()
    {
        $this->assertStringContainsString('{{Infobox medical condition', $this->testPhpMdContent,
            'test.php.md should contain {{Infobox medical condition template');
    }

    /**
     * Test that test.php.md contains Webarchive templates
     */
    public function testTestPhpMdContainsWebarchiveTemplates()
    {
        $this->assertStringContainsString('{{Webarchive', $this->testPhpMdContent,
            'test.php.md should contain {{Webarchive templates');
    }

    /**
     * Test that test.php.md contains proper template closing syntax
     */
    public function testTestPhpMdContainsTemplateClosingSyntax()
    {
        $openCount = substr_count($this->testPhpMdContent, '{{');
        $closeCount = substr_count($this->testPhpMdContent, '}}');

        $this->assertEquals($openCount, $closeCount,
            'test.php.md should have balanced template braces {{ and }}');
    }

    /**
     * Test that test.php.md contains URLs in citations
     */
    public function testTestPhpMdContainsUrlsInCitations()
    {
        $this->assertStringContainsString('url=https://', $this->testPhpMdContent,
            'test.php.md should contain URLs in citations');
        $this->assertStringContainsString('www.nhs.uk', $this->testPhpMdContent,
            'test.php.md should contain NHS UK website references');
    }

    /**
     * Test that test.php.md contains archive URLs
     */
    public function testTestPhpMdContainsArchiveUrls()
    {
        $this->assertStringContainsString('archive-url=', $this->testPhpMdContent,
            'test.php.md should contain archive-url parameters');
        $this->assertStringContainsString('web.archive.org', $this->testPhpMdContent,
            'test.php.md should contain Wayback Machine archive URLs');
    }

    /**
     * Test that test.php.md contains archive dates
     */
    public function testTestPhpMdContainsArchiveDates()
    {
        $this->assertStringContainsString('archive-date=', $this->testPhpMdContent,
            'test.php.md should contain archive-date parameters');
    }

    /**
     * Test that test.php.md contains access dates
     */
    public function testTestPhpMdContainsAccessDates()
    {
        $this->assertStringContainsString('access-date=', $this->testPhpMdContent,
            'test.php.md should contain access-date parameters');
    }

    /**
     * Test that test.php.md contains DOI identifiers
     */
    public function testTestPhpMdContainsDoiIdentifiers()
    {
        $this->assertStringContainsString('doi=', $this->testPhpMdContent,
            'test.php.md should contain DOI identifiers');
        $this->assertStringContainsString('10.1016/', $this->testPhpMdContent,
            'test.php.md should contain valid DOI format');
    }

    /**
     * Test that test.php.md contains PMID identifiers
     */
    public function testTestPhpMdContainsPmidIdentifiers()
    {
        $this->assertStringContainsString('pmid=', $this->testPhpMdContent,
            'test.php.md should contain PMID identifiers');
    }

    /**
     * Test that test.php.md contains Japanese text
     */
    public function testTestPhpMdContainsJapaneseText()
    {
        $this->assertStringContainsString('アカゲザル', $this->testPhpMdContent,
            'test.php.md should contain Japanese text (アカゲザル)');
        $this->assertStringContainsString('溶血性疾患', $this->testPhpMdContent,
            'test.php.md should contain Japanese medical terminology');
    }

    /**
     * Test that test.php.md contains Infobox parameters
     */
    public function testTestPhpMdContainsInfoboxParameters()
    {
        $infoboxParams = ['name', 'synonym', 'image', 'specialty', 'symptoms', 'complications', 'diagnosis'];

        foreach ($infoboxParams as $param) {
            $this->assertStringContainsString("|$param", $this->testPhpMdContent,
                "test.php.md should contain infobox parameter: $param");
        }
    }

    /**
     * Test that test.php.md contains category tag
     */
    public function testTestPhpMdContainsCategoryTag()
    {
        $this->assertStringContainsString('[[Category:', $this->testPhpMdContent,
            'test.php.md should contain category tag');
        $this->assertStringContainsString('Translated from MDWiki', $this->testPhpMdContent,
            'test.php.md should contain category: Translated from MDWiki');
    }

    /**
     * Test that test.php.md contains references section
     */
    public function testTestPhpMdContainsReferencesSection()
    {
        $this->assertStringContainsString('== 参考文献 ==', $this->testPhpMdContent,
            'test.php.md should contain references section in Japanese');
        $this->assertStringContainsString('<references />', $this->testPhpMdContent,
            'test.php.md should contain <references /> tag');
    }

    /**
     * Test that test.php.md has multiple reference instances
     */
    public function testTestPhpMdHasMultipleReferenceInstances()
    {
        $refCount = substr_count($this->testPhpMdContent, '<ref');

        $this->assertGreaterThan(10, $refCount,
            'test.php.md should have more than 10 reference instances');
    }

    /**
     * Test that test.php.md contains citation with title parameter
     */
    public function testTestPhpMdContainsCitationWithTitle()
    {
        $this->assertStringContainsString('title=', $this->testPhpMdContent,
            'test.php.md should contain title parameters in citations');
        $this->assertStringContainsString('Rhesus disease', $this->testPhpMdContent,
            'test.php.md should contain article titles');
    }

    /**
     * Test that test.php.md contains citation with journal parameter
     */
    public function testTestPhpMdContainsCitationWithJournal()
    {
        $this->assertStringContainsString('journal=', $this->testPhpMdContent,
            'test.php.md should contain journal parameters in citations');
    }

    /**
     * Test that test.php.md contains citation with volume and issue
     */
    public function testTestPhpMdContainsCitationWithVolumeAndIssue()
    {
        $this->assertStringContainsString('volume=', $this->testPhpMdContent,
            'test.php.md should contain volume parameters');
        $this->assertStringContainsString('issue=', $this->testPhpMdContent,
            'test.php.md should contain issue parameters');
        $this->assertStringContainsString('pages=', $this->testPhpMdContent,
            'test.php.md should contain pages parameters');
    }

    /**
     * Test that test.php.md contains inline references within text
     */
    public function testTestPhpMdContainsInlineReferences()
    {
        // Check that references appear inline with text (not just in isolation)
        $this->assertMatchesRegularExpression('/\w+\s*<ref/', $this->testPhpMdContent,
            'test.php.md should contain inline references following text');
    }

    /**
     * Test that test.php.md contains wikilinks
     */
    public function testTestPhpMdContainsWikilinks()
    {
        $this->assertStringContainsString('[[', $this->testPhpMdContent,
            'test.php.md should contain wikilink opening brackets');
        $this->assertStringContainsString(']]', $this->testPhpMdContent,
            'test.php.md should contain wikilink closing brackets');
    }

    /**
     * Test that test.php.md contains medical terminology wikilinks
     */
    public function testTestPhpMdContainsMedicalWikilinks()
    {
        $medicalTerms = ['[[Paediatrics]]', '[[haematology]]', '[[Low red blood cells]]', '[[jaundice]]'];

        $foundTerms = 0;
        foreach ($medicalTerms as $term) {
            if (strpos($this->testPhpMdContent, $term) !== false) {
                $foundTerms++;
            }
        }

        $this->assertGreaterThan(0, $foundTerms,
            'test.php.md should contain at least one medical terminology wikilink');
    }

    /**
     * Test that test.php.md contains author information in citations
     */
    public function testTestPhpMdContainsAuthorInformation()
    {
        $this->assertStringContainsString('last=', $this->testPhpMdContent,
            'test.php.md should contain author last name parameters');
        $this->assertStringContainsString('first=', $this->testPhpMdContent,
            'test.php.md should contain author first name parameters');
    }

    /**
     * Test that test.php.md has proper UTF-8 encoding
     */
    public function testTestPhpMdHasProperUtf8Encoding()
    {
        $isValidUtf8 = mb_check_encoding($this->testPhpMdContent, 'UTF-8');

        $this->assertTrue($isValidUtf8,
            'test.php.md should have valid UTF-8 encoding for multilingual content');
    }

    /**
     * Test that test.php.md contains url-status parameter
     */
    public function testTestPhpMdContainsUrlStatus()
    {
        $this->assertStringContainsString('url-status=', $this->testPhpMdContent,
            'test.php.md should contain url-status parameters');
        $this->assertStringContainsString('url-status=live', $this->testPhpMdContent,
            'test.php.md should indicate live URL status');
    }

    /**
     * Test that test.php.md contains language parameter
     */
    public function testTestPhpMdContainsLanguageParameter()
    {
        $this->assertStringContainsString('language=', $this->testPhpMdContent,
            'test.php.md should contain language parameters in citations');
    }

    /**
     * Test that test.php.md contains date formatting
     */
    public function testTestPhpMdContainsDateFormatting()
    {
        $this->assertStringContainsString('date=', $this->testPhpMdContent,
            'test.php.md should contain date parameters');
        $this->assertMatchesRegularExpression('/\d{1,2}\s+\w+\s+\d{4}/', $this->testPhpMdContent,
            'test.php.md should contain properly formatted dates');
    }

    /**
     * Test that test.php.md contains nested templates
     */
    public function testTestPhpMdContainsNestedTemplates()
    {
        // Check for templates within ref tags (nested structure)
        $this->assertMatchesRegularExpression('/<ref[^>]*>.*?\{\{Cite/', $this->testPhpMdContent,
            'test.php.md should contain citation templates nested within ref tags');
    }

    /**
     * Test that test.php.md serves as valid test fixture
     */
    public function testTestPhpMdIsValidTestFixture()
    {
        // Check that file contains diverse wikitext elements for testing
        $hasRefs = strpos($this->testPhpMdContent, '<ref') !== false;
        $hasTemplates = strpos($this->testPhpMdContent, '{{') !== false;
        $hasWikilinks = strpos($this->testPhpMdContent, '[[') !== false;
        $hasMultilingualContent = preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $this->testPhpMdContent);

        $this->assertTrue($hasRefs, 'test.php.md should contain references for testing');
        $this->assertTrue($hasTemplates, 'test.php.md should contain templates for testing');
        $this->assertTrue($hasWikilinks, 'test.php.md should contain wikilinks for testing');
        $this->assertTrue($hasMultilingualContent, 'test.php.md should contain multilingual content for testing');
    }
}