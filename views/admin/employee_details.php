<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <div class="flex items-center gap-4">
        <a href="/payrollsystem/admin/employees" class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Employee Profile</h1>
            <p class="text-gray-500 text-sm">View and manage employee details</p>
        </div>
    </div>
    
    <!-- We can add Edit / Actions here if needed in the future -->
    <button onclick="document.getElementById('editModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors shadow-md shadow-primary/20">
        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Profile
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Profile Card -->
    <div class="lg:col-span-1" data-aos="fade-up" data-aos-delay="0">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden text-center relative pt-12 pb-8 px-6">
            <!-- Background Decorative Header -->
            <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-primary to-indigo-500"></div>
            
            <div class="w-24 h-24 rounded-full bg-white dark:bg-gray-700 mx-auto mb-4 border-4 border-white dark:border-gray-800 shadow-lg relative z-10 flex items-center justify-center overflow-hidden">
                <i class="fa-solid fa-user text-4xl text-gray-400"></i>
            </div>
            
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1"><?= htmlspecialchars($data['employee']['FirstName'] . ' ' . $data['employee']['LastName']) ?></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'No Position') ?> &bull; <?= htmlspecialchars($data['employee']['DeptName'] ?? 'No Department') ?></p>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $data['employee']['Status'] === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' ?>">
                <span class="w-2 h-2 rounded-full mr-2 <?= $data['employee']['Status'] === 'Active' ? 'bg-green-500' : 'bg-red-500' ?>"></span>
                <?= htmlspecialchars($data['employee']['Status']) ?>
            </div>
            
            <hr class="my-6 border-gray-100 dark:border-gray-700">
            
            <div class="flex justify-between text-sm text-left px-2">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1">Employee ID</p>
                    <p class="font-semibold text-gray-900 dark:text-white">EMP-<?= htmlspecialchars(str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT)) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 dark:text-gray-400 mb-1">Join Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?= date('M j, Y', strtotime($data['employee']['JoinDate'])) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Details -->
    <div class="lg:col-span-2 space-y-6" data-aos="fade-up" data-aos-delay="100">
        <!-- Personal Information -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                <i class="fa-regular fa-address-card text-primary mr-2"></i> Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full Name</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($data['employee']['FirstName'] . ' ' . $data['employee']['LastName']) ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email Address (Login)</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($data['employee']['Email']) ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone Number</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= !empty($data['employee']['PhoneNumber']) ? htmlspecialchars($data['employee']['PhoneNumber']) : '<span class="text-gray-400 italic">Not provided</span>' ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Residential Address</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= !empty($data['employee']['Address']) ? htmlspecialchars($data['employee']['Address']) : '<span class="text-gray-400 italic">Not provided</span>' ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Gender</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($data['employee']['Gender'] ?? 'Other') ?></p>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                <i class="fa-solid fa-briefcase text-emerald-500 mr-2"></i> Employment Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Department</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($data['employee']['DeptName'] ?? 'No Department') ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Position / Title</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'No Position') ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date of Joining</p>
                    <p class="text-gray-900 dark:text-white font-medium"><?= date('F j, Y', strtotime($data['employee']['JoinDate'])) ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Basic Salary</p>
                    <p class="text-emerald-600 dark:text-emerald-400 font-bold"><?= number_format($data['employee']['BasicSalary']) ?> MMK</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Employee Profile</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/employee/<?= $data['employee']['EmpID'] ?>" method="POST" class="p-6">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="edit">

            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Personal Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($data['employee']['FirstName']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($data['employee']['LastName']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                    <select name="gender" id="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="Other" <?= ($data['employee']['Gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                        <option value="Male" <?= ($data['employee']['Gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($data['employee']['Gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
            </div>

            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Job Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                    <select name="department_id" id="department_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?= $dept['DeptID'] ?>" <?= $dept['DeptID'] == $data['employee']['DeptID'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['DeptName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="position_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position</label>
                    <select name="position_id" id="position_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <?php foreach($data['positions'] as $pos): ?>
                            <option value="<?= $pos['PositionID'] ?>" data-department-id="<?= $pos['DeptID'] ?>" data-basic-salary="<?= $pos['BasicSalary'] ?? 0 ?>" <?= $pos['PositionID'] == $data['employee']['PositionID'] ? 'selected' : '' ?>><?= htmlspecialchars($pos['PositionName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="join_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Join Date</label>
                    <input type="date" name="join_date" id="join_date" value="<?= !empty($data['employee']['JoinDate']) ? date('Y-m-d', strtotime($data['employee']['JoinDate'])) : '' ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Basic Salary</label>
                    <input type="number" name="basic_salary" id="basic_salary" value="<?= htmlspecialchars($data['employee']['BasicSalary']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Status</label>
                    <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="Active" <?= $data['employee']['Status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $data['employee']['Status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Account Security</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($data['employee']['Email']) ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" placeholder="Enter new password..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Contact Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($data['employee']['PhoneNumber']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($data['employee']['Address']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Save Changes</button>
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
    });
</script>
