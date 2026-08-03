<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Application</h1>
    <button onclick="document.getElementById('applyModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Apply Leave
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Applied On</th>
                    <th scope="col" class="px-6 py-4">Leave Type</th>
                    <th scope="col" class="px-6 py-4">Duration</th>
                    <th scope="col" class="px-6 py-4">Reason</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4">Admin Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['myLeaves'])): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="6" class="px-6 py-4 text-center">No leave applications found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['myLeaves'] as $lr): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4"><?= date('M j, Y', strtotime($lr['created_at'])) ?></td>
                        <td class="px-6 py-4">
                            <?= htmlspecialchars($lr['leave_type_name']) ?>
                            <?php if($lr['is_paid']): ?>
                                <span class="ml-1 px-1.5 py-0.5 text-[10px] font-medium bg-green-100 text-green-800 rounded dark:bg-green-900 dark:text-green-300">Paid</span>
                            <?php else: ?>
                                <span class="ml-1 px-1.5 py-0.5 text-[10px] font-medium bg-gray-100 text-gray-800 rounded dark:bg-gray-700 dark:text-gray-300">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs">
                                <?= date('M j', strtotime($lr['start_date'])) ?> - <?= date('M j, Y', strtotime($lr['end_date'])) ?>
                                <div class="font-semibold mt-0.5"><?= $lr['days'] ?> Day(s)</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-[150px] truncate" title="<?= htmlspecialchars($lr['reason']) ?>">
                            <?= htmlspecialchars($lr['reason']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($lr['status'] === 'Approved'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Approved</span>
                            <?php elseif($lr['status'] === 'Rejected'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Rejected</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-xs italic text-gray-500 max-w-[150px] truncate" title="<?= htmlspecialchars($lr['admin_remark'] ?? '') ?>">
                            <?= !empty($lr['admin_remark']) ? htmlspecialchars($lr['admin_remark']) : '-' ?>
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
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Apply for Leave</h3>
            <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/employee/leaves" method="POST" class="p-6 space-y-4" id="leaveForm">
            <input type="hidden" name="action" value="apply">
            
            <div>
                <label for="leave_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                <select name="leave_type_id" id="leave_type_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">Select Leave Type</option>
                    <?php foreach($data['leaveTypes'] as $lt): ?>
                        <option value="<?= $lt['id'] ?>"><?= htmlspecialchars($lt['name']) ?> (<?= $lt['is_paid'] ? 'Paid' : 'Unpaid' ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>
            <div id="dateError" class="text-red-500 text-xs mt-1 hidden"><i class="fa-solid fa-circle-exclamation mr-1"></i> End Date cannot be earlier than Start Date.</div>
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
    const durationDisplay = document.getElementById('durationDisplay');
    const durationDays = document.getElementById('durationDays');
    const leaveForm = document.getElementById('leaveForm');

    function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (end < start) {
                dateError.classList.remove('hidden');
                durationDisplay.classList.add('hidden');
            } else {
                dateError.classList.add('hidden');
                // Calculate days inclusive of start and end
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                durationDays.innerText = diffDays;
                durationDisplay.classList.remove('hidden');
            }
        } else {
            dateError.classList.add('hidden');
            durationDisplay.classList.add('hidden');
        }
    }

    startDate.addEventListener('change', calculateDuration);
    endDate.addEventListener('change', calculateDuration);

    leaveForm.addEventListener('submit', function(e) {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            if (end < start) {
                e.preventDefault();
                dateError.classList.remove('hidden');
                return false;
            }
        }
        
        // Disable button to prevent double submit
        const submitBtn = leaveForm.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Submitting...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
    });
});
</script>
