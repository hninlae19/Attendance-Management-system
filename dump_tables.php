<?php
$db = new PDO('mysql:host=localhost;dbname=payrolldb', 'root', '');
$stmt = $db->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
file_put_contents('tables_dump.txt', print_r($tables, true));
?>
