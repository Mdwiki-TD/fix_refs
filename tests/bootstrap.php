<?php

namespace WpRefs\Tests;

use PHPUnit\Framework\TestCase;

// Set test environment
putenv('APP_ENV=testing');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// use WpRefs\Tests\MyFunctionTest;
// Load the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';
// Load bootstap.php file
require __DIR__ . '/../src/bootstap.php';


class MyFunctionTest extends TestCase
{
    protected function assertEqualCompare(string $expected, string $input, string $result)
    {
        // ---
        $result = preg_replace("/\r\n/", "\n", $result);
        $expected = preg_replace("/\r\n/", "\n", $expected);
        // ---
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }
}
