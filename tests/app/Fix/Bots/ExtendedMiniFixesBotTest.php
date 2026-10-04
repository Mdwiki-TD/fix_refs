<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\MyFunctionTest;
use App\Fix\Bots\MiniFixesBot;

class ExtendedMiniFixesBotTest extends MyFunctionTest
{
    /**
     * Data provider for testing section title fixes across different languages.
     *
     * @return array<string, array{lang: string, input: string}>
     */
    public static function sectionTitlesProvider(): array
    {
        return [
            'Russian section titles processing' => [
                'lang' => 'ru',
                'input' => <<<'TXT'
== Ссылки  ==

====Ссылки====

== Примечания 3 ==
TXT
                ,
            ],
            'Swahili section titles processing' => [
                'lang' => 'sw',
                'input' => <<<'TXT'
== Marejeleo 1 ==

====Marejeleo====

=== Marejeleo ===
TXT
                ,
            ],
        ];
    }

    /**
     * Test section titles formatting and normalization per language.
     *
     * @param string $lang
     * @param string $input
     * @return void
     */
    #[DataProvider('sectionTitlesProvider')]
    public function testFixSectionTitles(string $lang, string $input): void
    {
        $newText = MiniFixesBot::fix_sections_titles($input, $lang);

        $this->assertNotEmpty($newText, "The function fix_sections_titles should return non-empty string.");

        // Assert that section titles were processed and modified
        $this->assertNotEquals(
            $input,
            $newText,
            "Expected section titles in '$lang' to be modified and normalized."
        );
    }
}
