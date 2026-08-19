<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
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
            <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm mt-1">Review scheduled overtime sessions, accept or decline assignments, and clock in for OT shifts.</p>
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
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-200 dark:border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white font-mono"><?= $data['upcoming'] ?></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-cyan-300/80">Upcoming Shifts</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-200 dark:border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-violet-500/20 text-violet-300 border border-violet-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white font-mono"><?= $data['totalHours'] ?> <span class="text-xs font-normal text-gray-600 dark:text-gray-400">h</span></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-violet-300/80">Total OT Hours</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-200 dark:border-violet-500/20 hover:-translate-y-1 transition-all">
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

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-200 dark:border-violet-500/20 hover:-translate-y-1 transition-all">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white font-mono"><?= $data['pending'] ?></p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-300/80">Pending Action</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ OVERTIME ASSIGNMENTS TABLE ============ -->
<div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-200 dark:border-violet-500/20 mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="p-4 px-6 border-b border-violet-900/40 flex justify-between items-center bg-gray-50 dark:bg-gray-800/60">
        <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2 font-outfit">
            <i class="fa-solid fa-clipboard-list text-amber-400"></i> My Assigned Overtime Records
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-900/80 text-violet-700 dark:text-violet-300/80 border-b border-gray-200 dark:border-violet-900/40">
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
            <tbody class="divide-y divide-violet-900/30">
                <?php if (empty($data['overtimes'])): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                <i class="fa-solid fa-mug-hot text-xl"></i>
                            </div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">No overtime assignments found</p>
                            <p class="text-xs text-gray-500 mt-0.5">Assigned overtime shifts from your supervisor will appear here.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['overtimes'] as $ot): ?>
                    <tr class="hover:bg-violet-950/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?= date('D, M j, Y', strtotime($ot['OvertimeDate'])) ?></td>
                        <td class="px-6 py-4 text-xs text-gray-700 dark:text-gray-300 font-medium">
                            <?php if ($ot['StartTime'] && $ot['EndTime']): ?>
                                <?= date('h:i A', strtotime($ot['StartTime'])) ?> - <?= date('h:i A', strtotime($ot['EndTime'])) ?>
                            <?php else: ?>
                                <span class="text-gray-500 italic">Unspecified</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-amber-400 font-mono">
                                <?= $ot['TotalHours'] ?> <span class="text-xs text-gray-500 font-normal">hrs</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300 font-mono"><?= number_format($ot['RateMultiplier'], 1) ?>x</td>
                        <td class="px-6 py-4 font-extrabold text-emerald-400 font-mono"><?= number_format($ot['OTAmount'], 2) ?> <span class="text-xs text-gray-500 font-normal">MMK</span></td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'Pending' => 'bg-amber-500/15 text-amber-300 border border-amber-500/30',
                                    'Accepted' => 'bg-blue-500/15 text-blue-300 border border-blue-500/30',
                                    'Rejected' => 'bg-rose-500/15 text-rose-300 border border-rose-500/30',
                                    'InProgress' => 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30',
                                    'Completed' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30',
                                    'NoOT' => 'bg-gray-500/15 text-gray-700 dark:text-gray-300 border border-gray-500/30'
                                ];
                                $colorClass = $statusColors[$ot['Status']] ?? 'bg-gray-500/15 text-gray-700 dark:text-gray-300 border border-gray-500/30';
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= $colorClass ?>">
                                <?php if($ot['Status'] == 'InProgress') echo '<i class="fa-solid fa-spinner fa-spin mr-1"></i> In Progress'; else echo $ot['Status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($ot['Status'] === 'Pending'): ?>
                                <form method="POST" action="/payrollsystem/employee/overtime" class="inline-flex gap-2">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <button type="submit" name="action" value="reject" class="px-3 py-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 font-bold text-xs transition-all hover:scale-105">Reject</button>
                                    <button type="submit" name="action" value="accept" class="px-3 py-1.5 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 font-bold text-xs transition-all hover:scale-105">Accept</button>
                                </form>
                            <?php elseif ($ot['Status'] === 'Accepted'): ?>
                                <?php 
                                    $startTimeTime = strtotime($ot['OvertimeDate'] . ' ' . $ot['StartTime']);
                                    $endTimeTime = strtotime($ot['OvertimeDate'] . ' ' . $ot['EndTime']);
                                    $now = time();
                                    $isWithinWindow = ($now >= ($startTimeTime - 600) && $now < $endTimeTime);
                                ?>
                                <form method="POST" action="/payrollsystem/employee/overtime" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <?php if ($isWithinWindow): ?>
                                        <button type="submit" name="action" value="checkin" class="px-4 py-1.5 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 text-white font-bold text-xs transition-all hover:scale-105 shadow-md shadow-blue-500/25">Check In</button>
                                    <?php else: ?>
                                        <button type="button" class="px-4 py-1.5 rounded-lg bg-gray-500/20 text-gray-600 dark:text-gray-400 border border-gray-500/30 font-bold text-xs cursor-not-allowed" title="Check-in available 10 mins before start time">Check In</button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-gray-500 font-mono italic">Processed</span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
