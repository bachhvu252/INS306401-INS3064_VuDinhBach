<?php

namespace Core;

class Controller {
    public function view($path, $data = []) {
        extract($data);
        $file = "../app/Views/" . $path . ".php";
        if (file_exists($file)) {
            require_once $file;
        } else {
            die("View $path not found");
        }
    }
}
