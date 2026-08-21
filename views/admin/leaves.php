<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-calendar-minus"></i>
                    <span>Leave Administration</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['leaveRequests'] ?? []) ?> Applications
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Leave <span class="gradient-text">Requests</span> Queue
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Review employee leave applications, grant approvals, or decline requests with administrative remarks.</p>
        </div>
        <div class="flex gap-2">
            <a href="/payrollsystem/admin/leaveTypes" class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <i class="fa-solid fa-sliders text-indigo-600"></i>
                <span>Configure Leave Types</span>
            </a>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-5 mb-6 border border-slate-200 dark:border-slate-700 shadow-sm" data-aos="fade-up" data-aos-delay="50">
    <form method="GET" action="/payrollsystem/admin/leaves" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Search Employee</label>
            <input type="text" name="search" value="<?= htmlspecialchars($data['filters']['search']) ?>" placeholder="Name or employee code..." class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
            <select name="department_id" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                <option value="">All Departments</option>
                <?php foreach($data['departments'] as $dept): ?>
                    <option value="<?= $dept['DeptID'] ?>" <?= ($data['filters']['department_id'] ?? '') == $dept['DeptID'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['DeptName']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Application Date</label>
            <input type="date" name="date" value="<?= htmlspecialchars($data['filters']['date'] ?? '') ?>" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
        </div>
        <div class="flex items-end gap-2">
            <a href="/payrollsystem/admin/leaves" class="w-full py-2.5 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-rotate-right"></i> Clear Filters
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 mb-8 shadow-sm" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Leave Type</th>
                    <th scope="col" class="px-6 py-4">Duration</th>
                    <th scope="col" class="px-6 py-4">Reason</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if(empty($data['leaveRequests'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-calendar-check text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No leave requests found</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Submitted employee leave applications will appear here for review.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['leaveRequests'] as $lr): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    <?= strtoupper(substr($lr['FirstName'],0,1) . substr($lr['LastName'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs"><?= htmlspecialchars($lr['FirstName'] . ' ' . $lr['LastName']) ?></div>
                                    <div class="text-[11px] text-indigo-600 dark:text-sky-400 font-mono font-semibold">EMP-<?= str_pad($lr['EmpID'], 4, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="font-bold text-slate-900 dark:text-white text-xs"><?= htmlspecialchars($lr['LeaveType']) ?></div>
                            <?php if($lr['IsPaid']): ?>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 rounded-md">Paid Leave</span>
                            <?php else: ?>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 rounded-md">Unpaid Leave</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                                <?= date('M j', strtotime($lr['StartDate'])) ?> - <?= date('M j, Y', strtotime($lr['EndDate'])) ?>
                                <div class="font-extrabold text-amber-600 dark:text-amber-400 mt-0.5 font-mono text-[11px]"><?= $lr['days'] ?> Day(s)</div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 max-w-[200px] truncate text-slate-600 dark:text-slate-400 text-xs" title="<?= htmlspecialchars($lr['Reason']) ?>">
                            <?= htmlspecialchars($lr['Reason']) ?>
                        </td>
                        <td class="px-6 py-3.5">
                            <?php if($lr['Status'] === 'Approved'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                                </span>
                            <?php elseif($lr['Status'] === 'Rejected'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Rejected
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <?php if($lr['Status'] === 'Pending'): ?>
                                <button onclick="actionLeave(<?= $lr['RequestID'] ?>)" class="inline-flex items-center gap-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all hover:scale-105 shadow-sm">
                                    <i class="fa-solid fa-list-check"></i> Review
                                </button>
                            <?php else: ?>
                                <span class="text-slate-400 text-xs font-mono">Processed</span>
                                <?php if(!empty($lr['admin_remark'])): ?>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 italic mt-0.5 truncate max-w-[140px] ml-auto" title="<?= htmlspecialchars($lr['admin_remark']) ?>">"<?= htmlspecialchars($lr['admin_remark']) ?>"</div>
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
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-between text-xs">
        <div class="text-slate-600 dark:text-slate-400 font-mono font-medium">
            Page <?= $data['page'] ?> of <?= $data['total_pages'] ?>
        </div>
        <div class="flex gap-1.5">
            <?php 
                $queryString = http_build_query(array_merge($_GET, ['page' => max(1, $data['page'] - 1)]));
                $prevUrl = "?" . $queryString;
            ?>
            <a href="<?= $data['page'] > 1 ? $prevUrl : '#' ?>" class="px-3.5 py-1.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-bold <?= $data['page'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">Prev</a>
            
            <?php 
                $queryString = http_build_query(array_merge($_GET, ['page' => min($data['total_pages'], $data['page'] + 1)]));
                $nextUrl = "?" . $queryString;
            ?>
            <a href="<?= $data['page'] < $data['total_pages'] ? $nextUrl : '#' ?>" class="px-3.5 py-1.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-bold <?= $data['page'] >= $data['total_pages'] ? 'opacity-50 cursor-not-allowed' : '' ?>">Next</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Action Modal -->
<div id="actionModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-calendar-check text-indigo-500"></i> Review Leave Application
            </h3>
            <button type="button" onclick="document.getElementById('actionModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leaves" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="id" id="request_id">
            
            <div class="mb-5">
                <label for="admin_remark" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Administrative Remarks (Optional)</label>
                <textarea name="admin_remark" id="admin_remark" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm resize-none" placeholder="Add approval remarks or rejection rationale..."></textarea>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="submit" name="action" value="reject" class="w-full py-2.5 text-xs font-extrabold text-rose-600 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 rounded-xl transition-all hover:scale-105 shadow-sm flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>
                <button type="submit" name="action" value="approve" class="w-full py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 rounded-xl transition-all shadow-lg shadow-emerald-500/25 hover:scale-105 flex items-center justify-center gap-1.5">
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
