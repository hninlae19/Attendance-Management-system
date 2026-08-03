<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Payslips</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Month/Year</th>
                    <th scope="col" class="px-6 py-4">Basic Salary</th>
                    <th scope="col" class="px-6 py-4 text-green-600">Earnings</th>
                    <th scope="col" class="px-6 py-4 text-red-600">Deductions</th>
                    <th scope="col" class="px-6 py-4 font-bold text-gray-900 dark:text-white">Net Pay</th>
                    <th scope="col" class="px-6 py-4 text-center">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['myPayslips'])): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                            No payslips available yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['myPayslips'] as $pr): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <?= date('F Y', mktime(0, 0, 0, $pr['month'], 10, $pr['year'])) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            <?= number_format($pr['basic_salary']) ?> MMK
                        </td>
                        <td class="px-6 py-4 text-green-600">
                            +<?= number_format($pr['ot_amount'] + $pr['bonus_amount'] + $pr['allowance_amount']) ?>
                        </td>
                        <td class="px-6 py-4 text-red-600">
                            -<?= number_format($pr['deduction_amount']) ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-primary dark:text-indigo-400 text-lg">
                            <?= number_format($pr['net_salary']) ?> MMK
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($pr['status'] === 'Paid'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Paid</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300">Generated</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                <i class="fa-solid fa-download mr-1"></i> Download PDF
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
