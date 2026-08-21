<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4" data-aos="fade-down">
    <div class="flex items-center gap-4">
        <a href="/payrollsystem/admin/employees" class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight font-outfit">Employee Profile</h1>
            <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Comprehensive employee records, employment status, and administration tools</p>
        </div>
    </div>
    
    <button onclick="document.getElementById('editModal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-extrabold shadow-lg shadow-indigo-500/25 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
        <i class="fa-solid fa-pen-to-square"></i>
        <span>Edit Profile</span>
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Profile Card -->
    <div class="lg:col-span-1" data-aos="fade-up" data-aos-delay="0">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden text-center relative pt-12 pb-8 px-6">
            <!-- Background Decorative Header -->
            <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600"></div>
            
            <div class="w-24 h-24 rounded-3xl bg-white dark:bg-slate-800 mx-auto mb-4 border-4 border-white dark:border-slate-800 shadow-xl relative z-10 flex items-center justify-center overflow-hidden">
                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white text-2xl font-extrabold flex items-center justify-center font-outfit">
                    <?= strtoupper(substr($data['employee']['FirstName'] ?? 'E', 0, 1) . substr($data['employee']['LastName'] ?? 'M', 0, 1)) ?>
                </div>
            </div>
            
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1 font-outfit"><?= htmlspecialchars($data['employee']['FirstName'] . ' ' . $data['employee']['LastName']) ?></h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-4"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'No Position') ?> &bull; <?= htmlspecialchars($data['employee']['DeptName'] ?? 'No Department') ?></p>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm <?= $data['employee']['Status'] === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800' : 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800' ?>">
                <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?= $data['employee']['Status'] === 'Active' ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                <?= htmlspecialchars($data['employee']['Status']) ?>
            </div>
            
            <hr class="my-6 border-slate-100 dark:border-slate-700">
            
            <div class="flex justify-between text-xs text-left px-2">
                <div>
                    <p class="text-slate-400 uppercase font-bold text-[10px] tracking-wider mb-1">Employee ID</p>
                    <p class="font-bold text-slate-900 dark:text-white font-mono">EMP-<?= htmlspecialchars(str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT)) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-slate-400 uppercase font-bold text-[10px] tracking-wider mb-1">Join Date</p>
                    <p class="font-bold text-slate-900 dark:text-white"><?= date('M j, Y', strtotime($data['employee']['JoinDate'])) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Details -->
    <div class="lg:col-span-2 space-y-6" data-aos="fade-up" data-aos-delay="100">
        <!-- Personal Information -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center border-b border-slate-100 dark:border-slate-700 pb-3 gap-2">
                <i class="fa-regular fa-address-card text-indigo-500"></i> Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Full Name</p>
                    <p class="text-slate-900 dark:text-white font-bold text-sm"><?= htmlspecialchars($data['employee']['FirstName'] . ' ' . $data['employee']['LastName']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</p>
                    <p class="text-slate-900 dark:text-white font-semibold text-sm"><?= htmlspecialchars($data['employee']['Email']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Phone Number</p>
                    <p class="text-slate-900 dark:text-white font-semibold text-sm"><?= !empty($data['employee']['PhoneNumber']) ? htmlspecialchars($data['employee']['PhoneNumber']) : '<span class="text-slate-400 italic">Not provided</span>' ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Residential Address</p>
                    <p class="text-slate-900 dark:text-white font-medium text-sm"><?= !empty($data['employee']['Address']) ? htmlspecialchars($data['employee']['Address']) : '<span class="text-slate-400 italic">Not provided</span>' ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Gender</p>
                    <p class="text-slate-900 dark:text-white font-semibold text-sm"><?= htmlspecialchars($data['employee']['Gender'] ?? 'Other') ?></p>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center border-b border-slate-100 dark:border-slate-700 pb-3 gap-2">
                <i class="fa-solid fa-briefcase text-emerald-500"></i> Employment Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Department</p>
                    <p class="text-slate-900 dark:text-white font-bold text-sm"><?= htmlspecialchars($data['employee']['DeptName'] ?? 'No Department') ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Position / Title</p>
                    <p class="text-slate-900 dark:text-white font-bold text-sm"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'No Position') ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Date of Joining</p>
                    <p class="text-slate-900 dark:text-white font-semibold text-sm"><?= date('F j, Y', strtotime($data['employee']['JoinDate'])) ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Baseline Salary</p>
                    <p class="text-emerald-600 dark:text-emerald-400 font-extrabold font-mono text-sm"><?= number_format($data['employee']['BasicSalary']) ?> MMK</p>
                </div>
            </div>
        </div>

        <!-- Employee Data Links -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center border-b border-slate-100 dark:border-slate-700 pb-3 gap-2">
                <i class="fa-solid fa-link text-indigo-500"></i> Employee Quick Actions
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                <a href="/payrollsystem/admin/attendance?search=EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?>" class="flex items-center p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-200 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-indigo-950/40 transition-all group shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center mr-3 dark:bg-sky-950/60 dark:text-sky-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-sky-400 transition-colors">Attendance Log</p>
                    </div>
                </a>

                <a href="/payrollsystem/admin/leaves?search=EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?>" class="flex items-center p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-200 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-indigo-950/40 transition-all group shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mr-3 dark:bg-amber-950/60 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-calendar-minus"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-sky-400 transition-colors">Leave Records</p>
                    </div>
                </a>

                <a href="/payrollsystem/admin/overtime_assignments?search=EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?>" class="flex items-center p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-200 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-indigo-950/40 transition-all group shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3 dark:bg-indigo-950/60 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-sky-400 transition-colors">Overtime Shifts</p>
                    </div>
                </a>

                <button type="button" onclick="document.getElementById('editModal').classList.remove('hidden'); setTimeout(() => document.getElementById('password').focus(), 100);" class="flex items-center p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-200 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-indigo-950/40 transition-all group shadow-sm text-left w-full">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mr-3 dark:bg-rose-950/60 dark:text-rose-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-sky-400 transition-colors">Password Reset</p>
                    </div>
                </button>

                <a href="/payrollsystem/admin/employee_salary_history/<?= $data['employee']['EmpID'] ?>" class="flex items-center p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200 hover:border-indigo-200 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-indigo-950/40 transition-all group shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 dark:bg-emerald-950/60 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-sky-400 transition-colors">Salary History</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-user-pen text-indigo-500"></i> Edit Employee Profile
            </h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/employee/<?= $data['employee']['EmpID'] ?>" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">

            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-2 flex items-center gap-2">
                <i class="fa-regular fa-user text-indigo-500"></i> Personal Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($data['employee']['FirstName']) ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($data['employee']['LastName']) ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Gender</label>
                    <select name="gender" id="gender" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                        <option value="Other" <?= ($data['employee']['Gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        <option value="Male" <?= ($data['employee']['Gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($data['employee']['Gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
            </div>

            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-briefcase text-emerald-500"></i> Job & Payroll Placement
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div>
                    <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
                    <select name="department_id" id="department_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?= $dept['DeptID'] ?>" <?= $dept['DeptID'] == $data['employee']['DeptID'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['DeptName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="position_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Position</label>
                    <select name="position_id" id="position_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                        <?php foreach($data['positions'] as $pos): ?>
                            <option value="<?= $pos['PositionID'] ?>" data-department-id="<?= $pos['DeptID'] ?>" data-basic-salary="<?= $pos['BasicSalary'] ?? 0 ?>" <?= $pos['PositionID'] == $data['employee']['PositionID'] ? 'selected' : '' ?>><?= htmlspecialchars($pos['PositionName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="join_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Join Date</label>
                    <input type="date" name="join_date" id="join_date" value="<?= !empty($data['employee']['JoinDate']) ? date('Y-m-d', strtotime($data['employee']['JoinDate'])) : '' ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div>
                    <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Basic Salary</label>
                    <input type="number" name="basic_salary" id="basic_salary" value="<?= htmlspecialchars($data['employee']['BasicSalary']) ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm font-mono font-bold">
                </div>
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Account Status</label>
                    <select name="status" id="status" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                        <option value="Active" <?= $data['employee']['Status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $data['employee']['Status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-lock text-indigo-500"></i> Account Security
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($data['employee']['Email']) ?>" readonly class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 cursor-not-allowed rounded-xl text-xs shadow-inner">
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" placeholder="Enter new password..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                    <span id="password-error" class="text-xs text-rose-500 hidden mt-1 font-bold">Password must be at least 6 characters.</span>
                </div>
            </div>

            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-phone text-indigo-500"></i> Contact Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Phone</label>
                    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($data['employee']['PhoneNumber']) ?>" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                    <span id="phone-error" class="text-xs text-rose-500 hidden mt-1 font-bold">Invalid phone number format.</span>
                </div>
                <div>
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Address</label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($data['employee']['Address']) ?>" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-open edit modal if hash is #edit
    if (window.location.hash === '#edit') {
        document.getElementById('editModal').classList.remove('hidden');
        // Clean up URL without triggering a reload
        history.replaceState(null, null, ' ');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('department_id');
        const positionSelect = document.getElementById('position_id');
        const positionOptions = Array.from(positionSelect.options);
        
        function filterPositions(resetSelection) {
            const selectedDeptId = departmentSelect.value;
            
            if (resetSelection) {
                positionSelect.value = "";
                document.getElementById('basic_salary').value = "";
            }
            
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
        }

        departmentSelect.addEventListener('change', function() {
            filterPositions(true);
        });

        // Initial filter on page load without resetting current selection
        filterPositions(false);

        positionSelect.addEventListener('change', function() {
            if (this.selectedIndex === -1) return;
            const selectedOption = this.options[this.selectedIndex];
            const basicSalary = selectedOption.getAttribute('data-basic-salary');
            const basicSalaryInput = document.getElementById('basic_salary');
            if (basicSalary && basicSalary > 0) {
                basicSalaryInput.value = basicSalary;
            } else if (this.value !== "") {
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
                    this.classList.add('border-red-500', 'focus:border-red-500');
                } else {
                    phoneError.classList.add('hidden');
                    this.classList.remove('border-red-500', 'focus:border-red-500');
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
                    this.classList.add('border-red-500', 'focus:border-red-500');
                } else {
                    passwordError.classList.add('hidden');
                    this.classList.remove('border-red-500', 'focus:border-red-500');
                }
            });
        }
    });
</script>
