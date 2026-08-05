<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/views');
$iterator = new RecursiveIteratorIterator($dir);
$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Regex to match <form ... method="POST" ...>
        // Use a lookahead or just simple replacement
        // Only replace if it doesn't already have csrf_token
        if (strpos($content, 'name="csrf_token"') === false && preg_match('/<form[^>]*method="POST"[^>]*>/i', $content)) {
            $newContent = preg_replace_callback('/(<form[^>]*method="POST"[^>]*>)/i', function($matches) {
                return $matches[1] . "\n    <input type=\"hidden\" name=\"csrf_token\" value=\"<?= \$this->generateCsrfToken() ?>\">\n";
            }, $content);
            
            if ($newContent !== $content) {
                file_put_contents($file->getPathname(), $newContent);
                echo "Updated: " . $file->getPathname() . "\n";
                $count++;
            }
        }
    }
}
echo "Total files updated: $count\n";
