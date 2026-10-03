<?php

namespace Tests;
// Comprehensive test to verify Polish language fixes work correctly

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\MyFunctionTest;
use App\Fix\Index;

class ComprehensiveTest extends MyFunctionTest
{
    /**
     * Data provider for Polish Choroba infobox test cases.
     *
     * @return array
     */
    public static function chorobaInfoboxProvider(): array
    {
        return [
            'Standard Choroba infobox - all parameters missing' => [
                'input' => '{{Choroba infobox|nazwa polska=Astma}}',
                'lang' => 'pl',
                'expectedParams' => ['nazwa naukowa', 'ICD11', 'ICD10', 'DSM-5', 'OMIM', 'MeshID', 'commons'],
                'shouldNotAdd' => [],
                'notDuplicated' => [],
            ],
            'Lowercase template name' => [
                'input' => '{{choroba infobox|nazwa polska=Test}}',
                'lang' => 'pl',
                'expectedParams' => ['ICD10'],
                'shouldNotAdd' => [],
                'notDuplicated' => [],
            ],
            'UPPERCASE template name' => [
                'input' => '{{CHOROBA INFOBOX|nazwa polska=Test}}',
                'lang' => 'pl',
                'expectedParams' => ['ICD10'],
                'shouldNotAdd' => [],
                'notDuplicated' => [],
            ],
            'Mixed case template name' => [
                'input' => '{{ChOrObA InFoBoX|nazwa polska=Test}}',
                'lang' => 'pl',
                'expectedParams' => ['ICD10'],
                'shouldNotAdd' => [],
                'notDuplicated' => [],
            ],
            'Some parameters already exist' => [
                'input' => '{{Choroba infobox|nazwa polska=Test|ICD10=J45|OMIM=123456}}',
                'lang' => 'pl',
                'expectedParams' => ['nazwa naukowa', 'ICD11', 'DSM-5', 'MeshID'],
                'shouldNotAdd' => [],
                'notDuplicated' => ['ICD10', 'OMIM'],
            ],
            'Full article with Choroba infobox' => [
                'input' => <<<'TXT'
{{Choroba infobox
|nazwa polska = Cukrzyca
|obraz = Insulin glucose metabolism ZP.svg
}}

'''Cukrzyca''' – grupa chorób metabolicznych.

== Zobacz też ==
* [[Insulina]]

== Przypisy ==
<references />
TXT,
                'lang' => 'pl',
                'expectedParams' => ['ICD10', 'ICD11', 'OMIM'],
                'shouldNotAdd' => [],
                'notDuplicated' => [],
            ],
            'Non-Polish language should not apply fixes' => [
                'input' => '{{Choroba infobox|nazwa polska=Test}}',
                'lang' => 'en',
                'expectedParams' => [],
                'shouldNotAdd' => ['ICD10', 'ICD11'],
                'notDuplicated' => [],
            ],
            'Different template (not Choroba) - no changes' => [
                'input' => '{{Infobox person|name=Test}}',
                'lang' => 'pl',
                'expectedParams' => [],
                'shouldNotAdd' => ['ICD10'],
                'notDuplicated' => [],
            ],
        ];
    }

    /**
     * Test Polish Choroba infobox support with various test cases.
     *
     * @param string $input
     * @param string $lang
     * @param array $expectedParams
     * @param array $shouldNotAdd
     * @param array $notDuplicated
     * @return void
     */
    #[DataProvider('chorobaInfoboxProvider')]
    public function testPolishChorobaInfobox(
        string $input,
        string $lang,
        array $expectedParams,
        array $shouldNotAdd,
        array $notDuplicated
    ): void {
        $result = Index::fix_page($input, "Test Article", false, true, false, $lang, "", "");

        // Verify that expected parameters are present
        foreach ($expectedParams as $param) {
            $this->assertStringContainsString(
                $param,
                $result,
                "Expected parameter '$param' not found in result."
            );
        }

        // Verify that parameters that should not be added are absent
        foreach ($shouldNotAdd as $param) {
            $this->assertStringNotContainsString(
                $param,
                $result,
                "Parameter '$param' should not be present in result."
            );
        }

        // Verify that parameters are not duplicated
        foreach ($notDuplicated as $param) {
            $count = preg_match_all('/\|' . preg_quote($param, '/') . '\s*=/', $result, $matches);
            $this->assertLessThanOrEqual(
                1,
                $count,
                "Parameter '$param' is duplicated ($count occurrences)."
            );
        }
    }
}
