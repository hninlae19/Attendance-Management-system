<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Types</h1>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Add Leave Type
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-4">ID</th>
                <th scope="col" class="px-6 py-4">Name</th>
                <th scope="col" class="px-6 py-4 text-center">Days Allowed</th>
                <th scope="col" class="px-6 py-4 text-center">Paid Type</th>
                <th scope="col" class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data['leaveTypes'])): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="5" class="px-6 py-4 text-center">No leave types found.</td>
                </tr>
            <?php else: ?>
                <?php foreach($data['leaveTypes'] as $lt): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4"><?= $lt['id'] ?></td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($lt['name']) ?></td>
                    <td class="px-6 py-4 text-center"><?= htmlspecialchars($lt['days_allowed']) ?> Days</td>
                    <td class="px-6 py-4 text-center">
                        <?php if($lt['is_paid']): ?>
                            <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Paid Leave</span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Unpaid Leave</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editLeaveType(<?= $lt['id'] ?>, '<?= htmlspecialchars(addslashes($lt['name'])) ?>', <?= $lt['days_allowed'] ?>, <?= $lt['is_paid'] ?>)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                        <form action="/payrollsystem/admin/leave_types" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $lt['id'] ?>">
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
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Leave Type</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="add">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Name</label>
                <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div>
                <label for="days_allowed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Days Allowed (per year)</label>
                <input type="number" name="days_allowed" id="days_allowed" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="flex items-center">
                <input id="is_paid" name="is_paid" type="checkbox" value="1" checked class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_paid" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">This is a Paid Leave</label>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Leave Type</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div>
                <label for="edit_days_allowed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Days Allowed (per year)</label>
                <input type="number" name="days_allowed" id="edit_days_allowed" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="flex items-center">
                <input id="edit_is_paid" name="is_paid" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="edit_is_paid" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">This is a Paid Leave</label>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editLeaveType(id, name, days, isPaid) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_days_allowed').value = days;
        document.getElementById('edit_is_paid').checked = isPaid == 1;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
