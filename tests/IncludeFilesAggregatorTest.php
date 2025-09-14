<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class IncludeFilesAggregatorTest extends TestCase
{
    private string $aggregatorPath;

    protected function setUp(): void
    {
        $this->aggregatorPath = __DIR__ . DIRECTORY_SEPARATOR . 'include_filesTest.php';
        $this->assertFileExists($this->aggregatorPath, 'Aggregator file tests/include_filesTest.php must exist.');
    }

    public function test_source_contains_expected_includes_and_globs(): void
    {
        $src = file_get_contents($this->aggregatorPath);
        $this->assertIsString($src);

        // Fixed includes
        $expectedFixed = [
            "include_once __DIR__ . '/test_bot.php';",
            "include_once __DIR__ . '/WikiParse/include_it.php';",
            "include_once __DIR__ . '/sw.php';",
            "include_once __DIR__ . '/md_cat.php';",
            "include_once __DIR__ . '/es.php';",
            "include_once __DIR__ . '/index.php';",
        ];
        foreach ($expectedFixed as $needle) {
            $this->assertStringContainsString($needle, $src, "Expected fixed include not found: {$needle}");
        }

        // Glob-based includes
        $expectedGlobs = [
            'foreach (glob(__DIR__ . "/helps_bots/*.php") as $filename) {',
            'foreach (glob(__DIR__ . "/infoboxes/*.php") as $filename) {',
            'foreach (glob(__DIR__ . "/Parse/*.php") as $filename) {',
            'foreach (glob(__DIR__ . "/bots/*.php") as $filename) {',
        ];
        foreach ($expectedGlobs as $needle) {
            $this->assertStringContainsString($needle, $src, "Expected glob include not found: {$needle}");
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function test_including_aggregator_succeeds_without_warnings(): void
    {
        // Convert warnings/notices to exceptions for strictness
        set_error_handler(static function (int $errno, string $errstr): bool {
            throw new \ErrorException($errstr, 0, $errno);
        });

        ob_start();
        try {
            $result = include $this->aggregatorPath;
            $output = ob_get_clean();

            // include returns 1 if the included file does not return a value
            $this->assertTrue($result === 1 || $result === true, 'Including aggregator should succeed.');
            // Best-effort: aggregator should not emit output; if it does, expose it while not being overly strict
            $this->assertSame('', $output, "Aggregator should not produce output; got: " . var_export($output, true));
        } finally {
            restore_error_handler();
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function test_including_aggregator_twice_is_idempotent(): void
    {
        set_error_handler(static function (int $errno, string $errstr): bool {
            throw new \ErrorException($errstr, 0, $errno);
        });

        ob_start();
        try {
            $first = include $this->aggregatorPath;
            $second = include $this->aggregatorPath; // include (not include_once) to re-execute aggregator; inner include_once guards re-inclusion
            $output = ob_get_clean();

            $this->assertTrue(($first === 1 || $first === true) && ($second === 1 || $second === true), 'Including aggregator twice should not fail.');
            $this->assertSame('', $output, "Aggregator should remain quiet when included twice.");
        } finally {
            restore_error_handler();
        }
    }

    public function test_globbed_directories_have_php_files_present(): void
    {
        $base = __DIR__;
        $dirs = [
            $base . '/helps_bots',
            $base . '/infoboxes',
            $base . '/Parse',
            $base . '/bots',
        ];

        foreach ($dirs as $dir) {
            $this->assertDirectoryExists($dir, "Expected directory does not exist: {$dir}");
            $files = glob($dir . '/*.php') ?: [];
            $this->assertIsArray($files, "glob() must return an array for: {$dir}");
            $this->assertNotEmpty($files, "Expected at least one PHP file in: {$dir}");
            foreach ($files as $phpFile) {
                $this->assertFileExists($phpFile);
            }
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function test_each_globbed_file_is_individually_include_safe(): void
    {
        $base = __DIR__;
        $globs = [
            $base . '/helps_bots/*.php',
            $base . '/infoboxes/*.php',
            $base . '/Parse/*.php',
            $base . '/bots/*.php',
        ];
        $all = [];
        foreach ($globs as $pattern) {
            $matches = glob($pattern) ?: [];
            $all = array_merge($all, $matches);
        }
        $this->assertNotEmpty($all, 'There should be PHP files matched by the aggregator globs.');

        // Strict error handling to catch deprecated/notice-level issues during include
        set_error_handler(static function (int $errno, string $errstr): bool {
            throw new \ErrorException($errstr, 0, $errno);
        });

        try {
            foreach ($all as $file) {
                ob_start();
                $result = include_once $file;
                $output = ob_get_clean();
                $this->assertTrue($result === 1 || $result === true, "Including {$file} should succeed.");
                $this->assertSame('', $output, "Including {$file} should not produce output.");
            }
        } finally {
            restore_error_handler();
        }
    }

    public function test_fixed_includes_exist_relative_to_tests_dir(): void
    {
        $base = __DIR__;
        $fixed = [
            $base . '/test_bot.php',
            $base . '/WikiParse/include_it.php',
            $base . '/sw.php',
            $base . '/md_cat.php',
            $base . '/es.php',
            $base . '/index.php',
        ];
        foreach ($fixed as $path) {
            $this->assertFileExists($path, "Expected file missing: {$path}");
        }
    }
}