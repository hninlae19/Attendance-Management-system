<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Employee Directory</h1>
        <p class="text-gray-500 text-sm mt-1">Manage your team members and their account access.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-primary/30 flex items-center transition-all hover:-translate-y-0.5">
        <i class="fa-solid fa-plus mr-2"></i> Add Employee
    </button>
</div>

<!-- Filters and Search -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between" data-aos="fade-up">
    <div class="flex items-center gap-3 w-full md:w-auto">
        <div class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-search"></i>
            </div>
            <input type="text" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Search employees...">
        </div>
        <select class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">All Departments</option>
            <?php foreach($data['departments'] as $dept): ?>
                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
        <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 p-2 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Export to Excel">
            <i class="fa-solid fa-file-excel"></i>
        </button>
        <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 p-2 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Export to PDF">
            <i class="fa-solid fa-file-pdf"></i>
        </button>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Department & Role</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(empty($data['employees'])): ?>
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-users-slash text-2xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <p class="font-medium text-gray-900 dark:text-white">No employees found.</p>
                            <p class="text-sm mt-1">Try adding a new employee or adjust your filters.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['employees'] as $emp): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-sm ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all shadow-sm">
                                    <?= strtoupper(substr($emp['first_name'],0,1) . substr($emp['last_name'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></div>
                                    <div class="text-xs text-primary font-medium">EMP-<?= htmlspecialchars($emp['employee_code']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-gray-300"><?= htmlspecialchars($emp['department_name']) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($emp['position_name']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-300 flex items-center"><i class="fa-regular fa-envelope mr-1.5 text-gray-400"></i> <?= htmlspecialchars($emp['email']) ?></div>
                            <?php if(!empty($emp['phone'])): ?>
                            <div class="text-xs text-gray-500 mt-1 flex items-center"><i class="fa-solid fa-phone mr-1.5 text-gray-400"></i> <?= htmlspecialchars($emp['phone']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($emp['status'] === 'Active'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="/payrollsystem/admin/employee/<?= $emp['id'] ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors tooltip" title="View Profile">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="/payrollsystem/admin/employee/<?= $emp['id'] ?>#edit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 transition-colors tooltip" title="Edit Employee">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="/payrollsystem/admin/employees" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this employee?');">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-colors tooltip" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center text-sm text-gray-500">
        <div>Showing <span class="font-bold text-gray-900 dark:text-white"><?= count($data['employees'] ?? []) ?></span> employees</div>
        <div class="flex gap-1">
            <button class="px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-400 cursor-not-allowed" disabled>Prev</button>
            <button class="px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Next</button>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-4xl w-full shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa-solid fa-user-plus text-primary mr-3"></i> Add New Employee</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/employees" method="POST" class="p-6 md:p-8">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wider flex items-center"><i class="fa-regular fa-id-card text-gray-400 mr-2"></i> Account Details</h4>
                        <div class="space-y-4 bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email Address (Login ID)</label>
                                <input type="email" name="email" id="email" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                                <input type="password" name="password" id="password" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wider flex items-center"><i class="fa-solid fa-address-book text-gray-400 mr-2"></i> Contact Details</h4>
                        <div class="space-y-4 bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Residential Address</label>
                                <textarea name="address" id="address" rows="2" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wider flex items-center"><i class="fa-regular fa-user text-gray-400 mr-2"></i> Personal Details</h4>
                        <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="col-span-2 hidden">
                                <label for="employee_code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Employee Code</label>
                                <input type="text" name="employee_code" id="employee_code" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm" value="AUTO">
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                                <input type="text" name="first_name" id="first_name" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                            </div>
                            <div class="col-span-2">
                                <label for="gender" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                <select name="gender" id="gender" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm appearance-none cursor-pointer">
                                    <option value="Other">Other</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wider flex items-center"><i class="fa-solid fa-briefcase text-gray-400 mr-2"></i> Job Details</h4>
                        <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div>
                                <label for="department_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Department</label>
                                <select name="department_id" id="department_id" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm appearance-none cursor-pointer">
                                    <option value="">Select Department</option>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="position_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Position</label>
                                <select name="position_id" id="position_id" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm appearance-none cursor-pointer">
                                    <option value="">Select Position</option>
                                    <?php foreach($data['positions'] as $pos): ?>
                                        <option value="<?= $pos['id'] ?>" data-department-id="<?= $pos['department_id'] ?>" data-basic-salary="<?= $pos['basic_salary'] ?? 0 ?>"><?= htmlspecialchars($pos['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="join_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Join Date</label>
                                <input type="date" name="join_date" id="join_date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="basic_salary" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Basic Salary</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 font-medium">MMK</div>
                                    <input type="number" name="basic_salary" id="basic_salary" required class="w-full pl-12 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-primary rounded-xl shadow-lg shadow-primary/30 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">Create Employee</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const positionSelect = document.getElementById('position_id');
    const positionOptions = Array.from(positionSelect.options);

    departmentSelect.addEventListener('change', function() {
        const selectedDeptId = this.value;
        
        // Reset and clear current selection
        positionSelect.value = "";
        document.getElementById('basic_salary').value = "";
        
        // Hide/Show options based on department
        positionOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = 'block'; // Always show "Select Position"
                return;
            }
            
            const deptId = option.getAttribute('data-department-id');
            if (selectedDeptId === "" || deptId === selectedDeptId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    positionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const basicSalary = selectedOption.getAttribute('data-basic-salary');
        const basicSalaryInput = document.getElementById('basic_salary');
        if (basicSalary && basicSalary > 0) {
            basicSalaryInput.value = basicSalary;
        } else {
            basicSalaryInput.value = "";
        }
    });
});
</script>
