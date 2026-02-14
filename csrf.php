<?php

namespace WpRefs\csrf;

/**
 * CSRF Token Management for MDWiki Tools.
 *
 * Provides functions to generate and verify CSRF tokens for form protection.
 *
 * Usage:
 *   include_once __DIR__ . '/csrf.php';
 *   use function WpRefs\csrf\generate_csrf_token;
 *   use function WpRefs\csrf\verify_csrf_token;
 *
 * @package WpRefs\csrf
 * @author MDWiki Team
 */

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

/**
 * Verify the CSRF token submitted with a POST request.
 *
 * Checks if the submitted token exists in the session's token list.
 * Tokens are single-use and removed after successful verification.
 *
 * @return bool True if the token is valid, false otherwise
 */
function verify_csrf_token(): bool
{
	$csrf_key = "csrf_tokens";

	// Initialize empty token array if not set
	if (!isset($_SESSION[$csrf_key]) || !is_array($_SESSION[$csrf_key])) {
		$_SESSION[$csrf_key] = [];
		// Security: No tokens in session means form was not properly initialized
		return false;
	}

	// Get the submitted token
	$submitted_token = $_POST['csrf_token'] ?? null;

	// No token submitted - verification fails
	if (!$submitted_token) {
		return false;
	}

	// Check if token exists in the valid tokens list
	if (in_array($submitted_token, $_SESSION[$csrf_key], true)) {
		// Valid token - remove it (single use)
		$_SESSION[$csrf_key] = array_values(
			array_diff($_SESSION[$csrf_key], [$submitted_token])
		);
		return true;
	}

	// Invalid or reused token
	return false;
}

/**
 * Generate a new CSRF token for form protection.
 *
 * Creates a cryptographically secure random token and stores it
 * in the session for later verification. Each token is single-use.
 *
 * @return string The generated 64-character hex token
 */
function generate_csrf_token(): string
{
	$token = bin2hex(random_bytes(32));
	if (!isset($_SESSION['csrf_tokens'])) {
		$_SESSION['csrf_tokens'] = [];
	}
	$_SESSION['csrf_tokens'][] = $token;
	return $token;
}
