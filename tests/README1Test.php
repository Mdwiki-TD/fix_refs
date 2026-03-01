<?php

use FixRefs\Tests\MyFunctionTest;

class README1Test extends MyFunctionTest
{
    private string $readmeFile = __DIR__ . '/../README1.md';
    private string $readmeContent;

    protected function setUp(): void
    {
        $this->readmeContent = file_get_contents($this->readmeFile);
    }

    /**
     * Test that README1.md file exists
     */
    public function testReadmeFileExists()
    {
        $this->assertFileExists($this->readmeFile, 'README1.md file should exist');
    }

    /**
     * Test that README1.md is readable and not empty
     */
    public function testReadmeIsNotEmpty()
    {
        $this->assertNotEmpty($this->readmeContent, 'README1.md should not be empty');
        $this->assertGreaterThan(100, strlen($this->readmeContent), 'README1.md should have substantial content');
    }

    /**
     * Test that README1.md contains the main title
     */
    public function testReadmeContainsMainTitle()
    {
        $this->assertStringContainsString('# Wikipedia Reference Fixer', $this->readmeContent,
            'README should contain the main title');
    }

    /**
     * Test that README1.md contains Overview section
     */
    public function testReadmeContainsOverviewSection()
    {
        $this->assertStringContainsString('## Overview', $this->readmeContent,
            'README should contain Overview section');
        $this->assertStringContainsString('PHP-based tool', $this->readmeContent,
            'Overview should describe the tool as PHP-based');
    }

    /**
     * Test that README1.md contains Project Structure section
     */
    public function testReadmeContainsProjectStructureSection()
    {
        $this->assertStringContainsString('## Project Structure', $this->readmeContent,
            'README should contain Project Structure section');
    }

    /**
     * Test that README1.md documents Core Controller
     */
    public function testReadmeDocumentsCoreController()
    {
        $this->assertStringContainsString('### 1. Core Controller', $this->readmeContent,
            'README should document Core Controller');
        $this->assertStringContainsString('index.php', $this->readmeContent,
            'README should mention index.php');
    }

    /**
     * Test that README1.md documents WikiParse Module
     */
    public function testReadmeDocumentsWikiParseModule()
    {
        $this->assertStringContainsString('### 2. WikiParse Module', $this->readmeContent,
            'README should document WikiParse Module');
        $this->assertStringContainsString('fix_src/WikiParse', $this->readmeContent,
            'README should mention WikiParse path');
    }

    /**
     * Test that README1.md documents Bot Module
     */
    public function testReadmeDocumentsBotModule()
    {
        $this->assertStringContainsString('### 3. Bot Module', $this->readmeContent,
            'README should document Bot Module');
        $this->assertStringContainsString('fix_src/bots', $this->readmeContent,
            'README should mention bots path');
    }

    /**
     * Test that README1.md contains System Architecture section
     */
    public function testReadmeContainsSystemArchitecture()
    {
        $this->assertStringContainsString('## System Architecture', $this->readmeContent,
            'README should contain System Architecture section');
        $this->assertStringContainsString('### Workflow', $this->readmeContent,
            'README should describe workflow');
    }

    /**
     * Test that README1.md contains Design Principles section
     */
    public function testReadmeContainsDesignPrinciples()
    {
        $this->assertStringContainsString('## Design Principles', $this->readmeContent,
            'README should contain Design Principles section');
        $this->assertStringContainsString('Modular Structure', $this->readmeContent,
            'README should mention modular structure principle');
    }

    /**
     * Test that README1.md contains How to Use section
     */
    public function testReadmeContainsHowToUseSection()
    {
        $this->assertStringContainsString('## How to Use', $this->readmeContent,
            'README should contain How to Use section');
        $this->assertStringContainsString('php index.php', $this->readmeContent,
            'README should contain usage command');
    }

    /**
     * Test that README1.md contains Contributing section
     */
    public function testReadmeContainsContributingSection()
    {
        $this->assertStringContainsString('## Contributing', $this->readmeContent,
            'README should contain Contributing section');
    }

    /**
     * Test that README1.md contains License section
     */
    public function testReadmeContainsLicenseSection()
    {
        $this->assertStringContainsString('## License', $this->readmeContent,
            'README should contain License section');
        $this->assertStringContainsString('MIT License', $this->readmeContent,
            'README should mention MIT License');
    }

    /**
     * Test that README1.md mentions all DataModel classes
     */
    public function testReadmeMentionsDataModelClasses()
    {
        $dataModelClasses = ['Citation.php', 'ExternalLink.php', 'InternalLink.php', 'Table.php', 'Template.php'];

        foreach ($dataModelClasses as $class) {
            $this->assertStringContainsString($class, $this->readmeContent,
                "README should mention DataModel class: $class");
        }
    }

    /**
     * Test that README1.md mentions configuration file
     */
    public function testReadmeMentionsConfigurationFile()
    {
        $this->assertStringContainsString('fixwikirefs.json', $this->readmeContent,
            'README should mention configuration file fixwikirefs.json');
    }

    /**
     * Test that README1.md has proper markdown heading hierarchy
     */
    public function testReadmeHasProperHeadingHierarchy()
    {
        // Should start with H1
        $this->assertMatchesRegularExpression('/^# /', $this->readmeContent,
            'README should start with H1 heading');

        // Should have H2 sections
        $this->assertMatchesRegularExpression('/\n## /', $this->readmeContent,
            'README should contain H2 headings');

        // Should have H3 subsections
        $this->assertMatchesRegularExpression('/\n### /', $this->readmeContent,
            'README should contain H3 headings');
    }

    /**
     * Test that README1.md workflow describes initialization step
     */
    public function testReadmeWorkflowDescribesInitialization()
    {
        $this->assertStringContainsString('1. **Initialization**', $this->readmeContent,
            'README workflow should describe initialization');
        $this->assertStringContainsString('fix_page_here', $this->readmeContent,
            'README should mention fix_page_here function');
    }

    /**
     * Test that README1.md workflow describes parsing step
     */
    public function testReadmeWorkflowDescribesParsing()
    {
        $this->assertStringContainsString('2. **Parsing & Data Modeling**', $this->readmeContent,
            'README workflow should describe parsing');
    }

    /**
     * Test that README1.md workflow describes automated fixes step
     */
    public function testReadmeWorkflowDescribesAutomatedFixes()
    {
        $this->assertStringContainsString('3. **Automated Fixes**', $this->readmeContent,
            'README workflow should describe automated fixes');
    }

    /**
     * Test that README1.md has a Diagram section at the end
     */
    public function testReadmeHasDiagramSection()
    {
        $this->assertStringContainsString('# Diagram', $this->readmeContent,
            'README should have a Diagram section');
    }

    /**
     * Test that README1.md mentions required dependencies
     */
    public function testReadmeMentionsPhpRequirement()
    {
        $this->assertStringContainsString('PHP', $this->readmeContent,
            'README should mention PHP requirement');
    }

    /**
     * Test that README1.md contains all required bot files
     */
    public function testReadmeMentionsBotFiles()
    {
        $botFiles = ['es_months.php', 'es_refs.php', 'expend_refs.php', 'remove_duplicate_refs.php'];

        foreach ($botFiles as $botFile) {
            $this->assertStringContainsString($botFile, $this->readmeContent,
                "README should mention bot file: $botFile");
        }
    }
}