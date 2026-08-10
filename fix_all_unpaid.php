<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Fetch all records
    $stmt = $conn->query("SELECT * FROM deductions WHERE type = 'Unpaid Leave' AND start_date IS NULL ORDER BY employee_id, related_id, date");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $groups = [];
    foreach ($records as $row) {
        $key = $row['employee_id'] . '_' . ($row['related_id'] ?? 'null') . '_' . $row['reason'];
        if (!isset($groups[$key])) {
            $groups[$key] = [];
        }
        $groups[$key][] = $row;
    }
    
    $conn->beginTransaction();
    
    $fixed_count = 0;
    
    foreach ($groups as $key => $group_records) {
        // Sort by date just in case
        usort($group_records, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        
        $subgroups = [];
        $current_subgroup = [];
        $prev_date = null;
        
        foreach ($group_records as $r) {
            if ($prev_date === null) {
                $current_subgroup[] = $r;
            } else {
                // check if contiguous (difference is 1 day)
                $diff = (strtotime($r['date']) - strtotime($prev_date)) / (60 * 60 * 24);
                if ($diff <= 1.0) { // contiguous or same day
                    $current_subgroup[] = $r;
                } else {
                    $subgroups[] = $current_subgroup;
                    $current_subgroup = [$r];
                }
            }
            $prev_date = $r['date'];
        }
        if (!empty($current_subgroup)) {
            $subgroups[] = $current_subgroup;
        }
        
        // Process each subgroup
        foreach ($subgroups as $sg) {
            if (count($sg) > 1) {
                $first = $sg[0];
                $last = end($sg);
                $total_days = count($sg);
                
                // Calculate total amount
                $total_amount = 0;
                foreach ($sg as $r) {
                    $total_amount += $r['amount'];
                }
                
                // Update the first record
                $updStmt = $conn->prepare("UPDATE deductions SET start_date = :sd, end_date = :ed, total_absent_days = :td, amount = :amt WHERE id = :id");
                $updStmt->execute([
                    ':sd' => $first['date'],
                    ':ed' => $last['date'],
                    ':td' => $total_days,
                    ':amt' => $total_amount,
                    ':id' => $first['id']
                ]);
                
                // Delete the rest
                for ($i = 1; $i < count($sg); $i++) {
                    $delStmt = $conn->prepare("DELETE FROM deductions WHERE id = :id");
                    $delStmt->execute([':id' => $sg[$i]['id']]);
                }
                $fixed_count++;
            } else {
                // If only 1 day, just set start_date and end_date to that day
                $single = $sg[0];
                $updStmt = $conn->prepare("UPDATE deductions SET start_date = :sd, end_date = :ed, total_absent_days = 1 WHERE id = :id");
                $updStmt->execute([
                    ':sd' => $single['date'],
                    ':ed' => $single['date'],
                    ':id' => $single['id']
                ]);
                $fixed_count++;
            }
        }
    }
    
    $conn->commit();
    echo "Successfully fixed and consolidated $fixed_count groups of unpaid leave records.";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
