<?php
// TODO: Convert this file to class

// Debug test to see what parameters are in the template

use FixRefs\Tests\MyFunctionTest;
use function WikiParse\Template\getTemplates;

class DebugParams extends MyFunctionTest
{
}
$input = <<<'TXT'
{{Choroba infobox
|nazwa polska = Astma
|ICD10 = J45
|MeshID = D001249
}}
TXT;

echo "Debugging parameter names:\n";
echo "==========================\n\n";

$temps = getTemplates($input);

foreach ($temps as $temp) {
    $name = $temp->getStripName();
    echo "Template name: $name\n";

    $params = $temp->getParameters();
    echo "Parameters:\n";
    foreach ($params as $key => $value) {
        echo "  Key: '" . $key . "' => Value: '" . $value . "'\n";
    }
}
