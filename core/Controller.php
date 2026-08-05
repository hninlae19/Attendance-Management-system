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

    // CSRF Protection
    public function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function validateCsrfToken($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            die("CSRF Token Validation Failed.");
        }
        return true;
    }
}
