<?php

namespace api;
use api\Config\Database;


class Router {
    
    private array $routes = [];
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getRouter(string $method, string $uri, string $action) 
    {
        $this->routes[] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    public function requestHandler() 
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $route) {
            if ($requestMethod === $route['method'] && preg_match("#^" . $route['uri'] . "$#", $requestUri, $matches)) {
                list($class, $method) = explode('@', $route['action']);

                $class = 'app\\Controllers\\' . $class;

                if (class_exists($class)) {
                    $controller = new $class($this->db);
                    if (method_exists($controller, $method)) {
                        array_shift($matches);
                        return call_user_func_array([$controller, $method], $matches);
                    }
                }
            }
        }
        http_response_code(404);
        echo json_encode(["message" => "ENDPOINT NOT FOUND"]);
    }
}
