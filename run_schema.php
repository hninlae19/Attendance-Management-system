<?php
$db = new PDO('mysql:host=localhost;charset=utf8', 'root', '');
$sql = file_get_contents('photo_schema.sql');
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $stmt) {
    if (empty($stmt)) continue;
    try {
        $db->exec($stmt);
        echo "Executed: " . substr($stmt, 0, 50) . "...\n";
    } catch (PDOException $e) {
        echo "Error in: " . substr($stmt, 0, 50) . "... -> " . $e->getMessage() . "\n";
    }
}
echo 'Schema updated successfully';
