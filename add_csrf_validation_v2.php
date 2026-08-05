<?php
$files = [
    __DIR__ . '/controllers/AuthController.php',
    __DIR__ . '/controllers/AdminController.php',
    __DIR__ . '/controllers/EmployeeController.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'validateCsrfToken') === false) {
        $content = str_replace(
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST') {",
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST') {\n            \$this->validateCsrfToken(\$_POST['csrf_token'] ?? '');",
            $content
        );
        
        $content = str_replace(
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST' && isset(\$_POST['action'])) {",
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST' && isset(\$_POST['action'])) {\n            \$this->validateCsrfToken(\$_POST['csrf_token'] ?? '');",
            $content
        );
        
        $content = str_replace(
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST' && isset(\$_POST['action']) && \$_POST['action'] === 'correction') {",
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST' && isset(\$_POST['action']) && \$_POST['action'] === 'correction') {\n            \$this->validateCsrfToken(\$_POST['csrf_token'] ?? '');",
            $content
        );

        $content = str_replace(
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST' && isset(\$_POST['action']) && \$_POST['action'] === 'apply') {",
            "if (\$_SERVER['REQUEST_METHOD'] == 'POST' && isset(\$_POST['action']) && \$_POST['action'] === 'apply') {\n            \$this->validateCsrfToken(\$_POST['csrf_token'] ?? '');",
            $content
        );

        file_put_contents($file, $content);
        echo "Updated validation in: $file\n";
    }
}
