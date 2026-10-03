<?php

namespace App;

/**
 * CSRF Token Management for MDWiki Tools.
 *
 * Provides class methods to generate and verify CSRF tokens for form protection.
 */
class Csrf
{
	public const CSRF_SESSION_KEY = "csrf_tokens";

	private static function initSession(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}
	/**
	 * Generate a new CSRF token for form protection.
	 *
	 * Creates a cryptographically secure random token and stores it
	 * in the session for later verification. Each token is single-use.
	 *
	 * @return string The generated 64-character hex token
	 */
	public static function generateToken(): string
	{
		self::initSession();

		$token = bin2hex(random_bytes(32));
		if (!isset($_SESSION[self::CSRF_SESSION_KEY])) {
			$_SESSION[self::CSRF_SESSION_KEY] = [];
		}
		$_SESSION[self::CSRF_SESSION_KEY][] = $token;
		return $token;
	}

	/**
	 * Verify the CSRF token submitted with a POST request.
	 *
	 * Checks if the submitted token exists in the session's token list.
	 * Tokens are single-use and removed after successful verification.
	 *
	 * @return bool True if the token is valid, false otherwise
	 */
	public static function verifyToken(): bool
	{
		self::initSession();

		// Initialize empty token array if not set
		if (!isset($_SESSION[self::CSRF_SESSION_KEY]) || !is_array($_SESSION[self::CSRF_SESSION_KEY])) {
			$_SESSION[self::CSRF_SESSION_KEY] = [];
			// Security: No tokens in session means form was not properly initialized
			return false;
		}

		// Get the submitted token
		$submittedToken = $_POST['csrf_token'] ?? null;

		// No token submitted - verification fails
		if (!$submittedToken) {
			return false;
		}

		// Check if token exists in the valid tokens list
		if (in_array($submittedToken, $_SESSION[self::CSRF_SESSION_KEY], true)) {
			// Valid token - remove it (single use)
			$_SESSION[self::CSRF_SESSION_KEY] = array_values(
				array_diff($_SESSION[self::CSRF_SESSION_KEY], [$submittedToken])
			);
			return true;
		}

		// Invalid or reused token
		return false;
	}
}
