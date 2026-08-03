<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Employees</h1>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Add Employee
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">EMP Code</th>
                    <th scope="col" class="px-6 py-4">Name</th>
                    <th scope="col" class="px-6 py-4">Department</th>
                    <th scope="col" class="px-6 py-4">Position</th>
                    <th scope="col" class="px-6 py-4">Account Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['employees'])): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="6" class="px-6 py-4 text-center">No employees found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['employees'] as $emp): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($emp['employee_code']) ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                            <div class="text-xs text-gray-500 font-normal"><?= htmlspecialchars($emp['email']) ?></div>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($emp['department_name']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($emp['position_name']) ?></td>
                        <td class="px-6 py-4">
                            <?php if($emp['status'] === 'Active'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Active</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap">
                            <a href="/payrollsystem/admin/employee/<?= $emp['id'] ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-eye"></i> View</a>
                            <a href="/payrollsystem/admin/employee/<?= $emp['id'] ?>#edit" class="font-medium text-amber-600 dark:text-amber-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form action="/payrollsystem/admin/employees" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this employee? This will also remove their user account and login access.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fa-solid fa-trash"></i> Delete</button>
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
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add New Employee</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/employees" method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Account Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address (Login ID)</label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Personal Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="employee_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee Code</label>
                    <input type="text" name="employee_code" id="employee_code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Job Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                    <select name="department_id" id="department_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="">Select...</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="position_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position</label>
                    <select name="position_id" id="position_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="">Select...</option>
                        <?php foreach($data['positions'] as $pos): ?>
                            <option value="<?= $pos['id'] ?>"><?= htmlspecialchars($pos['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="join_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Join Date</label>
                    <input type="date" name="join_date" id="join_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Basic Salary</label>
                    <input type="number" name="basic_salary" id="basic_salary" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>
            
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Contact Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <input type="text" name="address" id="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Create Employee</button>
            </div>
        </form>
    </div>
</div>
