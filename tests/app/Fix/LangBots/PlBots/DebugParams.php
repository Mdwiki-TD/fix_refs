<?php

namespace Tests;

// Debug test to see what parameters are in the template

use Tests\MyFunctionTest;
use App\Fix\WikiParse\ParserTemplates;

class DebugParams extends MyFunctionTest
{
    /**
     * Test and verify that template names and parameters are parsed correctly.
     *
     * @return void
     */
    public function testTemplateParameterParsing(): void
    {
        $input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma
|ICD10 = J45
|MeshID = D001249
}}
TXT;

        $templates = (new ParserTemplates($input))->getTemplates();

        // Verify that template parsing succeeded
        $this->assertNotEmpty($templates, "Failed to parse templates from input text.");

        $template = $templates[0];

        // Assert template name
        $this->assertSame('Choroba infobox', $template->getStripName());

        // Assert parsed parameters and their values
        $params = $template->getParameters();

        $this->assertArrayHasKey('nazwa polska', $params);
        $this->assertSame('Astma', trim($params['nazwa polska']));

        $this->assertArrayHasKey('ICD10', $params);
        $this->assertSame('J45', trim($params['ICD10']));

        $this->assertArrayHasKey('MeshID', $params);
        $this->assertSame('D001249', trim($params['MeshID']));
    }
}
