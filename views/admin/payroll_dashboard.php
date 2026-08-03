<?php
$monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
$currentMonthName = $monthNames[(int)$data['selectedMonth']];
?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white mr-3 shadow-lg shadow-indigo-500/30">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                Payroll Dashboard
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Financial overview for <?= $currentMonthName ?> <?= htmlspecialchars($data['selectedYear']) ?></p>
        </div>
        
        <form method="GET" action="/payrollsystem/admin/payroll_dashboard" class="flex gap-2">
            <select name="month" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary shadow-sm">
                <?php foreach($monthNames as $m => $name): ?>
                    <option value="<?= $m ?>" <?= $m == $data['selectedMonth'] ? 'selected' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
            <select name="year" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary shadow-sm">
                <?php for($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                    <option value="<?= $y ?>" <?= $y == $data['selectedYear'] ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-md transition-colors"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">
        <!-- Total Employees -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Employees</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($data['stats']['total_employees'] ?? 0) ?></h3>
        </div>

        <!-- Total Payroll Cost -->
        <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl p-6 shadow-xl shadow-indigo-500/20 text-white hover:-translate-y-1 transition-all relative overflow-hidden group xl:col-span-2">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-xl border border-white/20">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <p class="text-indigo-100 font-medium mb-1 relative z-10">Total Payroll Cost</p>
            <h3 class="text-3xl font-bold relative z-10"><?= number_format($data['stats']['total_payroll'] ?? 0) ?> MMK</h3>
        </div>

        <!-- Total Overtime Cost -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Overtime Cost</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($data['stats']['total_ot'] ?? 0) ?> MMK</h3>
        </div>

        <!-- Total Deductions -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-minus-circle"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Deductions</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($data['stats']['total_deduction'] ?? 0) ?> MMK</h3>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="/payrollsystem/admin/payroll?month=<?= $data['selectedMonth'] ?>&year=<?= $data['selectedYear'] ?>" class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors group">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-list-check text-xl"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">Monthly Summary</span>
                </a>
                <a href="/payrollsystem/admin/payroll_reports" class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors group">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-invoice text-xl"></i>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">Export Reports</span>
                </a>
            </div>
        </div>
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center items-center text-center">
            <div class="w-20 h-20 rounded-full bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center mb-4">
                <i class="fa-solid fa-sack-dollar text-3xl text-cyan-600 dark:text-cyan-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Total Bonus Provided</h3>
            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600"><?= number_format($data['stats']['total_bonus'] ?? 0) ?> MMK</p>
        </div>
    </div>
</div>
