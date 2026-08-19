<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-file-invoice-dollar text-emerald-400"></i>
                    <span>Compensation Statement</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Salary History</span>
            </h1>
            <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm mt-1">Review your monthly compensation breakdown, bonuses, overtime pay, deductions, and download payslips.</p>
        </div>
        <div class="px-4 py-2.5 rounded-2xl bg-surface/90 border border-gray-300 dark:border-violet-700/30 text-center shadow-lg backdrop-blur-md">
            <div class="text-[10px] uppercase tracking-widest text-violet-400 font-bold">Total Payslips</div>
            <div class="text-xl font-extrabold text-gray-900 dark:text-white font-mono"><?= count($data['payrolls'] ?? []) ?> Records</div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-200 dark:border-violet-500/20 mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="p-4 px-6 border-b border-violet-900/40 flex justify-between items-center bg-gray-50 dark:bg-gray-800/60">
        <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2 font-outfit">
            <i class="fa-solid fa-money-check-dollar text-emerald-400"></i> Monthly Payslip Statements
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-900/80 text-violet-700 dark:text-violet-300/80 border-b border-gray-200 dark:border-violet-900/40">
                <tr>
                    <th scope="col" class="px-6 py-4">Payroll Month</th>
                    <th scope="col" class="px-6 py-4">Basic Salary</th>
                    <th scope="col" class="px-6 py-4">Bonuses</th>
                    <th scope="col" class="px-6 py-4">Overtime</th>
                    <th scope="col" class="px-6 py-4">Deductions</th>
                    <th scope="col" class="px-6 py-4">Net Salary</th>
                    <th scope="col" class="px-6 py-4 text-center">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-violet-900/30">
                <?php if(empty($data['payrolls'])): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                            </div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">No salary history available yet</p>
                            <p class="text-xs text-gray-500 mt-0.5">Your monthly payroll payslips will appear here once processed.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['payrolls'] as $pr): ?>
                    <tr class="hover:bg-violet-950/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            <?= htmlspecialchars($pr['PayrollMonth']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300 font-mono">
                            <?= number_format($pr['BasicSalary'] ?? 0) ?> <span class="text-xs text-gray-500">MMK</span>
                        </td>
                        <td class="px-6 py-4 text-emerald-400 font-bold font-mono">
                            +<?= number_format($pr['BonousAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-4 text-amber-400 font-bold font-mono">
                            +<?= number_format($pr['OvertimeAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-4 text-rose-400 font-bold font-mono">
                            -<?= number_format($pr['LeaveDeductionAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-4 font-extrabold text-emerald-400 text-base font-mono">
                            <?= number_format($pr['NetSalary'] ?? 0) ?> <span class="text-xs font-normal text-gray-600 dark:text-gray-400">MMK</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($pr['Status'] === 'Paid'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Paid</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> <?= htmlspecialchars($pr['Status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/payrollsystem/employee/payroll_slip/<?= $pr['PayrollID'] ?>" target="_blank" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-violet-600/20 hover:bg-violet-600/40 text-violet-300 border border-violet-500/30 text-xs font-bold transition-all hover:scale-105">
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
