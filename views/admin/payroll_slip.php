<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Payroll Slip' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 30px 20px;
            color: #0f172a;
        }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .slip-container {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            padding: 44px;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border: 1px solid #e2e8f0;
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
                border: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print max-w-[820px] mx-auto mb-6 flex justify-between items-center">
    <a href="/payrollsystem/admin/payroll" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5">
        <i class="fa-solid fa-arrow-left"></i> Back to Payroll
    </a>
    <div class="flex gap-2">
        <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-extrabold shadow-lg shadow-indigo-500/25 transition-all flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Print Payslip
        </button>
        <button onclick="window.close()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors">
            Close
        </button>
    </div>
</div>

<?php 
$p = $data['payroll']; 
$grossSalary = $p['BasicSalary'] + $p['OvertimeAmount'] + $p['BonousAmount'];
$totalDeductions = ($p['LeaveDeductionAmount'] ?? 0) + ($p['late_deduction_amount'] ?? 0);
?>

<div class="slip-container">
    <div class="border-b border-slate-200 pb-6 mb-6 flex justify-between items-start">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm">
                    <i class="fa-solid fa-building"></i>
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 font-outfit tracking-tight">Enterprise HR</h1>
            </div>
            <p class="text-slate-500 text-xs font-medium">Official Employee Earnings & Settlement Statement</p>
        </div>
        <div class="text-right">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-1 font-mono">
                <?= htmlspecialchars($p['PayrollMonth']) ?>
            </div>
            <p class="text-slate-400 text-[11px]">Issued on <?= date('d M Y') ?></p>
            <p class="text-xs mt-1">
                Status: 
                <span class="font-bold <?= $p['Status'] === 'Paid' ? 'text-emerald-600' : 'text-amber-600' ?>">
                    <?= $p['Status'] ?? 'Pending' ?>
                </span>
            </p>
        </div>
    </div>

    <!-- Employee & Attendance Info -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
            <h3 class="font-bold text-slate-700 uppercase text-[11px] tracking-wider mb-3 pb-1 border-b border-slate-200 flex items-center gap-1.5">
                <i class="fa-solid fa-user-tie text-indigo-500"></i> Employee Information
            </h3>
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Employee ID:</span>
                    <span class="font-bold text-slate-900 font-mono">EMP-<?= htmlspecialchars($p['employee_code'] ?? str_pad($p['EmpID'], 4, '0', STR_PAD_LEFT)) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Full Name:</span>
                    <span class="font-bold text-slate-900"><?= htmlspecialchars($p['FirstName'] . ' ' . $p['LastName']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Department:</span>
                    <span class="font-semibold text-slate-800"><?= htmlspecialchars($p['DeptName'] ?? 'N/A') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Position:</span>
                    <span class="font-semibold text-slate-800"><?= htmlspecialchars($p['PositionName'] ?? 'N/A') ?></span>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
            <h3 class="font-bold text-slate-700 uppercase text-[11px] tracking-wider mb-3 pb-1 border-b border-slate-200 flex items-center gap-1.5">
                <i class="fa-solid fa-clock text-indigo-500"></i> Attendance Summary
            </h3>
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Present Days:</span>
                    <span class="font-bold text-emerald-600 font-mono"><?= $p['present_days'] ?> Days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Approved Leaves:</span>
                    <span class="font-semibold text-indigo-600 font-mono"><?= $p['leave_days'] ?> Days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Absences (Full/Half):</span>
                    <span class="font-bold text-rose-600 font-mono"><?= $p['absent_days'] ?> FD / <?= $p['half_days'] ?> HD</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Overtime Hours:</span>
                    <span class="font-bold text-amber-600 font-mono"><?= number_format($p['ot_hours'] ?? 0, 1) ?> hrs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings & Deductions -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-200">
            <h3 class="font-bold text-emerald-700 uppercase text-[11px] tracking-wider mb-3 pb-1 border-b border-emerald-200 flex items-center gap-1.5">
                <i class="fa-solid fa-plus-circle text-emerald-500"></i> Gross Earnings
            </h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="text-slate-600">Base Salary</span>
                    <span class="font-mono font-bold text-slate-900"><?= number_format($p['BasicSalary']) ?> MMK</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-600">Overtime Compensation</span>
                    <span class="font-mono font-bold text-amber-600">+<?= number_format($p['OvertimeAmount']) ?> MMK</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-600">Bonuses & Performance Rewards</span>
                    <span class="font-mono font-bold text-emerald-600">+<?= number_format($p['BonousAmount']) ?> MMK</span>
                </div>
                
                <div class="flex justify-between pt-2 border-t border-slate-200 font-bold text-slate-900 text-xs">
                    <span>Total Gross Pay</span>
                    <span class="font-mono text-indigo-700"><?= number_format($grossSalary) ?> MMK</span>
                </div>
            </div>
        </div>

        <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-200">
            <h3 class="font-bold text-rose-700 uppercase text-[11px] tracking-wider mb-3 pb-1 border-b border-rose-200 flex items-center gap-1.5">
                <i class="fa-solid fa-minus-circle text-rose-500"></i> Deductions
            </h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="text-slate-600">Unpaid Leave Deductions</span>
                    <span class="font-mono font-bold text-rose-600">-<?= number_format($p['LeaveDeductionAmount'] ?? 0) ?> MMK</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-600">Late Attendance Deductions</span>
                    <span class="font-mono font-bold text-rose-600">-<?= number_format($p['late_deduction_amount'] ?? 0) ?> MMK</span>
                </div>
                
                <div class="flex justify-between pt-7 border-t border-slate-200 font-bold text-slate-900 text-xs">
                    <span>Total Deductions</span>
                    <span class="font-mono text-rose-600">-<?= number_format($totalDeductions) ?> MMK</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Pay Banner -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-5 flex justify-between items-center border border-emerald-200 mb-8 shadow-sm">
        <div>
            <h2 class="text-base font-extrabold text-emerald-950 font-outfit">Take-Home Net Salary</h2>
            <p class="text-xs text-emerald-700">Final disbursed amount after all earnings and deductions</p>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono">
            <?= number_format($p['NetSalary']) ?> <span class="text-base font-bold text-emerald-800">MMK</span>
        </div>
    </div>
    
    <!-- Signatures -->
    <div class="pt-8 border-t border-slate-200 flex justify-between">
        <div class="text-center w-48">
            <div class="border-b border-slate-400 pb-10 mb-2"></div>
            <p class="text-xs font-bold text-slate-700">Authorized Officer</p>
            <p class="text-[10px] text-slate-400">Finance & HR Department</p>
        </div>
        <div class="text-center w-48">
            <div class="border-b border-slate-400 pb-10 mb-2"></div>
            <p class="text-xs font-bold text-slate-700">Employee Signature</p>
            <p class="text-[10px] text-slate-400">Acknowledgment of Receipt</p>
        </div>
    </div>

</div>

</body>
</html>
