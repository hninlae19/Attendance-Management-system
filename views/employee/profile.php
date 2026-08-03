<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden text-center p-6 flex flex-col items-center">
            <div class="w-32 h-32 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-500 mb-4 text-4xl shadow-inner border-4 border-white dark:border-gray-800">
                <i class="fa-solid fa-user"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['first_name'] . ' ' . $data['employee']['last_name']) ?></h2>
            <p class="text-gray-500 dark:text-gray-400 mb-4"><?= htmlspecialchars($data['employee']['position_name']) ?></p>
            
            <div class="w-full flex justify-center gap-3">
                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold dark:bg-green-900/30 dark:text-green-400">Active Employee</span>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Personal Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Employee Code</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['employee_code']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Email Address</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Phone Number</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['phone'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Address</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['address'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Employment Details</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Department</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['department_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Join Date</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= date('F j, Y', strtotime($data['employee']['join_date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Basic Salary</p>
                        <p class="font-medium text-gray-900 dark:text-white"><?= number_format($data['employee']['basic_salary']) ?> MMK</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
