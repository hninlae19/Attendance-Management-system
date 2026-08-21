<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Salary Breakdown</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['payrolls'] ?? []) ?> Statements
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Salary History: <span class="gradient-text"><?= htmlspecialchars($data['employee']['FirstName'] . ' ' . $data['employee']['LastName']) ?></span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Review historical monthly earnings, overtime additions, bonus payouts, and deduction items.</p>
        </div>
        <a href="/payrollsystem/admin/employees" class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-indigo-600"></i>
            <span>Back to Employees</span>
        </a>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Payroll Month</th>
                    <th scope="col" class="px-6 py-4">Base Salary</th>
                    <th scope="col" class="px-6 py-4">Bonuses</th>
                    <th scope="col" class="px-6 py-4">Overtime</th>
                    <th scope="col" class="px-6 py-4">Deductions</th>
                    <th scope="col" class="px-6 py-4">Net Salary</th>
                    <th scope="col" class="px-6 py-4 text-center">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if(empty($data['payrolls'])): ?>
                    <tr class="bg-white dark:bg-slate-800">
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No salary history available yet for this employee</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['payrolls'] as $pr): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                        <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white font-mono text-xs">
                            <?= htmlspecialchars($pr['PayrollMonth']) ?>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-700 dark:text-slate-300 font-mono font-semibold">
                            <?= number_format($pr['BasicSalary'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            +<?= number_format($pr['BonousAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs font-bold text-amber-600 dark:text-amber-400">
                            +<?= number_format($pr['OvertimeAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs font-bold text-rose-600 dark:text-rose-400">
                            -<?= number_format(($pr['LeaveDeductionAmount'] ?? 0) + ($pr['late_deduction_amount'] ?? 0)) ?>
                        </td>
                        <td class="px-6 py-3.5 font-mono font-extrabold text-slate-900 dark:text-white text-xs">
                            <?= number_format($pr['NetSalary'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            <?php if ($pr['Status'] === 'Paid'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800">
                                    <i class="fa-solid fa-check text-[10px] mr-1"></i> Paid
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800">
                                    <i class="fa-solid fa-clock text-[10px] mr-1"></i> <?= htmlspecialchars($pr['Status']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <a href="/payrollsystem/admin/payroll_slip/<?= $pr['PayrollID'] ?>" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 inline-flex items-center justify-center transition-colors shadow-sm" title="View Payslip">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
