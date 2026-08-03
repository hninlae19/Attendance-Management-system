<?php
$directory = __DIR__ . '/views';
$controllers = __DIR__ . '/controllers';

function replaceInDir($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            // Replace href="/ with href="/payrollsystem/
            $content = preg_replace('/href="\/([a-zA-Z0-9_]+)/', 'href="/payrollsystem/$1', $content);
            // Replace action="/ with action="/payrollsystem/
            $content = preg_replace('/action="\/([a-zA-Z0-9_]+)/', 'action="/payrollsystem/$1', $content);
            
            file_put_contents($file->getPathname(), $content);
        }
    }
}

replaceInDir($directory);

function replaceInControllers($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            // Replace redirect('/ with redirect('/payrollsystem/
            $content = preg_replace('/redirect\(\'\/([a-zA-Z0-9_]+)/', 'redirect(\'/payrollsystem/$1', $content);
            $content = preg_replace('/redirect\("\/([a-zA-Z0-9_]+)/', 'redirect("/payrollsystem/$1', $content);
            file_put_contents($file->getPathname(), $content);
        }
    }
}

replaceInControllers($controllers);

echo "Paths fixed.\n";
