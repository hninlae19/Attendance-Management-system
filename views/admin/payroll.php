<?php
$monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
$currentMonthName = $monthNames[(int)$data['selectedMonth']];
?>
<div class="space-y-6" x-data="{ 
    paymentModal: false, 
    selectedPayrollId: null, 
    empName: '',
    netSalary: 0,
    searchQuery: '',
    openPaymentModal(id, name, amount) {
        this.selectedPayrollId = id;
        this.empName = name;
        this.netSalary = amount;
        this.paymentModal = true;
    }
}">
    <!-- Header & Controls -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6" data-aos="fade-down">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Monthly Payroll Summary</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1"><?= $currentMonthName ?> <?= htmlspecialchars($data['selectedYear']) ?></p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <!-- Filter -->
            <form method="GET" action="/payrollsystem/admin/payroll" class="flex gap-2">
                <select name="month" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary shadow-sm text-sm">
                    <?php foreach($monthNames as $m => $name): ?>
                        <option value="<?= $m ?>" <?= $m == $data['selectedMonth'] ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="year" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary shadow-sm text-sm">
                    <?php for($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $data['selectedYear'] ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-xl shadow-sm transition-colors text-sm font-medium">Filter</button>
            </form>
            
            <!-- Generate -->
            <form method="POST" action="/payrollsystem/admin/payroll" class="inline">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                <input type="hidden" name="action" value="generate">
                <input type="hidden" name="month" value="<?= $data['selectedMonth'] ?>">
                <input type="hidden" name="year" value="<?= $data['selectedYear'] ?>">
                <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-xl shadow-md transition-colors text-sm font-medium flex items-center justify-center gap-2">
                    <i class="fa-solid fa-gears"></i> Generate Payroll
                </button>
            </form>
        </div>
    </div>
    
    <!-- Search Bar -->
    <div class="relative w-full max-w-sm mb-4">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-search text-gray-400"></i>
        </div>
        <input type="text" x-model="searchQuery" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary shadow-sm" placeholder="Filter by employee name...">
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold tracking-wider">Employee</th>
                        <th class="px-4 py-3">Department</th>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3 bg-gray-100 dark:bg-gray-800/50">Basic Salary (MMK)</th>
                        <th class="px-4 py-3">Present</th>
                        <th class="px-4 py-3">Leave</th>
                        <th class="px-4 py-3 text-rose-500 font-semibold">FD Absent</th>
                        <th class="px-4 py-3 text-orange-500 font-semibold">HD Absent</th>
                        <th class="px-4 py-3">Late</th>
                        <th class="px-4 py-3">OT Hrs</th>
                        <th class="px-4 py-3 text-orange-600 dark:text-orange-400 font-semibold">OT Pay (MMK)</th>
                        <th class="px-4 py-3 text-teal-600 dark:text-teal-400 font-semibold">Bonus (MMK)</th>
                        <th class="px-4 py-3 text-rose-600 dark:text-rose-400 font-semibold">Leave Ded (MMK)</th>
                        <th class="px-4 py-3 text-rose-600 dark:text-rose-400 font-semibold">Late Ded (MMK)</th>
                        <th class="px-4 py-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 font-bold">Gross (MMK)</th>
                        <th class="px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-bold">Net (MMK)</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['payrolls'])): ?>
                    <tr>
                        <td colspan="18" class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                            <p>No payroll generated for this month yet.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($data['payrolls'] as $p): ?>
                        <tr x-show="searchQuery === '' || '<?= strtolower(addslashes($p['FirstName'] . ' ' . $p['LastName'])) ?>'.includes(searchQuery.toLowerCase())" class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all shadow-sm">
                                        <?= strtoupper(substr($p['FirstName'],0,1) . substr($p['LastName'],0,1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($p['FirstName'] . ' ' . $p['LastName']) ?></div>
                                        <div class="text-xs text-primary font-medium">EMP-<?= htmlspecialchars($p['employee_code']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><?= htmlspecialchars($p['DeptName']) ?></td>
                            <td class="px-4 py-3">N/A</td> <!-- Join pos query later if needed -->
                            <td class="px-4 py-3 bg-gray-50 dark:bg-gray-800/30"><?= number_format($p['BasicSalary']) ?></td>
                            
                            <td class="px-4 py-3"><?= $p['present_days'] ?></td>
                            <td class="px-4 py-3"><?= $p['leave_days'] ?></td>
                            <td class="px-4 py-3 text-rose-600 font-bold"><?= $p['absent_days'] ?></td>
                            <td class="px-4 py-3 text-orange-600 font-bold"><?= $p['half_days'] ?></td>
                            <td class="px-4 py-3"><?= $p['late_days'] ?></td>
                            <td class="px-4 py-3"><?= number_format($p['ot_hours'] ?? 0, 1) ?></td>
                            
                            <td class="px-4 py-3 text-orange-600 dark:text-orange-400"><?= number_format($p['OvertimeAmount']) ?></td>
                            <td class="px-4 py-3 text-teal-600 dark:text-teal-400"><?= number_format($p['BonousAmount']) ?></td>
                            
                            <td class="px-4 py-3 text-rose-600 dark:text-rose-400"><?= number_format($p['LeaveDeductionAmount'] ?? 0) ?></td>
                            <td class="px-4 py-3 text-rose-600 dark:text-rose-400"><?= number_format($p['late_deduction_amount'] ?? 0) ?></td>
                            
                            <?php $grossSalary = $p['BasicSalary'] + $p['OvertimeAmount'] + $p['BonousAmount']; ?>
                            <td class="px-4 py-3 bg-indigo-50 dark:bg-indigo-900/10 font-bold text-indigo-700 dark:text-indigo-400"><?= number_format($grossSalary) ?></td>
                            <td class="px-4 py-3 bg-emerald-50 dark:bg-emerald-900/10 font-bold text-emerald-700 dark:text-emerald-400 text-lg"><?= number_format($p['NetSalary']) ?></td>
                            
                            <td class="px-4 py-3">
                                <?php if($p['Status'] === 'Paid'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Paid</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="/payrollsystem/admin/payroll_slip/<?= $p['PayrollID'] ?>" target="_blank" class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary transition-colors" title="Print Slip">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <?php if($p['Status'] !== 'Paid'): ?>
                                    <button @click="openPaymentModal(<?= $p['PayrollID'] ?>, '<?= addslashes(htmlspecialchars($p['FirstName'] . ' ' . $p['LastName'])) ?>', <?= $p['NetSalary'] ?>)" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium ml-2 transition-colors">
                                        Pay
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="paymentModal" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 dark:bg-gray-900/80 backdrop-blur-sm" x-cloak>
        <div @click.away="paymentModal = false" class="relative w-full max-w-md p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-emerald-500"></i> Make Payment
                </h3>
                <button @click="paymentModal = false" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="/payrollsystem/admin/payroll" class="p-4 md:p-5">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                <input type="hidden" name="action" value="pay">
                <input type="hidden" name="payroll_id" :value="selectedPayrollId">
                <input type="hidden" name="month" value="<?= $data['selectedMonth'] ?>">
                <input type="hidden" name="year" value="<?= $data['selectedYear'] ?>">
                
                <div class="mb-5 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-600">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Paying Salary for:</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="empName"></p>
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Net Amount:</span>
                        <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400"><span x-text="new Intl.NumberFormat().format(netSalary)"></span> MMK</span>
                    </div>
                </div>

                <div class="grid gap-4 mb-5">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment Method</label>
                        <select name="payment_method" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                            <option value="">Select Method</option>
                            <option value="Cash">Cash</option>
                            <option value="KBZ Bank">KBZ Bank</option>
                            <option value="AYA Bank">AYA Bank</option>
                            <option value="CB Bank">CB Bank</option>
                            <option value="UAB Bank">UAB Bank</option>
                            <option value="Wave Pay">Wave Pay</option>
                            <option value="KBZ Pay">KBZ Pay</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="text-white inline-flex w-full justify-center items-center bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-xl text-sm px-5 py-3 text-center dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800 transition-colors">
                    <i class="fa-solid fa-check-circle mr-2"></i> Confirm Payment
                </button>
            </form>
        </div>
    </div>
</div>
