<?php

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : ($_SERVER['REQUEST_SCHEME'] ?? 'http');
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/customer/public/index.php');
$publicPath = rtrim(str_replace('index.php', '', $script), '/') . '/';
$projectPath = preg_replace('#/customer/public/?$#', '/', $publicPath);

define('ROOT', $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $publicPath);
define('PROJECT_ROOT', $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $projectPath);

define('APP_NAME', 'ShopHub');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_marketplace');
