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
     * Verify the CSRF token submitted with a POST request.
     *
     * @return bool True if the token is valid, false otherwise
     */
    public static function verify_csrf_token(): bool
    {
        self::initSession();

        if (!isset($_SESSION[self::CSRF_SESSION_KEY]) || !is_array($_SESSION[self::CSRF_SESSION_KEY])) {
            $_SESSION[self::CSRF_SESSION_KEY] = [];
            return false;
        }

        $submittedToken = $_POST['csrf_token'] ?? null;

        if (!$submittedToken) {
            return false;
        }

        if (in_array($submittedToken, $_SESSION[self::CSRF_SESSION_KEY], true)) {
            $_SESSION[self::CSRF_SESSION_KEY] = array_values(
                array_diff($_SESSION[self::CSRF_SESSION_KEY], [$submittedToken])
            );
            return true;
        }

        return false;
    }

    /**
     * Generate a new CSRF token for form protection.
     *
     * @return string The generated 64-character hex token
     */
    public static function generate_csrf_token(): string
    {
        self::initSession();

        $token = bin2hex(random_bytes(32));
        if (!isset($_SESSION[self::CSRF_SESSION_KEY])) {
            $_SESSION[self::CSRF_SESSION_KEY] = [];
        }
        $_SESSION[self::CSRF_SESSION_KEY][] = $token;
        return $token;
    }
}
