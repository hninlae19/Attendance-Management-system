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
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-file-invoice-dollar text-emerald-400"></i>
                        <span>Payroll Operations</span>
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 text-xs font-bold uppercase tracking-wider font-mono">
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
                <p class="text-gray-300 text-xs sm:text-sm mt-1">Review basic salary, calculate proration by join date, disburse bonuses, and manage payment settlements.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <!-- Filters Form -->
                <form method="GET" action="/payrollsystem/admin/payroll" class="flex flex-wrap items-center gap-2">
                    <select name="emp_id" onchange="this.form.submit()" class="rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white text-xs py-2.5 px-3 focus:ring-2 focus:ring-violet-500 cursor-pointer shadow-inner">
                        <option value="">All Employees</option>
                        <?php foreach($data['employees'] as $emp): ?>
                            <option value="<?= $emp['EmpID'] ?>" <?= ($data['selectedEmpId'] ?? '') == $emp['EmpID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="month" onchange="this.form.submit()" class="rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white text-xs py-2.5 px-3 focus:ring-2 focus:ring-violet-500 cursor-pointer shadow-inner">
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
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-gray-950 text-xs font-extrabold shadow-lg shadow-emerald-500/25 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Calculate Salary</span>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-violet-500/20 flex items-center justify-between shadow-lg" data-aos="fade-up">
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-violet-600 dark:text-violet-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" x-model="searchQuery" 
                   class="bg-gray-50 dark:bg-gray-900/60 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white text-xs rounded-xl focus:ring-2 focus:ring-violet-500 block w-full pl-9 p-2.5 placeholder-gray-500 shadow-inner" 
                   placeholder="Quick search employee name in table...">
        </div>
        <div class="text-xs text-gray-600 dark:text-gray-400 font-mono hidden sm:block">
            Found <span class="text-emerald-600 dark:text-emerald-400 font-bold"><?= count($data['payrolls'] ?? []) ?></span> records
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-200 dark:border-violet-500/20 mb-8 shadow-xl" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400 whitespace-nowrap">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-900/80 text-violet-700 dark:text-violet-300/80 border-b border-gray-200 dark:border-violet-900/40">
                    <tr>
                        <?php if ($data['selectedMonth'] === 'all'): ?>
                            <th class="px-4 py-4 font-bold text-cyan-600 dark:text-cyan-300">Payroll Month</th>
                        <?php endif; ?>
                        <th class="px-4 py-4 font-semibold tracking-wider">Employee</th>
                        <th class="px-4 py-4 font-semibold tracking-wider">Emp Code</th>
                        <th class="px-4 py-4">Department</th>
                        <th class="px-4 py-4">Position</th>
                        <th class="px-4 py-4 bg-gray-100 dark:bg-violet-950/40 text-violet-800 dark:text-violet-200">Basic Salary</th>
                        <th class="px-4 py-4 text-center">Present</th>
                        <th class="px-4 py-4 text-center">Leave</th>
                        <th class="px-4 py-4 text-rose-600 dark:text-rose-400 text-center font-bold">FD Absent</th>
                        <th class="px-4 py-4 text-amber-600 dark:text-amber-400 text-center font-bold">HD Absent</th>
                        <th class="px-4 py-4 text-center">Late</th>
                        <th class="px-4 py-4 text-center">OT Hrs</th>
                        <th class="px-4 py-4 text-amber-600 dark:text-amber-400 font-bold">OT Pay</th>
                        <th class="px-4 py-4 text-emerald-600 dark:text-emerald-400 font-bold">Bonus</th>
                        <th class="px-4 py-4 text-rose-600 dark:text-rose-400 font-bold">Leave Ded</th>
                        <th class="px-4 py-4 text-rose-600 dark:text-rose-400 font-bold">Late Ded</th>
                        <th class="px-4 py-4 bg-violet-100 dark:bg-violet-950/50 text-violet-800 dark:text-violet-300 font-bold">Gross (MMK)</th>
                        <th class="px-4 py-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 font-extrabold text-base">Net Salary (MMK)</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-violet-900/30">
                    <?php if(empty($data['payrolls'])): ?>
                    <tr>
                        <td colspan="20" class="px-4 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-violet-900/40 flex items-center justify-center mb-2 text-violet-600 dark:text-violet-400">
                                <i class="fa-solid fa-folder-open text-2xl"></i>
                            </div>
                            <p class="font-semibold text-gray-900 dark:text-gray-300">No payroll generated for this period</p>
                            <p class="text-xs text-gray-500 mt-0.5">Click "Calculate Salary" above to process attendance and generate monthly payroll.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($data['payrolls'] as $p): ?>
                        <tr x-show="searchQuery === '' || '<?= strtolower(addslashes($p['FirstName'] . ' ' . $p['LastName'])) ?>'.includes(searchQuery.toLowerCase())" class="hover:bg-gray-50 dark:hover:bg-violet-950/20 transition-colors group">
                            <?php if ($data['selectedMonth'] === 'all'): ?>
                                <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white">
                                    <i class="fa-regular fa-calendar-alt text-cyan-600 dark:text-cyan-400 mr-1.5"></i><?= htmlspecialchars($p['PayrollMonth']) ?>
                                </td>
                            <?php endif; ?>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600/30 to-cyan-500/30 text-cyan-700 dark:text-cyan-300 border border-violet-200 dark:border-violet-500/30 flex items-center justify-center font-extrabold text-xs shadow-inner">
                                        <?= strtoupper(substr($p['FirstName'] ?? 'A',0,1) . substr($p['LastName'] ?? 'B',0,1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-300 transition-colors"><?= htmlspecialchars(($p['FirstName'] ?? '') . ' ' . ($p['LastName'] ?? '')) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-xs text-gray-600 dark:text-gray-400">
                                EMP-<?= htmlspecialchars($p['employee_code'] ?? str_pad($p['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT)) ?>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-gray-700 dark:text-gray-300"><?= htmlspecialchars($p['DeptName'] ?? '—') ?></td>
                            <td class="px-4 py-3.5 text-xs text-violet-600 dark:text-violet-400"><?= htmlspecialchars($p['PositionName'] ?? '—') ?></td>
                            <td class="px-4 py-3.5 bg-gray-50 dark:bg-gray-800 font-mono text-gray-800 dark:text-gray-200"><?= number_format($p['BasicSalary']) ?></td>
                            
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-gray-700 dark:text-gray-300"><?= $p['present_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-gray-700 dark:text-gray-300"><?= $p['leave_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-rose-600 dark:text-rose-400 font-bold"><?= $p['absent_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-amber-600 dark:text-amber-400 font-bold"><?= $p['half_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-gray-700 dark:text-gray-300"><?= $p['late_days'] ?></td>
                            <td class="px-4 py-3.5 text-center font-mono text-xs text-gray-700 dark:text-gray-300"><?= number_format($p['ot_hours'] ?? 0, 1) ?></td>
                            
                            <td class="px-4 py-3.5 font-mono text-amber-600 dark:text-amber-400 font-bold">+<?= number_format($p['OvertimeAmount']) ?></td>
                            <td class="px-4 py-3.5 font-mono text-emerald-600 dark:text-emerald-400 font-bold">+<?= number_format($p['BonousAmount']) ?></td>
                            
                            <td class="px-4 py-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold">-<?= number_format($p['LeaveDeductionAmount'] ?? 0) ?></td>
                            <td class="px-4 py-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold">-<?= number_format($p['late_deduction_amount'] ?? 0) ?></td>
                            
                            <?php $grossSalary = $p['BasicSalary'] + $p['OvertimeAmount'] + $p['BonousAmount']; ?>
                            <td class="px-4 py-3.5 bg-gray-100 dark:bg-gray-800 font-mono font-bold text-violet-700 dark:text-violet-300"><?= number_format($grossSalary) ?></td>
                            <td class="px-4 py-3.5 bg-emerald-50 dark:bg-emerald-950/20 font-mono font-extrabold text-emerald-700 dark:text-emerald-400 text-sm"><?= number_format($p['NetSalary']) ?></td>
                            
                            <td class="px-4 py-3.5 text-center">
                                <?php if($p['Status'] === 'N/A'): ?>
                                    <span class="text-gray-500 italic text-xs">Aggregated</span>
                                <?php elseif($p['Status'] === 'Paid'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Paid</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Pending</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-4 py-3.5 text-right">
                                <?php if($p['Status'] !== 'N/A'): ?>
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="/payrollsystem/admin/payroll_slip/<?= $p['PayrollID'] ?>" target="_blank" 
                                       class="w-7 h-7 rounded-lg bg-violet-600/20 text-violet-300 border border-violet-500/30 flex items-center justify-center hover:bg-violet-600/40 hover:scale-105 transition-all text-xs" title="Print Slip">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <?php if($p['Status'] !== 'Paid'): ?>
                                    <button @click="openPaymentModal(<?= $p['PayrollID'] ?>, '<?= addslashes(htmlspecialchars($p['FirstName'] . ' ' . $p['LastName'])) ?>', <?= $p['NetSalary'] ?>)" 
                                            class="px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 font-bold text-xs transition-all hover:scale-105">
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
    <div x-show="paymentModal" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 dark:bg-gray-950/80 backdrop-blur-md p-4" x-cloak>
        <div @click.away="paymentModal = false" class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-violet-500/30 overflow-hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-violet-900/40 bg-gray-50 dark:bg-gray-900/80">
                <h3 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-wallet text-emerald-600 dark:text-emerald-400"></i> Settle Salary Payment
                </h3>
                <button @click="paymentModal = false" type="button" class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-violet-900/40 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
            <form method="POST" action="/payrollsystem/admin/payroll" class="p-6">
                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                <input type="hidden" name="action" value="pay">
                <input type="hidden" name="payroll_id" :value="selectedPayrollId">
                <input type="hidden" name="month" value="<?= $data['selectedMonth'] ?>">
                <input type="hidden" name="year" value="<?= $data['selectedYear'] ?>">
                
                <div class="mb-5 bg-gray-50 dark:bg-gray-900/60 p-4 rounded-2xl border border-gray-200 dark:border-violet-700/30 shadow-inner">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-violet-700 dark:text-violet-300 mb-1">Paying Salary For</p>
                    <p class="text-base font-extrabold text-gray-900 dark:text-white" x-text="empName"></p>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-violet-900/40 flex justify-between items-center">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Net Amount:</span>
                        <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono"><span x-text="new Intl.NumberFormat().format(netSalary)"></span> MMK</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-violet-700 dark:text-violet-300">Disbursement Method</label>
                    <select name="payment_method" required class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white text-xs rounded-xl focus:ring-2 focus:ring-violet-500 block w-full p-3 shadow-inner cursor-pointer">
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
                
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-gray-950 font-extrabold rounded-xl text-xs transition-all shadow-lg shadow-emerald-500/25 hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Confirm & Mark as Paid</span>
                </button>
            </form>
        </div>
    </div>
</div>
