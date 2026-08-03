<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overtime Requests</h1>
</div>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Date & Time</th>
                    <th scope="col" class="px-6 py-4">Duration</th>
                    <th scope="col" class="px-6 py-4">Type</th>
                    <th scope="col" class="px-6 py-4">Reason</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['overtimeRequests'])): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700">
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-business-time text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                            <p class="text-lg">No overtime requests found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['overtimeRequests'] as $ot): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <?= htmlspecialchars($ot['first_name'] . ' ' . $ot['last_name']) ?>
                            <div class="text-xs text-gray-500 font-normal"><?= htmlspecialchars($ot['employee_code']) ?></div>
                        </td>
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
                        <td class="px-6 py-4 text-right">
                            <?php if($ot['status'] === 'Pending'): ?>
                                <form action="/payrollsystem/admin/overtime" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $ot['id'] ?>">
                                    <button type="submit" name="action" value="approve" class="bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors mr-1">Approve</button>
                                    <button type="submit" name="action" value="reject" class="bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Reject</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
