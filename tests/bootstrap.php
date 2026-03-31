<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Define CI constants needed by the codebase
define('BASEPATH', __DIR__ . '/../system/');
define('APPPATH', __DIR__ . '/../application/');
define('FCPATH', __DIR__ . '/../');
define('ENVIRONMENT', 'testing');

// Stub CI framework functions
if (!function_exists('log_message')) {
    function log_message(string $level, string $message): void
    {
    }
}

if (!function_exists('show_error')) {
    function show_error($message, $status_code = 500)
    {
        throw new RuntimeException($message);
    }
}

// Stub CI config object
class MockCiConfig
{
    private array $items = ['language' => 'english'];

    public function item(string $key)
    {
        return $this->items[$key] ?? null;
    }
}

if (!function_exists('get_instance')) {
    function &get_instance()
    {
        static $instance;
        if (!$instance) {
            $instance = new stdClass();
            $instance->config = new MockCiConfig();
        }
        return $instance;
    }
}

if (!function_exists('site_url')) {
    function site_url(string $path = ''): string
    {
        return 'http://localhost/ea/' . $path;
    }
}

// Config class stub
if (!class_exists('Config')) {
    class Config
    {
        const BASE_URL = 'http://localhost/ea';
        const LANGUAGE = 'english';
        const DEBUG_MODE = true;
    }
}

// Stub CI_Model
if (!class_exists('CI_Model')) {
    class CI_Model
    {
        public $db;
    }
}

// Mock DB classes for model tests
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

    public function select(): self { return $this; }
    public function from(): self { return $this; }
    public function join(): self { return $this; }
    public function where(): self { return $this; }
    public function get(): MockDbResult { return new MockDbResult($this->defaultRows); }
}

// Load constants (needed by password_helper, etc.)
require_once APPPATH . 'config/constants.php';

// Load helpers
require_once APPPATH . 'helpers/config_helper.php';
require_once APPPATH . 'helpers/array_helper.php';
require_once APPPATH . 'helpers/date_helper.php';
require_once APPPATH . 'helpers/validation_helper.php';
require_once APPPATH . 'helpers/password_helper.php';

// Load HTMLPurifier
require_once __DIR__ . '/../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';
require_once APPPATH . 'helpers/html_helper.php';

// Stub lang() after helpers
if (!function_exists('lang')) {
    function lang(string $key): string
    {
        return $key;
    }
}

// Stub setting() with configurable return values for tests
if (!function_exists('setting')) {
    function setting($key = null)
    {
        $defaults = [
            'date_format' => 'DMY',
            'time_format' => 'regular',
            'require_first_name' => '1',
            'require_last_name' => '1',
            'require_email' => '1',
        ];

        if (is_string($key)) {
            return $defaults[$key] ?? '';
        }

        return '';
    }
}

// Load core classes
require_once APPPATH . 'core/EA_Model.php';

// Load libraries
require_once APPPATH . 'libraries/Pdf_utils.php';
require_once APPPATH . 'libraries/Document_generator.php';
