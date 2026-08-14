<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Payroll Slip' ?></title>
    <!-- Tailwind CSS (CDN for standalone print page) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .slip-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .slip-container {
                box-shadow: none;
                max-width: 100%;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print text-center mb-6">
    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
        Print Slip
    </button>
    <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg ml-2 transition-colors">
        Close
    </button>
</div>

<?php 
$p = $data['payroll']; 
$grossSalary = $p['BasicSalary'] + $p['OvertimeAmount'] + $p['BonousAmount'];
$totalDeductions = $p['LeaveDeductionAmount']; // Add late_deduction or other_deductions if added to db later
?>

<div class="slip-container">
    <div class="border-b-2 border-gray-200 pb-6 mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Company Name</h1>
            <p class="text-gray-500 mt-1">Official Payroll Payslip</p>
        </div>
        <div class="text-right">
            <p class="font-bold text-gray-800 text-lg">Payslip for <?= htmlspecialchars($p['PayrollMonth']) ?></p>
            <p class="text-gray-500 text-sm mt-1">Generated on <?= date('d M Y') ?></p>
            <p class="text-gray-500 text-sm">Status: <span class="font-bold <?= $p['Status'] === 'Paid' ? 'text-green-600' : 'text-yellow-600' ?>"><?= $p['Status'] ?></span></p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="font-bold text-gray-700 uppercase text-xs mb-3 border-b pb-2">Employee Details</h3>
            <div class="grid grid-cols-3 gap-2 text-sm">
                <div class="text-gray-500">Employee ID:</div>
                <div class="col-span-2 font-medium">EMP-<?= str_pad($p['EmpID'], 5, '0', STR_PAD_LEFT) ?></div>
                
                <div class="text-gray-500">Name:</div>
                <div class="col-span-2 font-medium"><?= htmlspecialchars($p['FirstName'] . ' ' . $p['LastName']) ?></div>
                
                <div class="text-gray-500">Department:</div>
                <div class="col-span-2 font-medium"><?= htmlspecialchars($p['DeptName'] ?? 'N/A') ?></div>
                
                <div class="text-gray-500">Position:</div>
                <div class="col-span-2 font-medium"><?= htmlspecialchars($p['PositionName'] ?? 'N/A') ?></div>
            </div>
        </div>
        <div>
            <h3 class="font-bold text-gray-700 uppercase text-xs mb-3 border-b pb-2">Attendance Summary</h3>
            <div class="grid grid-cols-3 gap-2 text-sm">
                <div class="text-gray-500">Present Days:</div>
                <div class="col-span-2 font-medium"><?= $p['present_days'] ?></div>
                
                <div class="text-gray-500">Leave Days:</div>
                <div class="col-span-2 font-medium"><?= $p['leave_days'] ?></div>
                
                <div class="text-gray-500">Absent Days:</div>
                <div class="col-span-2 font-medium text-red-600"><?= $p['absent_days'] ?></div>
                
                <div class="text-gray-500">Overtime:</div>
                <div class="col-span-2 font-medium text-orange-600"><?= $p['ot_hours'] ?> hrs</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="font-bold text-gray-700 uppercase text-xs mb-3 border-b border-gray-300 pb-2">Earnings</h3>
            <div class="flex justify-between items-center py-2 text-sm">
                <span class="text-gray-700">Basic Salary</span>
                <span class="font-medium"><?= number_format($p['BasicSalary']) ?> MMK</span>
            </div>
            <div class="flex justify-between items-center py-2 text-sm">
                <span class="text-gray-700">Overtime Pay</span>
                <span class="font-medium text-orange-600"><?= number_format($p['OvertimeAmount']) ?> MMK</span>
            </div>
            <div class="flex justify-between items-center py-2 text-sm">
                <span class="text-gray-700">Bonuses / Rewards</span>
                <span class="font-medium text-teal-600"><?= number_format($p['BonousAmount']) ?> MMK</span>
            </div>
            
            <div class="flex justify-between items-center py-3 mt-2 border-t border-gray-200">
                <span class="font-bold text-gray-900">Gross Earnings</span>
                <span class="font-bold text-gray-900"><?= number_format($grossSalary) ?> MMK</span>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-gray-700 uppercase text-xs mb-3 border-b border-gray-300 pb-2">Deductions</h3>
            <div class="flex justify-between items-center py-2 text-sm">
                <span class="text-gray-700">Unpaid Leave Deduction</span>
                <span class="font-medium text-red-600">- <?= number_format($p['LeaveDeductionAmount']) ?> MMK</span>
            </div>
            <div class="flex justify-between items-center py-2 text-sm">
                <span class="text-gray-700">Other Deductions</span>
                <span class="font-medium text-red-600">- 0 MMK</span>
            </div>
            <div class="flex justify-between items-center py-3 mt-10 border-t border-gray-200">
                <span class="font-bold text-gray-900">Total Deductions</span>
                <span class="font-bold text-red-600">- <?= number_format($totalDeductions) ?> MMK</span>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 flex justify-between items-center border border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Net Pay</h2>
            <p class="text-sm text-gray-500">Amount transferred to employee</p>
        </div>
        <div class="text-3xl font-bold text-green-600">
            <?= number_format($p['NetSalary']) ?> <span class="text-xl">MMK</span>
        </div>
    </div>
    
    <div class="mt-12 pt-8 border-t border-gray-200 flex justify-between">
        <div class="text-center w-48">
            <div class="border-b border-gray-400 pb-8 mb-2"></div>
            <p class="text-sm text-gray-600">Employer Signature</p>
        </div>
        <div class="text-center w-48">
            <div class="border-b border-gray-400 pb-8 mb-2"></div>
            <p class="text-sm text-gray-600">Employee Signature</p>
        </div>
    </div>

</div>

<script>
    // Automatically trigger print dialog on page load
    window.onload = function() {
        // setTimeout(() => window.print(), 500);
    }
</script>
</body>
</html>
