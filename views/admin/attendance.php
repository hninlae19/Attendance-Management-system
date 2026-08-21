<div x-data="attendanceManager()" class="space-y-6">

    <!-- ============ HEADER BANNER ============ -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                        <i class="fa-solid fa-clipboard-user"></i>
                        <span>Time & Attendance</span>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                    Attendance <span class="gradient-text">Management</span>
                </h1>
                <p class="text-indigo-100 text-xs sm:text-sm mt-1">Audit employee biometric clock events, overtime, and work schedule fulfillment.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-print text-indigo-600"></i>
                    <span>Print Log</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5" data-aos="fade-up" data-aos-delay="100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            
            <!-- View Type -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">View Interval</label>
                <select x-model="filters.view_type" @change="handleViewTypeChange()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <!-- Date Range -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Start Date</label>
                <input type="date" x-model="filters.date_start" @change="fetchData()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
            </div>
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">End Date</label>
                <input type="date" x-model="filters.date_end" @change="fetchData()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
            </div>

            <!-- Department -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
                <select x-model="filters.department_id" @change="fetchData()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="">All Departments</option>
                    <?php foreach($data['departments'] as $dept): ?>
                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Employee -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Employee</label>
                <select x-model="filters.employee_id" @change="fetchData()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="">All Employees</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <option value="<?= $emp['EmpID'] ?>"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                <select x-model="filters.status" @change="fetchData()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Absent">Absent</option>
                    <option value="On Leave">On Leave</option>
                </select>
            </div>

            <!-- Search -->
            <div class="lg:col-span-2 xl:col-span-3" x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-search text-xs"></i>
                    </div>
                    <input type="text" x-model.debounce.500ms="filters.search" @input="fetchData()" placeholder="Search by name or code..." class="w-full pl-9 px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
            </div>

            <!-- Reset Button -->
            <div class="flex items-end xl:col-span-1">
                <button @click="resetFilters()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white font-bold py-2.5 px-4 rounded-xl transition-colors text-xs flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-rotate-right"></i> Reset
                </button>
            </div>

        </div>
    </div>

    <!-- Data Table & Controls -->
    <div x-show="filters.view_type !== 'corrections'" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden relative" data-aos="fade-up" data-aos-delay="200">
        
        <!-- Loading Overlay -->
        <div x-show="loading" class="absolute inset-0 z-10 bg-white/70 dark:bg-slate-900/70 backdrop-blur-sm flex items-center justify-center">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-indigo-500 border-t-transparent"></div>
        </div>

        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <div class="text-xs text-slate-600 dark:text-slate-300 font-medium">
                Showing <span class="font-bold text-slate-900 dark:text-white font-mono" x-text="records.length"></span> records (Total: <span class="font-bold text-slate-900 dark:text-white font-mono" x-text="pagination.total"></span>)
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Rows per page:</label>
                <select x-model="pagination.limit" @change="fetchData(1)" class="px-2.5 py-1 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-800 dark:text-slate-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider sticky top-0 z-0">
                    <tr>
                        <th scope="col" class="px-6 py-4">Employee</th>
                        <th scope="col" class="px-6 py-4">Department</th>
                        <th scope="col" class="px-6 py-4">Date</th>
                        <th scope="col" class="px-6 py-4">Check In/Out</th>
                        <th scope="col" class="px-6 py-4 text-center">Working Hrs</th>
                        <th scope="col" class="px-6 py-4 text-center">OT Hrs</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <template x-if="records.length === 0 && !loading">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 text-indigo-500 mb-3">
                                    <i class="fa-solid fa-folder-open text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">No Records Found</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-xs">Try adjusting your filters or date range.</p>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="record in records" :key="record.id">
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm" x-text="record.first_name.charAt(0) + record.last_name.charAt(0)"></div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-xs" x-text="record.first_name + ' ' + record.last_name"></div>
                                        <div class="text-[11px] text-indigo-600 dark:text-sky-400 font-mono font-semibold">EMP-<span x-text="record.employee_code"></span></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-slate-700 dark:text-slate-300 text-xs" x-text="record.department_name"></td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900 dark:text-white text-xs" x-text="formatDate(record.date)"></td>
                            <td class="px-6 py-3.5 text-xs whitespace-nowrap">
                                <div class="flex items-center text-emerald-600 dark:text-emerald-400 mb-1 font-mono font-medium">
                                    <i class="fa-solid fa-arrow-right-to-bracket w-4 text-[10px]"></i>
                                    <span x-text="formatTime(record.check_in)"></span>
                                </div>
                                <div class="flex items-center text-rose-600 dark:text-rose-400 font-mono font-medium">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-[10px]"></i>
                                    <span x-text="formatTime(record.check_out)"></span>
                                    <span x-show="record.is_auto_checkout == 1" class="ml-1 text-[9px] bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 px-1 py-0.2 rounded font-bold" title="Auto Check-Out">Auto</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-center font-mono font-bold text-xs text-slate-800 dark:text-slate-200" x-text="formatNumber(record.working_hours) + 'h'"></td>
                            <td class="px-6 py-3.5 text-center">
                                <span x-show="record.ot_hours" class="px-2 py-0.5 text-xs font-bold font-mono text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800" x-text="formatNumber(record.ot_hours) + 'h'"></span>
                                <span x-show="!record.ot_hours" class="text-slate-400 text-xs">-</span>
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border" 
                                      :class="{
                                          'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800': record.status === 'Present',
                                          'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800': record.status === 'Late' || record.status === 'Late Check-In',
                                          'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/50 dark:text-orange-300 dark:border-orange-800': record.status === 'Half Day' || record.status === 'Half-day absent',
                                          'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800': record.status === 'Absent' || record.status === 'Full-day absent',
                                          'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800': record.status === 'On Leave'
                                      }">
                                      <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="{
                                          'bg-emerald-500': record.status === 'Present',
                                          'bg-amber-500': record.status === 'Late' || record.status === 'Late Check-In',
                                          'bg-orange-500': record.status === 'Half Day' || record.status === 'Half-day absent',
                                          'bg-rose-500': record.status === 'Absent' || record.status === 'Full-day absent',
                                          'bg-indigo-500': record.status === 'On Leave'
                                      }"></span>
                                      <span x-text="record.status"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <button @click="openModal(record)" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-indigo-950/50 dark:hover:text-indigo-400 inline-flex items-center justify-center transition-colors shadow-sm" title="View Details">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50 dark:bg-slate-900/50">
            <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">
                Page <span class="font-bold text-slate-900 dark:text-white font-mono" x-text="pagination.page"></span> of <span class="font-bold text-slate-900 dark:text-white font-mono" x-text="pagination.total_pages"></span>
            </span>
            <div class="inline-flex rounded-xl shadow-sm overflow-hidden" role="group">
                <button type="button" @click="fetchData(pagination.page - 1)" :disabled="pagination.page <= 1" class="px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    <i class="fa-solid fa-chevron-left mr-1"></i> Prev
                </button>
                <button type="button" @click="fetchData(pagination.page + 1)" :disabled="pagination.page >= pagination.total_pages" class="px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-l-0 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    Next <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Detail Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="closeModal()" class="bg-white dark:bg-slate-800 rounded-3xl max-w-3xl w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
            
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-id-card-clip text-indigo-500"></i> 
                    Attendance Details
                </h3>
                <button @click="closeModal()" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <div class="p-6" x-if="selectedRecord">
                
                <!-- Employee Header Info -->
                <div class="flex items-center gap-4 mb-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-sky-500 flex items-center justify-center text-white text-xl font-bold shadow-sm">
                        <span x-text="selectedRecord?.first_name?.charAt(0) + selectedRecord?.last_name?.charAt(0)"></span>
                    </div>
                    <div>
                        <h4 class="text-base font-extrabold text-slate-900 dark:text-white" x-text="selectedRecord?.first_name + ' ' + selectedRecord?.last_name"></h4>
                        <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5 font-medium flex items-center gap-2">
                            <span class="font-mono text-indigo-600 dark:text-sky-400 font-bold">EMP-<span x-text="selectedRecord?.employee_code"></span></span>
                            <span>•</span>
                            <span x-text="selectedRecord?.PositionName || 'No Position'"></span> 
                            <span>•</span> 
                            <span x-text="selectedRecord?.department_name || 'Unassigned'"></span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Standard Attendance Panel -->
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-700">
                        <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center gap-2">
                            <i class="fa-regular fa-clock text-indigo-500"></i> Daily Shift Logs
                        </h5>
                        <ul class="space-y-3 text-xs">
                            <li class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400">Date</span>
                                <span class="font-bold text-slate-900 dark:text-white" x-text="formatDate(selectedRecord?.date)"></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400">Check In</span>
                                <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400" x-text="formatTime(selectedRecord?.check_in)"></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400">Check Out</span>
                                <div class="flex items-center font-mono font-bold">
                                    <span class="text-rose-600 dark:text-rose-400" x-text="formatTime(selectedRecord?.check_out)"></span>
                                    <span x-show="selectedRecord?.is_auto_checkout == 1" class="ml-2 text-[9px] bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 px-1.5 py-0.5 rounded font-bold" title="Auto Check-Out">Auto</span>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400">Working Hours</span>
                                <span class="font-mono font-bold text-slate-900 dark:text-white" x-text="formatNumber(selectedRecord?.working_hours) + ' hrs'"></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400">Status</span>
                                <span class="font-bold" 
                                    :class="{
                                        'text-emerald-600': selectedRecord?.status === 'Present',
                                        'text-amber-600': selectedRecord?.status === 'Late' || selectedRecord?.status === 'Late Check-In',
                                        'text-orange-600': selectedRecord?.status === 'Half Day' || selectedRecord?.status === 'Half-day absent',
                                        'text-rose-600': selectedRecord?.status === 'Absent' || selectedRecord?.status === 'Full-day absent'
                                    }"
                                    x-text="selectedRecord?.status"></span>
                            </li>
                            <li class="flex justify-between items-center pt-2 border-t border-slate-200 dark:border-slate-700">
                                <span class="text-slate-500 dark:text-slate-400">Late By</span>
                                <span class="font-mono font-bold" :class="selectedRecord?.late_minutes > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'" x-text="(selectedRecord?.late_minutes || 0) + ' mins'"></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Overtime Panel -->
                    <div class="bg-indigo-50/50 dark:bg-indigo-950/20 rounded-2xl p-5 border border-indigo-100 dark:border-indigo-900/40">
                        <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider mb-4 border-b border-indigo-100 dark:border-indigo-800/60 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-amber-500"></i> Overtime (Approved)
                        </h5>
                        
                        <div x-show="selectedRecord?.ot_hours">
                            <ul class="space-y-3 text-xs">
                                <li class="flex justify-between items-center">
                                    <span class="text-slate-600 dark:text-slate-400">OT Type</span>
                                    <span class="font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/60 px-2 py-0.5 rounded text-xs" x-text="selectedRecord?.ot_type"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-slate-600 dark:text-slate-400">Start Time</span>
                                    <span class="font-mono font-semibold text-slate-900 dark:text-white" x-text="formatTime(selectedRecord?.ot_start)"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-slate-600 dark:text-slate-400">End Time</span>
                                    <span class="font-mono font-semibold text-slate-900 dark:text-white" x-text="formatTime(selectedRecord?.ot_end)"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-slate-600 dark:text-slate-400">OT Hours</span>
                                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400" x-text="formatNumber(selectedRecord?.ot_hours) + ' hrs'"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-slate-600 dark:text-slate-400">Hourly Rate</span>
                                    <span class="font-mono font-bold text-slate-900 dark:text-white" x-text="'MMK ' + formatCurrency(selectedRecord?.ot_rate) + '/hr'"></span>
                                </li>
                                <li class="flex justify-between items-center pt-2 border-t border-indigo-100 dark:border-indigo-800">
                                    <span class="text-slate-600 dark:text-slate-400 font-semibold">Total OT Amount</span>
                                    <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm" x-text="'MMK ' + formatCurrency(selectedRecord?.ot_amount)"></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div x-show="!selectedRecord?.ot_hours" class="flex flex-col items-center justify-center h-40 text-center">
                            <i class="fa-solid fa-moon text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-xs text-slate-500 dark:text-slate-400">No approved overtime<br>for this shift.</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Remarks -->
                <div class="mt-5" x-show="selectedRecord?.ot_remark">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">OT Admin Remarks</label>
                    <div class="bg-amber-50 dark:bg-amber-950/40 p-3.5 rounded-xl border border-amber-200 dark:border-amber-800 text-xs text-amber-900 dark:text-amber-200 italic">
                        <i class="fa-solid fa-quote-left text-amber-400 mr-1.5"></i>
                        <span x-text="selectedRecord?.ot_remark"></span>
                    </div>
                </div>

            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end bg-slate-50 dark:bg-slate-900/50">
                <button @click="closeModal()" class="px-5 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Close Details
                </button>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceManager', () => ({
        records: [],
        loading: false,
        modalOpen: false,
        selectedRecord: null,
        
        filters: {
            view_type: 'daily',
            date_start: '<?= date('Y-m-d') ?>',
            date_end: '<?= date('Y-m-d') ?>',
            department_id: '',
            employee_id: '',
            status: '',
            search: ''
        },
        
        pagination: {
            page: 1,
            limit: 10,
            total: 0,
            total_pages: 1
        },

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab === 'corrections') {
                this.filters.view_type = 'corrections';
            } else {
                this.handleViewTypeChange();
            }
        },

        handleViewTypeChange() {
            if (this.filters.view_type === 'corrections') return;

            const getLocalISODate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const today = new Date();
            
            if (this.filters.view_type === 'daily') {
                const dateStr = getLocalISODate(today);
                this.filters.date_start = dateStr;
                this.filters.date_end = dateStr;
                this.fetchData(1);
            } else if (this.filters.view_type === 'weekly') {
                const day = today.getDay();
                const diff = today.getDate() - day + (day == 0 ? -6:1);
                const monday = new Date(today.setDate(diff));
                const sunday = new Date(today.setDate(monday.getDate() + 6));
                
                this.filters.date_start = getLocalISODate(monday);
                this.filters.date_end = getLocalISODate(sunday);
                this.fetchData(1);
            } else if (this.filters.view_type === 'monthly') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                
                this.filters.date_start = getLocalISODate(firstDay);
                this.filters.date_end = getLocalISODate(lastDay);
                this.fetchData(1);
            } else if (this.filters.view_type === 'yearly') {
                const firstDay = new Date(today.getFullYear(), 0, 1);
                const lastDay = new Date(today.getFullYear(), 11, 31);
                
                this.filters.date_start = getLocalISODate(firstDay);
                this.filters.date_end = getLocalISODate(lastDay);
                this.fetchData(1);
            }
        },

        resetFilters() {
            this.filters.view_type = 'daily';
            this.filters.department_id = '';
            this.filters.employee_id = '';
            this.filters.status = '';
            this.filters.search = '';
            this.handleViewTypeChange(); // This sets dates to daily and fetches
        },

        async fetchData(page = 1) {
            if (this.filters.view_type === 'corrections') return;
            
            this.loading = true;
            this.pagination.page = page;

            // Build Query String
            const params = new URLSearchParams({
                page: this.pagination.page,
                limit: this.pagination.limit,
                date_start: this.filters.date_start,
                date_end: this.filters.date_end,
                department_id: this.filters.department_id,
                employee_id: this.filters.employee_id,
                status: this.filters.status,
                search: this.filters.search,
            });

            try {
                const response = await fetch(`/payrollsystem/admin/attendanceApi?${params.toString()}`);
                const result = await response.json();
                
                this.records = result.data;
                this.pagination.total = result.total;
                this.pagination.total_pages = result.total_pages;
            } catch (error) {
                console.error('Error fetching attendance data:', error);
                // Optionally show a toast notification here
            } finally {
                this.loading = false;
            }
        },

        openModal(record) {
            this.selectedRecord = record;
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.modalOpen = false;
            document.body.style.overflow = '';
            setTimeout(() => { this.selectedRecord = null; }, 300);
        },

        // Formatters
        formatDate(dateString) {
            if (!dateString) return '-';
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        },
        
        formatTime(timeString) {
            if (!timeString) return '--:--';
            // Parse time string (e.g. "09:00:00")
            const [hourStr, min] = timeString.split(':');
            let hour = parseInt(hourStr);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            hour = hour % 12;
            hour = hour ? hour : 12;
            return `${hour}:${min} ${ampm}`;
        },
        
        formatNumber(num) {
            if (num === null || num === undefined) return '0.0';
            return parseFloat(num).toFixed(1);
        },

        formatCurrency(num) {
            if (num === null || num === undefined) return '0.00';
            return parseFloat(num).toFixed(2);
        }
    }));
});
</script>
