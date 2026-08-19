$viewsDir = "c:\wamp64\www\payrollsystem\views"
$files = Get-ChildItem -Path $viewsDir -Recurse -Filter *.php

$count = 0
foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    
    # We want to match <input ... class="..." ...> where class contains bg-gray-50 or bg-gray-100 but no text-gray-[0-9]00
    # PowerShell regex replace with evaluation is a bit tricky, but we can do a simpler match.
    $pattern = '(?i)<(?:input|select|textarea)[^>]+class="([^"]*bg-gray-(?:50|100)[^"]*)"[^>]*>'
    
    $newContent = [regex]::Replace($content, $pattern, {
        param($match)
        $fullMatch = $match.Value
        $classes = $match.Groups[1].Value
        
        if ($classes -notmatch 'text-gray-\d00') {
            $newClasses = "text-gray-900 " + $classes
            return $fullMatch.Replace("class=`"$classes`"", "class=`"$newClasses`"")
        }
        return $fullMatch
    })
    
    if ($newContent -ne $content) {
        Set-Content -Path $file.FullName -Value $newContent -NoNewline
        Write-Host "Updated $($file.FullName)"
        $count++
    }
}

Write-Host "Finished updating $count files."
