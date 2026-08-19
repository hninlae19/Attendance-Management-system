<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Types</h1>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Add Leave Type
    </button>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
<div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 flex items-start">
    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 mr-3"></i>
    <div>
        <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Save Failed</h4>
        <p class="text-sm text-red-600 dark:text-red-300 mt-1">A leave type with this name already exists. Please choose a unique name.</p>
    </div>
</div>
<?php endif; ?>

<div class="bg-surface dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-4">ID</th>
                <th scope="col" class="px-6 py-4">Leave Type</th>
                <th scope="col" class="px-6 py-4 text-center">Days Allowed</th>
                <th scope="col" class="px-6 py-4 text-center">Is Paid?</th>
                <th scope="col" class="px-6 py-4 text-center">Deduction Rate</th>
                <th scope="col" class="px-6 py-4 text-center">Req. Duration (Months)</th>
                <th scope="col" class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data['leaveTypes'])): ?>
                <tr class="bg-surface border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="6" class="px-6 py-4 text-center">No leave types found.</td>
                </tr>
            <?php else: ?>
                <?php foreach($data['leaveTypes'] as $lt): ?>
                <tr class="bg-surface border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4"><?= $lt['LeaveTypeID'] ?></td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($lt['LeaveType']) ?></td>
                    <td class="px-6 py-4 text-center font-bold"><?= $lt['DaysAllowed'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php if($lt['IsPaid']): ?>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Yes</span>
                        <?php else: ?>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">No</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-500"><?= $lt['DeductionRate'] ?></td>
                    <td class="px-6 py-4 text-center font-bold text-gray-600 dark:text-gray-400"><?= $lt['DurationMonths'] ?></td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editLeaveType(<?= $lt['LeaveTypeID'] ?>, '<?= htmlspecialchars(addslashes($lt['LeaveType'])) ?>', <?= $lt['DaysAllowed'] ?>, <?= $lt['IsPaid'] ? 1 : 0 ?>, <?= $lt['DeductionRate'] ?>, <?= $lt['DurationMonths'] ?>)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                        <form action="/payrollsystem/admin/leave_types" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $lt['LeaveTypeID'] ?>">
                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-surface dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Leave Type</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type Name</label>
                <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Days Allowed</label>
                <input type="number" name="days" id="days" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_paid" id="is_paid" value="1" class="text-gray-900 w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                <label for="is_paid" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Is Paid Leave?</label>
            </div>
            <div class="mb-4">
                <label for="deduction_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deduction Rate (if unpaid/exceeded)</label>
                <input type="number" step="0.01" name="deduction_rate" id="deduction_rate" value="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="duration_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Required Employment Duration (Months)</label>
                <input type="number" name="duration_months" id="duration_months" value="0" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-surface border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-surface dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Leave Type</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="edit_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Days Allowed</label>
                <input type="number" name="days" id="edit_days" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_paid" id="edit_is_paid" value="1" class="text-gray-900 w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                <label for="edit_is_paid" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Is Paid Leave?</label>
            </div>
            <div class="mb-4">
                <label for="edit_deduction_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deduction Rate</label>
                <input type="number" step="0.01" name="deduction_rate" id="edit_deduction_rate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="edit_duration_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Required Employment Duration (Months)</label>
                <input type="number" name="duration_months" id="edit_duration_months" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-surface border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editLeaveType(id, name, days, isPaid, deductionRate, durationMonths) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_days').value = days;
        document.getElementById('edit_is_paid').checked = isPaid === 1;
        document.getElementById('edit_deduction_rate').value = deductionRate;
        document.getElementById('edit_duration_months').value = durationMonths;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
