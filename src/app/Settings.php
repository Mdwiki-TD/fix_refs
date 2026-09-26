<?php

namespace WpRefs\Settings;

use function WpRefs\TestBot\echo_test;

function get_curl(string $url): string
{
    $usrAgent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $usrAgent);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $output = curl_exec($ch);
    if ($output === false) {
        echo_test("<br>cURL Error: " . curl_error($ch) . "<br>$url");
        $output = '';
    }

    curl_close($ch);

    return $output;
}

function json_load_file(string $filename): array
{
    if (!is_file($filename)) {
        return [];
    }

    $content = file_get_contents($filename);
    if ($content === false || $content === '') {
        return [];
    }

    /** @var array<string, mixed>|null $decoded */
    $decoded = json_decode($content, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * @return array<string, mixed>
 */
function loadSettings(): array
{
    $url = "http://localhost:9001/api.php?get=language_settings";
    if (($_SERVER['SERVER_NAME'] ?? '') === 'mdwiki.toolforge.org') {
        $url = "https://mdwiki.toolforge.org/api.php?get=language_settings";
        $remoteData = get_curl($url);
    } else {
        $remoteData = @file_get_contents($url);
        if ($remoteData === false) {
            $remoteData = '';
        }
    }

    /** @var array<string, mixed>|null $decoded */
    $decoded = json_decode($remoteData, true);

    if (!is_array($decoded) || empty($decoded['results'])) {
        $localFile = __DIR__ . '/resources/language_settings.json';
        $decoded = json_load_file($localFile);
    }

    $data = $decoded['results'] ?? [];
    $new = [];
    if (is_array($data)) {
        foreach ($data as $value) {
            if (is_array($value) && isset($value['lang_code'])) {
                $new[(string)$value['lang_code']] = $value;
            }
        }
    }

    return $new;
}
