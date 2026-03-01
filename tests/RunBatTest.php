<?php

use FixRefs\Tests\MyFunctionTest;

class RunBatTest extends MyFunctionTest
{
    private string $runBatFile = __DIR__ . '/../run.bat';
    private string $runBatContent;

    protected function setUp(): void
    {
        $this->runBatContent = file_get_contents($this->runBatFile);
    }

    /**
     * Test that run.bat file exists
     */
    public function testRunBatFileExists()
    {
        $this->assertFileExists($this->runBatFile, 'run.bat file should exist');
    }

    /**
     * Test that run.bat is readable and not empty
     */
    public function testRunBatIsNotEmpty()
    {
        $this->assertNotEmpty($this->runBatContent, 'run.bat should not be empty');
        $this->assertGreaterThan(10, strlen($this->runBatContent), 'run.bat should have content');
    }

    /**
     * Test that run.bat contains phpunit command
     */
    public function testRunBatContainsPhpunitCommand()
    {
        $this->assertStringContainsString('phpunit', $this->runBatContent,
            'run.bat should contain phpunit command');
    }

    /**
     * Test that run.bat contains phpstan command
     */
    public function testRunBatContainsPhpstanCommand()
    {
        $this->assertStringContainsString('phpstan', $this->runBatContent,
            'run.bat should contain phpstan command');
    }

    /**
     * Test that run.bat references vendor/bin directory
     */
    public function testRunBatReferencesVendorBin()
    {
        $this->assertStringContainsString('vendor/bin', $this->runBatContent,
            'run.bat should reference vendor/bin directory');
    }

    /**
     * Test that run.bat phpunit command references tests directory
     */
    public function testRunBatPhpunitReferencesTestsDirectory()
    {
        $this->assertStringContainsString('tests', $this->runBatContent,
            'run.bat phpunit command should reference tests directory');
    }

    /**
     * Test that run.bat phpunit command has testdox flag
     */
    public function testRunBatPhpunitHasTestdoxFlag()
    {
        $this->assertStringContainsString('--testdox', $this->runBatContent,
            'run.bat phpunit command should have --testdox flag');
    }

    /**
     * Test that run.bat phpunit command has colors flag
     */
    public function testRunBatPhpunitHasColorsFlag()
    {
        $this->assertStringContainsString('--colors=always', $this->runBatContent,
            'run.bat phpunit command should have --colors=always flag');
    }

    /**
     * Test that run.bat phpunit command references phpunit.xml config
     */
    public function testRunBatPhpunitReferencesConfig()
    {
        $this->assertStringContainsString('-c phpunit.xml', $this->runBatContent,
            'run.bat phpunit command should reference phpunit.xml config');
    }

    /**
     * Test that run.bat phpstan command has analyse argument
     */
    public function testRunBatPhpstanHasAnalyseArgument()
    {
        $this->assertStringContainsString('analyse', $this->runBatContent,
            'run.bat phpstan command should have analyse argument');
    }

    /**
     * Test that run.bat has proper line structure
     */
    public function testRunBatHasProperLineStructure()
    {
        $lines = explode("\n", trim($this->runBatContent));
        $nonEmptyLines = array_filter($lines, function($line) {
            return trim($line) !== '';
        });

        $this->assertGreaterThanOrEqual(2, count($nonEmptyLines),
            'run.bat should have at least 2 non-empty command lines');
    }

    /**
     * Test that run.bat phpunit command is properly formatted
     */
    public function testRunBatPhpunitCommandFormat()
    {
        $this->assertMatchesRegularExpression(
            '/vendor\/bin\/phpunit\s+tests\s+--testdox\s+--colors=always\s+-c\s+phpunit\.xml/',
            $this->runBatContent,
            'run.bat phpunit command should be properly formatted'
        );
    }

    /**
     * Test that run.bat phpstan command is properly formatted
     */
    public function testRunBatPhpstanCommandFormat()
    {
        $this->assertMatchesRegularExpression(
            '/vendor\/bin\/phpstan\s+analyse/',
            $this->runBatContent,
            'run.bat phpstan command should be properly formatted'
        );
    }

    /**
     * Test that run.bat does not contain syntax errors (basic check)
     */
    public function testRunBatNoObviousSyntaxErrors()
    {
        // Check that commands don't have obvious typos
        $this->assertStringNotContainsString('phpuint', $this->runBatContent,
            'run.bat should not contain typo phpuint');
        $this->assertStringNotContainsString('phpstan analyse', $this->runBatContent,
            'run.bat should not have extra space in phpstan analyse');
    }

    /**
     * Test that run.bat commands are in correct order
     */
    public function testRunBatCommandsOrder()
    {
        $phpunitPos = strpos($this->runBatContent, 'phpunit');
        $phpstanPos = strpos($this->runBatContent, 'phpstan');

        $this->assertNotFalse($phpunitPos, 'phpunit command should be present');
        $this->assertNotFalse($phpstanPos, 'phpstan command should be present');
        $this->assertLessThan($phpstanPos, $phpunitPos,
            'phpunit command should appear before phpstan command');
    }

    /**
     * Test that run.bat paths use forward slashes (Unix-style)
     */
    public function testRunBatUsesForwardSlashes()
    {
        $this->assertStringContainsString('vendor/bin/', $this->runBatContent,
            'run.bat should use forward slashes for paths');
        $this->assertStringNotContainsString('vendor\\bin\\', $this->runBatContent,
            'run.bat should not use backslashes for paths');
    }

    /**
     * Test that run.bat ends with proper line termination
     */
    public function testRunBatHasProperLineTermination()
    {
        $this->assertMatchesRegularExpression('/\n$/', $this->runBatContent,
            'run.bat should end with a newline character');
    }

    /**
     * Test that run.bat contains all required phpunit flags
     */
    public function testRunBatContainsAllRequiredPhpunitFlags()
    {
        $requiredFlags = ['--testdox', '--colors=always', '-c phpunit.xml'];

        foreach ($requiredFlags as $flag) {
            $this->assertStringContainsString($flag, $this->runBatContent,
                "run.bat should contain phpunit flag: $flag");
        }
    }

    /**
     * Test that run.bat does not contain commented out commands
     */
    public function testRunBatNoCommentedCommands()
    {
        $lines = explode("\n", $this->runBatContent);
        $commentedCommands = array_filter($lines, function($line) {
            $trimmed = trim($line);
            return $trimmed !== '' && (strpos($trimmed, '#') === 0 || strpos($trimmed, 'REM') === 0);
        });

        $this->assertEmpty($commentedCommands,
            'run.bat should not contain commented out command lines');
    }

    /**
     * Test that run.bat file is small and focused
     */
    public function testRunBatIsCompactScript()
    {
        $lineCount = count(explode("\n", $this->runBatContent));
        $this->assertLessThan(20, $lineCount,
            'run.bat should be a compact script with less than 20 lines');
    }
}