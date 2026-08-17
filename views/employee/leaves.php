<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#180f33] via-[#241447] to-[#121c3b] border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-plane-departure text-secondary"></i>
                    <span>Leave Management</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Leave</span> Portal
            </h1>
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Track your annual leave entitlement, submit time-off requests, and monitor approval statuses.</p>
        </div>
        <button onclick="document.getElementById('applyModal').classList.remove('hidden')" 
                class="px-5 py-3 rounded-2xl bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-extrabold text-xs tracking-wide shadow-xl shadow-violet-600/30 transition-all duration-300 hover:scale-105 active:scale-95 flex items-center gap-2.5 font-outfit">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>APPLY FOR LEAVE</span>
        </button>
    </div>
</div>

<?php if(isset($_SESSION['leave_error'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs font-semibold flex items-center gap-3 backdrop-blur-sm animate-pulse">
        <i class="fa-solid fa-circle-exclamation text-base"></i>
        <span><?= htmlspecialchars($_SESSION['leave_error']) ?></span>
    </div>
    <?php unset($_SESSION['leave_error']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['leave_success'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs font-semibold flex items-center gap-3 backdrop-blur-sm animate-pulse">
        <i class="fa-solid fa-circle-check text-base"></i>
        <span><?= htmlspecialchars($_SESSION['leave_success']) ?></span>
    </div>
    <?php unset($_SESSION['leave_success']); ?>
<?php endif; ?>

<!-- ============ LEAVE BALANCES GRID ============ -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xs uppercase font-extrabold tracking-widest text-violet-300 flex items-center gap-2">
            <i class="fa-solid fa-wallet text-secondary"></i>
            <span>Leave Entitlements & Balances</span>
        </h3>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php foreach($data['leaveBalances'] as $lb): ?>
            <?php 
            $isUnlimited = $lb['DaysAllowed'] >= 999;
            $used = $lb['used'];
            $limit = $lb['DaysAllowed'];
            $pct = $isUnlimited ? 0 : ($limit > 0 ? min(100, round(($used / $limit) * 100)) : 100);
            $exhausted = !$isUnlimited && $used >= $limit;
            ?>
            <div class="card-glass rounded-3xl p-5 flex flex-col justify-between hover:-translate-y-1 transition-all duration-300 border border-violet-500/20 <?= !$lb['is_eligible'] ? 'opacity-60' : '' ?>">
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="text-sm font-extrabold text-white flex items-center gap-2 font-outfit">
                            <i class="fa-solid fa-calendar-check text-cyan-400 text-xs"></i>
                            <span><?= htmlspecialchars($lb['LeaveType']) ?></span>
                        </h4>
                        <?php if($lb['is_paid']): ?>
                            <span class="px-2 py-0.5 text-[9px] uppercase font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 rounded-full">Paid</span>
                        <?php else: ?>
                            <span class="px-2 py-0.5 text-[9px] uppercase font-bold bg-gray-500/20 text-gray-300 border border-gray-500/30 rounded-full">Unpaid</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!$lb['is_eligible']): ?>
                        <p class="text-[11px] text-rose-400 font-medium mb-4 flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <span><?= htmlspecialchars($lb['ineligible_reason']) ?></span>
                        </p>
                    <?php else: ?>
                        <p class="text-[11px] text-gray-400 mb-4">Total days taken this year.</p>
                    <?php endif; ?>
                </div>
                
                <?php if($lb['is_eligible']): ?>
                <div class="pt-2 border-t border-violet-900/30">
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-2xl font-black text-white font-mono"><?= $used ?></span>
                        <span class="text-xs text-gray-400 font-bold">/ <?= $isUnlimited ? '∞' : $limit ?> days</span>
                    </div>
                    <div class="w-full h-2 bg-darker/80 rounded-full overflow-hidden border border-violet-900/40">
                        <div class="h-full rounded-full transition-all duration-500 <?= $exhausted ? 'bg-rose-500' : 'bg-gradient-to-r from-violet-500 via-cyan-400 to-emerald-400' ?>" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============ LEAVE REQUESTS LIST ============ -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xs uppercase font-extrabold tracking-widest text-violet-300 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-secondary"></i>
            <span>My Application History</span>
        </h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if(empty($data['leaveRequests'])): ?>
            <div class="col-span-full p-12 text-center card-glass rounded-3xl">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-surface flex items-center justify-center mb-3 text-violet-400 border border-violet-800/30">
                    <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <p class="font-bold text-white text-base font-outfit">No leave applications found</p>
                <p class="text-xs text-gray-400 mt-1">Submit a leave request using the button above to request time off.</p>
            </div>
        <?php else: ?>
            <?php foreach($data['leaveRequests'] as $lr): ?>
            <div class="card-glass rounded-3xl p-5 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group border border-violet-500/20">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-white flex items-center gap-2 font-outfit">
                            <?= htmlspecialchars($lr['LeaveType']) ?>
                            <?php if($lr['IsPaid']): ?>
                                <span class="px-2 py-0.5 text-[9px] uppercase font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 rounded-full">Paid</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 text-[9px] uppercase font-bold bg-gray-500/20 text-gray-300 border border-gray-500/30 rounded-full">Unpaid</span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div>
                        <?php if($lr['Status'] === 'Approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Approved</span>
                        <?php elseif($lr['Status'] === 'Rejected'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-300 border border-rose-500/30"><span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span> Rejected</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5"></span> Pending</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3.5 bg-darker/60 rounded-2xl border border-violet-900/30 mb-3">
                    <div class="text-center w-1/2 border-r border-violet-900/40">
                        <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-wider mb-0.5">Start Date</p>
                        <p class="font-mono text-xs font-bold text-white"><?= date('M j, Y', strtotime($lr['StartDate'])) ?></p>
                    </div>
                    <div class="text-center w-1/2">
                        <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-wider mb-0.5">End Date</p>
                        <p class="font-mono text-xs font-bold text-white"><?= date('M j, Y', strtotime($lr['EndDate'])) ?></p>
                    </div>
                </div>
                
                <div class="text-xs text-gray-300 mb-1.5 flex justify-between">
                    <span class="text-gray-400">Duration:</span>
                    <strong class="text-cyan-300 font-mono"><?= $lr['days'] ?> Day(s)</strong>
                </div>
                <div class="text-xs text-gray-300 mb-2">
                    <span class="text-gray-400">Reason:</span>
                    <span class="text-gray-200"><?= htmlspecialchars($lr['Reason']) ?></span>
                </div>

                <?php if(!empty($lr['admin_remark'])): ?>
                <div class="mt-3 p-3 bg-violet-950/40 rounded-xl text-xs text-violet-300 border border-violet-800/40">
                    <span class="font-bold text-[10px] uppercase tracking-wider block mb-0.5 text-secondary">Admin Remark:</span>
                    <?= htmlspecialchars($lr['admin_remark']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- ============ APPLY LEAVE MODAL ============ -->
<div id="applyModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-md flex items-center justify-center p-4">
    <div class="card-glass rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-violet-500/30" data-aos="zoom-in">
        <div class="px-6 py-4 border-b border-violet-900/40 flex justify-between items-center bg-surface/90">
            <h3 class="text-base font-extrabold text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-plane-departure text-secondary"></i> Apply for Leave
            </h3>
            <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-surface text-gray-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/employee/leaves" method="POST" class="p-6 space-y-4" id="leaveForm">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="apply">
            
            <div>
                <label for="leave_type_id" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Leave Type</label>
                <select name="leave_type_id" id="leave_type_id" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                    <option value="">Select Leave Type</option>
                    <?php foreach($data['leaveBalances'] as $lb): ?>
                        <?php 
                        $disabled = '';
                        $suffix = $lb['is_paid'] ? 'Paid' : 'Unpaid';
                        if (!$lb['is_eligible']) {
                            $disabled = 'disabled';
                            $suffix .= ' - Ineligible';
                        } elseif ($lb['DaysAllowed'] < 999 && $lb['used'] >= $lb['DaysAllowed']) {
                            $disabled = 'disabled';
                            $suffix .= ' - Exhausted';
                        }
                        ?>
                        <option value="<?= $lb['LeaveTypeID'] ?>" <?= $disabled ?>><?= htmlspecialchars($lb['LeaveType']) ?> (<?= $suffix ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">End Date</label>
                    <input type="date" name="end_date" id="end_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                </div>
            </div>
            <div id="dateError" class="text-rose-400 text-xs mt-1 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> End Date cannot be earlier than Start Date.</div>
            <div id="attendanceError" class="text-rose-400 text-xs mt-1 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> You have already checked in today. Your leave request must start from tomorrow.</div>
            <div id="durationDisplay" class="text-xs font-bold text-cyan-300 mt-2 hidden p-2 bg-darker/60 rounded-xl border border-violet-900/30"><i class="fa-solid fa-clock mr-1"></i> Total Duration: <span id="durationDays" class="font-extrabold text-white font-mono"></span> Day(s)</div>

            <div>
                <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Reason</label>
                <textarea name="reason" id="reason" rows="3" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner placeholder-gray-500" placeholder="Please provide leave reason..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-3 border-t border-violet-900/40">
                <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-bold text-gray-400 hover:text-white bg-surface rounded-xl border border-violet-900/30 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl shadow-lg shadow-violet-600/30 transition-all hover:scale-105 font-outfit">Submit Application</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const dateError = document.getElementById('dateError');
    const attendanceError = document.getElementById('attendanceError');
    const durationDisplay = document.getElementById('durationDisplay');
    const durationDays = document.getElementById('durationDays');
    const leaveForm = document.getElementById('leaveForm');
    const submitBtn = leaveForm.querySelector('button[type="submit"]');
    
    // Add conflict error element
    const conflictError = document.createElement('div');
    conflictError.id = 'conflictError';
    conflictError.className = 'text-rose-400 text-xs mt-1 hidden';
    conflictError.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> <span id="conflictMessage"></span>';
    dateError.parentNode.insertBefore(conflictError, dateError.nextSibling);
    
    const hasClockedInToday = <?= json_encode($data['hasClockedInToday']) ?>;
    
    const now = new Date();
    const todayStr = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = new Date(tomorrow.getTime() - (tomorrow.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
    
    startDate.min = tomorrowStr;
    endDate.min = tomorrowStr;

    async function checkConflicts() {
        if (!startDate.value) return false;
        
        try {
            const res = await fetch('/payrollsystem/api/validate_conflict', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    start_date: startDate.value,
                    end_date: endDate.value || startDate.value
                })
            });
            const data = await res.json();
            if (data.has_conflict) {
                document.getElementById('conflictMessage').innerText = data.messages[0];
                conflictError.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return true;
            } else {
                conflictError.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return false;
            }
        } catch (e) {
            console.error(e);
            return false;
        }
    }

    async function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            let hasError = false;
            dateError.classList.add('hidden');
            attendanceError.classList.add('hidden');
            
            if (hasClockedInToday && startDate.value === todayStr) {
                attendanceError.classList.remove('hidden');
                hasError = true;
            }

            if (end < start) {
                dateError.classList.remove('hidden');
                hasError = true;
            }
            
            if (hasError) {
                durationDisplay.classList.add('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                durationDays.innerText = diffDays;
                durationDisplay.classList.remove('hidden');
                
                await checkConflicts();
            }
        } else {
            dateError.classList.add('hidden');
            attendanceError.classList.add('hidden');
            conflictError.classList.add('hidden');
            durationDisplay.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    startDate.addEventListener('change', calculateDuration);
    endDate.addEventListener('change', calculateDuration);

    leaveForm.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }
        
        let hasError = false;
        
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (hasClockedInToday && startDate.value === todayStr) {
                attendanceError.classList.remove('hidden');
                hasError = true;
            }
            
            if (end < start) {
                dateError.classList.remove('hidden');
                hasError = true;
            }
        }
        
        if (hasError) {
            e.preventDefault();
            return false;
        }
        
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Submitting...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
    });
});
</script>
