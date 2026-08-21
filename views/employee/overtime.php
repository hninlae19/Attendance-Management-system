<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Overtime Schedule</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Overtime</span> Assignments
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Review scheduled overtime sessions, accept or decline assignments, and clock in for OT shifts.</p>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-3" data-aos="fade-up">
        <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
        <span><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3" data-aos="fade-up">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <span><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<!-- ============ SUMMARY STATS ============ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8" data-aos="fade-up" data-aos-delay="50">
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900 dark:text-white font-mono"><?= $data['upcoming'] ?></p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Upcoming Shifts</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900 dark:text-white font-mono"><?= $data['totalHours'] ?> <span class="text-xs font-normal text-slate-400">h</span></p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total OT Hours</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono"><?= number_format($data['totalEarnings']) ?></p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Earnings (MMK)</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900 dark:text-white font-mono"><?= $data['pending'] ?></p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Action</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ OVERTIME ASSIGNMENTS TABLE ============ -->
<div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="p-4 px-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
        <h3 class="font-extrabold text-slate-900 dark:text-white text-sm flex items-center gap-2 font-outfit">
            <i class="fa-solid fa-clipboard-list text-amber-500"></i> My Assigned Overtime Records
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Scheduled Time</th>
                    <th class="px-6 py-4">Hours</th>
                    <th class="px-6 py-4">Multiplier</th>
                    <th class="px-6 py-4">Total Amount</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if (empty($data['overtimes'])): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-2 text-indigo-500">
                                <i class="fa-solid fa-mug-hot text-xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No overtime assignments found</p>
                            <p class="text-xs text-slate-400 mt-0.5">Assigned overtime shifts from your supervisor will appear here.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['overtimes'] as $ot): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-xs"><?= date('D, M j, Y', strtotime($ot['OvertimeDate'])) ?></td>
                        <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-300 font-medium">
                            <?php if ($ot['StartTime'] && $ot['EndTime']): ?>
                                <?= date('h:i A', strtotime($ot['StartTime'])) ?> - <?= date('h:i A', strtotime($ot['EndTime'])) ?>
                            <?php else: ?>
                                <span class="text-slate-400 italic">Unspecified</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-amber-600 dark:text-amber-400 font-mono text-xs">
                                <?= $ot['TotalHours'] ?> <span class="text-xs text-slate-400 font-normal">hrs</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-mono text-xs"><?= number_format($ot['RateMultiplier'], 1) ?>x</td>
                        <td class="px-6 py-4 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-xs"><?= number_format($ot['OTAmount'], 2) ?> <span class="text-xs text-slate-400 font-normal">MMK</span></td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'Pending' => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
                                    'Accepted' => 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800',
                                    'Rejected' => 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800',
                                    'InProgress' => 'bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800',
                                    'Completed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
                                    'NoOT' => 'bg-slate-100 text-slate-700 border border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600'
                                ];
                                $colorClass = $statusColors[$ot['Status']] ?? 'bg-slate-100 text-slate-700 border border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600';
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= $colorClass ?>">
                                <?php if($ot['Status'] == 'InProgress') echo '<i class="fa-solid fa-spinner fa-spin mr-1"></i> In Progress'; elseif($ot['Status'] == 'NoOT') echo 'No OT'; else echo htmlspecialchars($ot['Status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($ot['Status'] === 'Pending'): ?>
                                <form method="POST" action="/payrollsystem/employee/overtime" class="inline-flex gap-2">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <button type="submit" name="action" value="reject" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-300 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 font-bold text-xs transition-all shadow-sm">Reject</button>
                                    <button type="submit" name="action" value="accept" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-300 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 font-bold text-xs transition-all shadow-sm">Accept</button>
                                </form>
                            <?php elseif (in_array($ot['Status'], ['Accepted', 'Approved', 'Assigned'])): ?>
                                <?php 
                                    $rawStart = trim($ot['StartTime'] ?? '');
                                    $rawEnd = trim($ot['EndTime'] ?? '');
                                    $startTimeTime = (strpos($rawStart, '-') !== false || strpos($rawStart, ' ') !== false) ? strtotime($rawStart) : strtotime($ot['OvertimeDate'] . ' ' . $rawStart);
                                    $endTimeTime = (strpos($rawEnd, '-') !== false || strpos($rawEnd, ' ') !== false) ? strtotime($rawEnd) : strtotime($ot['OvertimeDate'] . ' ' . $rawEnd);
                                    if ($endTimeTime <= $startTimeTime) {
                                        $endTimeTime += 86400; // Overnight overtime
                                    }
                                    $now = time();
                                    $isWithinWindow = ($now >= ($startTimeTime - 600) && $now <= $endTimeTime);
                                    $isPast = ($now > $endTimeTime);
                                ?>
                                <form method="POST" action="/payrollsystem/employee/overtime" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <?php if ($isWithinWindow): ?>
                                        <button type="submit" name="action" value="checkin" class="px-4 py-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-extrabold text-xs transition-all shadow-md shadow-indigo-500/25 hover:scale-105">Check In</button>
                                    <?php elseif ($isPast): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-semibold">Shift Ended</span>
                                    <?php else: ?>
                                        <button type="button" class="px-4 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 dark:bg-slate-800 dark:text-slate-500 dark:border-slate-700 font-bold text-xs cursor-not-allowed" title="Check-in available at <?= date('h:i A', $startTimeTime - 600) ?>">Check In</button>
                                    <?php endif; ?>
                                </form>
                            <?php elseif ($ot['Status'] === 'InProgress'): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800 text-xs font-bold font-mono animate-pulse">
                                    <i class="fa-solid fa-clock text-amber-500"></i> Active Shift
                                </span>
                            <?php else: ?>
                                <span class="text-xs text-slate-400 font-mono italic">Processed</span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
