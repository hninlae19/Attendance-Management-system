<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bonus Management</h1>
        <p class="text-gray-500 text-sm mt-1">Manage employee performance bonuses and other rewards.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/30">
        <i class="fa-solid fa-plus mr-2"></i> Add Bonus
    </button>
</div>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Department</th>
                    <th scope="col" class="px-6 py-4">Amount</th>
                    <th scope="col" class="px-6 py-4">Type</th>
                    <th scope="col" class="px-6 py-4">Date</th>
                    <th scope="col" class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['bonuses'])): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-gift text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                            No bonuses recorded.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['bonuses'] as $bonus): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars(($bonus['FirstName'] ?? '') . ' ' . ($bonus['LastName'] ?? '')) ?></div>
                            <div class="text-xs text-gray-500 mt-1">EMP-<?= str_pad($bonus['EmpID'] ?? 0, 5, '0', STR_PAD_LEFT) ?></div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                            <?= htmlspecialchars($bonus['DeptName'] ?? 'N/A') ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-teal-600 dark:text-teal-400">
                            <?= number_format($bonus['Amount'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-900 dark:text-teal-300"><?= htmlspecialchars($bonus['BonusType'] ?? '') ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 dark:text-gray-300"><?= date('M j, Y', strtotime($bonus['BonusDate'] ?? 'now')) ?></div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="/payrollsystem/admin/bonuses" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this bonus?');">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $bonus['EmpBonousID'] ?>">
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
            <h3 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fa-solid fa-gift mr-2 text-teal-500"></i> Add New Bonus</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/bonuses" method="POST" class="p-6 space-y-4">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="add">
            
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Employee</label>
                <select name="employee_id" id="employee_id" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <option value="">Choose Employee...</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <?php if($emp['Status'] === 'Active'): ?>
                            <option value="<?= $emp['EmpID'] ?>"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?> (EMP-<?= str_pad($emp['EmpID'], 5, '0', STR_PAD_LEFT) ?>)</option>
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
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date" name="date" id="date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bonus Type</label>
                <select name="type" id="type" required onchange="const ct = document.getElementById('custom_type_container'); const cti = document.getElementById('custom_type'); if(this.value === 'Other') { ct.classList.remove('hidden'); cti.setAttribute('required', 'required'); } else { ct.classList.add('hidden'); cti.removeAttribute('required'); }" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <option value="Performance">Performance Bonus</option>
                    <option value="Annual">Annual Bonus</option>
                    <option value="Referral">Referral Bonus</option>
                    <option value="Other">Other / Custom</option>
                </select>
            </div>

            <div id="custom_type_container" class="hidden">
                <label for="custom_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom Bonus Type</label>
                <input type="text" name="custom_type" id="custom_type" placeholder="e.g. Holiday Bonus" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason / Description</label>
                <input type="text" name="reason" id="reason" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700 transition-all duration-300 hover:shadow-lg hover:shadow-primary/30">Save Bonus</button>
            </div>
        </form>
    </div>
</div>
