<?php

use FixRefs\Tests\MyFunctionTest;

use function WpRefs\PL\FixPlInfobox\add_missing_params_to_choroba_infobox;
use function WpRefs\PL\FixPlInfobox\pl_fixes;

class pl_infoboxTest extends MyFunctionTest
{
    public function testAddMissingParamsToChorobaInfobox()
    {
        $input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz = 
|opis obrazu = 
}}
TXT;
        $result = add_missing_params_to_choroba_infobox($input);
        
        // Check that all required parameters are present (without requiring specific spacing)
        $this->assertStringContainsString('nazwa naukowa', $result);
        $this->assertStringContainsString('ICD11', $result);
        $this->assertMatchesRegularExpression('/\|ICD11\s*=/', $result);
        $this->assertMatchesRegularExpression('/\|ICD11 nazwa\s*=/', $result);
        $this->assertMatchesRegularExpression('/\|ICD10\s*=/', $result);
        $this->assertMatchesRegularExpression('/\|ICD10 nazwa\s*=/', $result);
        $this->assertStringContainsString('DSM-5', $result);
        $this->assertMatchesRegularExpression('/\|DSM-5\s*=/', $result);
        $this->assertMatchesRegularExpression('/\|DSM-5 nazwa\s*=/', $result);
        $this->assertMatchesRegularExpression('/\|DSM-IV\s*=/', $result);
        $this->assertMatchesRegularExpression('/\|DSM-IV nazwa\s*=/', $result);
        $this->assertStringContainsString('ICDO', $result);
        $this->assertStringContainsString('DiseasesDB', $result);
        $this->assertStringContainsString('OMIM', $result);
        $this->assertStringContainsString('MedlinePlus', $result);
        $this->assertStringContainsString('MeshID', $result);
        $this->assertStringContainsString('commons', $result);
        
        // Check that original parameters are still present
        $this->assertStringContainsString('nazwa polska', $result);
        $this->assertStringContainsString('Astma oskrzelowa', $result);
    }

    public function testCaseInsensitiveTemplateName()
    {
        // Test with different case variations
        $inputs = [
            '{{choroba infobox|nazwa polska=Test}}',
            '{{CHOROBA INFOBOX|nazwa polska=Test}}',
            '{{Choroba Infobox|nazwa polska=Test}}',
            '{{ChOrObA iNfObOx|nazwa polska=Test}}'
        ];
        
        foreach ($inputs as $input) {
            $result = add_missing_params_to_choroba_infobox($input);
            $this->assertStringContainsString('nazwa naukowa', $result, "Failed for input: $input");
            $this->assertStringContainsString('ICD11', $result, "Failed for input: $input");
        }
    }

    public function testDoesNotAddExistingParams()
    {
        $input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|ICD10 = J45
|MeshID = D001249
}}
TXT;
        $result = add_missing_params_to_choroba_infobox($input);
        
        // Count occurrences using regex - should be exactly 1 for each existing param
        $icd10_count = preg_match_all('/\|ICD10\s*=/', $result, $icd10_matches);
        $meshid_count = preg_match_all('/\|MeshID\s*=/', $result, $meshid_matches);
        
        $this->assertEquals(1, $icd10_count);
        $this->assertEquals(1, $meshid_count);
        
        // But should still add missing ones
        $this->assertStringContainsString('nazwa naukowa', $result);
        $this->assertStringContainsString('ICD11', $result);
    }

    public function testIgnoresOtherTemplates()
    {
        $input = '{{Some other template|param=value}}';
        $result = add_missing_params_to_choroba_infobox($input);
        
        // Should return unchanged
        $this->assertEquals($input, $result);
    }

    public function testPlFixesFunction()
    {
        $input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Test
}}
TXT;
        $result = pl_fixes($input);
        
        // Test that pl_fixes calls the add_missing_params function
        $this->assertStringContainsString('nazwa naukowa', $result);
        $this->assertStringContainsString('ICD10', $result);
    }

    public function testMultipleChorobaTemplates()
    {
        $input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Test1
}}

Some text here.

{{Choroba infobox
|nazwa polska = Test2
}}
TXT;
        $result = add_missing_params_to_choroba_infobox($input);
        
        // Both templates should get the parameters
        // Use regex to match exact parameter (not substring that matches both ICD10 and ICD10 nazwa)
        $icd10Count = preg_match_all('/\|ICD10\s*=/', $result, $matches);
        $this->assertEquals(2, $icd10Count, "Both Choroba infobox templates should have ICD10 parameter");
    }
}
