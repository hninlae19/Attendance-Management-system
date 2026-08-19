<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-calendar-minus text-secondary"></i>
                    <span>Leave Administration</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 text-xs font-bold uppercase tracking-wider font-mono">
                    <?= count($data['leaveRequests'] ?? []) ?> Applications
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Leave <span class="gradient-text">Requests</span> Queue
            </h1>
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Review employee leave applications, grant approvals, or decline requests with administrative remarks.</p>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="card-glass rounded-2xl p-5 mb-6 border border-violet-500/20 shadow-lg" data-aos="fade-up" data-aos-delay="50">
    <form method="GET" action="/payrollsystem/admin/leaves" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-violet-700 dark:text-violet-300 mb-1.5">Search Employee</label>
            <input type="text" name="search" value="<?= htmlspecialchars($data['filters']['search']) ?>" placeholder="Name or employee code..." class="w-full px-3.5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-violet-500 text-xs shadow-inner">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-violet-700 dark:text-violet-300 mb-1.5">Department</label>
            <select name="department_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 text-xs shadow-inner cursor-pointer">
                <option value="">All Departments</option>
                <?php foreach($data['departments'] as $dept): ?>
                    <option value="<?= $dept['DeptID'] ?>" <?= ($data['filters']['department_id'] ?? '') == $dept['DeptID'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['DeptName']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-violet-700 dark:text-violet-300 mb-1.5">Application Date</label>
            <input type="date" name="date" value="<?= htmlspecialchars($data['filters']['date'] ?? '') ?>" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-violet-500 text-xs shadow-inner">
        </div>
        <div class="flex items-end gap-2">
            <a href="/payrollsystem/admin/leaves" class="w-full py-2.5 text-center bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-violet-600/20 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-violet-900/40 font-bold rounded-xl text-xs transition-colors">
                Clear Filters
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card-glass rounded-3xl overflow-hidden border border-gray-200 dark:border-violet-500/20 mb-8 shadow-xl" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800/80 text-violet-700 dark:text-violet-300/80 border-b border-gray-200 dark:border-violet-900/40">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Leave Type</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Duration</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Reason</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-violet-900/30">
                <?php if(empty($data['leaveRequests'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-violet-900/40 flex items-center justify-center mb-2 text-violet-600 dark:text-violet-400">
                                <i class="fa-solid fa-calendar-check text-2xl"></i>
                            </div>
                            <p class="font-semibold text-gray-900 dark:text-gray-300">No leave requests found</p>
                            <p class="text-xs text-gray-500 mt-0.5">Submitted employee leave applications will appear here for review.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['leaveRequests'] as $lr): ?>
                    <tr class="hover:bg-violet-950/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-violet-600/30 to-cyan-500/30 text-cyan-300 border border-violet-500/30 flex items-center justify-center font-extrabold text-xs shadow-inner">
                                    <?= strtoupper(substr($lr['FirstName'],0,1) . substr($lr['LastName'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-300 transition-colors"><?= htmlspecialchars($lr['FirstName'] . ' ' . $lr['LastName']) ?></div>
                                    <div class="text-[11px] text-cyan-600 dark:text-cyan-400 font-mono">EMP-<?= str_pad($lr['EmpID'], 4, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white text-xs"><?= htmlspecialchars($lr['LeaveType']) ?></div>
                            <?php if($lr['IsPaid']): ?>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 text-[10px] font-bold bg-emerald-500/15 text-emerald-300 rounded-full border border-emerald-500/30">Paid Leave</span>
                            <?php else: ?>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 text-[10px] font-bold bg-gray-500/15 text-gray-400 rounded-full border border-gray-500/30">Unpaid Leave</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-700 dark:text-gray-300">
                                <?= date('M j', strtotime($lr['StartDate'])) ?> - <?= date('M j, Y', strtotime($lr['EndDate'])) ?>
                                <div class="font-extrabold text-amber-600 dark:text-amber-400 mt-0.5 font-mono"><?= $lr['days'] ?> Day(s)</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-[200px] truncate text-gray-600 dark:text-gray-400 text-xs" title="<?= htmlspecialchars($lr['Reason']) ?>">
                            <?= htmlspecialchars($lr['Reason']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($lr['Status'] === 'Approved'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Approved
                                </span>
                            <?php elseif($lr['Status'] === 'Rejected'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> Rejected
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($lr['Status'] === 'Pending'): ?>
                                <button onclick="actionLeave(<?= $lr['RequestID'] ?>)" class="inline-flex items-center gap-1.5 bg-violet-600/30 hover:bg-violet-600/50 text-violet-300 border border-violet-500/40 px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all hover:scale-105 shadow-md">
                                    <i class="fa-solid fa-list-check"></i> Review
                                </button>
                            <?php else: ?>
                                <span class="text-gray-500 text-xs font-mono">Processed</span>
                                <?php if(!empty($lr['admin_remark'])): ?>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 italic mt-1 truncate max-w-[140px] ml-auto" title="<?= htmlspecialchars($lr['admin_remark']) ?>">"<?= htmlspecialchars($lr['admin_remark']) ?>"</div>
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
    <div class="px-6 py-4 border-t border-gray-200 dark:border-violet-900/40 bg-gray-50 dark:bg-gray-800/60 flex items-center justify-between text-xs">
        <div class="text-gray-600 dark:text-gray-400 font-mono">
            Page <?= $data['page'] ?> of <?= $data['total_pages'] ?>
        </div>
        <div class="flex gap-1.5">
            <?php 
                $queryString = http_build_query(array_merge($_GET, ['page' => max(1, $data['page'] - 1)]));
                $prevUrl = "?" . $queryString;
            ?>
            <a href="<?= $data['page'] > 1 ? $prevUrl : '#' ?>" class="px-3 py-1.5 border border-gray-300 dark:border-violet-700/30 rounded-xl bg-white dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-violet-600/20 transition-colors <?= $data['page'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">Prev</a>
            
            <?php 
                $queryString = http_build_query(array_merge($_GET, ['page' => min($data['total_pages'], $data['page'] + 1)]));
                $nextUrl = "?" . $queryString;
            ?>
            <a href="<?= $data['page'] < $data['total_pages'] ? $nextUrl : '#' ?>" class="px-3 py-1.5 border border-gray-300 dark:border-violet-700/30 rounded-xl bg-white dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-violet-600/20 transition-colors <?= $data['page'] >= $data['total_pages'] ? 'opacity-50 cursor-not-allowed' : '' ?>">Next</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Action Modal -->
<div id="actionModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-gray-900/50 dark:bg-gray-950/80 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-gray-200 dark:border-violet-500/30 transform transition-all">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-violet-900/40 flex justify-between items-center bg-gray-50 dark:bg-gray-800/80">
            <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-calendar-check text-secondary"></i> Review Leave Application
            </h3>
            <button type="button" onclick="document.getElementById('actionModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-violet-900/40 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leaves" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="id" id="request_id">
            
            <div class="mb-5">
                <label for="admin_remark" class="block text-xs font-bold uppercase tracking-wider text-violet-700 dark:text-violet-300 mb-2">Administrative Remarks (Optional)</label>
                <textarea name="admin_remark" id="admin_remark" rows="3" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-violet-700/30 text-gray-900 dark:text-white rounded-2xl focus:ring-2 focus:ring-violet-500 text-xs shadow-inner resize-none" placeholder="Add approval remarks or rejection rationale..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" name="action" value="reject" class="w-full py-2.5 text-xs font-extrabold text-rose-300 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 rounded-xl transition-all hover:scale-105 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>
                <button type="submit" name="action" value="approve" class="w-full py-2.5 text-xs font-extrabold text-gray-950 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 rounded-xl transition-all shadow-lg shadow-emerald-500/25 hover:scale-105 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Approve
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
