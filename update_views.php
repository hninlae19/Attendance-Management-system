<?php

function processDir($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            processDir($path);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            
            // Employee mappings
            $content = preg_replace("/\\[['\"]first_name['\"]\\]/", "['FirstName']", $content);
            $content = preg_replace("/\\[['\"]last_name['\"]\\]/", "['LastName']", $content);
            $content = preg_replace("/\\[['\"]email['\"]\\]/", "['Email']", $content);
            $content = preg_replace("/\\[['\"]phone['\"]\\]/", "['PhoneNumber']", $content);
            $content = preg_replace("/\\[['\"]address['\"]\\]/", "['Address']", $content);
            $content = preg_replace("/\\[['\"]gender['\"]\\]/", "['Gender']", $content);
            $content = preg_replace("/\\[['\"]status['\"]\\]/", "['Status']", $content);
            $content = preg_replace("/\\[['\"]join_date['\"]\\]/", "['JoinDate']", $content);
            $content = preg_replace("/\\[['\"]department_id['\"]\\]/", "['DeptID']", $content);
            $content = preg_replace("/\\[['\"]position_id['\"]\\]/", "['PositionID']", $content);
            
            // Department mappings
            $content = preg_replace("/\\[['\"]department_name['\"]\\]/", "['DeptName']", $content);
            $content = preg_replace("/\\[['\"]name['\"]\\](?!\\s*==)/", "['DeptName']", $content);
            
            // Positions
            $content = preg_replace("/\\[['\"]basic_salary['\"]\\]/", "['BasicSalary']", $content);
            
            // Leave Type
            $content = preg_replace("/\\[['\"]days_allowed['\"]\\]/", "['DaysAllowed']", $content);
            
            // Leave Request
            $content = preg_replace("/\\[['\"]start_date['\"]\\]/", "['StartDate']", $content);
            $content = preg_replace("/\\[['\"]end_date['\"]\\]/", "['EndDate']", $content);
            $content = preg_replace("/\\[['\"]reason['\"]\\]/", "['Reason']", $content);
            
            // Attendance
            $content = preg_replace("/\\[['\"]date['\"]\\]/", "['AttendanceDate']", $content);
            $content = preg_replace("/\\[['\"]check_in['\"]\\]/", "['CheckInTime']", $content);
            $content = preg_replace("/\\[['\"]check_out['\"]\\]/", "['CheckOutTime']", $content);

            // Payroll
            $content = preg_replace("/\\[['\"]net_salary['\"]\\]/", "['NetSalary']", $content);
            $content = preg_replace("/\\[['\"]month['\"]\\]/", "['Month']", $content);
            $content = preg_replace("/\\[['\"]year['\"]\\]/", "['Year']", $content);
            
            // Variable replacements
            $content = str_replace("\$dept['id']", "\$dept['DeptID']", $content);
            $content = str_replace("\$emp['id']", "\$emp['EmpID']", $content);
            $content = str_replace("\$employee['id']", "\$employee['EmpID']", $content);
            $content = str_replace("\$pos['id']", "\$pos['PositionID']", $content);
            $content = str_replace("\$leave['id']", "\$leave['LeaveID']", $content);
            $content = str_replace("\$att['id']", "\$att['AttendanceID']", $content);
            $content = str_replace("\$pr['id']", "\$pr['PayrollID']", $content);
            
            $content = str_replace("\$dept['name']", "\$dept['DeptName']", $content);
            $content = str_replace("\$pos['name']", "\$pos['PositionName']", $content);
            
            file_put_contents($path, $content);
        }
    }
}

processDir(__DIR__ . '/views');
echo "Views updated.";
