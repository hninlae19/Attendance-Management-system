<?php
$file = 'c:/wamp64/www/payrollsystem/models/LeaveRequest.php';
$content = file_get_contents($file);

$newApprovalLogic = <<<'PHP'
        if($stmt->execute()) {
            if ($action === 'approve') {
                // Fetch leave details
                $leaveStmt = $this->conn->prepare("SELECT lr.*, lt.is_paid FROM " . $this->table . " lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.id = ?");
                $leaveStmt->execute([$id]);
                $leave = $leaveStmt->fetch(PDO::FETCH_ASSOC);

                require_once __DIR__ . '/Deduction.php';
                $deduction = new Deduction();

                if ($leave && $leave['is_paid'] == 1) {
                    $year = date('Y', strtotime($leave['start_date']));
                    
                    // Get limit from settings table
                    $settingStmt = $this->conn->query("SELECT paid_leave_limit FROM settings LIMIT 1");
                    $settings = $settingStmt->fetch(PDO::FETCH_ASSOC);
                    $limit = $settings ? (int)$settings['paid_leave_limit'] : 35;

                    // Get total approved paid leaves for this year (all paid types)
                    $sumStmt = $this->conn->prepare("SELECT SUM(lr.days) as total FROM " . $this->table . " lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.employee_id = ? AND lr.status = 'Approved' AND lt.is_paid = 1 AND YEAR(lr.start_date) = ? AND lr.id != ?");
                    $sumStmt->execute([$leave['employee_id'], $year, $id]);
                    $past_total = $sumStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

                    $new_total = $past_total + $leave['days'];
                    if ($new_total > $limit) {
                        $excess_days = $new_total - $limit;
                        if ($excess_days > $leave['days']) {
                            $excess_days = $leave['days'];
                        }

                        if ($excess_days > 0) {
                            $current_date = strtotime($leave['end_date']);
                            for ($i = 0; $i < $excess_days; $i++) {
                                $date_str = date('Y-m-d', $current_date);
                                $deduction->applyAutomatedDeduction($leave['employee_id'], 'Unpaid Leave', $date_str, 'Exceeded Paid Leave Limit (Unpaid Leave)', 'Leave Management System', $id, 1.0, 'Active');
                                $current_date = strtotime("-1 day", $current_date);
                            }
                        }
                    }
                } elseif ($leave && $leave['is_paid'] == 0) {
                    // Update pending deductions to active
                    $updDed = "UPDATE deductions SET status = 'Active', reason = 'Approved Unpaid Leave' WHERE related_id = ? AND type = 'Unpaid Leave'";
                    $updStmt = $this->conn->prepare($updDed);
                    $updStmt->execute([$id]);
                }
            } elseif ($action === 'reject') {
                // Cancel pending deductions
                $updDed = "UPDATE deductions SET status = 'Cancelled' WHERE related_id = ? AND type = 'Unpaid Leave'";
                $updStmt = $this->conn->prepare($updDed);
                $updStmt->execute([$id]);
            }

            // Get user_id for notification
PHP;

$pattern = '/if\(\$stmt->execute\(\)\) \{\s*if \(\$action === \'approve\'\).*?\/\/ Get user_id for notification/s';
$content = preg_replace($pattern, $newApprovalLogic, $content);
file_put_contents($file, $content);
echo "Updated LeaveRequest.php";
