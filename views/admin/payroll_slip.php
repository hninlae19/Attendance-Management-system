<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - <?= htmlspecialchars($payroll['employee_code']) ?> - <?= $monthNames[$payroll['month']] ?> <?= $payroll['year'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #1f2937; }
        .print-container { max-w-4xl; margin: 2rem auto; padding: 3rem; background: white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        @media print {
            body { background: white; }
            .print-container { box-shadow: none; margin: 0; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
        .table-border { border-collapse: collapse; width: 100%; }
        .table-border th, .table-border td { border: 1px solid #e5e7eb; padding: 0.75rem 1rem; text-align: left; }
        .table-border th { background-color: #f9fafb; font-weight: 600; color: #4b5563; }
        .amount-col { text-align: right !important; }
    </style>
</head>
<body>

<?php
$monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
$currentMonthName = $monthNames[(int)$payroll['month']];
?>

<div class="no-print w-full flex justify-center pt-8 pb-4">
    <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium shadow-md transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Print / Save as PDF
    </button>
</div>

<div class="print-container">
    <!-- Header -->
    <div class="flex justify-between items-start mb-12 border-b-2 border-gray-100 pb-8">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl font-black tracking-tighter">
                HR
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">MYANMAR CORP</h1>
                <p class="text-gray-500 text-sm mt-1">123 Yangon Business Tower, Myanmar</p>
                <p class="text-gray-500 text-sm">contact@myanmarcorp.com | +95 1 234 567</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-3xl font-black text-indigo-600 tracking-tight uppercase">Salary Slip</h2>
            <p class="text-gray-500 font-medium mt-1">For the month of</p>
            <p class="text-xl font-bold text-gray-800"><?= $currentMonthName ?> <?= $payroll['year'] ?></p>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="grid grid-cols-2 gap-8 mb-10 bg-gray-50 p-6 rounded-xl border border-gray-100">
        <div>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 font-medium w-32">Employee Name:</td><td class="py-1 font-bold text-gray-900"><?= htmlspecialchars($payroll['first_name'] . ' ' . $payroll['last_name']) ?></td></tr>
                <tr><td class="py-1 text-gray-500 font-medium w-32">Employee Code:</td><td class="py-1 font-bold text-gray-900"><?= htmlspecialchars($payroll['employee_code']) ?></td></tr>
                <tr><td class="py-1 text-gray-500 font-medium w-32">Join Date:</td><td class="py-1 font-medium text-gray-800"><?= date('d F Y', strtotime($payroll['join_date'] ?? '')) ?></td></tr>
            </table>
        </div>
        <div>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500 font-medium w-32">Department:</td><td class="py-1 font-bold text-gray-900"><?= htmlspecialchars($payroll['department_name'] ?? 'N/A') ?></td></tr>
                <tr><td class="py-1 text-gray-500 font-medium w-32">Position:</td><td class="py-1 font-medium text-gray-800"><?= htmlspecialchars($payroll['position_name'] ?? 'N/A') ?></td></tr>
                <tr><td class="py-1 text-gray-500 font-medium w-32">Status:</td><td class="py-1 font-medium text-gray-800">
                    <?= $payroll['status'] === 'Paid' ? '<span class="text-emerald-600 font-bold">PAID</span>' : '<span class="text-amber-600 font-bold">PENDING</span>' ?>
                </td></tr>
            </table>
        </div>
    </div>

    <!-- Payroll Details -->
    <div class="grid grid-cols-2 gap-8 mb-10">
        <!-- Earnings -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b-2 border-indigo-100 pb-2 mb-4 uppercase tracking-wider text-sm">Earnings</h3>
            <table class="table-border text-sm mb-4">
                <tr>
                    <td>Basic Salary</td>
                    <td class="amount-col font-medium text-gray-900"><?= number_format($payroll['basic_salary']) ?> MMK</td>
                </tr>
                <tr>
                    <td>Overtime Pay</td>
                    <td class="amount-col font-medium text-gray-900"><?= number_format($payroll['ot_amount']) ?> MMK</td>
                </tr>
                <tr>
                    <td>Bonus</td>
                    <td class="amount-col font-medium text-gray-900"><?= number_format($payroll['bonus_amount']) ?> MMK</td>
                </tr>
            </table>
            
            <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                <span class="font-bold text-indigo-900">Gross Salary</span>
                <span class="text-lg font-black text-indigo-700"><?= number_format($payroll['gross_salary']) ?> MMK</span>
            </div>
        </div>

        <!-- Deductions -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b-2 border-rose-100 pb-2 mb-4 uppercase tracking-wider text-sm">Deductions</h3>
            <table class="table-border text-sm mb-4">
                <tr>
                    <td>Leave Deduction</td>
                    <td class="amount-col font-medium text-rose-600"><?= number_format($payroll['leave_deduction_amount']) ?> MMK</td>
                </tr>
                <tr>
                    <td>Late Deduction</td>
                    <td class="amount-col font-medium text-rose-600"><?= number_format($payroll['late_deduction_amount']) ?> MMK</td>
                </tr>
                <tr>
                    <td>Other Deductions</td>
                    <td class="amount-col font-medium text-rose-600"><?= number_format($payroll['other_deduction_amount']) ?> MMK</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td class="amount-col">&nbsp;</td>
                </tr>
            </table>
            
            <div class="flex justify-between items-center bg-rose-50 p-4 rounded-lg border border-rose-100">
                <span class="font-bold text-rose-900">Total Deductions</span>
                <span class="text-lg font-black text-rose-700"><?= number_format($payroll['deduction_amount']) ?> MMK</span>
            </div>
        </div>
    </div>

    <!-- Net Salary -->
    <div class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-8 mb-12 flex justify-between items-center relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/10 rounded-full"></div>
        <div>
            <p class="text-emerald-700 font-bold uppercase tracking-wider mb-1">Net Pay</p>
            <p class="text-sm text-emerald-600/80 font-medium">Transferred via <?= htmlspecialchars($payroll['payment_method'] ?? 'System') ?></p>
        </div>
        <div class="text-right z-10">
            <h2 class="text-5xl font-black text-emerald-600 tracking-tighter"><?= number_format($payroll['net_salary']) ?> MMK</h2>
        </div>
    </div>

    <!-- Signatures -->
    <div class="grid grid-cols-2 gap-8 mt-16 pt-8 border-t border-gray-100">
        <div class="text-center px-12">
            <div class="border-b border-gray-400 h-16 mb-2"></div>
            <p class="text-sm font-bold text-gray-700">Employer Signature</p>
            <p class="text-xs text-gray-500 mt-1">Authorized Signatory</p>
        </div>
        <div class="text-center px-12">
            <div class="border-b border-gray-400 h-16 mb-2"></div>
            <p class="text-sm font-bold text-gray-700">Employee Signature</p>
            <p class="text-xs text-gray-500 mt-1">Date: ................................</p>
        </div>
    </div>
    
    <div class="mt-12 text-center text-xs text-gray-400 italic">
        <p>This is a computer generated document. No signature is required for electronic verification.</p>
    </div>
</div>

</body>
</html>
