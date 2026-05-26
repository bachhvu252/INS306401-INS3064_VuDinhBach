<?php

namespace Core;

class Router {
    protected $routes = [];

    public function add($method, $uri, $controller) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public function handle($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                list($controllerName, $action) = explode('@', $route['controller']);
                $controllerClass = "App\\Controllers\\" . $controllerName;
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $action)) {
                        return $controller->$action();
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
}
