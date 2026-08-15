<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
try {
    $stmt = $conn->query("SELECT EmpID, Email, PasswordResetRequest, Status FROM employee");
    echo "EMPLOYEES:\n";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    echo "\n\nQUERY RESULT:\n";
    $query = "SELECT e.EmpID, e.FirstName, e.LastName, e.Email, d.DeptName as DeptName
              FROM employee e
              LEFT JOIN position p ON e.PositionID = p.PositionID
              LEFT JOIN department d ON p.DeptID = d.DeptID
              WHERE e.PasswordResetRequest = 1 AND e.Status = 'Active'";
    $stmt2 = $conn->prepare($query);
    $stmt2->execute();
    print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
