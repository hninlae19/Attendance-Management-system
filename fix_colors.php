<?php
$directory = new RecursiveDirectoryIterator('c:/wamp64/www/payrollsystem/views');
$iterator = new RecursiveIteratorIterator($directory);
$regex = '/\.(php|html)$/i';

$count = 0;

foreach ($iterator as $file) {
    if (preg_match($regex, $file->getFilename())) {
        $content = file_get_contents($file->getPathname());
        
        // Find inputs, selects, textareas that have bg-gray-50 or bg-gray-100 but no text-gray-xxx
        $updated = preg_replace_callback('/<(input|select|textarea)[^>]+class="([^"]+)"[^>]*>/i', function($matches) {
            $fullTag = $matches[0];
            $tagType = $matches[1];
            $classes = $matches[2];
            
            if (preg_match('/bg-gray-(50|100)/', $classes) && !preg_match('/text-gray-\d00/', $classes)) {
                // Prepend text-gray-900 to the classes
                $newClasses = 'text-gray-900 ' . $classes;
                $newTag = str_replace('class="' . $classes . '"', 'class="' . $newClasses . '"', $fullTag);
                return $newTag;
            }
            
            return $fullTag;
        }, $content);
        
        if ($updated !== null && $updated !== $content) {
            file_put_contents($file->getPathname(), $updated);
            echo "Updated: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}

echo "Finished updating $count files.\n";
?>
