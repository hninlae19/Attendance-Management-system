<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-users"></i>
                    <span>Workforce Directory</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['employees'] ?? []) ?> Staff Members
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Employee <span class="gradient-text">Directory</span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Manage personnel profiles, position allocations, contact records, and system access credentials.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-plus text-indigo-600"></i>
            <span>Add New Employee</span>
        </button>
    </div>
</div>

<!-- Filters and Search Toolbar -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 mb-6 border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 items-center justify-between shadow-sm" data-aos="fade-up">
    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative w-full md:w-72">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" id="employeeSearchInput" onkeyup="filterEmployees()" 
                   class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-9 p-2.5 placeholder-slate-400 shadow-sm" 
                   placeholder="Search employee name or email...">
        </div>
        <select id="departmentFilterSelect" onchange="filterEmployees()" 
                class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-2.5 cursor-pointer shadow-sm">
            <option value="">All Departments</option>
            <?php foreach($data['departments'] as $dept): ?>
                <option value="<?= htmlspecialchars($dept['DeptName']) ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
        <button onclick="window.print()" class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white p-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all text-xs flex items-center gap-1.5 shadow-sm" title="Print Directory">
            <i class="fa-solid fa-print"></i>
            <span class="hidden sm:inline font-semibold">Print</span>
        </button>
    </div>
</div>

<!-- Directory Table View -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300" id="employeeTable">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Contact</th>
                    <th scope="col" class="px-6 py-4">Department</th>
                    <th scope="col" class="px-6 py-4">Position</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if(empty($data['employees'])): ?>
                    <tr class="bg-white dark:bg-slate-800" id="noResultsRow">
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-users-slash text-2xl"></i>
                            </div>
                            <p class="font-medium text-slate-900 dark:text-white">No employees found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr id="noResultsRow" style="display: none;" class="bg-white dark:bg-slate-800">
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-users-slash text-2xl"></i>
                            </div>
                            <p class="font-medium text-slate-900 dark:text-white">No employees match your filter.</p>
                        </td>
                    </tr>
                    <?php foreach($data['employees'] as $emp): ?>
                    <tr class="emp-row hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group"
                        data-name="<?= strtolower(htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName'])) ?>" 
                        data-email="<?= strtolower(htmlspecialchars($emp['Email'])) ?>"
                        data-department="<?= htmlspecialchars($emp['DeptName'] ?? 'Unassigned') ?>">
                        
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-extrabold text-xs shadow-sm">
                                    <?= strtoupper(substr($emp['FirstName'],0,1) . substr($emp['LastName'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            <?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?>
                                        </a>
                                    </div>
                                    <div class="text-xs text-indigo-600 dark:text-sky-400 font-mono font-semibold">EMP-<?= str_pad($emp['EmpID'], 4, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="text-xs text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fa-regular fa-envelope text-slate-400 w-3"></i> <?= htmlspecialchars($emp['Email']) ?>
                                </div>
                                <?php if(!empty($emp['PhoneNumber'])): ?>
                                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-slate-400 w-3"></i> <?= htmlspecialchars($emp['PhoneNumber']) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-200">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                <?= htmlspecialchars($emp['DeptName'] ?? 'Unassigned') ?>
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-medium text-xs">
                            <?= htmlspecialchars($emp['PositionName'] ?? 'No Position') ?>
                        </td>
                        
                        <td class="px-6 py-4">
                            <?php if($emp['Status'] === 'Active'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 flex items-center justify-center transition-colors shadow-sm" title="View Profile">
                                    <i class="fa-regular fa-user text-xs"></i>
                                </a>
                                <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>#edit" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/60 flex items-center justify-center border border-indigo-200 dark:border-indigo-800 transition-colors shadow-sm" title="Edit Profile">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="/payrollsystem/admin/employees" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to remove this employee?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $emp['EmpID'] ?>">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:hover:bg-rose-900/60 flex items-center justify-center border border-rose-200 dark:border-rose-800 transition-colors shadow-sm" title="Delete">
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
</div>


<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2.5 font-outfit">
                <i class="fa-solid fa-user-plus text-indigo-500"></i> Add New Employee
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
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
                        <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-regular fa-user"></i> Personal Details
                        </h4>
                        <div class="grid grid-cols-2 gap-3.5 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                            <div class="col-span-2 hidden">
                                <input type="hidden" name="employee_code" id="employee_code" value="AUTO">
                            </div>
                            <div>
                                <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">First Name</label>
                                <input type="text" name="first_name" id="first_name" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm">
                            </div>
                            <div>
                                <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm">
                            </div>
                            <div class="col-span-2">
                                <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Gender</label>
                                <select name="gender" id="gender" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm cursor-pointer">
                                    <option value="Other">Other</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-address-book"></i> Contact Information
                        </h4>
                        <div class="space-y-3.5 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                            <div>
                                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm">
                                <span id="phone-error" class="text-xs text-rose-500 hidden mt-1">Invalid phone number format.</span>
                            </div>
                            <div>
                                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Residential Address</label>
                                <textarea name="address" id="address" rows="2" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5">
                    <div>
                        <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-regular fa-id-card"></i> Account Credentials
                        </h4>
                        <div class="space-y-3.5 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Email Address (Login ID)</label>
                                <input type="email" name="email" id="email" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm">
                            </div>
                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Account Password (Default)</label>
                                <input type="password" name="password" id="password" required value="password" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm">
                                <span id="password-error" class="text-xs text-rose-500 hidden mt-1">Password must be at least 6 characters.</span>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1"><i class="fa-solid fa-circle-info text-indigo-500"></i> New employees are prompted to change password upon first login.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-indigo-600 dark:text-indigo-400 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-briefcase"></i> Job & Payroll Placement
                        </h4>
                        <div class="grid grid-cols-2 gap-3.5 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                            <div>
                                <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Department</label>
                                <select name="department_id" id="department_id" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm cursor-pointer">
                                    <option value="">Select Department</option>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="position_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Position</label>
                                <select name="position_id" id="position_id" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm cursor-pointer">
                                    <option value="">Select Position</option>
                                    <?php foreach($data['positions'] as $pos): ?>
                                        <option value="<?= $pos['PositionID'] ?>" data-department-id="<?= $pos['DeptID'] ?>" data-basic-salary="<?= $pos['BasicSalary'] ?? 0 ?>"><?= htmlspecialchars($pos['PositionName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="join_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Join Date</label>
                                <input type="date" name="join_date" id="join_date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm">
                            </div>
                            <div>
                                <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Basic Salary (MMK)</label>
                                <input type="number" name="basic_salary" id="basic_salary" required class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-white text-xs shadow-sm font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 hover:scale-105 transition-all">Create Employee Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
function filterEmployees() {
    const searchVal = (document.getElementById('employeeSearchInput').value || '').toLowerCase();
    const deptVal = (document.getElementById('departmentFilterSelect').value || '').toLowerCase();
    const rows = document.querySelectorAll('.emp-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        const dept = (row.getAttribute('data-department') || '').toLowerCase();
        
        const matchSearch = !searchVal || name.includes(searchVal) || email.includes(searchVal);
        const matchDept = !deptVal || dept === deptVal;

        if (matchSearch && matchDept) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        if (visibleCount === 0 && rows.length > 0) {
            noResultsRow.style.display = '';
        } else {
            noResultsRow.style.display = 'none';
        }
    }
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
