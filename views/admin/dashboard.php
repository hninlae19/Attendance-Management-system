<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
    <p class="text-gray-500 text-sm"><i class="fa-solid fa-clock mr-1"></i> <span id="current-time"><?= date('l, F j, Y h:i A') ?></span></p>
</div>

<!-- Dashboard Sections -->
<div class="space-y-8 mb-8" id="dashboard-stats">

    <!-- Attendance Dashboard -->
    <section data-aos="fade-up" data-aos-delay="0">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i> Attendance Overview
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Present -->
            <div class="bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/20 dark:to-gray-800 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-blue-100 dark:border-blue-800/30 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-sm font-medium text-blue-600/80 dark:text-blue-400/80 mb-1">Present Today</p>
                        <h3 class="text-3xl font-bold text-blue-700 dark:text-blue-300" id="stat-present"><?= $presentToday ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
            </div>
            <!-- Absent -->
            <div class="bg-gradient-to-br from-red-50 to-white dark:from-red-900/20 dark:to-gray-800 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-red-100 dark:border-red-800/30 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-sm font-medium text-red-600/80 dark:text-red-400/80 mb-1">Absent Today</p>
                        <h3 class="text-3xl font-bold text-red-700 dark:text-red-300" id="stat-absent"><?= $absentToday ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center shadow-lg shadow-red-500/30">
                        <i class="fa-solid fa-user-xmark"></i>
                    </div>
                </div>
            </div>
            <!-- Late -->
            <div class="bg-gradient-to-br from-orange-50 to-white dark:from-orange-900/20 dark:to-gray-800 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-orange-100 dark:border-orange-800/30 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-sm font-medium text-orange-600/80 dark:text-orange-400/80 mb-1">Late Today</p>
                        <h3 class="text-3xl font-bold text-orange-700 dark:text-orange-300" id="stat-late"><?= $lateToday ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/30">
                        <i class="fa-solid fa-person-running"></i>
                    </div>
                </div>
            </div>
            <!-- Overtime -->
            <div class="bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-900/20 dark:to-gray-800 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-indigo-100 dark:border-indigo-800/30 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-sm font-medium text-indigo-600/80 dark:text-indigo-400/80 mb-1">Pending Overtime</p>
                        <h3 class="text-3xl font-bold text-indigo-700 dark:text-indigo-300" id="stat-ot"><?= $pendingOvertime ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leave Dashboard -->
    <section data-aos="fade-up" data-aos-delay="100">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <i class="fa-solid fa-calendar-minus text-emerald-500 mr-2"></i> Leave Management
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pending -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pending Requests</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $pendingLeaves ?? 0 ?></h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <!-- Approved -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-emerald-100 dark:border-emerald-800/30 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-600/80 dark:text-emerald-400/80 mb-1">Approved Requests</p>
                    <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"><?= $approvedLeaves ?? 0 ?></h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 dark:text-emerald-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>
            <!-- Rejected -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl shadow-sm p-5 border border-red-100 dark:border-red-800/30 hover:border-red-300 dark:hover:border-red-500 transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600/80 dark:text-red-400/80 mb-1">Rejected Requests</p>
                    <h3 class="text-2xl font-bold text-red-600 dark:text-red-400"><?= $rejectedLeaves ?? 0 ?></h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Payroll Dashboard -->
    <section data-aos="fade-up" data-aos-delay="200">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
            <i class="fa-solid fa-money-check-dollar text-cyan-500 mr-2"></i> Payroll & Finance
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Cost -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-500/20 p-5 text-white hover:-translate-y-1 transition-transform duration-300">
                <p class="text-cyan-100 text-sm font-medium mb-1">Total Payroll Cost</p>
                <h3 class="text-2xl font-bold mb-4"><?= number_format($monthlyPayroll ?? 0) ?> MMK</h3>
                <div class="w-full bg-black/10 rounded-full h-1.5"><div class="bg-white h-1.5 rounded-full" style="width: 100%"></div></div>
            </div>
            <!-- Overtime Cost -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg shadow-gray-900/20 p-5 text-white hover:-translate-y-1 transition-transform duration-300">
                <p class="text-gray-400 text-sm font-medium mb-1">Overtime Cost</p>
                <h3 class="text-2xl font-bold mb-4 text-orange-400"><?= number_format($monthlyOvertimeCost ?? 0) ?> MMK</h3>
                <div class="w-full bg-white/10 rounded-full h-1.5"><div class="bg-orange-400 h-1.5 rounded-full" style="width: 40%"></div></div>
            </div>
            <!-- Bonus Amount -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg shadow-emerald-500/20 p-5 text-white hover:-translate-y-1 transition-transform duration-300">
                <p class="text-emerald-100 text-sm font-medium mb-1">Bonus Amount</p>
                <h3 class="text-2xl font-bold mb-4"><?= number_format($monthlyBonus ?? 0) ?> MMK</h3>
                <div class="w-full bg-black/10 rounded-full h-1.5"><div class="bg-white h-1.5 rounded-full" style="width: 25%"></div></div>
            </div>
            <!-- Deduction Amount -->
            <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl shadow-lg shadow-rose-500/20 p-5 text-white hover:-translate-y-1 transition-transform duration-300">
                <p class="text-rose-100 text-sm font-medium mb-1">Deduction Amount</p>
                <h3 class="text-2xl font-bold mb-4"><?= number_format($monthlyDeduction ?? 0) ?> MMK</h3>
                <div class="w-full bg-black/10 rounded-full h-1.5"><div class="bg-white h-1.5 rounded-full" style="width: 15%"></div></div>
            </div>
        </div>
    </section>

</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8" data-aos="fade-up">
    <!-- Attendance Overview Chart -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-4">Today's Attendance Overview</h2>
        <canvas id="attendanceChart" height="250"></canvas>
    </div>
    <!-- Payroll Trend Chart -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6" x-data="{ trendPeriod: 'monthly' }">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-900 dark:text-white">Payroll Trend</h2>
            <select x-model="trendPeriod" @change="updatePayrollTrend(trendPeriod)" class="text-sm border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-primary focus:border-primary">
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>
        <canvas id="payrollChart" height="250"></canvas>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" data-aos="fade-up">
    
    <!-- Recent Attendance -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-2">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h2 class="font-bold text-gray-900 dark:text-white">Recent Attendance Check-ins</h2>
            <a href="/payrollsystem/admin/attendance" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Employee</th>
                        <th scope="col" class="px-6 py-3">Department</th>
                        <th scope="col" class="px-6 py-3">Check In</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody id="recent-att-table">
                    <?php if(!empty($recentAttendance)): ?>
                        <?php foreach($recentAttendance as $att): ?>
                            <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary/20 text-primary flex items-center justify-center mr-3 font-bold text-xs">
                                        <?= strtoupper(substr($att['first_name'],0,1) . substr($att['last_name'],0,1)) ?>
                                    </div>
                                    <?= htmlspecialchars($att['first_name'] . ' ' . $att['last_name']) ?>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($att['department_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4"><?= date('h:i A', strtotime($att['check_in'])) ?></td>
                                <td class="px-6 py-4">
                                    <?php if($att['status'] == 'Present'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Present</span>
                                    <?php elseif($att['status'] == 'Late'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Late</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-900 dark:text-gray-300"><?= $att['status'] ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="no-att-row">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                                No attendance recorded today.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-bold text-gray-900 dark:text-white">Quick Actions</h2>
        </div>
        <div class="p-6 space-y-4">
            <a href="/payrollsystem/admin/payroll" class="flex items-center p-4 text-base font-bold text-gray-900 rounded-xl bg-gray-50 hover:bg-primary hover:text-white group transition-all duration-300 hover:shadow-lg dark:bg-gray-700 dark:text-white dark:hover:bg-primary">
                <i class="fa-solid fa-file-invoice-dollar text-primary group-hover:text-white transition-colors text-xl"></i>
                <span class="flex-1 ms-4 whitespace-nowrap">Generate Payroll</span>
            </a>
            <a href="/payrollsystem/admin/employees" class="flex items-center p-4 text-base font-bold text-gray-900 rounded-xl bg-gray-50 hover:bg-primary hover:text-white group transition-all duration-300 hover:shadow-lg dark:bg-gray-700 dark:text-white dark:hover:bg-primary">
                <i class="fa-solid fa-user-plus text-secondary group-hover:text-white transition-colors text-xl"></i>
                <span class="flex-1 ms-4 whitespace-nowrap">Add New Employee</span>
            </a>
            <a href="/payrollsystem/admin/settings" class="flex items-center p-4 text-base font-bold text-gray-900 rounded-xl bg-gray-50 hover:bg-primary hover:text-white group transition-all duration-300 hover:shadow-lg dark:bg-gray-700 dark:text-white dark:hover:bg-primary">
                <i class="fa-solid fa-gear text-gray-500 group-hover:text-white transition-colors text-xl"></i>
                <span class="flex-1 ms-4 whitespace-nowrap">System Settings</span>
            </a>
        </div>
    </div>
</div>

<script>
let payChart;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    const attCtx = document.getElementById('attendanceChart').getContext('2d');
    const attChart = new Chart(attCtx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Late', 'Absent', 'On Leave'],
            datasets: [{
                data: [<?= $presentToday ?? 0 ?>, <?= $lateToday ?? 0 ?>, <?= $absentToday ?? 0 ?>, <?= $employeesOnLeave ?? 0 ?>],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    const payCtx = document.getElementById('payrollChart').getContext('2d');
    payChart = new Chart(payCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Net Payroll',
                    data: [],
                    backgroundColor: '#10b981',
                    borderRadius: 4
                },
                {
                    label: 'Bonuses',
                    data: [],
                    backgroundColor: '#14b8a6',
                    borderRadius: 4
                },
                {
                    label: 'Deductions',
                    data: [],
                    backgroundColor: '#e11d48',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true },
                y: { stacked: true }
            }
        }
    });

    window.updatePayrollTrend = function(period = 'monthly') {
        fetch('/payrollsystem/admin/payrollTrendApi?period=' + period)
            .then(res => res.json())
            .then(data => {
                payChart.data.labels = data.labels;
                payChart.data.datasets[0].data = data.payroll;
                payChart.data.datasets[1].data = data.bonus;
                payChart.data.datasets[2].data = data.deduction;
                payChart.update();
            });
    };

    // Load initial trend data
    updatePayrollTrend('monthly');

    // Clock
    setInterval(() => {
        const now = new Date();
        document.getElementById('current-time').innerText = now.toLocaleString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
    }, 60000);

    // AJAX Polling every 30 seconds
    setInterval(() => {
        fetch('/payrollsystem/admin/dashboardApi')
            .then(res => res.json())
            .then(data => {
                // Update stats
                document.getElementById('stat-total-emp').innerText = data.totalEmployees;
                document.getElementById('stat-active-emp').innerText = data.activeEmployees;
                document.getElementById('stat-present').innerText = data.presentToday;
                document.getElementById('stat-late').innerText = data.lateToday;
                document.getElementById('stat-absent').innerText = data.absentToday;
                document.getElementById('stat-leave').innerText = data.employeesOnLeave;
                document.getElementById('stat-pend-leave').innerText = data.pendingLeaves;
                document.getElementById('stat-pend-ot').innerText = data.pendingOvertime;
                document.getElementById('stat-payroll').innerText = '$' + parseFloat(data.monthlyPayroll).toFixed(2);
                document.getElementById('stat-bonus').innerText = '$' + parseFloat(data.monthlyBonus).toFixed(2);
                document.getElementById('stat-deduction').innerText = '$' + parseFloat(data.monthlyDeduction).toFixed(2);

                // Update Attendance Chart
                attChart.data.datasets[0].data = [data.presentToday, data.lateToday, data.absentToday, data.employeesOnLeave];
                attChart.update();

                // Build Table HTML
                const tbody = document.getElementById('recent-att-table');
                if(data.recentAttendance.length > 0) {
                    let html = '';
                    data.recentAttendance.forEach(att => {
                        let statusBadge = '';
                        if(att.status === 'Present') statusBadge = '<span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Present</span>';
                        else if(att.status === 'Late') statusBadge = '<span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Late</span>';
                        else statusBadge = '<span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">'+att.status+'</span>';
                        
                        let initials = (att.first_name.charAt(0) + att.last_name.charAt(0)).toUpperCase();
                        let time = new Date('1970-01-01T' + att.check_in + 'Z').toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});

                        html += `
                            <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary/20 text-primary flex items-center justify-center mr-3 font-bold text-xs">${initials}</div>
                                    ${att.first_name} ${att.last_name}
                                </td>
                                <td class="px-6 py-4">${att.department_name || 'N/A'}</td>
                                <td class="px-6 py-4">${time}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500"><i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 block"></i>No attendance recorded today.</td></tr>`;
                }
            })
            .catch(err => console.error('Error fetching dashboard data:', err));
    }, 30000);
});
</script>
