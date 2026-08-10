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
                            +<?= number_format($pr['ot_amount'] + $pr['bonus_amount']) ?>
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
                            <?php if($pr['status'] === 'Paid'): ?>
                            <button onclick="viewPayslip(<?= htmlspecialchars(json_encode($pr)) ?>)" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                <i class="fa-solid fa-file-invoice mr-1"></i> View Salary Form
                            </button>
                            <?php else: ?>
                            <span class="text-xs text-gray-400 italic">Not available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Payslip Modal -->
<div id="payslipModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-file-invoice-dollar text-primary mr-2"></i> Salary Form
            </h3>
            <button type="button" onclick="document.getElementById('payslipModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="payslipContent">
            <!-- Dynamic Content populated by JS -->
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end">
            <button type="button" onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700 flex items-center">
                <i class="fa-solid fa-print mr-2"></i> Print Form
            </button>
        </div>
    </div>
</div>

<script>
function viewPayslip(data) {
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const monthName = months[data.month - 1];
    
    // Formatting helper
    const fmt = (num) => new Intl.NumberFormat().format(num) + ' MMK';

    const html = `
        <div class="text-center mb-6 border-b pb-4 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Payslip for ${monthName} ${data.year}</h2>
            <p class="text-sm text-gray-500 mt-1">Payment Date: ${data.payment_date || 'N/A'}</p>
        </div>
        
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Earnings</h4>
                <div class="flex justify-between py-1 text-sm"><span class="text-gray-600 dark:text-gray-300">Basic Salary:</span> <span class="font-medium text-gray-900 dark:text-white">${fmt(data.basic_salary)}</span></div>
                <div class="flex justify-between py-1 text-sm"><span class="text-gray-600 dark:text-gray-300">Overtime Pay:</span> <span class="font-medium text-gray-900 dark:text-white">${fmt(data.ot_amount)}</span></div>
                <div class="flex justify-between py-1 text-sm"><span class="text-gray-600 dark:text-gray-300">Bonus:</span> <span class="font-medium text-gray-900 dark:text-white">${fmt(data.bonus_amount)}</span></div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deductions</h4>
                <div class="flex justify-between py-1 text-sm"><span class="text-gray-600 dark:text-gray-300">Leave Deduction:</span> <span class="font-medium text-gray-900 dark:text-white">${fmt(data.leave_deduction_amount)}</span></div>
                <div class="flex justify-between py-1 text-sm"><span class="text-gray-600 dark:text-gray-300">Late Deduction:</span> <span class="font-medium text-gray-900 dark:text-white">${fmt(data.late_deduction_amount)}</span></div>
                <div class="flex justify-between py-1 text-sm"><span class="text-gray-600 dark:text-gray-300">Other Deductions:</span> <span class="font-medium text-gray-900 dark:text-white">${fmt(data.other_deduction_amount)}</span></div>
            </div>
        </div>
        
        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-5 rounded-xl border border-indigo-100 dark:border-indigo-800 flex justify-between items-center">
            <div>
                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-wider">Net Salary</p>
                <p class="text-xs text-indigo-500 mt-1">Total amount paid to employee</p>
            </div>
            <div class="text-3xl font-black text-indigo-700 dark:text-indigo-300">
                ${fmt(data.net_salary)}
            </div>
        </div>
    `;

    document.getElementById('payslipContent').innerHTML = html;
    document.getElementById('payslipModal').classList.remove('hidden');
}
</script>
