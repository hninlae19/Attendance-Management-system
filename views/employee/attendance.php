<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#180f33] via-[#241447] to-[#121c3b] border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
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
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Review your daily check-in logs, total working hours, and submit attendance correction requests.</p>
        </div>
        <div class="px-4 py-2.5 rounded-2xl bg-surface/90 border border-violet-700/30 text-center shadow-lg backdrop-blur-md">
            <div class="text-[10px] uppercase tracking-widest text-violet-400 font-bold">Total Logs</div>
            <div class="text-xl font-extrabold text-white font-mono"><?= count($data['myAttendance'] ?? []) ?> Days</div>
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

<div class="mb-8" x-data="{ tab: 'history' }">
    <!-- Tabs Header -->
    <div class="border-b border-violet-900/40 pb-px mb-6">
        <ul class="flex flex-wrap gap-2 text-xs font-bold">
            <li>
                <button @click="tab = 'history'" 
                        :class="tab === 'history' ? 'bg-violet-600/30 text-cyan-300 border-violet-500/50 shadow-lg shadow-violet-950/40' : 'text-gray-400 hover:text-white bg-surface/60 border-transparent'" 
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl border transition-all duration-300 font-outfit tracking-wide">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    <span>Attendance Records</span>
                </button>
            </li>
            <li>
                <button @click="tab = 'corrections'" 
                        :class="tab === 'corrections' ? 'bg-violet-600/30 text-cyan-300 border-violet-500/50 shadow-lg shadow-violet-950/40' : 'text-gray-400 hover:text-white bg-surface/60 border-transparent'" 
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl border transition-all duration-300 font-outfit tracking-wide">
                    <i class="fa-solid fa-code-pull-request text-sm"></i>
                    <span>Correction Requests</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- History Tab -->
    <div x-show="tab === 'history'" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php if(empty($data['myAttendance'])): ?>
                <div class="col-span-full p-12 text-center card-glass rounded-3xl">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-surface flex items-center justify-center mb-3 text-violet-400 border border-violet-800/30">
                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                    </div>
                    <p class="font-bold text-white text-base font-outfit">No attendance records found</p>
                    <p class="text-xs text-gray-400 mt-1">Daily clock-in records will appear here as you log attendance.</p>
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
                            <h3 class="text-base font-extrabold text-white flex items-center font-outfit">
                                <?php if($att['Status'] === 'Present'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Present
                                    </span>
                                <?php elseif($att['Status'] === 'Absent'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> Absent
                                    </span>
                                <?php elseif($att['Status'] === 'Late'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Late Check-In
                                    </span>
                                <?php elseif($att['Status'] === 'Half Day'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-500/15 text-yellow-300 border border-yellow-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1.5"></span> Half Day
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/15 text-blue-300 border border-blue-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-1.5"></span> <?= htmlspecialchars($att['Status']) ?>
                                    </span>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <button onclick="requestCorrection(<?= $att['AttendanceID'] ?>, '<?= $att['AttendanceDate'] ?>', '<?= $att['CheckInTime'] ?? '' ?>', '<?= $att['CheckOutTime'] ?? '' ?>')" 
                                class="w-8 h-8 rounded-xl bg-surface text-gray-400 hover:text-cyan-300 hover:bg-violet-600/30 border border-violet-800/30 transition-colors flex items-center justify-center shadow-sm"
                                title="Request Correction">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-3.5 bg-darker/60 rounded-2xl border border-violet-900/30">
                        <div class="text-center w-1/2 border-r border-violet-900/40">
                            <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-wider mb-0.5">Check IN</p>
                            <p class="font-mono text-sm font-extrabold text-emerald-300"><?= $att['CheckInTime'] ? date('h:i A', strtotime($att['CheckInTime'])) : '--:--' ?></p>
                        </div>
                        <div class="text-center w-1/2">
                            <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-wider mb-0.5">Check OUT</p>
                            <p class="font-mono text-sm font-extrabold text-cyan-300"><?= $att['CheckOutTime'] ? date('h:i A', strtotime($att['CheckOutTime'])) : '--:--' ?></p>
                        </div>
                    </div>
                    
                    <?php if(!empty($att['working_hours']) || !empty($att['ot_hours'])): ?>
                        <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-violet-900/30">
                            <span class="text-gray-400">Hours: <strong class="text-white"><?= $att['working_hours'] ?? '0' ?>h</strong></span>
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

    <!-- Corrections Tab -->
    <div x-show="tab === 'corrections'" class="pt-2" x-cloak>
        <div class="card-glass rounded-3xl overflow-hidden border border-violet-500/20">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-surface/80 text-violet-300/80 border-b border-violet-900/40">
                        <tr>
                            <th scope="col" class="px-6 py-4">Date</th>
                            <th scope="col" class="px-6 py-4">Requested In</th>
                            <th scope="col" class="px-6 py-4">Requested Out</th>
                            <th scope="col" class="px-6 py-4">Reason</th>
                            <th scope="col" class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-violet-900/30">
                        <?php if(empty($data['myCorrections'])): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="w-12 h-12 mx-auto bg-surface rounded-2xl border border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                        <i class="fa-solid fa-file-circle-check text-xl"></i>
                                    </div>
                                    <p class="font-semibold text-gray-300">No correction requests found</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Submitted attendance adjustment requests will display here.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['myCorrections'] as $corr): ?>
                            <tr class="hover:bg-violet-950/20 transition-colors">
                                <td class="px-6 py-4 font-bold text-white"><?= date('D, M j, Y', strtotime($corr['AttendanceDate'])) ?></td>
                                <td class="px-6 py-4 font-mono text-emerald-300"><?= $corr['corrected_check_in'] ? date('h:i A', strtotime($corr['corrected_check_in'])) : '—' ?></td>
                                <td class="px-6 py-4 font-mono text-cyan-300"><?= $corr['corrected_check_out'] ? date('h:i A', strtotime($corr['corrected_check_out'])) : '—' ?></td>
                                <td class="px-6 py-4 text-gray-300 max-w-[220px] truncate" title="<?= htmlspecialchars($corr['Reason']) ?>"><?= htmlspecialchars($corr['Reason']) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($corr['Status'] === 'Approved'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Approved</span>
                                    <?php elseif($corr['Status'] === 'Rejected'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30"><span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> Rejected</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Correction Modal (Dark Glassmorphic) -->
<div id="correctionModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-md flex items-center justify-center p-4">
    <div class="card-glass rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-violet-500/30" data-aos="zoom-in">
        <div class="px-6 py-4 border-b border-violet-900/40 flex justify-between items-center bg-surface/90">
            <h3 class="text-base font-extrabold text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-pen-to-square text-secondary"></i> Request Attendance Correction
            </h3>
            <button type="button" onclick="document.getElementById('correctionModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-surface text-gray-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/employee/attendance" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="correction">
            <input type="hidden" name="attendance_id" id="correction_attendance_id">
            
            <div class="p-3 bg-darker/60 rounded-xl border border-violet-900/30 text-xs text-gray-300">
                Attendance Date: <span id="correction_date_display" class="font-extrabold text-cyan-300 font-mono ml-1"></span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="corrected_check_in" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Correct Check-In</label>
                    <input type="time" name="corrected_check_in" id="corrected_check_in" class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                </div>
                <div>
                    <label for="corrected_check_out" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Correct Check-Out</label>
                    <input type="time" name="corrected_check_out" id="corrected_check_out" class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                </div>
            </div>

            <div>
                <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Reason for Correction</label>
                <textarea name="reason" id="reason" rows="3" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner placeholder-gray-500" placeholder="e.g., Forgot to check out, biometric scanner error..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-3 border-t border-violet-900/40">
                <button type="button" onclick="document.getElementById('correctionModal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-bold text-gray-400 hover:text-white bg-surface rounded-xl border border-violet-900/30 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl shadow-lg shadow-violet-600/30 transition-all hover:scale-105">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    function requestCorrection(id, date, checkIn, checkOut) {
        document.getElementById('correction_attendance_id').value = id;
        document.getElementById('correction_date_display').innerText = date;
        document.getElementById('corrected_check_in').value = checkIn;
        document.getElementById('corrected_check_out').value = checkOut;
        document.getElementById('correctionModal').classList.remove('hidden');
    }
</script>
