<?php



use PHPUnit\Framework\TestCase;
use function WpRefs\Bots\TxtLib2\extract_templates_and_params;

class txtlib2Test extends TestCase
{

    private $text_input = "";
    private $json_data = [];
    private $temp_data = [];

    protected function setUp(): void
    {
        $this->text_input = file_get_contents(__DIR__ . "/texts/txtlib2.txt");
        $this->json_data = json_decode(file_get_contents(__DIR__ . "/texts/txtlib2.json"), true);
        $this->temp_data = extract_templates_and_params($this->text_input);
    }

    public function test_input_text_not_empty(): void
    {
        $this->assertNotEmpty($this->text_input, "Input text file is empty!");
    }

    public function test_json_data_not_empty(): void
    {
        $this->assertNotEmpty($this->json_data, "JSON file is empty or invalid!");
    }

    public function test_temp_data_not_empty(): void
    {
        $this->assertNotEmpty($this->temp_data, "No templates were extracted!");
    }

    public function test_first_template_name(): void
    {
        $this->assertEquals(
            "Infobox drug",
            $this->temp_data[0]["name"],
            "Template name does not match the expected value."
        );
    }

    public function test_first_template_item_matches_input(): void
    {
        $this->assertEquals(
            trim($this->text_input),
            trim($this->temp_data[0]["item"]),
            "Extracted template text does not match the original input."
        );
    }

    public function test_first_template_params(): void
    {
        // Check that the extracted parameters match the ones in the JSON file
        $this->assertEquals(
            $this->json_data[0]["params"],
            $this->temp_data[0]["params"],
            "Extracted parameters do not match the expected data."
        );
    }

    public function test_specific_param_values(): void
    {
        // Verify specific parameter values as an additional check
        $params = $this->temp_data[0]["params"];
        $this->assertArrayHasKey("tradename", $params);
        $this->assertEquals("Jaypirca", $params["tradename"]);

        $this->assertArrayHasKey("legal_US", $params);
        $this->assertEquals("Rx-only", $params["legal_US"]);

        $this->assertArrayHasKey("CAS_number", $params);
        $this->assertEquals("2101700-15-4", $params["CAS_number"]);
    }

    public function test_count_of_params(): void
    {
        // Verify that the number of extracted parameters matches the expected count
        $expected_count = count($this->json_data[0]["params"]);
        $actual_count = count($this->temp_data[0]["params"]);
        $this->assertSame(
            $expected_count,
            $actual_count,
            "Number of extracted parameters does not match the expected count."
        );
    }
}
