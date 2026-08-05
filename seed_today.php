<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// 1. Ensure at least one department and employee exists
$deptId = 1;
$stmt = $conn->query("SELECT id FROM departments LIMIT 1");
$dept = $stmt->fetch();
if (!$dept) {
    $conn->exec("INSERT INTO departments (name) VALUES ('IT Department')");
    $deptId = $conn->lastInsertId();
} else {
    $deptId = $dept['id'];
}

$empId = 1;
$stmt = $conn->query("SELECT id FROM employees LIMIT 1");
$emp = $stmt->fetch();
if (!$emp) {
    $conn->exec("INSERT INTO employees (first_name, last_name, email, phone, position, department_id, join_date, status, password) 
                 VALUES ('John', 'Doe', 'john@example.com', '123456789', 'Developer', $deptId, '2023-01-01', 'Active', 'password_hash')");
    $empId = $conn->lastInsertId();
} else {
    $empId = $emp['id'];
}

// Ensure Leave Type exists
$ltId = 1;
$stmt = $conn->query("SELECT id FROM leave_types LIMIT 1");
$lt = $stmt->fetch();
if (!$lt) {
    $conn->exec("INSERT INTO leave_types (name, description, days_allowed, is_paid) VALUES ('Sick Leave', 'Sick Leave', 10, 1)");
    $ltId = $conn->lastInsertId();
} else {
    $ltId = $lt['id'];
}

// 2. Insert attendance for today
$today = date('Y-m-d');
$timeIn = date('Y-m-d 08:30:00');
$timeOut = date('Y-m-d 17:00:00');

$stmt = $conn->prepare("INSERT IGNORE INTO attendance (employee_id, check_in, check_out, status, date) VALUES (:emp, :cin, :cout, 'Present', :d)");
$stmt->execute([
    ':emp' => $empId,
    ':cin' => $timeIn,
    ':cout' => $timeOut,
    ':d' => $today
]);

// 3. Insert pending leave request
$stmt = $conn->prepare("INSERT INTO leave_requests (employee_id, leave_type_id, start_date, end_date, reason, status) VALUES (:emp, :lt, :start, :end, 'Flu', 'Pending')");
$stmt->execute([
    ':emp' => $empId,
    ':lt' => $ltId,
    ':start' => $today,
    ':end' => date('Y-m-d', strtotime('+2 days'))
]);

// 4. Insert pending overtime
$stmt = $conn->prepare("INSERT INTO overtime_requests (employee_id, date, start_time, end_time, hours, type, reason, status) VALUES (:emp, :d, '17:00:00', '19:00:00', 2.0, 'Regular', 'Project deadline', 'Pending')");
$stmt->execute([
    ':emp' => $empId,
    ':d' => $today
]);

echo 'Today\'s data seeded successfully.';
