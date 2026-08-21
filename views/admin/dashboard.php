<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Executive Dashboard</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Command <span class="gradient-text">Center</span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Real-time overview of workforce operations, attendance logs, and payroll metrics.</p>
        </div>
        <div class="bg-white/15 border border-white/20 px-4 py-2.5 rounded-2xl backdrop-blur-md text-white text-xs font-bold flex items-center gap-2 shadow-inner">
            <i class="fa-solid fa-calendar-day text-cyan-300"></i>
            <span id="current-time"><?= date('l, F j, Y') ?></span>
        </div>
    </div>
</div>

<!-- Primary Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8" data-aos="fade-up" data-aos-delay="100">
    
    <!-- Total Employees -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Total Employees</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white" id="stat-total-emp"><?= $totalEmployees ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
            <i class="fa-solid fa-circle-check mr-1.5 text-xs"></i> Active Staff: <span id="stat-active-emp" class="ml-1 font-mono"><?= $activeEmployees ?? 0 ?></span>
        </div>
    </div>

    <!-- Monthly Payroll Cost -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Monthly Payroll</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white font-mono" id="stat-payroll"><?= number_format($monthlyPayroll ?? 0, 2) ?> <span class="text-xs font-normal text-slate-500">MMK</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-semibold text-slate-600 dark:text-slate-400">
            <span>Bonuses: <span id="stat-bonus" class="text-emerald-600 dark:text-emerald-400 font-mono font-bold"><?= number_format($monthlyBonus ?? 0, 2) ?> MMK</span></span>
        </div>
    </div>

    <!-- Present Today -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Present Today</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white" id="stat-present"><?= $presentToday ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 border border-sky-100 dark:border-sky-800 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-bold text-amber-600 dark:text-amber-400">
            <i class="fa-solid fa-clock mr-1.5 text-xs"></i> Late Arrivals: <span id="stat-late" class="ml-1 font-mono"><?= $lateToday ?? 0 ?></span>
        </div>
    </div>

    <!-- On Leave & Absent -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Absent / On Leave</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">
                    <span id="stat-absent" class="text-rose-600 dark:text-rose-400"><?= $absentToday ?? 0 ?></span> 
                    <span class="text-slate-300 dark:text-slate-600 font-light mx-1">/</span> 
                    <span id="stat-leave" class="text-amber-600 dark:text-amber-400"><?= $employeesOnLeave ?? 0 ?></span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs w-full gap-2">
             <div class="h-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden flex">
                 <div class="bg-rose-500 h-full" style="width: 50%" title="Absent"></div>
                 <div class="bg-amber-500 h-full" style="width: 50%" title="On Leave"></div>
             </div>
        </div>
    </div>
</div>

<!-- Secondary Stats Row (Action Center & Attendance Trend) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="200">
    
    <!-- Action Center (Pending Items) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center font-outfit">
            <i class="fa-solid fa-bell-concierge text-indigo-500 mr-2"></i> Action Center
        </h2>
        
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 hover:border-indigo-500/30 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 border border-amber-200 dark:border-amber-800 flex items-center justify-center shadow-sm"><i class="fa-solid fa-calendar-minus"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Pending Leaves</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Requires supervisor approval</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-lg font-black text-amber-600 dark:text-amber-400 font-mono" id="stat-pend-leave"><?= $pendingLeaves ?? 0 ?></span>
                </div>
            </div>

            <!-- Pending Password Resets Widget -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 hover:border-rose-500/30 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center justify-center shadow-sm"><i class="fa-solid fa-key"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Password Resets</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Requires admin approval</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-lg font-black text-rose-600 dark:text-rose-400 font-mono" id="stat-pend-resets"><?= $pendingResets ?? 0 ?></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <a href="/payrollsystem/admin/leaves" class="block w-full py-2.5 px-3 text-center text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 rounded-xl transition-all shadow-sm">
                    View Leaves <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
                <a href="/payrollsystem/admin/password_resets" class="block w-full py-2.5 px-3 text-center text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 rounded-xl transition-all shadow-sm">
                    View Resets <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics Chart: Attendance Trend -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center font-outfit">
                <i class="fa-solid fa-chart-line text-indigo-500 mr-2"></i> Attendance Overview (This Week)
            </h2>
            <div class="bg-slate-100 dark:bg-slate-700 rounded-xl p-1 flex text-xs font-bold">
                <span class="px-3 py-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg shadow-sm">Week</span>
            </div>
        </div>
        <div class="h-[250px] w-full">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>
</div>

<!-- Bottom Row: Recent Activity & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="300">
    
    <!-- Recent Attendance Table -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h2 class="font-bold text-slate-900 dark:text-white text-sm font-outfit flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i> Live Attendance Feed
            </h2>
            <a href="/payrollsystem/admin/attendance" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">View All Logs</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Employee</th>
                        <th scope="col" class="px-6 py-3.5">Department</th>
                        <th scope="col" class="px-6 py-3.5">Time In</th>
                        <th scope="col" class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody id="recent-att-table" class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <?php if(!empty($recentAttendance)): ?>
                        <?php foreach($recentAttendance as $att): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                            <?= strtoupper(substr($att['FirstName'],0,1) . substr($att['LastName'],0,1)) ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white text-xs"><?= htmlspecialchars($att['FirstName'] . ' ' . $att['LastName']) ?></div>
                                            <div class="text-[11px] text-slate-400 font-mono">EMP-<?= str_pad($att['EmpID'], 4, '0', STR_PAD_LEFT) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                        <?= htmlspecialchars($att['DeptName'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 font-mono text-xs font-semibold text-slate-800 dark:text-slate-200">
                                    <?= date('h:i A', strtotime($att['CheckInTime'])) ?>
                                </td>
                                <td class="px-6 py-3.5">
                                    <?php if($att['Status'] == 'Present'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Present</span>
                                    <?php elseif($att['Status'] == 'Late'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Late</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5"></span> <?= htmlspecialchars($att['Status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="no-att-row">
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-2 text-indigo-500">
                                    <i class="fa-solid fa-folder-open text-xl"></i>
                                </div>
                                <p class="font-semibold text-slate-700 dark:text-slate-300 text-xs">No attendance recorded yet today</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden flex flex-col justify-between">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-black/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-lg font-extrabold mb-1 font-outfit">Quick Operations</h2>
            <p class="text-indigo-100 text-xs mb-5">Shortcut workflows for HR administrators</p>
            
            <div class="space-y-3">
                <a href="/payrollsystem/admin/payroll" class="flex items-center p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-sm transition-all duration-300 group shadow-sm">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-xs">Process Payroll</p>
                        <p class="text-[11px] text-indigo-100">Run monthly salary calculation</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs opacity-60 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </a>
                
                <a href="/payrollsystem/admin/employees" class="flex items-center p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-sm transition-all duration-300 group shadow-sm">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-xs">Onboard Employee</p>
                        <p class="text-[11px] text-indigo-100">Add new staff record</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs opacity-60 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </a>

                <a href="/payrollsystem/admin/overtimeAssign" class="flex items-center p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-sm transition-all duration-300 group shadow-sm">
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-business-time text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-xs">Assign Overtime</p>
                        <p class="text-[11px] text-indigo-100">Schedule employee shifts</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs opacity-60 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Chart.js
    const canvasEl = document.getElementById('attendanceChart');
    if (canvasEl) {
        const ctx = canvasEl.getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Present',
                    data: [45, 48, 46, 49, 47, 20, 5],
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 } }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    }

    // AJAX Polling every 30 seconds
    setInterval(() => {
        fetch('/payrollsystem/admin/dashboardApi')
            .then(res => res.json())
            .then(data => {
                const updateStat = (id, value) => {
                    const el = document.getElementById(id);
                    if (el) el.innerText = value;
                };

                updateStat('stat-total-emp', data.totalEmployees);
                updateStat('stat-active-emp', data.activeEmployees);
                updateStat('stat-present', data.presentToday);
                updateStat('stat-late', data.lateToday);
                updateStat('stat-absent', data.absentToday);
                updateStat('stat-leave', data.employeesOnLeave);
                updateStat('stat-pend-leave', data.pendingLeaves);
                updateStat('stat-pend-resets', data.pendingResets);
                updateStat('stat-payroll', parseFloat(data.monthlyPayroll).toLocaleString('en-US', {minimumFractionDigits: 2}) + ' MMK');
                updateStat('stat-bonus', parseFloat(data.monthlyBonus).toLocaleString('en-US', {minimumFractionDigits: 2}) + ' MMK');

                // Build Table HTML
                const tbody = document.getElementById('recent-att-table');
                if(tbody && data.recentAttendance && data.recentAttendance.length > 0) {
                    let html = '';
                    data.recentAttendance.forEach(att => {
                        let statusBadge = '';
                        if(att.status === 'Present') {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Present</span>`;
                        } else if(att.status === 'Late') {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Late</span>`;
                        } else {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1.5"></span> ${att.status}</span>`;
                        }
                        
                        let initials = (att.first_name.charAt(0) + att.last_name.charAt(0)).toUpperCase();
                        let time = new Date('1970-01-01T' + att.check_in + 'Z').toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
                        let empId = String(att.employee_id).padStart(4, '0');

                        html += `
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">${initials}</div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white text-xs">${att.first_name} ${att.last_name}</div>
                                            <div class="text-[11px] text-slate-400 font-mono">EMP-${empId}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">${att.department_name || 'N/A'}</span>
                                </td>
                                <td class="px-6 py-3.5 font-mono text-xs font-semibold text-slate-800 dark:text-slate-200">${time}</td>
                                <td class="px-6 py-3.5">${statusBadge}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                }
            })
            .catch(err => console.error('Error fetching dashboard data:', err));
    }, 30000);
});
</script>
