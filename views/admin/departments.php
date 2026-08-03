<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Departments</h1>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Add Department
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-4">ID</th>
                <th scope="col" class="px-6 py-4">Department Name</th>
                <th scope="col" class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data['departments'])): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="3" class="px-6 py-4 text-center">No departments found.</td>
                </tr>
            <?php else: ?>
                <?php foreach($data['departments'] as $dept): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4"><?= $dept['id'] ?></td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($dept['name']) ?></td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editDepartment(<?= $dept['id'] ?>, '<?= htmlspecialchars(addslashes($dept['name'])) ?>')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                        <form action="/payrollsystem/admin/departments" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $dept['id'] ?>">
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
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Department</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/departments" method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department Name</label>
                <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
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
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Department</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/departments" method="POST" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editDepartment(id, name) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
