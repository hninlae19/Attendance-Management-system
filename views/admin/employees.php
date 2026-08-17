<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#180f33] via-[#241447] to-[#121c3b] border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-users text-secondary"></i>
                    <span>Workforce Directory</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 text-xs font-bold uppercase tracking-wider font-mono">
                    <?= count($data['employees'] ?? []) ?> Staff Members
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Employee <span class="gradient-text">Directory</span>
            </h1>
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Manage personnel profiles, position allocations, contact records, and system access credentials.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-extrabold shadow-lg shadow-violet-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Add New Employee</span>
        </button>
    </div>
</div>

<!-- Filters and Search Toolbar -->
<div class="card-glass rounded-2xl p-4 mb-6 border border-violet-500/20 flex flex-col md:flex-row gap-4 items-center justify-between shadow-lg" data-aos="fade-up">
    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative w-full md:w-72">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-violet-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" id="employeeSearchInput" onkeyup="filterEmployees()" 
                   class="bg-darker/60 border border-violet-700/30 text-white text-xs rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 block w-full pl-9 p-2.5 placeholder-gray-500 shadow-inner" 
                   placeholder="Search employee name or email...">
        </div>
        <select id="departmentFilterSelect" onchange="filterEmployees()" 
                class="bg-darker/60 border border-violet-700/30 text-gray-300 text-xs rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 block p-2.5 cursor-pointer shadow-inner">
            <option value="">All Departments</option>
            <?php foreach($data['departments'] as $dept): ?>
                <option value="<?= htmlspecialchars($dept['DeptName']) ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
        <button onclick="window.print()" class="text-gray-400 hover:text-white p-2.5 border border-violet-700/30 rounded-xl bg-surface/60 hover:bg-violet-600/20 transition-all text-xs flex items-center gap-1.5" title="Print Directory">
            <i class="fa-solid fa-print"></i>
            <span class="hidden sm:inline">Print</span>
        </button>
    </div>
</div>

<!-- Table -->
<div class="card-glass rounded-3xl overflow-hidden border border-violet-500/20 mb-8 shadow-xl" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-400" id="employeeTable">
            <thead class="text-xs uppercase bg-surface/80 text-violet-300/80 border-b border-violet-900/40">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Department & Role</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-violet-900/30">
                <?php if(empty($data['employees'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-surface rounded-2xl border border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                <i class="fa-solid fa-users-slash text-xl"></i>
                            </div>
                            <p class="font-semibold text-gray-300">No employees found</p>
                            <p class="text-xs text-gray-500 mt-0.5">Try adding a new employee or adjust your search filter.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['employees'] as $emp): ?>
                    <tr class="hover:bg-violet-950/20 transition-colors group emp-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-600/30 to-cyan-500/30 text-cyan-300 border border-violet-500/30 flex items-center justify-center font-extrabold text-xs shadow-inner">
                                    <?= strtoupper(substr($emp['FirstName'],0,1) . substr($emp['LastName'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-white group-hover:text-violet-300 transition-colors emp-name"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?></div>
                                    <div class="text-[11px] text-cyan-400 font-mono">EMP-<?= htmlspecialchars(str_pad($emp['EmpID'], 4, '0', STR_PAD_LEFT)) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-200 text-xs emp-dept"><?= htmlspecialchars($emp['DeptName'] ?? 'No Department') ?></div>
                            <div class="text-[11px] text-violet-400 font-medium"><?= htmlspecialchars($emp['PositionName'] ?? 'No Position') ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-300 flex items-center gap-1.5"><i class="fa-regular fa-envelope text-violet-400"></i> <?= htmlspecialchars($emp['Email']) ?></div>
                            <?php if(!empty($emp['PhoneNumber'])): ?>
                            <div class="text-[11px] text-gray-400 mt-1 flex items-center gap-1.5"><i class="fa-solid fa-phone text-secondary"></i> <?= htmlspecialchars($emp['PhoneNumber']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($emp['Status'] === 'Active'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Active</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30"><span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 opacity-90 group-hover:opacity-100 transition-opacity">
                                <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>" class="w-8 h-8 rounded-xl bg-violet-600/20 text-violet-300 border border-violet-500/30 flex items-center justify-center hover:bg-violet-600/40 hover:scale-105 transition-all" title="View Profile">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="/payrollsystem/admin/employee_salary_history/<?= $emp['EmpID'] ?>" class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-300 border border-emerald-500/30 flex items-center justify-center hover:bg-emerald-600/40 hover:scale-105 transition-all" title="Salary History">
                                    <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                                </a>
                                <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>#edit" class="w-8 h-8 rounded-xl bg-amber-600/20 text-amber-300 border border-amber-500/30 flex items-center justify-center hover:bg-amber-600/40 hover:scale-105 transition-all" title="Edit Employee">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="/payrollsystem/admin/employees" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $emp['EmpID'] ?>">
                                    <button type="submit" class="w-8 h-8 rounded-xl bg-rose-600/20 text-rose-300 border border-rose-500/30 flex items-center justify-center hover:bg-rose-600/40 hover:scale-105 transition-all" title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
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
    <div class="px-6 py-4 border-t border-violet-900/40 bg-surface/60 flex justify-between items-center text-xs text-gray-400">
        <div>Showing <span class="font-bold text-white font-mono"><?= count($data['employees'] ?? []) ?></span> active staff profiles</div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-950/80 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="card-glass rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden border border-violet-500/30 transform transition-all">
        <div class="px-6 py-5 border-b border-violet-900/40 flex justify-between items-center bg-surface/80">
            <h3 class="text-lg font-extrabold text-white flex items-center gap-2.5 font-outfit">
                <i class="fa-solid fa-user-plus text-secondary"></i> Add New Employee
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-surface text-gray-400 hover:text-white border border-violet-900/40 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/employees" method="POST" class="p-6 md:p-8">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-5">
                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-regular fa-id-card text-secondary"></i> Account Credentials
                        </h4>
                        <div class="space-y-3.5 bg-darker/50 p-4 rounded-2xl border border-violet-700/30 shadow-inner">
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Email Address (Login ID)</label>
                                <input type="email" name="email" id="email" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner">
                            </div>
                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Account Password</label>
                                <input type="password" name="password" id="password" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner">
                                <span id="password-error" class="text-xs text-rose-400 hidden mt-1">Password must be at least 6 characters.</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-secondary"></i> Contact Information
                        </h4>
                        <div class="space-y-3.5 bg-darker/50 p-4 rounded-2xl border border-violet-700/30 shadow-inner">
                            <div>
                                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner">
                                <span id="phone-error" class="text-xs text-rose-400 hidden mt-1">Invalid phone number format.</span>
                            </div>
                            <div>
                                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Residential Address</label>
                                <textarea name="address" id="address" rows="2" class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5">
                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-regular fa-user text-secondary"></i> Personal Details
                        </h4>
                        <div class="grid grid-cols-2 gap-3.5 bg-darker/50 p-4 rounded-2xl border border-violet-700/30 shadow-inner">
                            <div class="col-span-2 hidden">
                                <input type="hidden" name="employee_code" id="employee_code" value="AUTO">
                            </div>
                            <div>
                                <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">First Name</label>
                                <input type="text" name="first_name" id="first_name" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner">
                            </div>
                            <div>
                                <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner">
                            </div>
                            <div class="col-span-2">
                                <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Gender</label>
                                <select name="gender" id="gender" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner cursor-pointer">
                                    <option value="Other">Other</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-secondary"></i> Job & Payroll Placement
                        </h4>
                        <div class="grid grid-cols-2 gap-3.5 bg-darker/50 p-4 rounded-2xl border border-violet-700/30 shadow-inner">
                            <div>
                                <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Department</label>
                                <select name="department_id" id="department_id" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner cursor-pointer">
                                    <option value="">Select Department</option>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="position_id" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Position</label>
                                <select name="position_id" id="position_id" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner cursor-pointer">
                                    <option value="">Select Position</option>
                                    <?php foreach($data['positions'] as $pos): ?>
                                        <option value="<?= $pos['PositionID'] ?>" data-department-id="<?= $pos['DeptID'] ?>" data-basic-salary="<?= $pos['BasicSalary'] ?? 0 ?>"><?= htmlspecialchars($pos['PositionName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="join_date" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Join Date</label>
                                <input type="date" name="join_date" id="join_date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner">
                            </div>
                            <div>
                                <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Basic Salary (MMK)</label>
                                <input type="number" name="basic_salary" id="basic_salary" required class="w-full px-3.5 py-2 bg-darker/80 border border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-white text-xs shadow-inner font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-violet-900/40">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2 text-xs font-bold text-gray-400 bg-surface border border-violet-900/40 rounded-xl hover:text-white transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl shadow-lg shadow-violet-600/30 hover:scale-105 transition-all">Create Employee Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
function filterEmployees() {
    const searchVal = (document.getElementById('employeeSearchInput').value || '').toLowerCase();
    const deptVal = (document.getElementById('departmentFilterSelect').value || '').toLowerCase();
    const rows = document.querySelectorAll('.emp-row');

    rows.forEach(row => {
        const name = (row.querySelector('.emp-name')?.textContent || '').toLowerCase();
        const dept = (row.querySelector('.emp-dept')?.textContent || '').toLowerCase();

        const matchSearch = !searchVal || name.includes(searchVal);
        const matchDept = !deptVal || dept.includes(deptVal);

        if (matchSearch && matchDept) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const positionSelect = document.getElementById('position_id');
    const positionOptions = Array.from(positionSelect.options);

    departmentSelect.addEventListener('change', function() {
        const selectedDeptId = this.value;
        positionSelect.value = "";
        document.getElementById('basic_salary').value = "";
        
        positionOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = 'block';
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

    // Inline Validation
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    if (phoneInput && phoneError) {
        phoneInput.addEventListener('input', function() {
            const phoneVal = this.value.trim();
            const isValid = /^[0-9\-\+\s\(\)]{7,20}$/.test(phoneVal) || phoneVal === '';
            if (!isValid) {
                phoneError.classList.remove('hidden');
                this.classList.add('border-rose-500');
            } else {
                phoneError.classList.add('hidden');
                this.classList.remove('border-rose-500');
            }
        });
    }

    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');
    if (passwordInput && passwordError) {
        passwordInput.addEventListener('input', function() {
            const pwdVal = this.value;
            if (pwdVal.length > 0 && pwdVal.length < 6) {
                passwordError.classList.remove('hidden');
                this.classList.add('border-rose-500');
            } else {
                passwordError.classList.add('hidden');
                this.classList.remove('border-rose-500');
            }
        });
    }
});
</script>
