<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Requests</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Leave Type</th>
                    <th scope="col" class="px-6 py-4">Duration</th>
                    <th scope="col" class="px-6 py-4">Reason</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['leaveRequests'])): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="6" class="px-6 py-4 text-center">No leave requests found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['leaveRequests'] as $lr): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <?= htmlspecialchars($lr['first_name'] . ' ' . $lr['last_name']) ?>
                            <div class="text-xs text-gray-500 font-normal"><?= htmlspecialchars($lr['employee_code']) ?></div>
                        </td>
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
                        <td class="px-6 py-4 max-w-xs truncate" title="<?= htmlspecialchars($lr['reason']) ?>">
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
                        <td class="px-6 py-4 text-right">
                            <?php if($lr['status'] === 'Pending'): ?>
                                <button onclick="actionLeave(<?= $lr['id'] ?>)" class="bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Review</button>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">Processed</span>
                                <?php if($lr['admin_remark']): ?>
                                    <div class="text-[10px] text-gray-500 mt-1 truncate max-w-[100px]" title="<?= htmlspecialchars($lr['admin_remark']) ?>">"<?= htmlspecialchars($lr['admin_remark']) ?>"</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Action Modal -->
<div id="actionModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Review Leave Request</h3>
            <button type="button" onclick="document.getElementById('actionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leaves" method="POST" class="p-6">
            <input type="hidden" name="id" id="request_id">
            
            <div class="mb-4">
                <label for="admin_remark" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Remark (Optional)</label>
                <textarea name="admin_remark" id="admin_remark" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Provide a reason if rejecting..."></textarea>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <button type="submit" name="action" value="reject" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 text-center shadow-sm">
                    <i class="fa-solid fa-xmark mr-1"></i> Reject Request
                </button>
                <button type="submit" name="action" value="approve" class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 text-center shadow-sm">
                    <i class="fa-solid fa-check mr-1"></i> Approve Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function actionLeave(id) {
        document.getElementById('request_id').value = id;
        document.getElementById('actionModal').classList.remove('hidden');
    }
</script>
