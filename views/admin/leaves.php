<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Leave Requests</h1>
        <p class="text-gray-500 text-sm mt-1">Review and manage employee leave applications.</p>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6" data-aos="fade-up" data-aos-delay="50">
    <form method="GET" action="/payrollsystem/admin/leaves" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Search Employee</label>
            <input type="text" name="search" value="<?= htmlspecialchars($data['filters']['search']) ?>" placeholder="Name..." class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
            <select name="department_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                <option value="">All Departments</option>
                <?php foreach($data['departments'] as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $data['filters']['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Date Range</label>
            <input type="date" name="date" value="<?= htmlspecialchars($data['filters']['date']) ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="w-full bg-primary hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm shadow-sm">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            <a href="/payrollsystem/admin/leaves" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                Clear
            </a>
        </div>
    </form>
</div>


<div class="bg-white dark:bg-gray-800 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Leave Type</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Duration</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Reason</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(empty($data['leaveRequests'])): ?>
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-calendar-xmark text-2xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <p class="font-medium text-gray-900 dark:text-white">No leave requests found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['leaveRequests'] as $lr): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all shadow-sm">
                                    <?= strtoupper(substr($lr['first_name'],0,1) . substr($lr['last_name'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($lr['first_name'] . ' ' . $lr['last_name']) ?></div>
                                    <div class="text-xs text-primary font-medium">EMP-<?= htmlspecialchars($lr['employee_code']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-gray-300"><?= htmlspecialchars($lr['leave_type_name']) ?></div>
                            <?php if($lr['is_paid']): ?>
                                <span class="inline-block mt-1 px-1.5 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded-md border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30">Paid</span>
                            <?php else: ?>
                                <span class="inline-block mt-1 px-1.5 py-0.5 text-[10px] font-bold bg-gray-50 text-gray-700 rounded-md border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-300">
                                <?= date('M j', strtotime($lr['start_date'])) ?> - <?= date('M j, Y', strtotime($lr['end_date'])) ?>
                                <div class="font-bold text-indigo-600 dark:text-indigo-400 mt-0.5"><?= $lr['days'] ?> Day(s)</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-[200px] truncate text-gray-600 dark:text-gray-400" title="<?= htmlspecialchars($lr['reason']) ?>">
                            <?= htmlspecialchars($lr['reason']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($lr['status'] === 'Approved'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                                </span>
                            <?php elseif($lr['status'] === 'Rejected'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Rejected
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($lr['status'] === 'Pending'): ?>
                                <button onclick="actionLeave(<?= $lr['id'] ?>)" class="inline-flex items-center bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm">
                                    <i class="fa-solid fa-list-check mr-2"></i> Review
                                </button>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs font-medium bg-gray-50 dark:bg-gray-800 px-3 py-1 rounded-lg border border-gray-100 dark:border-gray-700">Processed</span>
                                <?php if($lr['admin_remark']): ?>
                                    <div class="text-[10px] text-gray-500 mt-2 truncate max-w-[120px] ml-auto border-t border-gray-100 dark:border-gray-700 pt-1" title="<?= htmlspecialchars($lr['admin_remark']) ?>">"<?= htmlspecialchars($lr['admin_remark']) ?>"</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($data['total_pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Page <?= $data['page'] ?> of <?= $data['total_pages'] ?>
        </div>
        <div class="flex gap-1">
            <?php 
                $queryString = http_build_query(array_merge($_GET, ['page' => max(1, $data['page'] - 1)]));
                $prevUrl = "?" . $queryString;
            ?>
            <a href="<?= $data['page'] > 1 ? $prevUrl : '#' ?>" class="px-3 py-1 border border-gray-200 dark:border-gray-600 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 <?= $data['page'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">Prev</a>
            
            <?php 
                $queryString = http_build_query(array_merge($_GET, ['page' => min($data['total_pages'], $data['page'] + 1)]));
                $nextUrl = "?" . $queryString;
            ?>
            <a href="<?= $data['page'] < $data['total_pages'] ? $nextUrl : '#' ?>" class="px-3 py-1 border border-gray-200 dark:border-gray-600 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 <?= $data['page'] >= $data['total_pages'] ? 'opacity-50 cursor-not-allowed' : '' ?>">Next</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Action Modal -->
<div id="actionModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center"><i class="fa-solid fa-calendar-check text-primary mr-3"></i> Review Request</h3>
            <button type="button" onclick="document.getElementById('actionModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leaves" method="POST" class="p-6">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="id" id="request_id">
            
            <div class="mb-6">
                <label for="admin_remark" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Admin Remarks (Optional)</label>
                <textarea name="admin_remark" id="admin_remark" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm text-sm resize-none" placeholder="Provide a reason if rejecting..."></textarea>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" name="action" value="reject" class="w-full px-4 py-3 text-sm font-bold text-white bg-rose-500 rounded-xl hover:bg-rose-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-rose-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-xmark mr-2"></i> Reject
                </button>
                <button type="submit" name="action" value="approve" class="w-full px-4 py-3 text-sm font-bold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-check mr-2"></i> Approve
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function actionLeave(id) {
        document.getElementById('request_id').value = id;
        document.getElementById('actionModal').classList.remove('hidden');
    }
</script>
