<?php

namespace App\Routes;

abstract class Init{
    private $routes;

    abstract protected function initRoutes();
    public function __construct(){
        $this->initRoutes();
    }
    public function setRoutes(array $routes){
        $this->routes = $routes;
        $this->run($this->getUrl());
    }

    public function getRoutes(){
        return $this->routes;
    }
    /**
     * Will link the functionality array with its appropriate 
     * controllers and methods, if both the controller and the 
     * controller that has the method in question do not exist
     * 
     */
    protected function run($url){
        foreach ($this->getRoutes() as $key => $route){
            $pattern = preg_replace('/:\w+/', '(\w+)', $route['route']);
            $pattern = str_replace('/', '\/', $pattern);
            if (preg_match('/^' . $pattern . '$/', $url, $matches)) {
                array_shift($matches); 
                $class = "App\\Controller\\".ucfirst($route['controller']);
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
                return;
            }
        }
    }
    protected function getUrl(){
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
