<?php

namespace Tests;

// Debug test to see what's happening with duplicate parameters

use Tests\MyFunctionTest;
use function App\PL\FixPlInfobox\add_missing_params_to_choroba_infobox;
use App\WikiParse\ParserTemplates;

class DebugDuplicate extends MyFunctionTest
{
    /**
     * Test and verify that parameters in Choroba infobox are not duplicated during processing.
     *
     * @return void
     */
    public function testDuplicateParametersAreNotAdded(): void
    {
        $input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma
|ICD10 = J45
|MeshID = D001249
}}
TXT;

        // Verify initial template structure
        $templates = (new ParserTemplates($input))->getTemplates();
        $this->assertNotEmpty($templates, "Failed to parse templates from input text.");

        $initialParams = $templates[0]->getParameters();
        $this->assertArrayHasKey('ICD10', $initialParams, "Initial input missing expected parameter 'ICD10'.");

        // Process the infobox
        $result = add_missing_params_to_choroba_infobox($input);

        // Assert that ICD10 parameter is not duplicated
        $count = preg_match_all('/\|ICD10\s*=/', $result, $matches);
        $this->assertSame(
            1,
            $count,
            "Parameter '|ICD10 =' should occur exactly once in the processed result, found $count times."
        );
    }
}
