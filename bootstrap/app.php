<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Wbpms\Http\Middleware\AuthMiddleware;
use Wbpms\Http\Routing\Router;
use Wbpms\Infrastructure\Database\Connection;
use Wbpms\Infrastructure\Session\DatabaseSessionHandler;

// Load local environment variables when a .env file exists.
// Production environments such as Railway inject variables directly.
$dotenv = Dotenv::createUnsafeMutable(APP_ROOT);

if (is_file(APP_ROOT . '/.env')) {
    $dotenv->load();
}

// Populate $_ENV from process environment variables when necessary.
// This supports PHP installations using variables_order=GPCS,
// where Railway-injected variables may be available through getenv()
// but are not automatically populated into $_ENV.
$_wbpmsProcessEnv = getenv();

if (is_array($_wbpmsProcessEnv)) {
    foreach ($_wbpmsProcessEnv as $_wbpmsKey => $_wbpmsVal) {
        if (!array_key_exists($_wbpmsKey, $_ENV)) {
            $_ENV[$_wbpmsKey] = $_wbpmsVal;
        }
    }

    unset($_wbpmsProcessEnv, $_wbpmsKey, $_wbpmsVal);
}

$requiredVariables = [
    'APP_ENV',
    'APP_BASE_URL',
    'APP_KEY',
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_PASSWORD',
];

foreach ($requiredVariables as $key) {
    if (!isset($_ENV[$key])) {
        $value = getenv($key);

        if ($value !== false) {
            $_ENV[$key] = $value;
        }
    }
}

// Validate environment configuration before starting the application.
$dotenv->required($requiredVariables)->notEmpty();

// Configure PHP error reporting.
$appEnv = $_ENV['APP_ENV'] ?? 'production';

if ($appEnv === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// Business timezone for WBPMS.
// Database timestamps are stored in UTC.
date_default_timezone_set('Asia/Manila');

// Initialize database-backed sessions for web requests.
if (PHP_SAPI !== 'cli') {
    $dbConfig = require APP_ROOT . '/config/database.php';
    $connection = new Connection($dbConfig);

    $sessionHandler = new DatabaseSessionHandler(
        $connection->pdo(),
        1800,  // 30-minute idle timeout
        43200  // 12-hour absolute timeout
    );

    session_set_save_handler($sessionHandler, true);
    AuthMiddleware::startSession();
}

// Initialize router and register application routes.
$router = new Router();
require APP_ROOT . '/routes/web.php';

return $router;
