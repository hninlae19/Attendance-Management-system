<?php
$db = new PDO('mysql:host=localhost;dbname=payrolldb;charset=utf8', 'root', '');
$stmt = $db->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$live = '';
foreach($tables as $t) {
    $create = $db->query('SHOW CREATE TABLE `'.$t.'`')->fetchColumn(1);
    // strip AUTO_INCREMENT=...
    $create = preg_replace('/ AUTO_INCREMENT=\d+/', '', $create);
    $live .= $create . ";\n\n";
}

$db_sql = file_get_contents('db.sql');
// strip AUTO_INCREMENT=... from db.sql
$db_sql = preg_replace('/ AUTO_INCREMENT=\d+/', '', $db_sql);

file_put_contents('live_schema.sql', $live);
file_put_contents('db_clean.sql', $db_sql);
echo "Done";
