<?php
$files = [
    __DIR__ . '/controllers/AuthController.php',
    __DIR__ . '/controllers/AdminController.php',
    __DIR__ . '/controllers/EmployeeController.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'validateCsrfToken') === false) {
        // Find all if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $content = preg_replace(
            "/(if\s*\(\s*\\$_SERVER\['REQUEST_METHOD'\]\s*==\s*'POST'\s*\)\s*\{)/",
            "$1\n            \$this->validateCsrfToken(\$_POST['csrf_token'] ?? '');",
            $content
        );
        file_put_contents($file, $content);
        echo "Updated validation in: $file\n";
    }
}
