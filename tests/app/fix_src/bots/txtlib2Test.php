<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Bots\TxtLib2\extract_templates_and_params;

class txtlib2Test extends MyFunctionTest
{

    private $textInput = "";
    private $jsonData = [];
    private $tempData = [];

    protected function setUp(): void
    {
        $this->textInput = file_get_contents(__DIR__ . "/fixtures/txtlib2/input.txt");
        $this->jsonData = json_decode(file_get_contents(__DIR__ . "/fixtures/txtlib2/expected.json"), true);
        $this->tempData = extract_templates_and_params($this->textInput);
    }

    public function testInputTextNotEmpty(): void
    {
        $this->assertNotEmpty($this->textInput, "Input text file is empty!");
    }

    public function testJsonDataNotEmpty(): void
    {
        $this->assertNotEmpty($this->jsonData, "JSON file is empty or invalid!");
    }

    public function testTempDataNotEmpty(): void
    {
        $this->assertNotEmpty($this->tempData, "No templates were extracted!");
    }

    public function testFirstTemplateName(): void
    {
        $this->assertEquals(
            "Infobox drug",
            $this->tempData[0]["name"],
            "Template name does not match the expected value."
        );
    }

    public function testFirstTemplateItemMatchesInput(): void
    {
        $this->assertEquals(
            trim($this->textInput),
            trim($this->tempData[0]["item"]),
            "Extracted template text does not match the original input."
        );
    }

    public function testFirstTemplateParams(): void
    {
        // Check that the extracted parameters match the ones in the JSON file
        $this->assertEquals(
            $this->jsonData[0]["params"],
            $this->tempData[0]["params"],
            "Extracted parameters do not match the expected data."
        );
    }

    public function testSpecificParamValues(): void
    {
        // Verify specific parameter values as an additional check
        $params = $this->tempData[0]["params"];
        $this->assertArrayHasKey("tradename", $params);
        $this->assertEquals("Jaypirca", $params["tradename"]);

        $this->assertArrayHasKey("legal_US", $params);
        $this->assertEquals("Rx-only", $params["legal_US"]);

        $this->assertArrayHasKey("CAS_number", $params);
        $this->assertEquals("2101700-15-4", $params["CAS_number"]);
    }

    public function testCountOfParams(): void
    {
        // Verify that the number of extracted parameters matches the expected count
        $expectedCount = count($this->jsonData[0]["params"]);
        $actualCount = count($this->tempData[0]["params"]);
        $this->assertSame(
            $expectedCount,
            $actualCount,
            "Number of extracted parameters does not match the expected count."
        );
    }
}
