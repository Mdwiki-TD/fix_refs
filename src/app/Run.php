<?php

namespace App;

use App\LanguageSettings;
use App\Fix\Index;

class Run
{
    /**
     * Get language-specific settings or fallback defaults.
     *
     * @return array{move_dots: bool, expand: bool, add_en_lang: bool}
     */
    public static function getLangSettings(string $langcode): array
    {
        $setting = LanguageSettings::loadSettings();
        $langDefault = isset($setting[$langcode]) && is_array($setting[$langcode])
            ? $setting[$langcode]
            : [];

        return [
            'move_dots'  => isset($langDefault['move_dots']) && (int)$langDefault['move_dots'] === 1,
            'expand'     => true, // (isset($langDefault['expend']) && (int)$langDefault['expend'] === 1),
            'add_en_lang' => isset($langDefault['add_en_lang']) && (int)$langDefault['add_en_lang'] === 1,
        ];
    }

    public static function fixPgeWithSetting(
        string $sourcetitle,
        string $title,
        string $text,
        string $lang,
        int|string $mdwikiRevid,
        ?bool $moveDots = null,
        ?bool $expand = null,
        ?bool $addEnLang = null
    ): string {
        if ($moveDots === null && $expand === null && $addEnLang === null) {
            $settings = self::getLangSettings($lang);
            $moveDots  = $settings['move_dots'];
            $expand    = $settings['expand'];
            $addEnLang = $settings['add_en_lang'];
        }

        $newtext = Index::fixPage(
            $text,
            $title,
            $moveDots,
            $expand,
            $addEnLang,
            $lang,
            $sourcetitle,
            $mdwikiRevid
        );

        return !empty($newtext) ? (string)$newtext : $text;
    }
}
