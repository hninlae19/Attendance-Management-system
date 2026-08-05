<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Overtime Requests</h1>
        <p class="text-gray-500 text-sm mt-1">Review and manage employee overtime requests.</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Date & Time</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Duration</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Reason</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(empty($data['overtimeRequests'])): ?>
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-business-time text-2xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <p class="font-medium text-gray-900 dark:text-white">No overtime requests found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['overtimeRequests'] as $ot): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all shadow-sm">
                                    <?= strtoupper(substr($ot['first_name'],0,1) . substr($ot['last_name'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($ot['first_name'] . ' ' . $ot['last_name']) ?></div>
                                    <div class="text-xs text-primary font-medium">EMP-<?= htmlspecialchars($ot['employee_code']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-gray-300"><?= date('M j, Y', strtotime($ot['date'])) ?></div>
                            <div class="text-xs text-gray-500 mt-0.5"><?= date('h:i A', strtotime($ot['start_time'])) ?> - <?= date('h:i A', strtotime($ot['end_time'])) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/30">
                                <?= $ot['hours'] ?> Hrs
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($ot['type'] === 'Working Day'): ?>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-md border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/30">Working Day</span>
                            <?php elseif($ot['type'] === 'Weekend'): ?>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-purple-50 text-purple-700 rounded-md border border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800/30">Weekend</span>
                            <?php else: ?>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-orange-50 text-orange-700 rounded-md border border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800/30">Holiday</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 max-w-[200px] truncate text-gray-600 dark:text-gray-400" title="<?= htmlspecialchars($ot['reason']) ?>">
                            <?= htmlspecialchars($ot['reason']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($ot['status'] === 'Approved'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                                </span>
                            <?php elseif($ot['status'] === 'Rejected'): ?>
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
                            <?php if($ot['status'] === 'Pending'): ?>
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form action="/payrollsystem/admin/overtime" method="POST" class="inline m-0 p-0">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                                        <input type="hidden" name="id" value="<?= $ot['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors tooltip" title="Approve">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="/payrollsystem/admin/overtime" method="POST" class="inline m-0 p-0">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                                        <input type="hidden" name="id" value="<?= $ot['id'] ?>">
                                        <button type="submit" name="action" value="reject" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-colors tooltip" title="Reject">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs font-medium bg-gray-50 dark:bg-gray-800 px-3 py-1 rounded-lg border border-gray-100 dark:border-gray-700">Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
