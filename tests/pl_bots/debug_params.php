<?php

// Debug test to see what parameters are in the template

require_once __DIR__ . '/../../src/include_files.php';

use function WikiParse\Template\getTemplates;

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
