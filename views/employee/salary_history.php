<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Salary History</h1>
    <p class="text-gray-500 text-sm mt-1">View your monthly salary records, bonuses, deductions, and net pay.</p>
</div>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-400">
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
            <tbody>
                <?php if(empty($data['payrolls'])): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700">
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                            No salary history available yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['payrolls'] as $pr): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            <?= htmlspecialchars($pr['PayrollMonth']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                            <?= number_format($pr['BasicSalary'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-4 text-teal-600 dark:text-teal-400 font-medium">
                            +<?= number_format($pr['BonousAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-4 text-indigo-600 dark:text-indigo-400 font-medium">
                            +<?= number_format($pr['OvertimeAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-4 text-red-500 dark:text-red-400 font-medium">
                            -<?= number_format($pr['LeaveDeductionAmount'] ?? 0) ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-lg">
                            <?= number_format($pr['NetSalary'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($pr['Status'] === 'Paid'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Paid</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300"><?= htmlspecialchars($pr['Status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/payrollsystem/employee/payroll_slip/<?= $pr['PayrollID'] ?>" class="font-medium text-primary hover:text-indigo-700 hover:underline inline-flex items-center gap-1 transition-colors">
                                <i class="fa-solid fa-eye"></i> View Slip
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
