<?php
$monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
if ($data['selectedMonth'] === 'yearly') $currentMonthName = 'Yearly Total';
elseif ($data['selectedMonth'] === 'all') $currentMonthName = 'All Months';
else $currentMonthName = $monthNames[(int)$data['selectedMonth']];
?>
<div class="space-y-6" x-data="{ 
    paymentModal: false, 
    selectedPayrollId: null, 
    empName: '',
    netSalary: 0,
    searchQuery: '',
    openPaymentModal(id, name, amount) {
        this.selectedPayrollId = id;
        this.empName = name;
        this.netSalary = amount;
        this.paymentModal = true;
    }
}">
    <!-- ============ HEADER BANNER ============ -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Payroll Operations</span>
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                        <?= $currentMonthName ?> <?= htmlspecialchars($data['selectedYear']) ?>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                    <?php if ($data['selectedEmpName']): ?>
                        Salary Breakdown for <span class="gradient-text"><?= htmlspecialchars($data['selectedEmpName']) ?></span>
                    <?php else: ?>
                        Monthly <span class="gradient-text">Payroll</span> Summary
                    <?php endif; ?>
                </h1>
                <p class="text-indigo-100 text-xs sm:text-sm mt-1">Review basic salary, calculate proration by join date, disburse bonuses, and manage payment settlements.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <!-- Filters Form -->
                <form method="GET" action="/payrollsystem/admin/payroll" class="flex flex-wrap items-center gap-2">
                    <select name="emp_id" onchange="this.form.submit()" class="rounded-xl bg-white text-slate-800 border border-white/40 text-xs font-bold py-2.5 px-3 focus:ring-2 focus:ring-white shadow-sm cursor-pointer">
                        <option value="">All Employees</option>
                        <?php foreach($data['employees'] as $emp): ?>
                            <option value="<?= $emp['EmpID'] ?>" <?= ($data['selectedEmpId'] ?? '') == $emp['EmpID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="month" onchange="this.form.submit()" class="rounded-xl bg-white text-slate-800 border border-white/40 text-xs font-bold py-2.5 px-3 focus:ring-2 focus:ring-white shadow-sm cursor-pointer">
                        <option value="all" <?= $data['selectedMonth'] === 'all' ? 'selected' : '' ?>>All Months</option>
                        <option value="yearly" <?= $data['selectedMonth'] === 'yearly' ? 'selected' : '' ?>>Yearly Total</option>
                        <?php foreach($monthNames as $m => $name): ?>
                            <option value="<?= $m ?>" <?= $m == $data['selectedMonth'] ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
                
                <!-- Calculate Button -->
                <?php if ($data['selectedMonth'] !== 'yearly' && $data['selectedMonth'] !== 'all'): ?>
                <form method="POST" action="/payrollsystem/admin/payroll" class="inline m-0 p-0">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="generate">
                    <input type="hidden" name="month" value="<?= $data['selectedMonth'] ?>">
                    <input type="hidden" name="year" value="<?= $data['selectedYear'] ?>">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-calculator text-indigo-600"></i>
                        <span>Calculate Salary</span>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 flex items-center justify-between shadow-sm" data-aos="fade-up">
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" x-model="searchQuery" 
                   class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-9 p-2.5 placeholder-slate-400 shadow-sm" 
                   placeholder="Quick search employee name in table...">
        </div>
        <div class="text-xs text-slate-600 dark:text-slate-400 font-mono hidden sm:block">
            Found <span class="text-indigo-600 dark:text-sky-400 font-bold"><?= count($data['payrolls'] ?? []) ?></span> records
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 mb-8 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300 whitespace-nowrap">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                    <tr>
                        <?php if ($data['selectedMonth'] === 'all'): ?>
                            <th class="px-4 py-4 font-bold text-indigo-600 dark:text-sky-400">Payroll Month</th>
                        <?php endif; ?>
                        <th class="px-4 py-4">Employee</th>
                        <th class="px-4 py-4">Emp Code</th>
                        <th class="px-4 py-4">Department</th>
                        <th class="px-4 py-4">Position</th>
                        <th class="px-4 py-4 bg-slate-100 dark:bg-slate-900/50 text-slate-800 dark:text-slate-200">Basic Salary</th>
                        <th class="px-4 py-4 text-center">Present</th>
                        <th class="px-4 py-4 text-center">Leave</th>
                        <th class="px-4 py-4 text-rose-600 dark:text-rose-400 text-center font-bold">FD Absent</th>
                        <th class="px-4 py-4 text-amber-600 dark:text-amber-400 text-center font-bold">HD Absent</th>
                        <th class="px-4 py-4 text-center">Late</th>
                        <th class="px-4 py-4 text-center">OT Hrs</th>
                        <th class="px-4 py-4 text-amber-600 dark:text-amber-400 font-bold">OT Pay</th>
                        <th class="px-4 py-4 text-emerald-600 dark:text-emerald-400 font-bold">Bonus</th>
                        <th class="px-4 py-4 text-rose-600 dark:text-rose-400 font-bold">Att. Ded</th>
                        <th class="px-4 py-4 text-rose-600 dark:text-rose-400 font-bold">Leave Ded</th>
                        <th class="px-4 py-4 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-800 dark:text-indigo-300 font-bold">Gross (MMK)</th>
                        <th class="px-4 py-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-extrabold">Net Salary (MMK)</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <?php if(empty($data['payrolls'])): ?>
                    <tr>
                        <td colspan="20" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No payroll generated for this period</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Click "Calculate Salary" above to process attendance and generate monthly payroll.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($data['payrolls'] as $p): ?>
                        <tr x-show="searchQuery === '' || '<?= strtolower(addslashes($p['FirstName'] . ' ' . $p['LastName'])) ?>'.includes(searchQuery.toLowerCase())" class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                            <?php if ($data['selectedMonth'] === 'all'): ?>
                                <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white text-xs">
                                    <i class="fa-regular fa-calendar-alt text-indigo-500 mr-1.5"></i><?= htmlspecialchars($p['PayrollMonth']) ?>
                                </td>
                            <?php endif; ?>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                        <?= strtoupper(substr($p['FirstName'] ?? 'A',0,1) . substr($p['LastName'] ?? 'B',0,1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-xs"><?= htmlspecialchars(($p['FirstName'] ?? '') . ' ' . ($p['LastName'] ?? '')) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-xs text-indigo-600 dark:text-sky-400 font-semibold">
                                EMP-<?= htmlspecialchars($p['employee_code'] ?? str_pad($p['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT)) ?>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-slate-700 dark:text-slate-300"><?= htmlspecialchars($p['DeptName'] ?? '—') ?></td>
                            <td class="px-4 py-3.5 text-xs text-slate-700 dark:text-slate-300 font-medium"><?= htmlspecialchars($p['PositionName'] ?? '—') ?></td>
                            <td class="px-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 font-mono text-slate-800 dark:text-slate-200 text-xs font-semibold"><?= number_format($p['BasicSalary']) ?></td>
                            
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-slate-700 dark:text-slate-300"><?= $p['present_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-slate-700 dark:text-slate-300"><?= $p['leave_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-rose-600 dark:text-rose-400 font-bold"><?= $p['absent_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-amber-600 dark:text-amber-400 font-bold"><?= $p['half_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-slate-700 dark:text-slate-300">
                                <?= $p['late_days'] ?> <span class="text-[10px] text-slate-400 font-normal">(<?= $p['late_minutes'] ?? 0 ?>m)</span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-slate-700 dark:text-slate-300"><?= number_format($p['ot_hours'] ?? 0, 1) ?></td>
                            
                            <td class="px-4 py-3.5 font-mono text-amber-600 dark:text-amber-400 font-bold text-xs">+<?= number_format($p['OvertimeAmount']) ?></td>
                            <td class="px-4 py-3.5 font-mono text-emerald-600 dark:text-emerald-400 font-bold text-xs">+<?= number_format($p['BonousAmount']) ?></td>
                            
                            <td class="px-4 py-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold text-xs" title="Late: <?= number_format($p['late_deduction'] ?? 0) ?> | Half: <?= number_format($p['half_day_deduction'] ?? 0) ?> | Full: <?= number_format($p['full_day_deduction'] ?? 0) ?>">-<?= number_format($p['total_attendance_deduction'] ?? 0) ?></td>
                            <td class="px-4 py-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold text-xs">-<?= number_format(max(0, ($p['LeaveDeductionAmount'] ?? 0) - ($p['total_attendance_deduction'] ?? 0))) ?></td>
                            
                            <?php $grossSalary = $p['BasicSalary'] + $p['OvertimeAmount'] + $p['BonousAmount']; ?>
                            <td class="px-4 py-3.5 bg-indigo-50/50 dark:bg-indigo-950/20 font-mono font-bold text-indigo-700 dark:text-indigo-300 text-xs"><?= number_format($grossSalary) ?></td>
                            <td class="px-4 py-3.5 bg-emerald-50/60 dark:bg-emerald-950/30 font-mono font-black text-emerald-600 dark:text-emerald-400 text-xs"><?= number_format($p['NetSalary']) ?></td>
                            
                            <td class="px-4 py-3.5 text-center">
                                <?php if($p['Status'] === 'N/A'): ?>
                                    <span class="text-slate-400 italic text-xs">Aggregated</span>
                                <?php elseif($p['Status'] === 'Paid'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Paid</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-4 py-3.5 text-right">
                                <?php if($p['Status'] !== 'N/A'): ?>
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="/payrollsystem/admin/payroll_slip/<?= $p['PayrollID'] ?>" target="_blank" 
                                       class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center transition-all text-xs shadow-sm" title="Print Slip">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <?php if($p['Status'] !== 'Paid'): ?>
                                    <button @click="openPaymentModal(<?= $p['PayrollID'] ?>, '<?= addslashes(htmlspecialchars($p['FirstName'] . ' ' . $p['LastName'])) ?>', <?= $p['NetSalary'] ?>)" 
                                            class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 font-extrabold text-xs transition-all shadow-sm">
                                        Pay
                                    </button>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="paymentModal" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-950/70 backdrop-blur-sm p-4" x-cloak>
        <div @click.away="paymentModal = false" class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform transition-all animate__animated animate__fadeInUp">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-wallet text-emerald-500"></i> Settle Salary Payment
                </h3>
                <button @click="paymentModal = false" type="button" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
            <form method="POST" action="/payrollsystem/admin/payroll" class="p-6">
                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                <input type="hidden" name="action" value="pay">
                <input type="hidden" name="payroll_id" :value="selectedPayrollId">
                <input type="hidden" name="month" value="<?= $data['selectedMonth'] ?>">
                <input type="hidden" name="year" value="<?= $data['selectedYear'] ?>">
                
                <div class="mb-5 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Paying Salary For</p>
                    <p class="text-base font-extrabold text-slate-900 dark:text-white" x-text="empName"></p>
                    <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Net Amount:</span>
                        <span class="text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono"><span x-text="new Intl.NumberFormat().format(netSalary)"></span> MMK</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Disbursement Method</label>
                    <select name="payment_method" required class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-3 shadow-sm cursor-pointer">
                        <option value="">Select Payment Method</option>
                        <option value="Cash">Cash</option>
                        <option value="KBZ Bank">KBZ Bank</option>
                        <option value="AYA Bank">AYA Bank</option>
                        <option value="CB Bank">CB Bank</option>
                        <option value="UAB Bank">UAB Bank</option>
                        <option value="Wave Pay">Wave Pay</option>
                        <option value="KBZ Pay">KBZ Pay</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold rounded-xl text-xs transition-all shadow-lg shadow-emerald-500/25 hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Confirm & Mark as Paid</span>
                </button>
            </form>
        </div>
    </div>
</div>
