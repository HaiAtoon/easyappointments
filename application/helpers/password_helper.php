<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.0.0
 * ---------------------------------------------------------------------------- */

/**
 * Hash a password using Argon2id (preferred) or SHA-256 (legacy fallback).
 *
 * @param string $salt Salt value (used for legacy SHA-256 hashing, ignored for Argon2id).
 * @param string $password Plaintext password.
 *
 * @return string Hashed password string.
 *
 * @throws InvalidArgumentException
 */
function hash_password(string $salt, string $password): string
{
    if (strlen($password) > MAX_PASSWORD_LENGTH) {
        throw new InvalidArgumentException('The provided password is too long, please use a shorter value.');
    }

    if (defined('PASSWORD_ARGON2ID')) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3,
        ]);
    }

    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify a password against a hash.
 *
 * Supports both Argon2id/bcrypt (new) and legacy SHA-256 hashes.
 *
 * @param string $password Plaintext password to verify.
 * @param string $hash Stored hash to verify against.
 * @param string $salt Salt for legacy SHA-256 verification.
 *
 * @return bool True if password matches.
 */
function verify_password(string $password, string $hash, string $salt = ''): bool
{
    if (str_starts_with($hash, '$argon2') || str_starts_with($hash, '$2y$')) {
        return password_verify($password, $hash);
    }

    return hash_equals($hash, hash_password_legacy($salt, $password));
}

/**
 * Check if a password hash needs rehashing (upgrade from legacy to Argon2id).
 *
 * @param string $hash Stored hash.
 *
 * @return bool True if hash should be regenerated with modern algorithm.
 */
function ea_password_needs_rehash(string $hash): bool
{
    if (!str_starts_with($hash, '$argon2') && !str_starts_with($hash, '$2y$')) {
        return true;
    }

    $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;

    return \password_needs_rehash($hash, $algo);
}

/**
 * Legacy SHA-256 password hashing (for verifying old passwords only).
 *
 * @param string $salt Salt value.
 * @param string $password Plaintext password.
 *
 * @return string Legacy hash string.
 */
function hash_password_legacy(string $salt, string $password): string
{
    $half = (int) (strlen($salt) / 2);

    $hash = hash('sha256', substr($salt, 0, $half) . $password . substr($salt, $half));

    for ($i = 0; $i < 100000; $i++) {
        $hash = hash('sha256', $hash);
    }

    return $hash;
}

/**
 * Generate a new password salt.
 *
 * @return string Returns a cryptographically secure salt string.
 */
function generate_salt(): string
{
    return bin2hex(random_bytes(32));
}
