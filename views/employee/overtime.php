<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overtime Application</h1>
    <button onclick="document.getElementById('applyModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Apply Overtime
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Applied On</th>
                    <th scope="col" class="px-6 py-4">Date & Time</th>
                    <th scope="col" class="px-6 py-4">Duration</th>
                    <th scope="col" class="px-6 py-4">Type</th>
                    <th scope="col" class="px-6 py-4">Reason</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['myOvertime'])): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="6" class="px-6 py-4 text-center">No overtime applications found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['myOvertime'] as $ot): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4"><?= date('M j, Y', strtotime($ot['created_at'])) ?></td>
                        <td class="px-6 py-4">
                            <?= date('M j, Y', strtotime($ot['date'])) ?>
                            <div class="text-xs text-gray-500 mt-0.5"><?= date('h:i A', strtotime($ot['start_time'])) ?> - <?= date('h:i A', strtotime($ot['end_time'])) ?></div>
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            <?= $ot['hours'] ?> Hrs
                        </td>
                        <td class="px-6 py-4">
                            <?php if($ot['type'] === 'Working Day'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">Working Day</span>
                            <?php elseif($ot['type'] === 'Weekend'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full dark:bg-purple-900 dark:text-purple-300">Weekend</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full dark:bg-orange-900 dark:text-orange-300">Holiday</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 max-w-[200px] truncate" title="<?= htmlspecialchars($ot['reason']) ?>">
                            <?= htmlspecialchars($ot['reason']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($ot['status'] === 'Approved'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Approved</span>
                            <?php elseif($ot['status'] === 'Rejected'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Rejected</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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
document.getElementById('ot-form').addEventListener('submit', function(e) {
    const errorDiv = document.getElementById('ot-error-msg');
    errorDiv.classList.add('hidden');
    errorDiv.innerText = '';

    const dateStr = document.getElementById('ot_date').value;
    const startStr = document.getElementById('ot_start_time').value;
    const endStr = document.getElementById('ot_end_time').value;

    if (!dateStr || !startStr || !endStr) return;

    const selectedDate = new Date(dateStr + 'T00:00:00');
    const today = new Date();
    today.setHours(0,0,0,0);

    if (selectedDate < today) {
        e.preventDefault();
        errorDiv.innerText = 'Overtime request is only allowed for today or future dates.';
        errorDiv.classList.remove('hidden');
        return;
    }

    // Time validation
    const startTime = new Date('1970-01-01T' + startStr + ':00');
    const endTime = new Date('1970-01-01T' + endStr + ':00');

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

    // Checking existing OT via PHP server session error would be required here.
    // Client side we just show the PHP session error if it exists.
    <?php if(isset($_SESSION['ot_error'])): ?>
        e.preventDefault();
    <?php endif; ?>
});

// Display server-side errors
<?php if(isset($_SESSION['ot_error'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('applyModal').classList.remove('hidden');
        const errorDiv = document.getElementById('ot-error-msg');
        errorDiv.innerText = <?= json_encode($_SESSION['ot_error']) ?>;
        errorDiv.classList.remove('hidden');
    });
    <?php unset($_SESSION['ot_error']); ?>
<?php endif; ?>
</script>
