<?php

namespace App\Wikibots;

class Wikitext
{
	public static function from_api(string $title, string $lang): string
	{
		$usrAgent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)';
		$url = "https://{$lang}.wikipedia.org/w/api.php";
		$data = [
			'action' => 'query',
			'format' => 'json',
			'prop' => 'revisions',
			'rvslots' => '*',
			'rvprop' => 'content',
			'titles' => $title,
		];

		// echo $url . '?' . http_build_query($data, '', '&', PHP_QUERY_RFC3986) . "<br>";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $usrAgent);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&', PHP_QUERY_RFC3986));
		$response = curl_exec($ch);
		curl_close($ch);

		if (!is_string($response)) {
			return '';
		}

		/** @var array<string, mixed>|null $json */
		$json = json_decode($response, true);

		/** @var array<string, mixed> $pages */
		$pages = $json['query']['pages'] ?? [];

		foreach ($pages as $page) {
			if (is_array($page)) {
				$text = $page['revisions'][0]['slots']['main']['*'] ?? '';
				if (is_string($text) && !empty($text)) {
					return $text;
				}
			}
		}

		return '';
	}

	public static function from_rest(string $title, string $lang): string
	{
		$usrAgent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)';

		$title = str_replace("/", "%2F", $title);

		$url = "https://{$lang}.wikipedia.org/w/rest.php/v1/page/{$title}";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $usrAgent);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		$output = curl_exec($ch);
		curl_close($ch);

		if (!is_string($output)) {
			return '';
		}

		/** @var array<string, mixed>|null $json */
		$json = json_decode($output, true);
		// var_export(json_encode($json, JSON_PRETTY_PRINT));

		if (is_array($json) && isset($json['source']) && is_string($json['source'])) {
			return $json['source'];
		}
		return '';
	}

	public static function get_wikipedia_text(string $title, string $lang): string
	{
		// replace / with "%2F"

		$text = self::from_api($title, $lang);

		if (empty($text)) {
			$text = self::from_rest($title, $lang);
		}

		return $text;
	}
}
