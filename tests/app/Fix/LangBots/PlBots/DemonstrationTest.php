<?php

namespace Tests;

// Demonstration of Polish Choroba Infobox Parameter Addition Feature
use Tests\MyFunctionTest;
use function App\Fix\Index\fix_page;

class DemonstrationTest extends MyFunctionTest
{
    /**
     * Test and demonstrate the addition of missing medical parameters to the Polish Choroba Infobox.
     *
     * @return void
     */
    public function testChorobaInfoboxParameterAddition(): void
    {
        $originalArticle = <<<'ARTICLE'
{{Choroba infobox
|nazwa polska = Astma oskrzelowa
|obraz = Blausen 0620 Lungs NormalvsInflamedAirway.png
|opis obrazu = Normalne drogi oddechowe i drogi oddechowe astmatyka
}}

'''Astma oskrzelowa''' (łac. ''asthma bronchiale'') – przewlekła choroba zapalna dróg oddechowych, charakteryzująca się napadami duszności.

== Epidemiologia ==
Astma jest jedną z najczęstszych chorób przewlekłych na świecie.

== Objawy ==
* Duszność
* Kaszel
* Świszczący oddech

== Leczenie ==
Leczenie astmy obejmuje stosowanie leków wziewnych.

== Zobacz też ==
* [[Choroba obturacyjna płuc]]

== Przypisy ==
<references />

[[Kategoria:Choroby układu oddechowego]]
ARTICLE;

        // Process the article through fix_page function for Polish language
        $processedArticle = fix_page(
            $originalArticle,
            "Astma oskrzelowa", // title
            false,               // move_dots
            true,                // infobox expansion enabled
            false,               // add_en_lang
            "pl",                // language: Polish
            "Asthma",           // sourcetitle (English)
            "123456"            // mdwiki_revid
        );

        // Verify that the infobox was preserved in the processed article
        $this->assertStringContainsString('{{Choroba infobox', $processedArticle);

        // Expected medical classification parameters
        $expectedParams = [
            'nazwa naukowa',
            'ICD11',
            'ICD11 nazwa',
            'ICD10',
            'ICD10 nazwa',
            'DSM-5',
            'DSM-5 nazwa',
            'DSM-IV',
            'DSM-IV nazwa',
            'ICDO',
            'DiseasesDB',
            'OMIM',
            'MedlinePlus',
            'MeshID',
            'commons',
        ];

        // Assert that each medical parameter was successfully added to the infobox
        foreach ($expectedParams as $param) {
            $this->assertStringContainsString(
                $param,
                $processedArticle,
                "Expected parameter '$param' was not found in the processed infobox."
            );
        }
    }
}
