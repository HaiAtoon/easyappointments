<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Define CI constants needed by the codebase
define('BASEPATH', __DIR__ . '/../system/');
define('APPPATH', __DIR__ . '/../application/');
define('FCPATH', __DIR__ . '/../');
define('ENVIRONMENT', 'testing');

// Stub functions that depend on CI framework before loading helpers
if (!function_exists('log_message')) {
    function log_message(string $level, string $message): void
    {
    }
}

// Config class stub for config() helper
if (!class_exists('Config')) {
    class Config
    {
        const BASE_URL = 'http://localhost/ea';
        const LANGUAGE = 'english';
        const DEBUG_MODE = true;
    }
}

// Load helpers
require_once APPPATH . 'helpers/config_helper.php';

// Stub lang() if not already defined by CI
if (!function_exists('lang')) {
    function lang(string $key): string
    {
        return $key;
    }
}

// Stub setting() if not already defined
if (!function_exists('setting')) {
    function setting($key = null)
    {
        return '';
    }
}

// Stub CI_Model for model tests
if (!class_exists('CI_Model')) {
    class CI_Model
    {
        public $db;
    }
}

// Stub for DB result objects in model tests
class MockDbResult
{
    private int $rows;

    public function __construct(int $rows = 0)
    {
        $this->rows = $rows;
    }

    public function num_rows(): int
    {
        return $this->rows;
    }

    public function row_array(): ?array
    {
        return $this->rows > 0 ? ['id' => 1] : null;
    }

    public function result_array(): array
    {
        return [];
    }
}

class MockDb
{
    private int $defaultRows;

    public function __construct(int $defaultRows = 0)
    {
        $this->defaultRows = $defaultRows;
    }

    public function get_where(string $table, array $where = []): MockDbResult
    {
        return new MockDbResult($this->defaultRows);
    }
}

// Load core classes
require_once APPPATH . 'core/EA_Model.php';

// Load libraries
require_once APPPATH . 'libraries/Pdf_utils.php';
require_once APPPATH . 'libraries/Document_generator.php';

// Load HTMLPurifier for sanitization tests
require_once __DIR__ . '/../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';

// Load html helper for pure_html()
require_once APPPATH . 'helpers/html_helper.php';
