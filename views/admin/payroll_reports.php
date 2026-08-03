<?php
$monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
$currentMonth = date('n');
$currentYear = date('Y');
?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white mr-3 shadow-lg shadow-emerald-500/30">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                Payroll Reports
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Generate and export payroll reports in PDF and Excel formats.</p>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Monthly Payroll Report -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Monthly Payroll Report</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Comprehensive summary of all employee payrolls, earnings, and deductions for a specific month.</p>
                </div>
            </div>
            <form method="GET" action="/payrollsystem/admin/export_report" class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl flex flex-wrap gap-3 items-end">
                <input type="hidden" name="type" value="monthly">
                <div class="flex-1 min-w-[120px]">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                    <select name="month" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary focus:border-primary">
                        <?php foreach($monthNames as $m => $name): ?>
                            <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[100px]">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                    <select name="year" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary focus:border-primary">
                        <?php for($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <button type="submit" name="format" value="excel" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-file-excel mr-1"></i> Excel
                    </button>
                    <button type="button" onclick="window.print()" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Overtime Cost Report -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 flex-shrink-0">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Overtime Cost Report</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detailed breakdown of overtime hours and costs across all employees.</p>
                </div>
            </div>
            <form method="GET" action="/payrollsystem/admin/export_report" class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl flex flex-wrap gap-3 items-end">
                <input type="hidden" name="type" value="overtime">
                <div class="flex-1 min-w-[120px]">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                    <select name="month" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary focus:border-primary">
                        <?php foreach($monthNames as $m => $name): ?>
                            <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[100px]">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                    <select name="year" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary focus:border-primary">
                        <?php for($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <button type="submit" name="format" value="excel" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-file-excel mr-1"></i> Excel
                    </button>
                    <button type="button" onclick="window.print()" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Deduction Report -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-900/50 flex items-center justify-center text-rose-600 dark:text-rose-400 flex-shrink-0">
                    <i class="fa-solid fa-scissors text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Deduction Report</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Summary of all deductions including leaves, late penalties, and custom deductions.</p>
                </div>
            </div>
            <form method="GET" action="/payrollsystem/admin/export_report" class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl flex flex-wrap gap-3 items-end">
                <input type="hidden" name="type" value="deduction">
                <div class="flex-1 min-w-[120px]">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                    <select name="month" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary focus:border-primary">
                        <?php foreach($monthNames as $m => $name): ?>
                            <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[100px]">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                    <select name="year" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-primary focus:border-primary">
                        <?php for($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <button type="submit" name="format" value="excel" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-file-excel mr-1"></i> Excel
                    </button>
                    <button type="button" onclick="window.print()" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
