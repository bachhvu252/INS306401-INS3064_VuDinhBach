<?php

// Autoloader
spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Core\Router;

$router = new Router();

// Register Routes
$router->add('GET', '/products', 'ProductController@index');
$router->add('GET', '/products/create', 'ProductController@create');
$router->add('POST', '/products/create', 'ProductController@store');

// Handle Request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Automatically detect and strip the base folder path (XAMPP subdirectories)
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Normalize URI
$uri = '/' . trim($uri, '/');

// DEBUG: Uncomment the line below if you still get a 404 to see what path is being matched
// echo "Debug: Method=$method, URI=$uri"; 

$router->handle($method, $uri);
