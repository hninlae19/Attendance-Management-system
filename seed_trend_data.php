<?php
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Seed data for the last 6 months
for ($i = 5; $i >= 0; $i--) {
    $monthStr = date('Y-m', strtotime("-$i months"));
    $m = date('n', strtotime($monthStr));
    $y = date('Y', strtotime($monthStr));
    
    // Fake Payroll
    $net_salary = rand(5000000, 8000000);
    $ot_amount = rand(200000, 500000);
    $stmt = $conn->prepare("INSERT IGNORE INTO payroll (employee_id, month, year, basic_salary, net_salary, ot_amount, payment_date) VALUES (1, :m, :y, 300000, :net, :ot, :pdate)");
    $stmt->execute([
        ':m' => $m,
        ':y' => $y,
        ':net' => $net_salary,
        ':ot' => $ot_amount,
        ':pdate' => "$y-$m-28"
    ]);
    
    // Fake Bonus
    $bonus = rand(100000, 500000);
    $stmt2 = $conn->prepare("INSERT IGNORE INTO bonuses (employee_id, amount, reason, date) VALUES (1, :b, 'Performance', :d)");
    $stmt2->execute([
        ':b' => $bonus,
        ':d' => "$y-$m-15"
    ]);
    
    // Fake Deduction
    $deduction = rand(50000, 150000);
    $stmt3 = $conn->prepare("INSERT IGNORE INTO deductions (employee_id, amount, reason, date) VALUES (1, :ded, 'Late', :d)");
    $stmt3->execute([
        ':ded' => $deduction,
        ':d' => "$y-$m-10"
    ]);
}
echo 'Seeded successfully.';
