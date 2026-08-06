<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Deduction Management</h1>
        <p class="text-gray-500 text-sm mt-1">Manage manual deductions for employee payroll.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/30">
        <i class="fa-solid fa-plus mr-2"></i> Add Deduction
    </button>
</div>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Amount</th>
                    <th scope="col" class="px-6 py-4">Type / Reason</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['deductions'])): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-minus-circle text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                            No deductions recorded.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['deductions'] as $deduction): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars(($deduction['first_name'] ?? '') . ' ' . ($deduction['last_name'] ?? '')) ?></div>
                            <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($deduction['employee_code'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 font-bold text-rose-600 dark:text-rose-400">
                            <?= number_format($deduction['amount'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium bg-rose-100 text-rose-800 rounded-full dark:bg-rose-900 dark:text-rose-300"><?= htmlspecialchars($deduction['type'] ?? '') ?></span>
                            <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($deduction['reason'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300"><?= htmlspecialchars($deduction['status'] ?? '') ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="/payrollsystem/admin/deductions" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this deduction?');">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $deduction['id'] ?>">
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fa-solid fa-minus-circle mr-2 text-rose-500"></i> Add New Deduction</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/deductions" method="POST" class="p-6 space-y-4">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="add">
            
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Employee</label>
                <select name="employee_id" id="employee_id" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <option value="">Choose Employee...</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <?php if($emp['status'] === 'Active'): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= htmlspecialchars($emp['employee_code']) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (MMK)</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Applied</label>
                    <input type="date" name="date" id="date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deduction Type</label>
                <select name="type" id="type" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <option value="Half Day Absence">Half Day Absence</option>
                    <option value="Full Day Absence">Full Day Absence</option>
                    <option value="Damage">Damage to Company Property</option>
                    <option value="Loan">Loan Repayment</option>
                    <option value="Manual Adjustment">Manual Adjustment</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason / Description</label>
                <input type="text" name="reason" id="reason" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700 transition-all duration-300 hover:shadow-lg hover:shadow-primary/30">Save Deduction</button>
            </div>
        </form>
    </div>
</div>
