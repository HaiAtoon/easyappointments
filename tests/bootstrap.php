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

// Load libraries for testing
require_once APPPATH . 'libraries/Pdf_utils.php';
require_once APPPATH . 'libraries/Document_generator.php';
