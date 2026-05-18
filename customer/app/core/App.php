<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $this->loadController();
    }

    private function splitUrl()
    {
        $URL = $_GET['url'] ?? 'home';
        return explode('/', filter_var(trim($URL, '/'), FILTER_SANITIZE_URL));
    }

    private function loadController()
    {
        $URL = $this->splitUrl();
        $controllerName = ucfirst(strtolower($URL[0] ?? 'home'));
        $filename = "../app/controllers/" . $controllerName . ".php";

        if (file_exists($filename)) {
            require $filename;
            $this->controller = $controllerName;
            unset($URL[0]);
        } else {
            require "../app/controllers/_404.php";
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        if (isset($URL[1])) {
            $method = strtolower($URL[1]);
            if (method_exists($controller, $method)) {
                $this->method = $method;
                unset($URL[1]);
            }
        }

        $this->params = array_values($URL);
        call_user_func_array([$controller, $this->method], $this->params);
    }
}

$app = new App();
