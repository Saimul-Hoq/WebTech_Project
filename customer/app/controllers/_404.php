<?php

class _404 extends Controller
{
    public function index()
    {
        http_response_code(404);
        $this->view('404', ['title' => 'Page Not Found']);
    }
}
