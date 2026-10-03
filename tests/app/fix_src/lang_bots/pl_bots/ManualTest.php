<?php

namespace WpRefs\Tests;

// Simple manual test for Polish infobox functionality

use PHPUnit\Framework\Attributes\DataProvider;
use WpRefs\Tests\MyFunctionTest;
use App\Fix\lang_bots\pl_bots\FixPlInfobox;


class ManualTest extends MyFunctionTest
{
    /**
     * Data provider for testing Polish infobox parameter addition functionality.
     *
     * @return array
     */
    public static function manualTestCasesProvider(): array
    {
        return [
            'Basic functionality - Add missing parameters to Choroba infobox' => [
                'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz =
|opis obrazu =
}}
TXT
                ,
                'targetFunction' => 'add_missing_params_to_choroba_infobox',
                'expectedParams' => ['nazwa naukowa', 'ICD11', 'ICD10', 'DSM-5', 'OMIM', 'MeshID', 'commons'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [],
                'expectUnchanged' => false,
            ],
            'Case insensitive template name matching' => [
                'input' => '{{choroba INFOBOX|nazwa polska=Test}}',
                'targetFunction' => 'add_missing_params_to_choroba_infobox',
                'expectedParams' => ['ICD10'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [],
                'expectUnchanged' => false,
            ],
            'Don\'t duplicate existing parameters' => [
                'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma
|ICD10 = J45
|MeshID = D001249
}}
TXT
                ,
                'targetFunction' => 'add_missing_params_to_choroba_infobox',
                'expectedParams' => ['ICD10', 'MeshID'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [
                    'ICD10' => 1,
                    'MeshID' => 1,
                ],
                'expectUnchanged' => false,
            ],
            'Ignore non-Choroba templates' => [
                'input' => '{{Some other template|param=value}}',
                'targetFunction' => 'add_missing_params_to_choroba_infobox',
                'expectedParams' => [],
                'shouldNotContain' => ['ICD10', 'ICD11', 'nazwa naukowa'],
                'maxParameterCounts' => [],
                'expectUnchanged' => true,
            ],
            'pl_fixes wrapper function' => [
                'input' => '{{Choroba infobox|nazwa polska=Test}}',
                'targetFunction' => 'pl_fixes',
                'expectedParams' => ['nazwa naukowa', 'ICD10'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [],
                'expectUnchanged' => false,
            ],
        ];
    }

    /**
     * Test Polish Choroba Infobox utility functions with various scenarios.
     *
     * @param string $input
     * @param string $targetFunction
     * @param array $expectedParams
     * @param array $shouldNotContain
     * @param array $maxParameterCounts
     * @param bool $expectUnchanged
     * @return void
     */
    #[DataProvider('manualTestCasesProvider')]
    public function testPolishInfoboxFunctionality(
        string $input,
        string $targetFunction,
        array $expectedParams,
        array $shouldNotContain,
        array $maxParameterCounts,
        bool $expectUnchanged
    ): void {
        // Execute target function dynamically based on provider setting
        $result = ($targetFunction === 'pl_fixes')
            ? FixPlInfobox::pl_fixes($input)
            : FixPlInfobox::add_missing_params_to_choroba_infobox($input);

        if ($expectUnchanged) {
            $this->assertSame(
                $input,
                $result,
                "Expected input text to remain completely unchanged."
            );
            return;
        }

        // Assert expected parameters are present
        foreach ($expectedParams as $param) {
            $this->assertStringContainsString(
                $param,
                $result,
                "Expected parameter '$param' not found in processed infobox."
            );
        }

        // Assert parameters that should not be present are absent
        foreach ($shouldNotContain as $param) {
            $this->assertStringNotContainsString(
                $param,
                $result,
                "Unexpected parameter '$param' found in processed infobox."
            );
        }

        // Assert parameters are not duplicated
        foreach ($maxParameterCounts as $param => $maxAllowed) {
            $count = preg_match_all('/\|' . preg_quote($param, '/') . '\s*=/', $result, $matches);
            $this->assertSame(
                $maxAllowed,
                $count,
                "Parameter '$param' occurs $count times, expected exactly $maxAllowed."
            );
        }
    }
}
