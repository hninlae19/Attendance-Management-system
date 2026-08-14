<?php
$db = new PDO('mysql:host=localhost;dbname=payrolldb', 'root', '');
$stmt = $db->query("DESCRIBE leaverequest");
$schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
file_put_contents('lr_schema_dump.txt', print_r($schema, true));
?>
