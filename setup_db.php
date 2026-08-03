<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default wamp password
$dbname = 'payrolldb';

try {
    // Connect without DB name first to create it
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to MySQL server.\n";

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Database created or already exists.\n";
    
    // Connect to the specific database
    $pdo->exec("USE `$dbname`;");
    
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    if ($sql === false) {
        die("Could not read database.sql file.\n");
    }

    // Execute the SQL script
    $pdo->exec($sql);
    echo "SQL script executed successfully. Tables created.\n";

} catch(PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
