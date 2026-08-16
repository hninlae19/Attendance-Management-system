<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
$tables = ['leaverequest', 'overtimeassign', 'payroll', 'password_resets', 'notifications'];
foreach($tables as $t) {
    echo 'Table: ' . $t . PHP_EOL;
    $stmt = $conn->query('DESCRIBE ' . $t);
    if($stmt) {
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            echo '  ' . $row['Field'] . ' - ' . $row['Type'] . PHP_EOL;
        }
    } else {
        echo '  Not found.' . PHP_EOL;
    }
}
