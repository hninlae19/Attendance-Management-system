<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight font-outfit">
                Employee <span class="gradient-text">Directory</span>
            </h1>
            <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm mt-1">Manage personnel profiles, position allocations, contact records, and system access credentials.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-gray-900 dark:text-white text-xs font-extrabold shadow-lg shadow-violet-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
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
                   class="bg-darker/60 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white text-xs rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 block w-full pl-9 p-2.5 placeholder-gray-500 shadow-inner" 
                   placeholder="Search employee name or email...">
        </div>
        <select id="departmentFilterSelect" onchange="filterEmployees()" 
                class="bg-darker/60 border border-gray-300 dark:border-violet-700/30 text-gray-700 dark:text-gray-300 text-xs rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 block p-2.5 cursor-pointer shadow-inner">
            <option value="">All Departments</option>
            <?php foreach($data['departments'] as $dept): ?>
                <option value="<?= htmlspecialchars($dept['DeptName']) ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
        <button onclick="window.print()" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:text-white p-2.5 border border-gray-300 dark:border-violet-700/30 rounded-xl bg-surface/60 hover:bg-violet-600/20 transition-all text-xs flex items-center gap-1.5" title="Print Directory">
            <i class="fa-solid fa-print"></i>
            <span class="hidden sm:inline">Print</span>
        </button>
    </div>
</div>

<!-- Directory Grid View -->
<div id="directory-container" class="space-y-8 mb-8" data-aos="fade-up" data-aos-delay="100">
    <?php 
        // Group employees by department
        $groupedEmployees = [];
        $employeesList = $data['employees'] ?? [];
        foreach ($employeesList as $emp) {
            $dept = $emp['DeptName'] ?? 'Unassigned';
            if (!isset($groupedEmployees[$dept])) {
                $groupedEmployees[$dept] = [];
            }
            $groupedEmployees[$dept][] = $emp;
        }
        ksort($groupedEmployees);
    ?>

    <?php if(empty($employeesList)): ?>
        <div class="card-glass rounded-3xl p-12 text-center border border-violet-500/20 shadow-xl">
            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-violet-900/40 flex items-center justify-center mb-4 text-violet-400">
                <i class="fa-solid fa-users-slash text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No Employees Found</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Your directory is currently empty or no matches were found.</p>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-violet-600/20 hover:bg-violet-600/40 border border-violet-500/30 text-violet-300 font-bold transition-all inline-flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Add First Employee
            </button>
        </div>
    <?php else: ?>
        <?php foreach($groupedEmployees as $deptName => $employees): ?>
        <div class="department-group" data-department="<?= htmlspecialchars($deptName) ?>">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-3 border-b border-violet-500/20 pb-3">
                <div class="w-8 h-8 rounded-lg bg-surface border border-violet-500/30 flex items-center justify-center text-secondary shadow-inner">
                    <i class="fa-solid fa-users-rectangle text-sm"></i>
                </div>
                <?= htmlspecialchars($deptName) ?>
                <span class="text-[10px] font-bold text-violet-300 bg-violet-900/30 px-2 py-0.5 rounded-md border border-gray-300 dark:border-violet-700/30"><?= count($employees) ?> <?= count($employees) === 1 ? 'Member' : 'Members' ?></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
                <?php foreach($employees as $emp): ?>
                <div class="emp-card card-glass rounded-2xl p-5 flex flex-col justify-between hover:border-violet-400/50 hover:shadow-[0_8px_30px_-5px_rgba(124,58,237,0.25)] hover:-translate-y-1 transition-all duration-300 relative group" 
                     data-name="<?= strtolower(htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName'])) ?>" 
                     data-email="<?= strtolower(htmlspecialchars($emp['Email'])) ?>">
                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3.5">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-600/30 to-cyan-500/30 text-cyan-300 border border-violet-500/30 flex items-center justify-center font-extrabold text-lg shadow-inner group-hover:scale-105 transition-transform">
                                    <?= strtoupper(substr($emp['FirstName'],0,1) . substr($emp['LastName'],0,1)) ?>
                                </div>
                                <?php if($emp['Status'] === 'Active'): ?>
                                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-darker rounded-full flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]" title="Active"></div>
                                    </div>
                                <?php else: ?>
                                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-darker rounded-full flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-rose-400 shadow-[0_0_8px_rgba(251,113,133,0.8)]" title="Inactive"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>" class="font-bold text-gray-900 dark:text-white text-base hover:text-violet-300 transition-colors truncate block leading-tight mb-0.5" title="View Profile">
                                    <?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?>
                                </a>
                                <div class="text-xs text-violet-400 font-medium truncate"><?= htmlspecialchars($emp['PositionName'] ?? 'No Position') ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="space-y-2 mb-5 bg-darker/40 rounded-xl p-3 border border-violet-900/20">
                        <div class="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-300">
                            <div class="w-5 flex justify-center text-gray-500"><i class="fa-regular fa-envelope"></i></div>
                            <span class="truncate" title="<?= htmlspecialchars($emp['Email']) ?>"><?= htmlspecialchars($emp['Email']) ?></span>
                        </div>
                        <?php if(!empty($emp['PhoneNumber'])): ?>
                        <div class="flex items-center gap-2.5 text-xs text-gray-700 dark:text-gray-300">
                            <div class="w-5 flex justify-center text-gray-500"><i class="fa-solid fa-phone"></i></div>
                            <span><?= htmlspecialchars($emp['PhoneNumber']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center gap-2.5 text-[10px] text-gray-600 dark:text-gray-400 mt-2 pt-2 border-t border-violet-900/30">
                            <div class="w-5 flex justify-center text-gray-500"><i class="fa-solid fa-fingerprint text-[9px]"></i></div>
                            <span class="font-mono">EMP-<?= htmlspecialchars(str_pad($emp['EmpID'], 4, '0', STR_PAD_LEFT)) ?></span>
                            <span class="ml-auto flex items-center gap-1" title="Join Date"><i class="fa-regular fa-calendar text-[9px]"></i> <?= date('M Y', strtotime($emp['JoinDate'] ?? 'now')) ?></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-violet-500/20">
                        <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>" class="text-[11px] font-extrabold text-gray-900 dark:text-white bg-violet-600/30 hover:bg-violet-600 border border-violet-500/50 hover:border-violet-400 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-all shadow-sm">
                            <i class="fa-regular fa-user text-[10px]"></i> View Profile
                        </a>
                        <div class="flex items-center gap-1.5">
                            <a href="/payrollsystem/admin/employee/<?= $emp['EmpID'] ?>#edit" class="w-7 h-7 rounded-lg bg-surface border border-gray-700 hover:bg-amber-600/20 text-gray-600 dark:text-gray-400 hover:text-amber-300 hover:border-amber-500/30 flex items-center justify-center transition-all" title="Edit Profile">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            </a>
                            <form action="/payrollsystem/admin/employees" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to remove this employee?');">
                                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $emp['EmpID'] ?>">
                                <button type="submit" class="w-7 h-7 rounded-lg bg-surface border border-gray-700 hover:bg-rose-600/20 text-gray-600 dark:text-gray-400 hover:text-rose-300 hover:border-rose-500/30 flex items-center justify-center transition-all" title="Delete">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-950/80 backdrop-blur-md flex items-center justify-center p-4 transition-all">
    <div class="card-glass rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden border border-violet-500/30 transform transition-all">
        <div class="px-6 py-5 border-b border-violet-900/40 flex justify-between items-center bg-gray-50 dark:bg-gray-800/80">
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2.5 font-outfit">
                <i class="fa-solid fa-user-plus text-secondary"></i> Add New Employee
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-surface text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:text-white border border-gray-200 dark:border-violet-900/40 flex items-center justify-center transition-colors">
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
                            <i class="fa-regular fa-user text-secondary"></i> Personal Details
                        </h4>
                        <div class="grid grid-cols-2 gap-3.5 bg-darker/50 p-4 rounded-2xl border border-gray-300 dark:border-violet-700/30 shadow-inner">
                            <div class="col-span-2 hidden">
                                <input type="hidden" name="employee_code" id="employee_code" value="AUTO">
                            </div>
                            <div>
                                <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                                <input type="text" name="first_name" id="first_name" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner">
                            </div>
                            <div>
                                <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner">
                            </div>
                            <div class="col-span-2">
                                <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                <select name="gender" id="gender" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner cursor-pointer">
                                    <option value="Other">Other</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-secondary"></i> Contact Information
                        </h4>
                        <div class="space-y-3.5 bg-darker/50 p-4 rounded-2xl border border-gray-300 dark:border-violet-700/30 shadow-inner">
                            <div>
                                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner">
                                <span id="phone-error" class="text-xs text-rose-400 hidden mt-1">Invalid phone number format.</span>
                            </div>
                            <div>
                                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Residential Address</label>
                                <textarea name="address" id="address" rows="2" class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5">
                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-regular fa-id-card text-secondary"></i> Account Credentials
                        </h4>
                        <div class="space-y-3.5 bg-darker/50 p-4 rounded-2xl border border-gray-300 dark:border-violet-700/30 shadow-inner">
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Email Address (Login ID)</label>
                                <input type="email" name="email" id="email" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner">
                            </div>
                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Account Password (Default)</label>
                                <input type="password" name="password" id="password" required value="password" class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner">
                                <span id="password-error" class="text-xs text-rose-400 hidden mt-1">Password must be at least 6 characters.</span>
                                <p class="text-[10px] text-gray-600 dark:text-gray-400 mt-1"><i class="fa-solid fa-circle-info text-violet-400"></i> New employees are forced to change this upon first login.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-violet-300 mb-3 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-secondary"></i> Job & Payroll Placement
                        </h4>
                        <div class="grid grid-cols-2 gap-3.5 bg-darker/50 p-4 rounded-2xl border border-gray-300 dark:border-violet-700/30 shadow-inner">
                            <div>
                                <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Department</label>
                                <select name="department_id" id="department_id" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner cursor-pointer">
                                    <option value="">Select Department</option>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="position_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Position</label>
                                <select name="position_id" id="position_id" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner cursor-pointer">
                                    <option value="">Select Position</option>
                                    <?php foreach($data['positions'] as $pos): ?>
                                        <option value="<?= $pos['PositionID'] ?>" data-department-id="<?= $pos['DeptID'] ?>" data-basic-salary="<?= $pos['BasicSalary'] ?? 0 ?>"><?= htmlspecialchars($pos['PositionName']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="join_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Join Date</label>
                                <input type="date" name="join_date" id="join_date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner">
                            </div>
                            <div>
                                <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Basic Salary (MMK)</label>
                                <input type="number" name="basic_salary" id="basic_salary" required class="w-full px-3.5 py-2 bg-darker/80 border border-gray-300 dark:border-violet-700/30 rounded-xl focus:ring-2 focus:ring-violet-500 text-gray-900 dark:text-white text-xs shadow-inner font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-violet-900/40">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2 text-xs font-bold text-gray-600 dark:text-gray-400 bg-surface border border-gray-200 dark:border-violet-900/40 rounded-xl hover:text-gray-900 dark:text-white transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2 text-xs font-extrabold text-gray-900 dark:text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl shadow-lg shadow-violet-600/30 hover:scale-105 transition-all">Create Employee Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
function filterEmployees() {
    const searchVal = (document.getElementById('employeeSearchInput').value || '').toLowerCase();
    const deptVal = (document.getElementById('departmentFilterSelect').value || '').toLowerCase();
    const deptGroups = document.querySelectorAll('.department-group');

    deptGroups.forEach(group => {
        const groupDept = (group.getAttribute('data-department') || '').toLowerCase();
        const cards = group.querySelectorAll('.emp-card');
        let visibleCardsInGroup = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const email = card.getAttribute('data-email');
            
            const matchSearch = !searchVal || name.includes(searchVal) || email.includes(searchVal);
            const matchDept = !deptVal || groupDept === deptVal;

            if (matchSearch && matchDept) {
                card.style.display = '';
                visibleCardsInGroup++;
            } else {
                card.style.display = 'none';
            }
        });

        // Hide the whole department group if no cards are visible or if it's filtered out by department dropdown
        if (visibleCardsInGroup === 0) {
            group.style.display = 'none';
        } else {
            group.style.display = '';
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
