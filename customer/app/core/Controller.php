<?php

class Controller
{
    public function view($name, $data = [])
    {
        extract($data);

        $filename = "../app/views/" . $name . ".view.php";
        if (!file_exists($filename)) {
            $filename = "../app/views/404.view.php";
        }

        require $filename;
    }

    public function model($name)
    {
        $filename = "../app/models/" . $name . ".php";
        if (file_exists($filename)) {
            require_once $filename;
            return new $name();
        }

        throw new Exception("Model {$name} not found.");
    }
}
