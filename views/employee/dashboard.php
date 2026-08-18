<!-- ============ EMPLOYEE HERO BANNER WITH 3D MASCOT ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-8 mb-8 shadow-2xl" data-aos="fade-down">
    <!-- Ambient Glows -->
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -bottom-20 w-72 h-72 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute left-10 top-0 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
        <!-- Left Greeting & Badges -->
        <div class="max-w-2xl text-center lg:text-left space-y-3">
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Employee Portal</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight font-outfit">
                Hello, <span class="gradient-text"><?= htmlspecialchars($data['employee']['FirstName']) ?> <?= htmlspecialchars($data['employee']['LastName'] ?? '') ?></span>! 👋
            </h1>

            <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                Welcome to your self-service portal. Track your daily attendance, submit leave requests, monitor your overtime schedules, and download your monthly salary slips.
            </p>

            <!-- Department & Position Tags -->
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-1 text-xs">
                <span class="px-3 py-1.5 rounded-xl bg-surface/90 border border-violet-700/30 text-gray-300 flex items-center gap-2">
                    <i class="fa-solid fa-building-user text-primary-light"></i>
                    <span>Dept: <strong class="text-white"><?= htmlspecialchars($data['employee']['DeptName'] ?? 'General') ?></strong></span>
                </span>
                <span class="px-3 py-1.5 rounded-xl bg-surface/90 border border-violet-700/30 text-gray-300 flex items-center gap-2">
                    <i class="fa-solid fa-id-badge text-secondary"></i>
                    <span>Role: <strong class="text-white"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'Staff') ?></strong></span>
                </span>
            </div>
        </div>

        <!-- Right 3D Cartoon Employee Mascot -->
        <div class="flex flex-col sm:flex-row lg:flex-col items-center gap-4 flex-shrink-0">
            <div class="relative group">
                <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-violet-600 via-cyan-500 to-amber-500 opacity-60 blur-lg group-hover:opacity-100 transition-opacity animate-pulse-glow"></div>
                <div class="relative w-40 h-40 sm:w-44 sm:h-44 rounded-3xl overflow-hidden border-2 border-violet-400/40 shadow-2xl bg-surface/80">
                    <img src="/payrollsystem/assets/img/employee_hero_mascot.jpg" 
                         alt="Employee Mascot" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
            </div>

            <!-- Date Status Pill -->
            <div class="px-4 py-2 rounded-2xl bg-surface/90 border border-violet-500/30 text-center shadow-lg backdrop-blur-md">
                <div class="text-[11px] uppercase tracking-widest text-violet-300 font-bold flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-calendar-day text-secondary"></i>
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
        <div class="card-glass rounded-3xl p-6 lg:p-7 relative overflow-hidden text-center flex flex-col justify-between h-full">
            <!-- Background Glow -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-violet-600/10 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <!-- Card Header -->
                <div class="flex items-center justify-between pb-4 border-b border-violet-900/40 mb-6">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-violet-300/80 flex items-center gap-2">
                        <i class="fa-solid fa-fingerprint text-secondary text-sm"></i>
                        Biometric Time Clock
                    </span>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Live Terminal</span>
                </div>

                <!-- Digital Real-Time Clock -->
                <div class="p-5 rounded-2xl bg-surface/90 border border-violet-700/30 mb-6 shadow-inner relative group">
                    <div class="text-xs uppercase font-bold tracking-wider text-gray-400 mb-1">Current Server Time</div>
                    <div class="text-4xl sm:text-5xl font-mono font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-200 tracking-wider drop-shadow-lg" id="realTimeClock">
                        <?= date('H:i:s') ?>
                    </div>
                </div>

                <!-- Session Notifications -->
                <?php if (isset($_SESSION['att_error'])): ?>
                    <div class="mb-5 p-3.5 bg-rose-950/60 border border-rose-500/40 text-rose-300 rounded-xl text-xs font-semibold backdrop-blur-sm animate-pulse flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-base"></i>
                        <span><?= htmlspecialchars($_SESSION['att_error']) ?></span>
                    </div>
                    <?php unset($_SESSION['att_error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['att_success'])): ?>
                    <div class="mb-5 p-3.5 bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 rounded-xl text-xs font-semibold backdrop-blur-sm animate-pulse flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span><?= htmlspecialchars($_SESSION['att_success']) ?></span>
                    </div>
                    <?php unset($_SESSION['att_success']); ?>
                <?php endif; ?>

                <!-- Dynamic Attendance State -->
                <?php if (!$data['is_working_day']): ?>
                    <!-- Non-working day -->
                    <div class="p-6 bg-surface/90 border border-violet-800/40 rounded-2xl text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-900/30 text-gray-400 mb-3 border border-violet-700/30">
                            <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                        </div>
                        <p class="font-bold text-white text-base mb-1">Non-Working Day</p>
                        <p class="text-xs text-gray-400">Attendance recording is currently disabled for weekends / official holidays.</p>
                    </div>
                <?php elseif(!$data['todayRecord']): ?>
                    <!-- Not Clocked In Yet -->
                    <div class="mb-5 p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-start text-left gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-400 mt-0.5 text-base flex-shrink-0"></i>
                        <div>
                            <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wider">Attendance Required</h4>
                            <p class="text-[11px] text-amber-200/80 mt-0.5">You have not clocked in for today yet. Tap the biometric button below to record your start time.</p>
                        </div>
                    </div>
                    
                    <form action="/payrollsystem/employee/attendance" method="POST" class="relative z-10">
                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                        <input type="hidden" name="action" value="check_in">
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-amber-400 via-yellow-500 to-amber-500 hover:from-amber-300 hover:to-yellow-400 text-gray-950 font-extrabold text-base py-4 px-6 rounded-2xl shadow-xl shadow-amber-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="fa-solid fa-fingerprint text-2xl animate-pulse"></i>
                            <span class="tracking-wide font-outfit">CLOCK IN NOW</span>
                        </button>
                    </form>
                <?php elseif($data['todayRecord'] && !$data['todayRecord']['CheckOutTime']): ?>
                    <!-- Clocked In, Shift Active -->
                    <div class="mb-5 p-4 bg-surface/90 border border-cyan-500/40 rounded-2xl relative z-10 text-left flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase font-extrabold tracking-wider text-cyan-400 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                Active Shift Started
                            </div>
                            <div class="text-2xl font-extrabold text-white font-mono mt-1">
                                <?= date('h:i A', strtotime($data['todayRecord']['CheckInTime'])) ?>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-lg border border-cyan-500/30">
                            <i class="fa-solid fa-user-clock"></i>
                        </div>
                    </div>
                    
                    <form action="/payrollsystem/employee/attendance" method="POST" class="relative z-10">
                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                        <input type="hidden" name="action" value="check_out">
                        <button type="submit" class="w-full bg-gradient-to-r from-rose-500 via-red-600 to-rose-600 hover:from-rose-400 hover:to-red-500 text-white font-extrabold text-base py-4 px-6 rounded-2xl shadow-xl shadow-rose-600/30 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="fa-solid fa-right-from-bracket text-xl"></i>
                            <span class="tracking-wide font-outfit">CLOCK OUT SHIFT</span>
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Shift Completed -->
                    <div class="p-6 bg-surface/90 border border-emerald-500/30 rounded-2xl relative z-10 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 mb-3 border border-emerald-500/30">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </div>
                        <p class="font-extrabold text-white text-base mb-1 font-outfit">Shift Completed Today</p>
                        <p class="text-xs text-gray-400 mb-4">Your working hours have been logged successfully.</p>
                        
                        <div class="flex justify-between items-center text-xs px-4 py-2.5 bg-darker/60 rounded-xl border border-violet-900/30">
                            <span class="text-gray-400">IN: <strong class="text-emerald-300"><?= !empty($data['todayRecord']['CheckInTime']) ? date('h:i A', strtotime($data['todayRecord']['CheckInTime'])) : '--:--' ?></strong></span>
                            <span class="text-gray-600">|</span>
                            <span class="text-gray-400">OUT: <strong class="text-cyan-300"><?= !empty($data['todayRecord']['CheckOutTime']) ? date('h:i A', strtotime($data['todayRecord']['CheckOutTime'])) : '--:--' ?></strong>
                                <?php if(isset($data['todayRecord']['is_auto_checkout']) && $data['todayRecord']['is_auto_checkout'] == 1): ?>
                                    <span class="ml-1 text-[9px] bg-rose-900/50 text-rose-300 px-1.5 py-0.5 rounded-full border border-rose-800/50" title="System Auto Check-Out">Auto</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Badge -->
            <div class="mt-6 pt-4 border-t border-violet-900/40 flex items-center justify-center gap-2 text-gray-400 text-xs">
                <i class="fa-solid fa-shield-halved text-primary-light"></i>
                <span>Secure Biometric Timekeeping</span>
            </div>
        </div>
    </div>

    <!-- Quick Navigation & Tables -->
    <div class="lg:col-span-2 space-y-6" data-aos="fade-up" data-aos-delay="200">
        
        <!-- Quick Stat Pills -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card-glass rounded-2xl p-5 flex items-center group hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-violet-600/20 text-violet-300 border border-violet-500/30 flex items-center justify-center text-xl mr-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-violet-300/80">Employee ID</p>
                    <p class="text-lg font-extrabold text-white font-mono">EMP-<?= str_pad($data['employee']['EmpID'], 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>
            
            <div class="card-glass rounded-2xl p-5 flex items-center group hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-cyan-600/20 text-cyan-300 border border-cyan-500/30 flex items-center justify-center text-xl mr-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-cyan-300/80">Department</p>
                    <p class="text-base font-bold text-white truncate max-w-[140px]"><?= htmlspecialchars($data['employee']['DeptName'] ?? 'General') ?></p>
                </div>
            </div>
            
            <div class="card-glass rounded-2xl p-5 flex items-center group hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-amber-600/20 text-amber-300 border border-amber-500/30 flex items-center justify-center text-xl mr-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-300/80">Position</p>
                    <p class="text-base font-bold text-white truncate max-w-[140px]"><?= htmlspecialchars($data['employee']['PositionName'] ?? 'Staff') ?></p>
                </div>
            </div>
        </div>

        <!-- Upcoming Overtime Schedule -->
        <div class="card-glass rounded-2xl overflow-hidden">
            <div class="p-4 px-6 border-b border-violet-900/40 flex justify-between items-center bg-surface/60">
                <h3 class="font-bold text-white text-base flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-clock text-amber-400"></i> My Scheduled Overtime
                </h3>
                <a href="/payrollsystem/employee/overtime" class="text-xs font-bold text-violet-300 hover:text-white transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-surface/80 text-violet-300/80 border-b border-violet-900/40">
                        <tr>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-4 py-3.5">Schedule</th>
                            <th class="px-4 py-3.5">Hours</th>
                            <th class="px-4 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-violet-900/30">
                        <?php if (empty($data['upcomingOvertime'])): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="w-12 h-12 mx-auto bg-surface rounded-xl border border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                        <i class="fa-solid fa-mug-hot text-xl"></i>
                                    </div>
                                    <p class="font-semibold text-gray-300">No overtime scheduled</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Approved overtime assignments will show here.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['upcomingOvertime'] as $ot): ?>
                            <tr class="hover:bg-violet-950/20 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-white"><?= date('D, M j, Y', strtotime($ot['OvertimeDate'])) ?></td>
                                <td class="px-4 py-3.5 text-xs text-gray-300 font-medium">
                                    <?= $ot['StartTime'] ? date('h:i A', strtotime($ot['StartTime'])) : '—' ?> - 
                                    <?= $ot['EndTime'] ? date('h:i A', strtotime($ot['EndTime'])) : '—' ?>
                                </td>
                                <td class="px-4 py-3.5 font-extrabold text-amber-400">
                                    <?= $ot['TotalHours'] ?> <span class="text-xs text-gray-500">hrs</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <?php 
                                        $statusColors = [
                                            'Assigned' => 'bg-blue-500/15 text-blue-300 border border-blue-500/30',
                                            'Accepted' => 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30',
                                            'Rejected' => 'bg-red-500/15 text-red-300 border border-red-500/30',
                                            'In Progress' => 'bg-amber-500/15 text-amber-300 border border-amber-500/30',
                                            'Completed' => 'bg-teal-500/15 text-teal-300 border border-teal-500/30',
                                            'Approved' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30',
                                            'No Show' => 'bg-gray-500/15 text-gray-300 border border-gray-500/30',
                                            'Cancelled' => 'bg-rose-500/15 text-rose-300 border border-rose-500/30'
                                        ];
                                        $color = $statusColors[$ot['Status']] ?? 'bg-blue-500/15 text-blue-300 border border-blue-500/30';
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
        <div class="card-glass rounded-2xl overflow-hidden">
            <div class="p-4 px-6 border-b border-violet-900/40 flex justify-between items-center bg-surface/60">
                <h3 class="font-bold text-white text-base flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-money-check-dollar text-emerald-400"></i> My Recent Payslips
                </h3>
                <a href="/payrollsystem/employee/salary_history" class="text-xs font-bold text-violet-300 hover:text-white transition-colors">Salary History</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-surface/80 text-violet-300/80 border-b border-violet-900/40">
                        <tr>
                            <th class="px-5 py-3.5">Payroll Month</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Net Salary</th>
                            <th class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-violet-900/30">
                        <?php if (empty($data['recentPayrolls'])): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <div class="w-12 h-12 mx-auto bg-surface rounded-xl border border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                        <i class="fa-solid fa-folder-open text-xl"></i>
                                    </div>
                                    <p class="font-semibold text-gray-300">No payroll records yet</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Your monthly salary slips will appear here once processed.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['recentPayrolls'] as $pr): ?>
                            <tr class="hover:bg-violet-950/20 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-white"><?= htmlspecialchars($pr['PayrollMonth']) ?></td>
                                <td class="px-4 py-3.5">
                                    <?php if($pr['Status'] === 'Paid'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Paid</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-right font-extrabold text-emerald-400">
                                    <?= number_format($pr['NetSalary']) ?> <span class="text-xs font-normal text-gray-400">MMK</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="/payrollsystem/employee/payroll_slip/<?= $pr['PayrollID'] ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-violet-600/20 hover:bg-violet-600/40 text-violet-300 border border-violet-500/30 text-xs font-bold transition-all hover:scale-105">
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
