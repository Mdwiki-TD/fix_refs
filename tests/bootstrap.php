<?php

namespace FixRefs\Tests;

// use FixRefs\Tests\MyFunctionTest;
// تحميل autoloader الخاص بـ Composer
require __DIR__ . '/../vendor/autoload.php';

// تحميل ملف include_files.php
require __DIR__ . '/../work.php';

require __DIR__ . '/../src/include_files.php';

use PHPUnit\Framework\TestCase;

class MyFunctionTest extends TestCase
{
    protected function assertEqualCompare(string $expected, string $input, string $result)
    {
        // --
        $result = preg_replace("/\r\n/", "\n", $result);
        $expected = preg_replace("/\r\n/", "\n", $expected);
        // --
        if ($result === $input && $result !== $expected) {
            $this->fail("No changes were made! The function returned the input unchanged:\n$result");
        } else {
            $this->assertEquals($expected, $result, "Unexpected result:\n$result");
        }
    }
}
