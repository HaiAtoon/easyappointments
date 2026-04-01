<?php

namespace Tests\Unit\Config;

use PHPUnit\Framework\TestCase;

class SecurityConfigTest extends TestCase
{
    private string $appConfigPath;
    private string $routesPath;
    private string $databasePath;
    private string $rootConfigPath;
    private string $bootstrapPath;

    protected function setUp(): void
    {
        $this->appConfigPath = APPPATH . 'config/config.php';
        $this->routesPath = APPPATH . 'config/routes.php';
        $this->databasePath = APPPATH . 'config/database.php';
        $this->rootConfigPath = FCPATH . 'config.php';
        $this->bootstrapPath = __DIR__ . '/../../bootstrap.php';
    }

    public function test_https_enforcement_code_exists(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertStringContainsString('FORCE_HTTPS', $content);
        $this->assertStringContainsString("header('Location: https://", $content);
        $this->assertMatchesRegularExpression('/header\s*\(\s*[\'"]Location:\s*https:\/\//', $content);
    }

    public function test_https_redirect_uses_301_status(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertMatchesRegularExpression('/header\s*\(.+301\s*\)/', $content);
    }

    public function test_hsts_header_code_exists(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertStringContainsString('Strict-Transport-Security', $content);
        $this->assertStringContainsString('max-age=', $content);
        $this->assertStringContainsString('includeSubDomains', $content);
    }

    public function test_hsts_only_sent_over_https(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertMatchesRegularExpression(
            '/protocol\s*===\s*[\'"]https:\/\/[\'"].*Strict-Transport-Security/s',
            $content
        );
    }

    public function test_cors_uses_config_constant_not_hardcoded_wildcard(): void
    {
        $content = file_get_contents($this->routesPath);

        $this->assertStringContainsString('CORS_ALLOWED_ORIGINS', $content);
        $this->assertMatchesRegularExpression(
            '/defined\s*\(\s*[\'"]Config::CORS_ALLOWED_ORIGINS[\'"]\s*\)/',
            $content
        );
    }

    public function test_cors_validates_origin_against_allowed_list(): void
    {
        $content = file_get_contents($this->routesPath);

        $this->assertStringContainsString('in_array($origin', $content);
        $this->assertStringContainsString('$allowed_list', $content);
    }

    public function test_session_match_ip_is_true(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertMatchesRegularExpression(
            '/\$config\s*\[\s*[\'"]sess_match_ip[\'"]\s*\]\s*=\s*TRUE\s*;/',
            $content
        );
    }

    public function test_cookie_httponly_is_true(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertMatchesRegularExpression(
            '/\$config\s*\[\s*[\'"]cookie_httponly[\'"]\s*\]\s*=\s*TRUE\s*;/',
            $content
        );
    }

    public function test_cookie_samesite_is_strict(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertMatchesRegularExpression(
            '/\$config\s*\[\s*[\'"]cookie_samesite[\'"]\s*\]\s*=\s*[\'"]Strict[\'"]\s*;/',
            $content
        );
    }

    public function test_session_expiration_is_at_most_7200(): void
    {
        $content = file_get_contents($this->appConfigPath);

        preg_match('/\$config\s*\[\s*[\'"]sess_expiration[\'"]\s*\]\s*=\s*(\d+)\s*;/', $content, $matches);
        $this->assertNotEmpty($matches, 'sess_expiration setting not found');

        $expiration = (int)$matches[1];
        $this->assertLessThanOrEqual(7200, $expiration);
    }

    public function test_database_ssl_support_code_exists(): void
    {
        $content = file_get_contents($this->databasePath);

        $this->assertStringContainsString('DB_SSL', $content);
        $this->assertStringContainsString("defined('Config::DB_SSL')", $content);
        $this->assertStringContainsString('ssl_verify', $content);
        $this->assertMatchesRegularExpression('/[\'"]ssl_verify[\'"]\s*=>\s*true/', $content);
    }

    public function test_database_ssl_requires_key_cert_ca(): void
    {
        $content = file_get_contents($this->databasePath);

        $this->assertStringContainsString('ssl_key', $content);
        $this->assertStringContainsString('ssl_cert', $content);
        $this->assertStringContainsString('ssl_ca', $content);
    }

    public function test_encryption_key_is_valid_hex_and_sufficient_length(): void
    {
        $key = \Config::ENCRYPTION_KEY;

        $this->assertMatchesRegularExpression('/^[0-9a-fA-F]+$/', $key, 'ENCRYPTION_KEY must be hex');
        $this->assertGreaterThanOrEqual(64, strlen($key), 'ENCRYPTION_KEY must be >= 64 chars');
    }

    public function test_encryption_key_not_derived_from_apppath(): void
    {
        $bootstrapContent = file_get_contents($this->bootstrapPath);

        preg_match("/const\s+ENCRYPTION_KEY\s*=\s*['\"]([^'\"]+)['\"]/", $bootstrapContent, $matches);
        $this->assertNotEmpty($matches, 'ENCRYPTION_KEY constant not found in bootstrap');

        $key = $matches[1];
        $this->assertStringNotContainsString('APPPATH', $key);
        $this->assertNotEquals(md5(APPPATH), $key);
    }

    public function test_cookie_secure_is_dynamic_based_on_protocol(): void
    {
        $content = file_get_contents($this->appConfigPath);

        $this->assertMatchesRegularExpression(
            '/\$config\s*\[\s*[\'"]cookie_secure[\'"]\s*\]\s*=.*https/',
            $content
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\$config\s*\[\s*[\'"]cookie_secure[\'"]\s*\]\s*=\s*(TRUE|FALSE|true|false)\s*;/',
            $content,
            'cookie_secure should not be hardcoded to a static boolean'
        );
    }
}
