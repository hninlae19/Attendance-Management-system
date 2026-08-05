<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Ensure at least one department exists
    $stmt = $conn->query("SELECT id FROM departments LIMIT 1");
    $dept = $stmt->fetch();
    if (!$dept) {
        $conn->exec("INSERT INTO departments (name) VALUES ('IT Department')");
        $deptId = $conn->lastInsertId();
    } else {
        $deptId = $dept['id'];
    }

    // Ensure at least one position exists
    $stmt = $conn->query("SELECT id FROM positions LIMIT 1");
    $pos = $stmt->fetch();
    if (!$pos) {
        $conn->exec("INSERT INTO positions (name, base_salary) VALUES ('Developer', 1000.00)");
        $posId = $conn->lastInsertId();
    } else {
        $posId = $pos['id'];
    }

    // Ensure user exists
    $stmt = $conn->query("SELECT id FROM users WHERE email='john@example.com' LIMIT 1");
    $user = $stmt->fetch();
    if (!$user) {
        $hash = password_hash('password', PASSWORD_DEFAULT);
        $conn->exec("INSERT INTO users (email, password, role, status) VALUES ('john@example.com', '$hash', 'Employee', 'Active')");
        $userId = $conn->lastInsertId();
    } else {
        $userId = $user['id'];
    }

    // Ensure employee exists
    $stmt = $conn->query("SELECT id FROM employees LIMIT 1");
    $emp = $stmt->fetch();
    if (!$emp) {
        $conn->exec("INSERT INTO employees (user_id, employee_code, first_name, last_name, department_id, position_id, basic_salary, join_date, phone) 
                     VALUES ($userId, 'EMP001', 'John', 'Doe', $deptId, $posId, 1000.00, '2023-01-01', '123456789')");
        $empId = $conn->lastInsertId();
    } else {
        $empId = $emp['id'];
    }

    // Ensure Leave Type exists
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
    $timeIn = date('H:i:s', strtotime('08:30:00'));
    $timeOut = date('H:i:s', strtotime('17:00:00'));

    $stmt = $conn->prepare("INSERT IGNORE INTO attendance (employee_id, date, check_in, check_out, working_hours, status) VALUES (:emp, :d, :cin, :cout, 8.5, 'Present')");
    $stmt->execute([
        ':emp' => $empId,
        ':d' => $today,
        ':cin' => $timeIn,
        ':cout' => $timeOut
    ]);

    echo "Successfully seeded correct data!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
