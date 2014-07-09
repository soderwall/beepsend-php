<?php

/* Set error reporting */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

/* Set default values */
define('BASE_API_URL', 'https://api.beepsend.com');
define('API_VERSION', 2);

/* Register autoloader */
require_once __DIR__ . '/../vendor/autoload.php';