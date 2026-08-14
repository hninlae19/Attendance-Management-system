<!-- Employee Dashboard Redesign -->
<div class="mb-6 flex flex-col items-center sm:items-start text-center sm:text-left">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Hello, <?= htmlspecialchars($data['employee']['FirstName']) ?>! 👋</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Today is <?= date('l, F j, Y') ?></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Clock In/Out Widget (Navy/Gold Theme) -->
    <div class="lg:col-span-1">
        <div class="bg-gradient-to-br from-[#0B132B] to-[#1C2541] rounded-3xl shadow-xl overflow-hidden text-center p-8 border border-gray-800 relative">
            <!-- Subtle background decoration -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-5 rounded-full blur-xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#D4AF37] opacity-10 rounded-full blur-2xl"></div>
            
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-2">Time Clock</h2>
            
            <div class="text-5xl font-mono font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] to-[#F3E5AB] mb-8 tracking-wider drop-shadow-md" id="realTimeClock">
                <?= date('H:i:s') ?>
            </div>

            <?php if (isset($_SESSION['att_error'])): ?>
                <div class="mb-6 p-4 bg-red-900/50 border border-red-500/50 text-red-300 rounded-xl text-sm font-medium backdrop-blur-sm animate-pulse">
                    <?= htmlspecialchars($_SESSION['att_error']) ?>
                </div>
                <?php unset($_SESSION['att_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['att_success'])): ?>
                <div class="mb-6 p-4 bg-green-900/50 border border-green-500/50 text-green-300 rounded-xl text-sm font-medium backdrop-blur-sm animate-pulse">
                    <?= htmlspecialchars($_SESSION['att_success']) ?>
                </div>
                <?php unset($_SESSION['att_success']); ?>
            <?php endif; ?>

            <?php if(!$data['todayRecord']): ?>
                <!-- Not Clocked In Yet -->
                <div class="mb-4 p-4 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-start">
                    <i class="fa-solid fa-triangle-exclamation text-orange-400 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-bold text-orange-400">Attendance Required</h4>
                        <p class="text-xs text-orange-300 mt-0.5">You have not checked in for today yet. Please check in to record your attendance.</p>
                    </div>
                </div>
                
                <form action="/payrollsystem/employee/attendance" method="POST" class="relative z-10">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="check_in">
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-[#D4AF37] to-[#C5A017] hover:from-[#C5A017] hover:to-[#B49006] text-gray-900 font-extrabold text-lg py-4 px-6 rounded-2xl shadow-[0_10px_25px_-5px_rgba(212,175,55,0.4)] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center">
                        <i class="fa-solid fa-fingerprint text-2xl mr-3 opacity-90"></i>
                        CHECK-IN
                    </button>
                </form>
            <?php elseif($data['todayRecord'] && !$data['todayRecord']['CheckOutTime']): ?>
                <!-- Clocked In, waiting for Clock Out -->
                <div class="mb-6 p-4 bg-[#1A2A4A] border border-[#2A3A5A] rounded-2xl relative z-10">
                    <div class="text-xs uppercase font-bold tracking-wider text-gray-400 mb-1">Active Shift Started</div>
                    <div class="text-xl font-bold text-[#D4AF37]"><?= date('h:i A', strtotime($data['todayRecord']['CheckInTime'])) ?></div>
                </div>
                
                <form action="/payrollsystem/employee/attendance" method="POST" class="relative z-10">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="check_out">
                    <button type="submit" class="w-full bg-gradient-to-r from-[#FF6B6B] to-[#E63946] hover:from-[#E63946] hover:to-[#D62828] text-white font-extrabold text-lg py-4 px-6 rounded-2xl shadow-[0_10px_25px_-5px_rgba(230,57,70,0.4)] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center">
                        <i class="fa-solid fa-right-from-bracket mr-3 opacity-90"></i>
                        CHECK-OUT
                    </button>
                </form>
            <?php else: ?>
                <!-- Clocked Out -->
                <div class="p-6 bg-[#1A2A4A] border border-[#2A3A5A] rounded-2xl relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500/20 text-green-400 mb-4">
                        <i class="fa-solid fa-check text-3xl"></i>
                    </div>
                    <p class="font-bold text-white text-lg mb-4">Shift Completed</p>
                    <div class="flex justify-between items-center text-sm px-4 py-2 bg-black/20 rounded-lg">
                        <span class="text-gray-400">IN: <strong class="text-white"><?= date('h:i A', strtotime($data['todayRecord']['CheckInTime'])) ?></strong></span>
                        <span class="text-gray-500">|</span>
                        <span class="text-gray-400">OUT: <strong class="text-white"><?= date('h:i A', strtotime($data['todayRecord']['CheckOutTime'])) ?></strong>
                            <?php if(isset($data['todayRecord']['is_auto_checkout']) && $data['todayRecord']['is_auto_checkout'] == 1): ?>
                                <span class="ml-1 text-[10px] bg-red-900/50 text-red-300 px-1.5 py-0.5 rounded-full border border-red-800/50" title="System Auto Check-Out">Auto</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="lg:col-span-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-4">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['DeptName']) ?></p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-4">
                    <i class="fa-solid fa-briefcase text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['PositionName']) ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Payslips -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-money-check-dollar text-emerald-500"></i> My Recent Payslips
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3">Month</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Net Salary</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['recentPayrolls'])): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fa-solid fa-folder-open text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                    <p>No payroll records available yet.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['recentPayrolls'] as $pr): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($pr['PayrollMonth']) ?></td>
                                <td class="px-4 py-3">
                                    <?php if($pr['Status'] === 'Paid'): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Paid</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/30"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                    <?= number_format($pr['NetSalary']) ?> <span class="text-xs">MMK</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="/payrollsystem/employee/payroll_slip/<?= $pr['PayrollID'] ?>" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center justify-center gap-1 font-medium transition-colors">
                                        <i class="fa-solid fa-file-invoice"></i> View Slip
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('realTimeClock').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
