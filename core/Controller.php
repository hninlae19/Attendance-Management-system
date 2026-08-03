<?php
class Controller {
    // Load model
    public function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        if(file_exists(__DIR__ . '/../views/' . $view . '.php')) {
            require_once __DIR__ . '/../views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }

    // Redirect helper
    public function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}
