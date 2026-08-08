<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overtime Application</h1>
    <button onclick="document.getElementById('applyModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Apply Overtime
    </button>
</div>

<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 dark:border-gray-700">Admin Assigned Overtime</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if(empty($data['myAssignments'])): ?>
            <div class="col-span-full p-6 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 text-sm">No overtime assigned by admin yet.</p>
            </div>
        <?php else: ?>
            <?php foreach($data['myAssignments'] as $oa): ?>
            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl shadow-sm border border-indigo-100 dark:border-indigo-800 p-5 relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300">
                            <i class="fa-solid fa-clipboard-check mr-2"></i> <?= htmlspecialchars($oa['title']) ?>
                        </h3>
                        <p class="text-xs text-indigo-500 dark:text-indigo-400 mt-1">Assigned for: <?= date('M j, Y', strtotime($oa['date'])) ?></p>
                    </div>
                    <div>
                        <?php if($oa['status'] === 'Active'): ?>
                            <span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-xl dark:bg-green-900/30 dark:text-green-400">Assigned</span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-xl dark:bg-red-900/30 dark:text-red-400"><i class="fa-solid fa-ban mr-1"></i> Cancelled</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex flex-col p-3 bg-white dark:bg-gray-800 rounded-xl mb-4 border border-indigo-100/50 dark:border-indigo-800/50">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Time</p>
                    <p class="font-mono text-sm font-semibold text-gray-800 dark:text-gray-300 mt-1"><?= date('h:i A', strtotime($oa['start_time'])) ?> - <?= date('h:i A', strtotime($oa['end_time'])) ?></p>
                </div>
                
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-gray-900 dark:text-white">Task:</span> <?= htmlspecialchars($oa['reason']) ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 dark:border-gray-700">My Overtime Requests</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if(empty($data['myOvertime'])): ?>
        <div class="col-span-full p-8 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">No overtime applications found.</p>
        </div>
    <?php else: ?>
        <?php foreach($data['myOvertime'] as $ot): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <?php if($ot['type'] === 'Working Day'): ?>
                            <span class="text-blue-500"><i class="fa-solid fa-briefcase mr-2"></i> Working Day</span>
                        <?php elseif($ot['type'] === 'Weekend'): ?>
                            <span class="text-purple-500"><i class="fa-solid fa-umbrella-beach mr-2"></i> Weekend</span>
                        <?php else: ?>
                            <span class="text-orange-500"><i class="fa-solid fa-gifts mr-2"></i> Holiday</span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Applied: <?= date('M j, Y', strtotime($ot['created_at'])) ?></p>
                </div>
                <div>
                    <?php if($ot['status'] === 'Approved'): ?>
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-xl dark:bg-green-900/30 dark:text-green-400"><i class="fa-solid fa-check mr-1"></i> Approved</span>
                    <?php elseif($ot['status'] === 'Rejected'): ?>
                        <span class="px-2.5 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-xl dark:bg-red-900/30 dark:text-red-400"><i class="fa-solid fa-xmark mr-1"></i> Rejected</span>
                    <?php else: ?>
                        <span class="px-2.5 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-xl dark:bg-yellow-900/30 dark:text-yellow-400"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="flex flex-col p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl mb-4">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Date & Time</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-300"><?= date('M j, Y', strtotime($ot['date'])) ?></p>
                <p class="font-mono text-xs text-gray-600 dark:text-gray-400 mt-1"><?= date('h:i A', strtotime($ot['start_time'])) ?> - <?= date('h:i A', strtotime($ot['end_time'])) ?></p>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                <span class="font-medium text-gray-900 dark:text-white">Duration:</span> <?= $ot['hours'] ?> Hrs
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium text-gray-900 dark:text-white">Reason:</span> <?= htmlspecialchars($ot['reason']) ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Apply Modal -->
<div id="applyModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Apply for Overtime</h3>
            <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="ot-form" action="/payrollsystem/employee/overtime" method="POST" class="p-6 space-y-4">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="apply">
            
            <div id="ot-error-msg" class="hidden p-3 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800 mb-4" role="alert"></div>
            
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date" name="date" id="ot_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                    <input type="time" name="start_time" id="ot_start_time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                    <input type="time" name="end_time" id="ot_end_time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason/Task</label>
                <textarea name="reason" id="ot_reason" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Please provide details about the overtime work..."></textarea>
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
    const otForm = document.getElementById('ot-form');
    const dateInput = document.getElementById('ot_date');
    const startInput = document.getElementById('ot_start_time');
    const endInput = document.getElementById('ot_end_time');
    const submitBtn = otForm.querySelector('button[type="submit"]');
    const errorDiv = document.getElementById('ot-error-msg');

    // Add conflict error element
    const conflictError = document.createElement('div');
    conflictError.id = 'conflictError';
    conflictError.className = 'text-red-500 text-xs mt-1 hidden';
    conflictError.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> <span id="conflictMessage"></span>';
    endInput.parentNode.parentNode.parentNode.insertBefore(conflictError, endInput.parentNode.parentNode.nextSibling);

    async function checkConflicts() {
        if (!dateInput.value || !startInput.value || !endInput.value) return false;
        
        try {
            const res = await fetch('/payrollsystem/api/validate_conflict', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    date: dateInput.value,
                    start_time: startInput.value,
                    end_time: endInput.value
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

    dateInput.addEventListener('change', checkConflicts);
    startInput.addEventListener('change', checkConflicts);
    endInput.addEventListener('change', checkConflicts);

    otForm.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }

        errorDiv.classList.add('hidden');
        errorDiv.innerText = '';

        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const today = new Date();
        today.setHours(0,0,0,0);

        if (selectedDate < today) {
            e.preventDefault();
            errorDiv.innerText = 'Overtime request is only allowed for today or future dates.';
            errorDiv.classList.remove('hidden');
            return;
        }

        // Time validation
        const startTime = new Date('1970-01-01T' + startInput.value + ':00');
        const endTime = new Date('1970-01-01T' + endInput.value + ':00');

        if (endTime <= startTime) {
            e.preventDefault();
            errorDiv.innerText = 'End time must be after start time.';
            errorDiv.classList.remove('hidden');
            return;
        }

        const hours = (endTime - startTime) / (1000 * 60 * 60);
        if (hours > 4) {
            e.preventDefault();
            errorDiv.innerText = 'Overtime cannot exceed 4 hours per day.';
            errorDiv.classList.remove('hidden');
            return;
        }
    });

    // Display server-side errors
    <?php if(isset($_SESSION['ot_error'])): ?>
        document.getElementById('applyModal').classList.remove('hidden');
        errorDiv.innerText = <?= json_encode($_SESSION['ot_error']) ?>;
        errorDiv.classList.remove('hidden');
        <?php unset($_SESSION['ot_error']); ?>
    <?php endif; ?>
});
</script>
