<?php

namespace Tests;

// Integration test for Polish language fixes in main workflow

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\MyFunctionTest;
use App\Fix\Index;

class IntegrationTest extends MyFunctionTest
{
    /**
     * Data provider for integration test scenarios covering Polish language fixes.
     *
     * @return array<string, array{input: string, title: string, expandInfobox: bool, lang: string, expectedParams: array<int, string>, shouldNotContain: array<int, string>, maxParameterCounts: array<string, int>}>
     */
    public static function integrationTestCasesProvider(): array
    {
        return [
            'Polish article with Choroba infobox' => [
                'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz = Blausen 0620 Lungs NormalvsInflamedAirway.png
}}

'''Astma oskrzelowa''' (łac. ''asthma bronchiale'') – przewlekła choroba zapalna dróg oddechowych.

== Przypisy ==
<references />
TXT
                ,
                'title' => 'Astma oskrzelowa',
                'expandInfobox' => true,
                'lang' => 'pl',
                'expectedParams' => ['nazwa naukowa', 'ICD11', 'ICD10', 'DSM-5', 'OMIM', 'MeshID', 'commons'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [],
            ],
            'Polish article without Choroba infobox' => [
                'input' => <<<'TXT'
{{Infobox person
|name = Jan Kowalski
}}

'''Jan Kowalski''' był polskim lekarzem.

== Przypisy ==
<references />
TXT
                ,
                'title' => 'Jan Kowalski',
                'expandInfobox' => false,
                'lang' => 'pl',
                'expectedParams' => [],
                'shouldNotContain' => ['ICD10', 'ICD11', 'OMIM'],
                'maxParameterCounts' => [],
            ],
            'Case insensitive template matching' => [
                'input' => <<<'TXT'
{{choroba INFOBOX
|nazwa polska = Grypa
}}

Artykuł o grypie.
TXT
                ,
                'title' => 'Grypa',
                'expandInfobox' => true,
                'lang' => 'pl',
                'expectedParams' => ['ICD10'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [],
            ],
            'Non-Polish language (English)' => [
                'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Test
}}
TXT
                ,
                'title' => 'Test',
                'expandInfobox' => true,
                'lang' => 'en',
                'expectedParams' => [],
                'shouldNotContain' => ['ICD10', 'ICD11'],
                'maxParameterCounts' => [],
            ],
            'Don\'t duplicate existing parameters' => [
                'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Cukrzyca
|ICD10 = E10-E14
|OMIM = 222100
}}
TXT
                ,
                'title' => 'Cukrzyca',
                'expandInfobox' => true,
                'lang' => 'pl',
                'expectedParams' => ['ICD10', 'OMIM'],
                'shouldNotContain' => [],
                'maxParameterCounts' => [
                    'ICD10' => 1,
                    'OMIM' => 1,
                ],
            ],
        ];
    }

    /**
     * Test integration scenarios for Polish language fixes and infobox processing.
     *
     * @param string $input
     * @param string $title
     * @param bool $expandInfobox
     * @param string $lang
     * @param array<int, string> $expectedParams
     * @param array<int, string> $shouldNotContain
     * @param array<string, int> $maxParameterCounts
     * @return void
     */
    #[DataProvider('integrationTestCasesProvider')]
    public function testPolishIntegrationWorkflow(
        string $input,
        string $title,
        bool $expandInfobox,
        string $lang,
        array $expectedParams,
        array $shouldNotContain,
        array $maxParameterCounts
    ): void {
        $result = Index::fix_page($input, $title, false, $expandInfobox, false, $lang, "", "");

        // Assert expected parameters are present
        foreach ($expectedParams as $param) {
            $this->assertStringContainsString(
                $param,
                $result,
                "Expected parameter '$param' not found in processed article."
            );
        }

        // Assert parameters that should not be present are absent
        foreach ($shouldNotContain as $param) {
            $this->assertStringNotContainsString(
                $param,
                $result,
                "Unexpected parameter '$param' found in processed article."
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
