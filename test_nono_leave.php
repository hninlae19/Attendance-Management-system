<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Find "nono"
$stmt = $conn->query("SELECT e.EmpID, e.FirstName, e.LastName, p.BasicSalary FROM employee e JOIN position p ON e.PositionID = p.PositionID WHERE e.FirstName LIKE '%nono%' OR e.FirstName = 'no'");
$nono = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($nono);

if ($nono) {
    $empId = $nono['EmpID'];
    $basicSalary = $nono['BasicSalary'];
    $dailySalary = $basicSalary / 30;
    echo "\nDaily Salary: $dailySalary\n";
    
    // Leaves
    $stmt = $conn->prepare("SELECT * FROM leaverequest WHERE EmpID = :emp");
    $stmt->execute([':emp' => $empId]);
    $leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nLeaves:\n";
    print_r($leaves);
    
    // Leave types
    $stmt = $conn->query("SELECT * FROM leavetypes");
    $leaveTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nLeave Types:\n";
    print_r($leaveTypes);
}
