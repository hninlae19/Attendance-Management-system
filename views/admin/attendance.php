<div x-data="attendanceManager()" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4" data-aos="fade-down">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Attendance Management</h1>
            <p class="text-gray-500 text-sm mt-1">Manage and view detailed employee attendance records.</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors shadow-sm text-sm">
                <i class="fa-solid fa-file-export mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5" data-aos="fade-up" data-aos-delay="100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            
            <!-- View Type -->
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Attendance Type</label>
                <select x-model="filters.view_type" @change="handleViewTypeChange()" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="custom">Custom Date Range</option>
                </select>
            </div>

            <!-- Date Range -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                <input type="date" x-model="filters.date_start" @change="fetchData()" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
            </div>
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                <input type="date" x-model="filters.date_end" @change="fetchData()" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
            </div>

            <!-- Department -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                <select x-model="filters.department_id" @change="fetchData()" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <option value="">All Departments</option>
                    <?php foreach($data['departments'] as $dept): ?>
                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Employee -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Employee</label>
                <select x-model="filters.employee_id" @change="fetchData()" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <option value="">All Employees</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <option value="<?= $emp['EmpID'] ?>"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select x-model="filters.status" @change="fetchData()" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <option value="">All Statuses</option>
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>

            <!-- Search -->
            <div class="lg:col-span-2 xl:col-span-3" x-show="filters.view_type !== 'corrections'">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" x-model.debounce.500ms="filters.search" @input="fetchData()" placeholder="Search by name, code, or department..." class="w-full pl-10 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                </div>
            </div>

            <!-- Reset Button -->
            <div class="flex items-end xl:col-span-1">
                <button @click="resetFilters()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                    <i class="fa-solid fa-rotate-right mr-1"></i> Reset
                </button>
            </div>

        </div>
    </div>

    <!-- Data Table & Controls -->
    <div x-show="filters.view_type !== 'corrections'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative" data-aos="fade-up" data-aos-delay="200">
        
        <!-- Loading Overlay -->
        <div x-show="loading" class="absolute inset-0 z-10 bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm flex items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent"></div>
        </div>

        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Showing <span class="font-bold text-gray-900 dark:text-white" x-text="records.length"></span> records (Total: <span x-text="pagination.total"></span>)
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Per page:</label>
                <select x-model="pagination.limit" @change="fetchData(1)" class="px-2 py-1 bg-white border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 sticky top-0 z-0">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Check In/Out</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Working Hrs</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">OT Hrs</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="records.length === 0 && !loading">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                    <i class="fa-solid fa-folder-open text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No Records Found</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Try adjusting your filters or date range.</p>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="record in records" :key="record.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all shadow-sm" x-text="record.first_name.charAt(0) + record.last_name.charAt(0)"></div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white" x-text="record.first_name + ' ' + record.last_name"></div>
                                        <div class="text-xs text-primary font-medium">EMP-<span x-text="record.employee_code"></span></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-300" x-text="record.department_name"></td>
                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-white" x-text="formatDate(record.date)"></td>
                            <td class="px-6 py-3 text-xs whitespace-nowrap">
                                <div class="flex items-center text-green-600 dark:text-green-400 mb-1">
                                    <i class="fa-solid fa-arrow-right-to-bracket w-4"></i>
                                    <span x-text="formatTime(record.check_in)"></span>
                                </div>
                                <div class="flex items-center text-red-600 dark:text-red-400">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                    <span x-text="formatTime(record.check_out)"></span>
                                    <span x-show="record.is_auto_checkout == 1" class="ml-1 text-[10px] bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300" title="Auto Check-Out">Auto</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center font-medium" x-text="formatNumber(record.working_hours) + 'h'"></td>
                            <td class="px-6 py-3 text-center">
                                <span x-show="record.ot_hours" class="px-2 py-1 text-xs font-bold text-indigo-700 bg-indigo-50 rounded dark:bg-indigo-900/30 dark:text-indigo-400" x-text="formatNumber(record.ot_hours) + 'h'"></span>
                                <span x-show="!record.ot_hours" class="text-gray-400">-</span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border" 
                                      :class="{
                                          'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30': record.status === 'Present',
                                          'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/30': record.status === 'Late',
                                          'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800/30': record.status === 'Half Day',
                                          'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/30': record.status === 'Absent'
                                      }">
                                      <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="{
                                          'bg-emerald-500': record.status === 'Present',
                                          'bg-amber-500': record.status === 'Late',
                                          'bg-orange-500': record.status === 'Half Day',
                                          'bg-rose-500': record.status === 'Absent'
                                      }"></span>
                                      <span x-text="record.status"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <button @click="openModal(record)" class="text-gray-400 hover:text-primary transition-colors p-2 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 group-hover:text-primary" title="View Details">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/30 dark:bg-gray-800/30">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Page <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.page"></span> of <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.total_pages"></span>
            </span>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button type="button" @click="fetchData(pagination.page - 1)" :disabled="pagination.page <= 1" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 focus:ring-primary focus:text-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    <i class="fa-solid fa-chevron-left mr-1"></i> Prev
                </button>
                <button type="button" @click="fetchData(pagination.page + 1)" :disabled="pagination.page >= pagination.total_pages" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-l-0 border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 focus:ring-primary focus:text-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    Next <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Detail Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="closeModal()" class="bg-white dark:bg-gray-800 rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden transform transition-all" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-800/80">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-id-card-clip text-primary mr-3"></i> 
                    Attendance Details
                </h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30">
                    <i class="fa-solid fa-xmark text-xl w-6 h-6 flex items-center justify-center"></i>
                </button>
            </div>

            <div class="p-6" x-if="selectedRecord">
                
                <!-- Employee Header Info -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl font-bold shadow-inner">
                        <span x-text="selectedRecord?.first_name?.charAt(0) + selectedRecord?.last_name?.charAt(0)"></span>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white" x-text="selectedRecord?.first_name + ' ' + selectedRecord?.last_name"></h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">
                            <i class="fa-solid fa-hashtag mr-1"></i><span x-text="selectedRecord?.employee_code"></span> 
                            <span class="mx-2">•</span> 
                            <i class="fa-solid fa-briefcase mr-1"></i><span x-text="selectedRecord?.PositionName"></span> 
                            <span class="mx-2">•</span> 
                            <i class="fa-solid fa-building mr-1"></i><span x-text="selectedRecord?.department_name"></span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Standard Attendance Panel -->
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
                        <h5 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 border-b border-gray-200 dark:border-gray-700 pb-2 flex items-center">
                            <i class="fa-regular fa-clock mr-2 text-gray-400"></i> Daily Shift
                        </h5>
                        <ul class="space-y-3 text-sm">
                            <li class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Date</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="formatDate(selectedRecord?.date)"></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Check In</span>
                                <span class="font-medium text-green-600" x-text="formatTime(selectedRecord?.check_in)"></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Check Out</span>
                                <div class="flex items-center">
                                    <span class="font-medium text-red-600" x-text="formatTime(selectedRecord?.check_out)"></span>
                                    <span x-show="selectedRecord?.is_auto_checkout == 1" class="ml-2 text-[10px] bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300" title="Auto Check-Out">Auto</span>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Working Hours</span>
                                <span class="font-bold text-gray-900 dark:text-white" x-text="formatNumber(selectedRecord?.working_hours) + ' hrs'"></span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Status</span>
                                <span class="font-medium" 
                                    :class="{
                                        'text-green-600': selectedRecord?.status === 'Present',
                                        'text-yellow-600': selectedRecord?.status === 'Late',
                                        'text-orange-600': selectedRecord?.status === 'Half Day',
                                        'text-red-600': selectedRecord?.status === 'Absent'
                                    }"
                                    x-text="selectedRecord?.status"></span>
                            </li>
                            <li class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Late By</span>
                                <span class="font-bold" :class="selectedRecord?.late_minutes > 0 ? 'text-red-600' : 'text-green-600'" x-text="(selectedRecord?.late_minutes || 0) + ' mins'"></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Overtime Panel -->
                    <div class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-xl p-5 border border-indigo-100 dark:border-indigo-900/30">
                        <h5 class="text-sm font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider mb-4 border-b border-indigo-100 dark:border-indigo-800 pb-2 flex items-center">
                            <i class="fa-solid fa-bolt mr-2 text-indigo-400"></i> Overtime (Approved)
                        </h5>
                        
                        <div x-show="selectedRecord?.ot_hours">
                            <ul class="space-y-3 text-sm">
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">OT Type</span>
                                    <span class="font-medium text-gray-900 dark:text-white bg-indigo-100 dark:bg-indigo-900/50 px-2 py-0.5 rounded text-xs" x-text="selectedRecord?.ot_type"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Start Time</span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="formatTime(selectedRecord?.ot_start)"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">End Time</span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="formatTime(selectedRecord?.ot_end)"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">OT Hours</span>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400" x-text="formatNumber(selectedRecord?.ot_hours) + ' hrs'"></span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Hourly Rate</span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="'MMK ' + formatCurrency(selectedRecord?.ot_rate) + '/hr'"></span>
                                </li>
                                <li class="flex justify-between items-center pt-2 border-t border-indigo-100 dark:border-indigo-800">
                                    <span class="text-gray-600 dark:text-gray-400">Total OT Amount</span>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-base" x-text="'MMK ' + formatCurrency(selectedRecord?.ot_amount)"></span>
                                </li>
                            </ul>
                        </div>
                        
                        <div x-show="!selectedRecord?.ot_hours" class="flex flex-col items-center justify-center h-40 text-center">
                            <i class="fa-solid fa-moon text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No approved overtime<br>for this shift.</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Remarks -->
                <div class="mt-6" x-show="selectedRecord?.ot_remark">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">OT Admin Remarks</label>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800 text-sm text-gray-700 dark:text-gray-300 italic">
                        <i class="fa-solid fa-quote-left text-yellow-300 mr-2"></i>
                        <span x-text="selectedRecord?.ot_remark"></span>
                    </div>
                </div>

            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end bg-gray-50/50 dark:bg-gray-800/50">
                <button @click="closeModal()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-700 transition-all shadow-sm">
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
