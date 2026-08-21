<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Compensation History</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Salary</span> History
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Review your monthly compensation records, overtime additions, tax deductions, and download payslips.</p>
        </div>
        <div class="px-4 py-2.5 rounded-2xl bg-white/15 border border-white/30 text-center shadow-lg backdrop-blur-md">
            <div class="text-[10px] uppercase tracking-widest text-white font-bold">Total Slips</div>
            <div class="text-xl font-extrabold text-white font-mono"><?= count($data['payrolls'] ?? []) ?> Records</div>
        </div>
    </div>
</div>

<!-- ============ SALARY HISTORY TABLE ============ -->
<div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm mb-8" data-aos="fade-up">
    <div class="p-4 px-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
        <h3 class="font-extrabold text-slate-900 dark:text-white text-sm flex items-center gap-2 font-outfit">
            <i class="fa-solid fa-money-check-dollar text-emerald-500"></i> Monthly Payroll Records
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Payroll Month</th>
                    <th class="px-6 py-4">Base Salary</th>
                    <th class="px-6 py-4">OT Pay</th>
                    <th class="px-6 py-4">Bonus</th>
                    <th class="px-6 py-4">Deductions</th>
                    <th class="px-6 py-4">Net Salary</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if (empty($data['payrolls'])): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-2 text-indigo-500">
                                <i class="fa-solid fa-folder-open text-xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No payroll records found</p>
                            <p class="text-xs text-slate-400 mt-0.5">Your monthly processed payslips will appear here.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['payrolls'] as $pr): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-xs font-mono"><?= htmlspecialchars($pr['PayrollMonth']) ?></td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-700 dark:text-slate-300"><?= number_format($pr['BaseSalary'], 2) ?></td>
                        <td class="px-6 py-4 font-mono font-bold text-amber-600 dark:text-amber-400 text-xs">+<?= number_format($pr['OTPay'], 2) ?></td>
                        <td class="px-6 py-4 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-xs">+<?= number_format($pr['Bonus'] ?? 0, 2) ?></td>
                        <td class="px-6 py-4 font-mono font-bold text-rose-600 dark:text-rose-400 text-xs">-<?= number_format($pr['TotalDeductions'] ?? 0, 2) ?></td>
                        <td class="px-6 py-4 font-mono font-black text-emerald-600 dark:text-emerald-400 text-xs"><?= number_format($pr['NetSalary'], 2) ?> <span class="text-xs text-slate-400 font-normal">MMK</span></td>
                        <td class="px-6 py-4">
                            <?php if ($pr['Status'] === 'Paid'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Paid</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="/payrollsystem/employee/payroll_slip/<?= $pr['PayrollID'] ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 text-xs font-bold transition-all shadow-sm">
                                <i class="fa-solid fa-file-invoice"></i> View Slip
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
