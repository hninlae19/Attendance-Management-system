<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="mb-8 flex justify-between items-end" data-aos="fade-down">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Dashboard Overview</h1>
        <p class="text-gray-500 text-sm mt-1">Welcome back! Here's what's happening today.</p>
    </div>
    <div class="text-right">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <i class="fa-solid fa-calendar-day text-primary mr-2"></i> <span id="current-time"><?= date('l, F j, Y') ?></span>
        </p>
    </div>
</div>

<!-- Primary Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8" data-aos="fade-up" data-aos-delay="100">
    
    <!-- Total Employees -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Employees</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white" id="stat-total-emp"><?= $totalEmployees ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-primary dark:bg-indigo-900/30 dark:text-indigo-400 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-emerald-500 font-medium flex items-center"><i class="fa-solid fa-arrow-trend-up mr-1 text-xs"></i> Active: <span id="stat-active-emp" class="ml-1"><?= $activeEmployees ?? 0 ?></span></span>
        </div>
    </div>

    <!-- Monthly Payroll Cost -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Monthly Payroll</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white" id="stat-payroll">MMK <?= number_format($monthlyPayroll ?? 0, 2) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-gray-500 dark:text-gray-400 font-medium">Bonuses: <span id="stat-bonus" class="text-emerald-500">MMK <?= number_format($monthlyBonus ?? 0, 2) ?></span></span>
        </div>
    </div>

    <!-- Present Today -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Present Today</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white" id="stat-present"><?= $presentToday ?? 0 ?></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-orange-500 font-medium flex items-center"><i class="fa-solid fa-person-running mr-1 text-xs"></i> Late: <span id="stat-late" class="ml-1"><?= $lateToday ?? 0 ?></span></span>
        </div>
    </div>

    <!-- On Leave & Absent -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/5 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Absent / Leave</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white"><span id="stat-absent" class="text-red-500"><?= $absentToday ?? 0 ?></span> <span class="text-gray-300 dark:text-gray-600 font-light mx-1">/</span> <span id="stat-leave" class="text-amber-500"><?= $employeesOnLeave ?? 0 ?></span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm w-full gap-2">
             <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden flex">
                 <div class="bg-red-500 h-full" style="width: 50%"></div>
                 <div class="bg-amber-500 h-full" style="width: 50%"></div>
             </div>
        </div>
    </div>
</div>

<!-- Secondary Stats Row (Pending Requests & Analytics) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="200">
    
    <!-- Action Center (Pending Items) -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
            <i class="fa-solid fa-clipboard-list text-primary mr-2"></i> Action Center
        </h2>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 flex items-center justify-center"><i class="fa-solid fa-calendar-minus"></i></div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Pending Leaves</p>
                        <p class="text-xs text-gray-500">Requires manager approval</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xl font-bold text-gray-900 dark:text-white" id="stat-pend-leave"><?= $pendingLeaves ?? 0 ?></span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 hover:border-primary/30 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center"><i class="fa-solid fa-business-time"></i></div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">Pending Overtime</p>
                        <p class="text-xs text-gray-500">Awaiting verification</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xl font-bold text-gray-900 dark:text-white" id="stat-pend-ot"><?= $pendingOvertime ?? 0 ?></span>
                </div>
            </div>
            
            <a href="/payrollsystem/admin/leaves" class="block w-full py-2.5 px-4 text-center text-sm font-bold text-primary bg-primary/5 hover:bg-primary/10 rounded-xl transition-colors">
                View All Requests <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Analytics Chart: Attendance Trend -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-chart-area text-primary mr-2"></i> Attendance Overview (This Week)
            </h2>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-1 flex text-xs font-medium">
                <button class="px-3 py-1 bg-white dark:bg-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm">Week</button>
                <button class="px-3 py-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Month</button>
            </div>
        </div>
        <div class="h-[250px] w-full">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>
</div>

<!-- Bottom Row: Recent Activity & Data Table -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="300">
    
    <!-- Recent Attendance Table -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h2 class="font-bold text-gray-900 dark:text-white">Live Attendance Feed</h2>
            <a href="/payrollsystem/admin/attendance" class="text-sm font-bold text-primary hover:text-blue-700 transition-colors">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Time In</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="recent-att-table" class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if(!empty($recentAttendance)): ?>
                        <?php foreach($recentAttendance as $att): ?>
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center mr-3 font-bold text-xs ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all">
                                            <?= strtoupper(substr($att['FirstName'],0,1) . substr($att['LastName'],0,1)) ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($att['FirstName'] . ' ' . $att['LastName']) ?></div>
                                            <div class="text-xs text-gray-500">ID: EMP-<?= str_pad($att['employee_id'], 4, '0', STR_PAD_LEFT) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <?= htmlspecialchars($att['DeptName'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                                    <?= date('h:i A', strtotime($att['CheckInTime'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($att['Status'] == 'Present'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Present</span>
                                    <?php elseif($att['Status'] == 'Late'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/30 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Late</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span> <?= htmlspecialchars($att['Status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="no-att-row">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-folder-open text-2xl text-gray-300 dark:text-gray-600"></i>
                                </div>
                                <p class="font-medium text-gray-900 dark:text-white">No attendance recorded yet</p>
                                <p class="text-sm mt-1">Check-ins will appear here in real-time.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions / Setup -->
    <div class="bg-gradient-to-br from-primary to-indigo-700 rounded-2xl shadow-xl shadow-primary/20 p-6 text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-black/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-xl font-bold mb-2">Quick Actions</h2>
            <p class="text-primary-100 text-sm mb-6 opacity-90">Manage your workforce efficiently</p>
            
            <div class="space-y-3">
                <a href="/payrollsystem/admin/payroll" class="flex items-center p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur-sm transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">Process Payroll</p>
                        <p class="text-xs text-indigo-200">Run monthly salary calculation</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </a>
                
                <a href="/payrollsystem/admin/employees" class="flex items-center p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur-sm transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">Onboard Employee</p>
                        <p class="text-xs text-indigo-200">Add new staff member</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </a>

                <a href="/payrollsystem/admin/settings" class="flex items-center p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur-sm transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">System Settings</p>
                        <p class="text-xs text-indigo-200">Configure rules & policies</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Chart.js
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    
    // Gradient for chart area
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.5)'); // primary color
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');
    
    const attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Present',
                data: [45, 48, 46, 49, 47, 20, 5], // Mock data for presentation
                borderColor: '#4f46e5', // Tailwind primary
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Smooth curves
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleFont: { size: 13, family: "'Inter', sans-serif" },
                    bodyFont: { size: 13, family: "'Inter', sans-serif" },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(156, 163, 175, 0.1)', drawBorder: false },
                    ticks: { color: '#9ca3af', font: { family: "'Inter', sans-serif", size: 11 } }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#9ca3af', font: { family: "'Inter', sans-serif", size: 12 } }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // AJAX Polling every 30 seconds
    setInterval(() => {
        fetch('/payrollsystem/admin/dashboardApi')
            .then(res => res.json())
            .then(data => {
                // Safely update stats if they exist in the DOM
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
                updateStat('stat-pend-ot', data.pendingOvertime);
                updateStat('stat-payroll', 'MMK ' + parseFloat(data.monthlyPayroll).toFixed(2));
                updateStat('stat-bonus', 'MMK ' + parseFloat(data.monthlyBonus).toFixed(2));

                // Build Table HTML
                const tbody = document.getElementById('recent-att-table');
                if(data.recentAttendance.length > 0) {
                    let html = '';
                    data.recentAttendance.forEach(att => {
                        let statusBadge = '';
                        if(att.status === 'Present') {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Present</span>`;
                        } else if(att.status === 'Late') {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Late</span>`;
                        } else {
                            statusBadge = `<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span> ${att.status}</span>`;
                        }
                        
                        let initials = (att.first_name.charAt(0) + att.last_name.charAt(0)).toUpperCase();
                        let time = new Date('1970-01-01T' + att.check_in + 'Z').toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
                        let empId = String(att.employee_id).padStart(4, '0');

                        html += `
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary flex items-center justify-center mr-3 font-bold text-xs ring-2 ring-white group-hover:ring-primary/20 transition-all">${initials}</div>
                                        <div>
                                            <div class="font-bold text-gray-900">${att.first_name} ${att.last_name}</div>
                                            <div class="text-xs text-gray-500">ID: EMP-${empId}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${att.department_name || 'N/A'}</span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">${time}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500"><div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-3"><i class="fa-solid fa-folder-open text-2xl text-gray-300"></i></div><p class="font-medium text-gray-900">No attendance recorded yet</p></td></tr>`;
                }
            })
            .catch(err => console.error('Error fetching dashboard data:', err));
    }, 30000);
});
</script>
