<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-fingerprint text-secondary"></i>
                    <span>Time & Attendance Log</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Attendance</span> History
            </h1>
            <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm mt-1">Review your daily check-in logs and total working hours.</p>
        </div>
        <div class="px-4 py-2.5 rounded-2xl bg-surface/90 border border-gray-300 dark:border-violet-700/30 text-center shadow-lg backdrop-blur-md">
            <div class="text-[10px] uppercase tracking-widest text-violet-400 font-bold">Total Logs</div>
            <div class="text-xl font-extrabold text-gray-900 dark:text-white font-mono"><?= count($data['myAttendance'] ?? []) ?> Days</div>
        </div>
    </div>
</div>

<?php if(!$data['is_working_day']): ?>
<div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-200 text-xs flex items-center gap-3 backdrop-blur-md" role="alert">
    <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center flex-shrink-0 text-base">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <div>
        <strong class="font-bold text-amber-300">Non-Working Day Notice:</strong> Attendance recording is disabled today (weekend or scheduled public holiday).
    </div>
</div>
<?php endif; ?>

<div class="mb-8">
    <!-- History Tab -->
    <div class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php if(empty($data['myAttendance'])): ?>
                <div class="col-span-full p-12 text-center card-glass rounded-3xl">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center mb-3 text-violet-400 border border-violet-800/30">
                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                    </div>
                    <p class="font-bold text-gray-900 dark:text-white text-base font-outfit">No attendance records found</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Daily clock-in records will appear here as you log attendance.</p>
                </div>
            <?php else: ?>
                <?php foreach($data['myAttendance'] as $att): ?>
                <div class="card-glass rounded-3xl p-5 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group border border-violet-500/20">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-violet-400 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar text-secondary"></i>
                                <?= date('D, M j, Y', strtotime($att['AttendanceDate'])) ?>
                            </p>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center font-outfit">
                                <?php if($att['Status'] === 'Present'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Present
                                    </span>
                                <?php elseif($att['Status'] === 'Full-Day Absence' || $att['Status'] === 'Absent' || $att['Status'] === 'Full-day absent'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> Full-day absent
                                    </span>
                                <?php elseif($att['Status'] === 'Late'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Late Check-In
                                    </span>
                                <?php elseif($att['Status'] === 'Half-Day Absence' || $att['Status'] === 'Half Day' || $att['Status'] === 'Half-day absent'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-500/15 text-yellow-300 border border-yellow-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1.5"></span> Half-day absent
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/15 text-blue-300 border border-blue-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-1.5"></span> <?= htmlspecialchars($att['Status']) ?>
                                    </span>
                                <?php endif; ?>
                            </h3>
                        </div>

                    </div>
                    
                    <div class="flex items-center justify-between p-3.5 bg-darker/60 rounded-2xl border border-violet-900/30">
                        <div class="text-center w-1/2 border-r border-violet-900/40">
                            <p class="text-[10px] text-gray-600 dark:text-gray-400 uppercase font-extrabold tracking-wider mb-0.5">Check IN</p>
                            <p class="font-mono text-sm font-extrabold text-emerald-300"><?= $att['CheckInTime'] ? date('h:i A', strtotime($att['CheckInTime'])) : '--:--' ?></p>
                        </div>
                        <div class="text-center w-1/2">
                            <p class="text-[10px] text-gray-600 dark:text-gray-400 uppercase font-extrabold tracking-wider mb-0.5">Check OUT</p>
                            <p class="font-mono text-sm font-extrabold text-cyan-300"><?= $att['CheckOutTime'] ? date('h:i A', strtotime($att['CheckOutTime'])) : '--:--' ?></p>
                        </div>
                    </div>
                    
                    <?php if(!empty($att['working_hours']) || !empty($att['ot_hours'])): ?>
                        <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-violet-900/30">
                            <span class="text-gray-600 dark:text-gray-400">Hours: <strong class="text-gray-900 dark:text-white"><?= $att['working_hours'] ?? '0' ?>h</strong></span>
                            <?php if(!empty($att['ot_hours'])): ?>
                                <span class="text-amber-400 font-bold">OT: +<?= $att['ot_hours'] ?>h</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>


