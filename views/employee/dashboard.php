<!-- ============ EMPLOYEE HERO BANNER WITH 3D MASCOT ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-8 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
        <!-- Left Greeting & Badges -->
        <div class="max-w-2xl text-center lg:text-left space-y-3">
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Employee Portal</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight font-outfit">
                Hello, <span class="gradient-text"><?= htmlspecialchars($data['employee']['FirstName']) ?> <?= htmlspecialchars($data['employee']['LastName'] ?? '') ?></span>! 👋
            </h1>

            <p class="text-indigo-100 text-xs sm:text-sm md:text-base leading-relaxed">
                Welcome to your self-service portal. Track your daily attendance, submit leave requests, monitor your overtime schedules, and download your monthly salary slips.
            </p>

            <!-- Department & Position Tags -->
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-1 text-xs">
                <span class="px-3 py-1.5 rounded-xl bg-white/10 border border-white/20 text-white flex items-center gap-2 backdrop-blur-md font-medium">
                    <i class="fa-solid fa-building-user text-cyan-300"></i>
                    <span>Dept: <strong class="text-white"><?= htmlspecialchars($data['employee']['DeptName'] ?? 'General') ?></strong></span>
                </span>
                <span class="px-3 py-1.5 rounded-xl bg-white/10 border border-white/20 text-white flex items-center gap-2 backdrop-blur-md font-medium">
                    <i class="fa-solid fa-id-badge text-amber-300"></i>
                    <span>Role: <strong class="text-white"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'Staff') ?></strong></span>
                </span>
            </div>
        </div>

        <!-- Right 3D Cartoon Employee Mascot -->
        <div class="flex flex-col sm:flex-row lg:flex-col items-center gap-4 flex-shrink-0">
            <div class="relative group">
                <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-violet-600 via-cyan-500 to-amber-500 opacity-60 blur-lg group-hover:opacity-100 transition-opacity"></div>
                <div class="relative w-36 h-36 sm:w-40 sm:h-40 rounded-3xl overflow-hidden border-2 border-white/40 shadow-2xl bg-white/10">
                    <img src="/payrollsystem/assets/img/employee_hero_mascot.jpg" 
                         alt="Employee Mascot" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
            </div>

            <!-- Date Status Pill -->
            <div class="px-4 py-2 rounded-2xl bg-white/15 border border-white/30 text-center shadow-lg backdrop-blur-md">
                <div class="text-[11px] uppercase tracking-widest text-white font-bold flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-calendar-day text-cyan-300"></i>
                    <span><?= date('l, F j, Y') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MAIN CONTENT GRID ============ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Time Clock Biometric Widget -->
    <div class="lg:col-span-1" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-7 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden text-center flex flex-col justify-between h-full">
            <div>
                <!-- Card Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700 mb-6">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-fingerprint text-indigo-500 text-sm"></i>
                        Time Clock Terminal
                    </span>
                    <span class="text-[10px] font-bold uppercase px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800">Live Active</span>
                </div>

                <!-- Digital Real-Time Clock -->
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 mb-6 shadow-inner relative group">
                    <div class="text-[11px] uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400 mb-1">Current Server Time</div>
                    <div class="text-4xl sm:text-5xl font-mono font-black text-indigo-600 dark:text-sky-400 tracking-wider" id="realTimeClock">
                        <?= date('H:i:s') ?>
                    </div>
                </div>

                <!-- Session Notifications -->
                <?php if (isset($_SESSION['att_error'])): ?>
                    <div class="mb-5 p-3.5 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 rounded-2xl text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-base"></i>
                        <span><?= htmlspecialchars($_SESSION['att_error']) ?></span>
                    </div>
                    <?php unset($_SESSION['att_error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['att_success'])): ?>
                    <div class="mb-5 p-3.5 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-2xl text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span><?= htmlspecialchars($_SESSION['att_success']) ?></span>
                    </div>
                    <?php unset($_SESSION['att_success']); ?>
                <?php endif; ?>

                <!-- Dynamic Attendance State -->
                <?php if (!$data['is_working_day']): ?>
                    <!-- Non-working day -->
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 mb-3 border border-slate-200 dark:border-slate-700">
                            <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                        </div>
                        <p class="font-bold text-slate-900 dark:text-white text-base mb-1">Non-Working Day</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Attendance recording is currently disabled for weekends and public holidays.</p>
                    </div>
                <?php elseif(!$data['todayRecord']): ?>
                    <!-- Not Clocked In Yet -->
                    <div class="mb-5 p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-start text-left gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 text-base flex-shrink-0"></i>
                        <div>
                            <h4 class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Attendance Required</h4>
                            <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5">You have not clocked in for today yet. Tap the button below to record your start time.</p>
                        </div>
                    </div>
                    
                    <form action="/payrollsystem/employee/attendance" method="POST" class="relative z-10">
                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                        <input type="hidden" name="action" value="check_in">
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-extrabold text-sm py-4 px-6 rounded-2xl shadow-xl shadow-amber-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="fa-solid fa-fingerprint text-2xl animate-pulse"></i>
                            <span class="tracking-wide font-outfit">CLOCK IN NOW</span>
                        </button>
                    </form>
                <?php elseif($data['todayRecord'] && !$data['todayRecord']['CheckOutTime']): ?>
                    <!-- Clocked In, Shift Active -->
                    <div class="mb-5 p-4 bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 rounded-2xl relative z-10 text-left flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase font-extrabold tracking-wider text-sky-700 dark:text-sky-400 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                Active Shift Started
                            </div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white font-mono mt-1">
                                <?= date('h:i A', strtotime($data['todayRecord']['CheckInTime'])) ?>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-sky-100 dark:bg-sky-900/60 text-sky-600 dark:text-sky-300 flex items-center justify-center text-lg border border-sky-200 dark:border-sky-800">
                            <i class="fa-solid fa-user-clock"></i>
                        </div>
                    </div>
                    
                    <form action="/payrollsystem/employee/attendance" method="POST" class="relative z-10">
                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                        <input type="hidden" name="action" value="check_out">
                        <button type="submit" class="w-full bg-gradient-to-r from-rose-600 via-red-600 to-rose-600 hover:from-rose-500 hover:to-red-500 text-white font-extrabold text-sm py-4 px-6 rounded-2xl shadow-xl shadow-rose-600/30 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                            <span class="tracking-wide font-outfit">CLOCK OUT SHIFT</span>
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Shift Completed -->
                    <div class="p-6 bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl relative z-10 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 mb-3 border border-emerald-200 dark:border-emerald-800">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </div>
                        <p class="font-extrabold text-slate-900 dark:text-white text-base mb-1 font-outfit">Shift Completed Today</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Your working hours have been logged successfully.</p>
                        
                        <div class="flex justify-between items-center text-xs px-4 py-2.5 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                            <span class="text-slate-500 dark:text-slate-400">IN: <strong class="text-emerald-600 dark:text-emerald-400 font-mono font-bold"><?= !empty($data['todayRecord']['CheckInTime']) ? date('h:i A', strtotime($data['todayRecord']['CheckInTime'])) : '--:--' ?></strong></span>
                            <span class="text-slate-300 dark:text-slate-700">|</span>
                            <span class="text-slate-500 dark:text-slate-400">OUT: <strong class="text-sky-600 dark:text-sky-400 font-mono font-bold"><?= !empty($data['todayRecord']['CheckOutTime']) ? date('h:i A', strtotime($data['todayRecord']['CheckOutTime'])) : '--:--' ?></strong>
                                <?php if(isset($data['todayRecord']['is_auto_checkout']) && $data['todayRecord']['is_auto_checkout'] == 1): ?>
                                    <span class="ml-1 text-[9px] bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 px-1.5 py-0.5 rounded-full border border-rose-200 dark:border-rose-800" title="System Auto Check-Out">Auto</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Badge -->
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-center gap-2 text-slate-500 dark:text-slate-400 text-xs">
                <i class="fa-solid fa-shield-halved text-indigo-500"></i>
                <span>Enterprise Timekeeping Terminal</span>
            </div>
        </div>
    </div>

    <!-- Quick Navigation & Tables -->
    <div class="lg:col-span-2 space-y-6" data-aos="fade-up" data-aos-delay="200">
        
        <!-- Quick Stat Pills -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center group hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center text-xl mr-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Employee ID</p>
                    <p class="text-base font-extrabold text-slate-900 dark:text-white font-mono">EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center group hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800 flex items-center justify-center text-xl mr-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Department</p>
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[140px]"><?= htmlspecialchars($data['employee']['DeptName'] ?? 'General') ?></p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center group hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 flex items-center justify-center text-xl mr-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Position</p>
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[140px]"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'Staff') ?></p>
                </div>
            </div>
        </div>

        <!-- Upcoming Overtime Schedule -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="p-4 px-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-sm flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-clock text-amber-500"></i> My Scheduled Overtime
                </h3>
                <a href="/payrollsystem/employee/overtime" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 dark:text-sky-400 transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-4 py-3.5">Schedule</th>
                            <th class="px-4 py-3.5">Hours</th>
                            <th class="px-4 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        <?php if (empty($data['upcomingOvertime'])): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                    <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-2 text-indigo-500">
                                        <i class="fa-solid fa-mug-hot text-xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">No overtime scheduled</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Approved overtime assignments will show here.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['upcomingOvertime'] as $ot): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white text-xs"><?= date('D, M j, Y', strtotime($ot['OvertimeDate'])) ?></td>
                                <td class="px-4 py-3.5 text-xs text-slate-700 dark:text-slate-300 font-medium">
                                    <?= $ot['StartTime'] ? date('h:i A', strtotime($ot['StartTime'])) : '—' ?> - 
                                    <?= $ot['EndTime'] ? date('h:i A', strtotime($ot['EndTime'])) : '—' ?>
                                </td>
                                <td class="px-4 py-3.5 font-mono font-bold text-amber-600 dark:text-amber-400 text-xs">
                                    <?= $ot['TotalHours'] ?> <span class="text-xs text-slate-400">hrs</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <?php 
                                        $statusColors = [
                                            'Assigned' => 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800',
                                            'Accepted' => 'bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800',
                                            'Rejected' => 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800',
                                            'In Progress' => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
                                            'Completed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
                                            'Approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
                                            'No Show' => 'bg-slate-100 text-slate-700 border border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
                                            'Cancelled' => 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800'
                                        ];
                                        $color = $statusColors[$ot['Status']] ?? 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= $color ?>"><?= $ot['Status'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payslips Table -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="p-4 px-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-sm flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-money-check-dollar text-emerald-500"></i> My Recent Payslips
                </h3>
                <a href="/payrollsystem/employee/salary_history" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 dark:text-sky-400 transition-colors">Salary History</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Payroll Month</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Net Salary</th>
                            <th class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        <?php if (empty($data['recentPayrolls'])): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                    <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-2 text-indigo-500">
                                        <i class="fa-solid fa-folder-open text-xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">No payroll records yet</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Your monthly salary slips will appear here once processed.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['recentPayrolls'] as $pr): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white text-xs font-mono"><?= htmlspecialchars($pr['PayrollMonth']) ?></td>
                                <td class="px-4 py-3.5">
                                    <?php if($pr['Status'] === 'Paid'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Paid</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400 text-xs">
                                    <?= number_format($pr['NetSalary']) ?> <span class="text-xs font-normal text-slate-400">MMK</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="/payrollsystem/employee/payroll_slip/<?= $pr['PayrollID'] ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 text-xs font-bold transition-all shadow-sm">
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
        const clockEl = document.getElementById('realTimeClock');
        if (clockEl) {
            clockEl.innerText = timeString;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
