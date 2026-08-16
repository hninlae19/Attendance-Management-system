<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Application</h1>
    <button onclick="document.getElementById('applyModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Apply Leave
    </button>
</div>

<?php if(isset($_SESSION['leave_error'])): ?>
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg dark:bg-red-900/30 dark:border-red-600">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation text-red-500 dark:text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 dark:text-red-300 font-medium">
                    <?= htmlspecialchars($_SESSION['leave_error']) ?>
                </p>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['leave_error']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['leave_success'])): ?>
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg dark:bg-green-900/30 dark:border-green-600">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-check-circle text-green-500 dark:text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 dark:text-green-300 font-medium">
                    <?= htmlspecialchars($_SESSION['leave_success']) ?>
                </p>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['leave_success']); ?>
<?php endif; ?>

<div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <?php foreach($data['leaveBalances'] as $lb): ?>
        <?php 
        $isUnlimited = $lb['DaysAllowed'] >= 999;
        $used = $lb['used'];
        $limit = $lb['DaysAllowed'];
        $pct = $isUnlimited ? 0 : ($limit > 0 ? min(100, round(($used / $limit) * 100)) : 100);
        $exhausted = !$isUnlimited && $used >= $limit;
        
        $cardClass = $lb['is_eligible'] ? 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700' : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 opacity-60';
        ?>
        <div class="<?= $cardClass ?> rounded-2xl shadow-sm border p-5 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-md font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa-solid fa-calendar-check text-primary mr-2"></i> <?= htmlspecialchars($lb['LeaveType']) ?>
                    </h3>
                    <?php if($lb['is_paid']): ?>
                        <span class="px-2 py-0.5 text-[10px] uppercase font-bold bg-green-100 text-green-800 rounded-full dark:bg-green-900/50 dark:text-green-300">Paid</span>
                    <?php else: ?>
                        <span class="px-2 py-0.5 text-[10px] uppercase font-bold bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300">Unpaid</span>
                    <?php endif; ?>
                </div>
                
                <?php if(!$lb['is_eligible']): ?>
                    <p class="text-xs text-red-500 font-medium mb-4"><i class="fa-solid fa-lock text-xs mr-1"></i> <?= htmlspecialchars($lb['ineligible_reason']) ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Total used this year.</p>
                <?php endif; ?>
            </div>
            
            <?php if($lb['is_eligible']): ?>
            <div>
                <div class="flex justify-between items-end mb-1">
                    <span class="text-2xl font-black text-gray-900 dark:text-white"><?= $used ?></span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">/ <?= $isUnlimited ? '∞' : $limit ?> days</span>
                </div>
                <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full <?= $exhausted ? 'bg-red-500' : 'bg-primary' ?>" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if(empty($data['leaveRequests'])): ?>
        <div class="col-span-full p-8 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">No leave applications found.</p>
        </div>
    <?php else: ?>
        <?php foreach($data['leaveRequests'] as $lr): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <?= htmlspecialchars($lr['LeaveType']) ?>
                        <?php if($lr['IsPaid']): ?>
                            <span class="ml-2 px-2 py-0.5 text-[10px] uppercase font-bold bg-green-100 text-green-800 rounded-full dark:bg-green-900/50 dark:text-green-300">Paid</span>
                        <?php else: ?>
                            <span class="ml-2 px-2 py-0.5 text-[10px] uppercase font-bold bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300">Unpaid</span>
                        <?php endif; ?>
                    </h3>
                </div>
                <div>
                    <?php if($lr['Status'] === 'Approved'): ?>
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-xl dark:bg-green-900/30 dark:text-green-400"><i class="fa-solid fa-check mr-1"></i> Approved</span>
                    <?php elseif($lr['Status'] === 'Rejected'): ?>
                        <span class="px-2.5 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-xl dark:bg-red-900/30 dark:text-red-400"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                    <?php else: ?>
                        <span class="px-2.5 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-xl dark:bg-yellow-900/30 dark:text-yellow-400"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl mb-4">
                <div class="text-center w-1/2 border-r border-gray-200 dark:border-gray-700">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">From</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-300"><?= date('M j', strtotime($lr['StartDate'])) ?></p>
                </div>
                <div class="text-center w-1/2">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">To</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-300"><?= date('M j, Y', strtotime($lr['EndDate'])) ?></p>
                </div>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                <span class="font-medium text-gray-900 dark:text-white">Duration:</span> <?= $lr['days'] ?> Day(s)
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium text-gray-900 dark:text-white">Reason:</span> <?= htmlspecialchars($lr['Reason']) ?>
            </div>
            <?php if(!empty($lr['admin_remark'])): ?>
            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-blue-800 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                <span class="font-bold text-[10px] uppercase tracking-wider block mb-1">Admin Remark:</span>
                <?= htmlspecialchars($lr['admin_remark']) ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Apply Modal -->
<div id="applyModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Apply for Leave</h3>
            <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/employee/leaves" method="POST" class="p-6 space-y-4" id="leaveForm">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="apply">
            
            <div>
                <label for="leave_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                <select name="leave_type_id" id="leave_type_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
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
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>
            <div id="dateError" class="text-red-500 text-xs mt-1 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> End Date cannot be earlier than Start Date.</div>
            <div id="attendanceError" class="text-red-500 text-xs mt-1 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> You have already checked in today. Your leave request must start from tomorrow.</div>
            <div id="durationDisplay" class="text-sm font-medium text-primary mt-2 hidden"><i class="fa-solid fa-clock mr-1"></i> Duration: <span id="durationDays" class="font-bold"></span> Day(s)</div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason</label>
                <textarea name="reason" id="reason" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Please provide details..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Submit Application</button>
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
    conflictError.className = 'text-red-500 text-xs mt-1 hidden';
    conflictError.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> <span id="conflictMessage"></span>';
    dateError.parentNode.insertBefore(conflictError, dateError.nextSibling);
    
    const hasClockedInToday = <?= json_encode($data['hasClockedInToday']) ?>;
    
    // Get today's date and tomorrow's date in YYYY-MM-DD local timezone
    const now = new Date();
    const todayStr = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = new Date(tomorrow.getTime() - (tomorrow.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
    
    // Always require at least 1 day in advance
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
                // Calculate days inclusive of start and end
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                durationDays.innerText = diffDays;
                durationDisplay.classList.remove('hidden');
                
                // Now check conflicts
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
        
        // Disable button to prevent double submit
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Submitting...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
    });
});
</script>
