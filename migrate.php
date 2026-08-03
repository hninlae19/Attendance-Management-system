<?php
require_once __DIR__ . '/config/database.php';
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // 1. Alter Settings table
    $settingsSql = "ALTER TABLE settings 
        ADD COLUMN annual_leave_limit INT DEFAULT 14 AFTER max_ot_hours,
        ADD COLUMN casual_leave_limit INT DEFAULT 7 AFTER annual_leave_limit,
        ADD COLUMN medical_leave_limit INT DEFAULT 14 AFTER casual_leave_limit,
        ADD COLUMN paid_leave_limit INT DEFAULT 35 AFTER medical_leave_limit,
        ADD COLUMN unpaid_leave_rules TEXT NULL AFTER paid_leave_limit,
        ADD COLUMN half_day_leave_rules TEXT NULL AFTER unpaid_leave_rules,
        ADD COLUMN absent_deduction_rate DECIMAL(10,2) DEFAULT 0.00 AFTER half_day_leave_rules,
        ADD COLUMN half_day_deduction_rate DECIMAL(10,2) DEFAULT 0.00 AFTER absent_deduction_rate,
        ADD COLUMN late_deduction_rules TEXT NULL AFTER half_day_deduction_rate,
        ADD COLUMN excess_paid_leave_deduction_rules TEXT NULL AFTER late_deduction_rules,
        ADD COLUMN custom_deduction_rules TEXT NULL AFTER excess_paid_leave_deduction_rules";
    try {
        $conn->exec($settingsSql);
        echo "Settings altered.\n";
    } catch(PDOException $e) { echo "Settings: " . $e->getMessage() . "\n"; }

    // 2. Alter Deductions table
    $deductionsSql = "ALTER TABLE deductions 
        ADD COLUMN type VARCHAR(100) NULL AFTER date,
        ADD COLUMN status ENUM('Pending', 'Applied', 'Cancelled') DEFAULT 'Applied' AFTER amount,
        ADD COLUMN created_by VARCHAR(100) DEFAULT 'Admin' AFTER status,
        ADD COLUMN notes TEXT NULL AFTER created_by";
    try {
        $conn->exec($deductionsSql);
        echo "Deductions altered.\n";
    } catch(PDOException $e) { echo "Deductions: " . $e->getMessage() . "\n"; }

    // 3. Alter Bonuses table
    $bonusesSql = "ALTER TABLE bonuses 
        ADD COLUMN type VARCHAR(100) NULL AFTER date,
        ADD COLUMN notes TEXT NULL AFTER amount";
    try {
        $conn->exec($bonusesSql);
        echo "Bonuses altered.\n";
    } catch(PDOException $e) { echo "Bonuses: " . $e->getMessage() . "\n"; }

    // 4. Create Overtime Assignments tables
    $otAssignSql = "CREATE TABLE IF NOT EXISTS overtime_assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        date DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        reason TEXT NULL,
        assigned_by INT NOT NULL,
        status ENUM('Active', 'Completed', 'Cancelled') DEFAULT 'Active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    try {
        $conn->exec($otAssignSql);
        echo "OT Assignments created.\n";
    } catch(PDOException $e) { echo "OT Assignments: " . $e->getMessage() . "\n"; }

    $otAssignEmpSql = "CREATE TABLE IF NOT EXISTS overtime_assignment_employees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        assignment_id INT NOT NULL,
        employee_id INT NOT NULL,
        status ENUM('Assigned', 'Completed', 'Missed') DEFAULT 'Assigned',
        FOREIGN KEY (assignment_id) REFERENCES overtime_assignments(id) ON DELETE CASCADE,
        FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
    )";
    try {
        $conn->exec($otAssignEmpSql);
        echo "OT Assignment Employees created.\n";
    } catch(PDOException $e) { echo "OT Assignment Employees: " . $e->getMessage() . "\n"; }

    echo "Migration completed.\n";
} catch(Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
}
?>
