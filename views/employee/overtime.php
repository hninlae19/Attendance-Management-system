<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#180f33] via-[#241447] to-[#121c3b] border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-clock-rotate-left text-secondary"></i>
                    <span>Overtime Schedule</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Overtime</span> Assignments
            </h1>
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Review scheduled overtime sessions, accept or decline assignments, and clock in for OT shifts.</p>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs font-semibold flex items-center gap-3 backdrop-blur-sm animate-pulse" data-aos="fade-up">
        <i class="fa-solid fa-circle-check text-base"></i>
        <span><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<!-- ============ SUMMARY STATS ============ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8" data-aos="fade-up" data-aos-delay="50">
    <div class="card-glass rounded-3xl p-5 border border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-white font-mono"><?= $data['upcoming'] ?></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-cyan-300/80">Upcoming Shifts</p>
            </div>
        </div>
    </div>

    <div class="card-glass rounded-3xl p-5 border border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-violet-500/20 text-violet-300 border border-violet-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-white font-mono"><?= $data['totalHours'] ?> <span class="text-xs font-normal text-gray-400">h</span></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-violet-300/80">Total OT Hours</p>
            </div>
        </div>
    </div>

    <div class="card-glass rounded-3xl p-5 border border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div>
                <p class="text-xl font-extrabold text-emerald-400 font-mono"><?= number_format($data['totalEarnings']) ?></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-300/80">Earnings (MMK)</p>
            </div>
        </div>
    </div>

    <div class="card-glass rounded-3xl p-5 border border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-white font-mono"><?= $data['pending'] ?></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-300/80">Pending Action</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ OVERTIME ASSIGNMENTS TABLE ============ -->
<div class="card-glass rounded-3xl overflow-hidden border border-violet-500/20 mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="p-4 px-6 border-b border-violet-900/40 flex justify-between items-center bg-surface/60">
        <h3 class="font-bold text-white text-base flex items-center gap-2 font-outfit">
            <i class="fa-solid fa-clipboard-list text-amber-400"></i> My Assigned Overtime Records
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-surface/80 text-violet-300/80 border-b border-violet-900/40">
                <tr>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Scheduled Time</th>
                    <th class="px-6 py-4">Hours</th>
                    <th class="px-6 py-4">Rate / Hr</th>
                    <th class="px-6 py-4">Total Amount</th>
                    <th class="px-6 py-4">Actual Log</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-violet-900/30">
                <?php if (empty($data['overtimes'])): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-surface rounded-2xl border border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                <i class="fa-solid fa-mug-hot text-xl"></i>
                            </div>
                            <p class="font-semibold text-gray-300">No overtime assignments found</p>
                            <p class="text-xs text-gray-500 mt-0.5">Assigned overtime shifts from your supervisor will appear here.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['overtimes'] as $ot): ?>
                    <tr class="hover:bg-violet-950/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-white"><?= date('D, M j, Y', strtotime($ot['OvertimeDate'])) ?></td>
                        <td class="px-6 py-4 text-xs text-gray-300 font-medium">
                            <?php if ($ot['StartTime'] && $ot['EndTime']): ?>
                                <?= date('h:i A', strtotime($ot['StartTime'])) ?> - <?= date('h:i A', strtotime($ot['EndTime'])) ?>
                            <?php else: ?>
                                <span class="text-gray-500 italic">Unspecified</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-amber-400 font-mono">
                                <?= $ot['OvertimeHours'] ?> <span class="text-xs text-gray-500 font-normal">hrs</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-300 font-mono"><?= number_format($ot['OTRate'], 2) ?></td>
                        <td class="px-6 py-4 font-extrabold text-emerald-400 font-mono"><?= number_format($ot['OTAmount'], 2) ?> <span class="text-xs text-gray-500 font-normal">MMK</span></td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs <?= ($ot['ActualOTHours'] > 0) ? 'font-bold text-emerald-400' : 'text-gray-500' ?>">
                                <?= $ot['ActualOTHours'] > 0 ? $ot['ActualOTHours'] . ' hrs' : '—' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'Assigned' => 'bg-blue-500/15 text-blue-300 border border-blue-500/30',
                                    'Accepted' => 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30',
                                    'Rejected' => 'bg-rose-500/15 text-rose-300 border border-rose-500/30',
                                    'In Progress' => 'bg-amber-500/15 text-amber-300 border border-amber-500/30',
                                    'Completed' => 'bg-teal-500/15 text-teal-300 border border-teal-500/30',
                                    'Approved' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30',
                                    'No Show' => 'bg-gray-500/15 text-gray-300 border border-gray-500/30',
                                    'Cancelled' => 'bg-rose-500/15 text-rose-300 border border-rose-500/30'
                                ];
                                $colorClass = $statusColors[$ot['Status']] ?? $statusColors['Assigned'];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= $colorClass ?>"><?= $ot['Status'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($ot['Status'] === 'Assigned'): ?>
                                <div class="flex gap-2 justify-end">
                                    <form method="POST" action="/payrollsystem/employee/ot_action" class="inline m-0 p-0">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-extrabold rounded-xl text-xs transition-all shadow-md hover:scale-105">Accept</button>
                                    </form>
                                    <form method="POST" action="/payrollsystem/employee/ot_action" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to reject this overtime assignment?');">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="px-3.5 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 font-bold rounded-xl text-xs transition-colors">Reject</button>
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
                                        <button type="submit" class="px-4 py-1.5 bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-gray-950 rounded-xl text-xs font-extrabold transition-all shadow-md hover:scale-105">
                                            <i class="fa-solid fa-fingerprint mr-1"></i> OT Check-In
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-[11px] text-gray-500 bg-surface px-2.5 py-1 rounded-lg border border-violet-900/30">Pending Shift</span>
                                <?php endif; ?>
                            <?php elseif ($ot['Status'] === 'In Progress'): ?>
                                <form method="POST" action="/payrollsystem/employee/ot_attendance" class="inline m-0 p-0">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="ot_id" value="<?= $ot['OvertimeID'] ?>">
                                    <input type="hidden" name="action" value="check_out">
                                    <button type="submit" class="px-4 py-1.5 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white rounded-xl text-xs font-extrabold transition-all shadow-md hover:scale-105">
                                        <i class="fa-solid fa-right-from-bracket mr-1"></i> OT Check-Out
                                    </button>
                                </form>
                            <?php elseif ($ot['Status'] === 'Rejected' && !empty($ot['EmployeeResponse'])): ?>
                                <span class="text-xs text-gray-400 italic truncate max-w-[180px]" title="<?= htmlspecialchars($ot['EmployeeResponse']) ?>"><?= htmlspecialchars($ot['EmployeeResponse']) ?></span>
                            <?php else: ?>
                                <span class="text-gray-500 text-xs">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
