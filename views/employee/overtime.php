<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overtime Application</h1>
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
                            <i class="fa-solid fa-clipboard-check mr-2"></i> Assignment
                        </h3>
                        <p class="text-xs text-indigo-500 dark:text-indigo-400 mt-1">Assigned for: <?= date('M j, Y', strtotime($oa['OvertimeDate'])) ?></p>
                    </div>
                    <div>
                        <span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-xl dark:bg-green-900/30 dark:text-green-400">Assigned</span>
                    </div>
                </div>
                
                <div class="flex flex-col p-3 bg-white dark:bg-gray-800 rounded-xl mb-4 border border-indigo-100/50 dark:border-indigo-800/50">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Duration</p>
                    <p class="font-mono text-sm font-semibold text-gray-800 dark:text-gray-300 mt-1"><?= $oa['OvertimeHours'] ?> Hrs</p>
                </div>
                
                <div class="text-sm text-gray-600 dark:text-gray-400 flex justify-between">
                    <span><span class="font-medium text-gray-900 dark:text-white">Rate:</span> <?= number_format($oa['OTRate'], 2) ?> MMK</span>
                    <span><span class="font-medium text-gray-900 dark:text-white">Total:</span> <span class="text-emerald-600 font-bold"><?= number_format($oa['OTAmount'], 2) ?> MMK</span></span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
