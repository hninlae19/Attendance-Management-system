<div class="mb-6" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Overtime Assign</h1>
    <p class="text-gray-500 text-sm mt-1">View and respond to your overtime assignments.</p>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30 flex items-center" data-aos="fade-up">
        <i class="fa-solid fa-circle-check mr-2"></i> <?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<!-- Summary Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6" data-aos="fade-up" data-aos-delay="50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                <i class="fa-solid fa-calendar-clock text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white"><?= $data['upcoming'] ?></p>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Upcoming</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <i class="fa-solid fa-clock text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white"><?= $data['totalHours'] ?> <span class="text-xs font-medium text-gray-400">h</span></p>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total OT Hours</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                <i class="fa-solid fa-sack-dollar text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white"><?= number_format($data['totalEarnings']) ?></p>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">OT Earnings (MMK)</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="p-3 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                <i class="fa-solid fa-inbox text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white"><?= $data['pending'] ?></p>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pending Action</p>
            </div>
        </div>
    </div>
</div>

<!-- Overtime Assignments Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list text-orange-500"></i> My Overtime Assignments
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Time</th>
                    <th class="px-6 py-3">Hours</th>
                    <th class="px-6 py-3">Rate/Hr</th>
                    <th class="px-6 py-3">Total Amount</th>
                    <th class="px-6 py-3">Actual Hrs</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['overtimes'])): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-mug-hot text-2xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <p class="font-medium text-gray-900 dark:text-white">No overtime assignments found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['overtimes'] as $ot): ?>
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= date('D, M j, Y', strtotime($ot['OvertimeDate'])) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($ot['StartTime'] && $ot['EndTime']): ?>
                                <?= date('h:i A', strtotime($ot['StartTime'])) ?> - <?= date('h:i A', strtotime($ot['EndTime'])) ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/30">
                                <?= $ot['OvertimeHours'] ?> Hrs
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300"><?= number_format($ot['OTRate'], 2) ?></td>
                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400"><?= number_format($ot['OTAmount'], 2) ?> MMK</td>
                        <td class="px-6 py-4">
                            <span class="font-bold <?= ($ot['ActualOTHours'] > 0) ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' ?>">
                                <?= $ot['ActualOTHours'] > 0 ? $ot['ActualOTHours'] . ' Hrs' : '-' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'Assigned' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/30',
                                    'Accepted' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/30',
                                    'Rejected' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/30',
                                    'In Progress' => 'bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800/30',
                                    'Completed' => 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-900/30 dark:text-teal-400 dark:border-teal-800/30',
                                    'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/30',
                                    'No Show' => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800/30 dark:text-gray-400 dark:border-gray-700/30',
                                    'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/30'
                                ];
                                $colorClass = $statusColors[$ot['Status']] ?? $statusColors['Assigned'];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border <?= $colorClass ?>"><?= $ot['Status'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($ot['Status'] === 'Assigned'): ?>
                                <div class="flex gap-2 justify-end">
                                    <form method="POST" action="/payrollsystem/employee/ot_action" class="inline m-0 p-0">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="px-3 py-1 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 text-xs font-medium transition-colors shadow-sm">Accept</button>
                                    </form>
                                    <form method="POST" action="/payrollsystem/employee/ot_action" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to reject this overtime?');">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-xs font-medium transition-colors shadow-sm">Reject</button>
                                    </form>
                                </div>
                            <?php elseif ($ot['Status'] === 'Accepted'): ?>
                                <?php
                                    $today = date('Y-m-d');
                                    $now = time();
                                    $start = strtotime($ot['OvertimeDate'] . ' ' . $ot['StartTime']);
                                    $end = strtotime($ot['OvertimeDate'] . ' ' . $ot['EndTime']);
                                    if ($end < $start) $end += 86400;

                                    if ($ot['OvertimeDate'] === $today && $now >= ($start - 900) && $now <= $end):
                                ?>
                                    <form method="POST" action="/payrollsystem/employee/ot_attendance" class="inline m-0 p-0">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                        <input type="hidden" name="action" value="check_in">
                                        <button type="submit" class="px-4 py-1.5 bg-gradient-to-r from-[#D4AF37] to-[#C5A017] text-white rounded-md hover:from-[#C5A017] hover:to-[#B49006] text-xs font-bold transition-colors shadow-md">
                                            <i class="fa-solid fa-fingerprint mr-1"></i> Check In
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">Wait for schedule</span>
                                <?php endif; ?>
                            <?php elseif ($ot['Status'] === 'In Progress'): ?>
                                <form method="POST" action="/payrollsystem/employee/ot_attendance" class="inline m-0 p-0">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                    <input type="hidden" name="action" value="check_out">
                                    <button type="submit" class="px-4 py-1.5 bg-gradient-to-r from-[#FF6B6B] to-[#E63946] text-white rounded-md hover:from-[#E63946] hover:to-[#D62828] text-xs font-bold transition-colors shadow-md">
                                        <i class="fa-solid fa-right-from-bracket mr-1"></i> Check Out
                                    </button>
                                </form>
                            <?php elseif ($ot['Status'] === 'Rejected' && !empty($ot['EmployeeResponse'])): ?>
                                <span class="text-xs text-gray-500 dark:text-gray-400 italic truncate max-w-[180px]" title="<?= htmlspecialchars($ot['EmployeeResponse']) ?>"><?= htmlspecialchars($ot['EmployeeResponse']) ?></span>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
