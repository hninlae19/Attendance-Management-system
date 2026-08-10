<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Deduction Management</h1>
        <p class="text-gray-500 text-sm mt-1">Manage manual and automated deductions for employee payroll.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/30">
        <i class="fa-solid fa-plus mr-2"></i> Add Deduction
    </button>
</div>

<!-- Filters Section -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6" data-aos="fade-up" data-aos-delay="50">
    <form method="GET" action="/payrollsystem/admin/deductions" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="xl:col-span-2">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Search Employee</label>
            <input type="text" name="search" value="<?= htmlspecialchars($data['filters']['search'] ?? '') ?>" placeholder="Name or Code..." class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deduction Type</label>
            <select name="type" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                <option value="">All Types</option>
                <option value="Unpaid Leave" <?= ($data['filters']['type'] ?? '') == 'Unpaid Leave' ? 'selected' : '' ?>>Unpaid Leave</option>
                <option value="Half Day Absence" <?= ($data['filters']['type'] ?? '') == 'Half Day Absence' ? 'selected' : '' ?>>Half Day Absence</option>
                <option value="Full Day Absence" <?= ($data['filters']['type'] ?? '') == 'Full Day Absence' ? 'selected' : '' ?>>Full Day Absence</option>
                <option value="Damage" <?= ($data['filters']['type'] ?? '') == 'Damage' ? 'selected' : '' ?>>Damage</option>
                <option value="Loan" <?= ($data['filters']['type'] ?? '') == 'Loan' ? 'selected' : '' ?>>Loan</option>
                <option value="Manual Adjustment" <?= ($data['filters']['type'] ?? '') == 'Manual Adjustment' ? 'selected' : '' ?>>Manual Adjustment</option>
                <option value="Other" <?= ($data['filters']['type'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
            <input type="date" name="date_start" value="<?= htmlspecialchars($data['filters']['date_start'] ?? '') ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
            <input type="date" name="date_end" value="<?= htmlspecialchars($data['filters']['date_end'] ?? '') ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
        </div>
        <div class="flex items-end gap-2 xl:col-span-1">
            <button type="submit" class="w-full bg-primary hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm shadow-sm">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            <a href="/payrollsystem/admin/deductions" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                Clear
            </a>
        </div>
    </form>
</div>

<?php 
$sort = $data['sort'] ?? 'date';
$dir = $data['dir'] ?? 'DESC';

function buildSortUrl($column, $currentSort, $currentDir) {
    $newDir = ($currentSort === $column && $currentDir === 'ASC') ? 'DESC' : 'ASC';
    $params = $_GET;
    $params['sort'] = $column;
    $params['dir'] = $newDir;
    return '?' . http_build_query($params);
}

function getSortIcon($column, $currentSort, $currentDir) {
    if ($currentSort !== $column) return '<i class="fa-solid fa-sort text-gray-300 dark:text-gray-600 ml-1"></i>';
    return $currentDir === 'ASC' ? '<i class="fa-solid fa-sort-up text-primary ml-1"></i>' : '<i class="fa-solid fa-sort-down text-primary ml-1"></i>';
}
?>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">
                        <a href="<?= buildSortUrl('amount', $sort, $dir) ?>" class="hover:text-primary transition-colors flex items-center">
                            Amount <?= getSortIcon('amount', $sort, $dir) ?>
                        </a>
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Type / Reason</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">
                        <a href="<?= buildSortUrl('start_date', $sort, $dir) ?>" class="hover:text-primary transition-colors flex items-center">
                            Period <?= getSortIcon('start_date', $sort, $dir) ?>
                        </a>
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(empty($data['deductions'])): ?>
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-minus-circle text-2xl text-gray-300 dark:text-gray-500 block"></i>
                            </div>
                            <p class="font-medium text-gray-900 dark:text-white">No deductions found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['deductions'] as $deduction): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars(($deduction['first_name'] ?? '') . ' ' . ($deduction['last_name'] ?? '')) ?></div>
                            <div class="text-xs text-primary font-medium mt-1">EMP-<?= htmlspecialchars($deduction['employee_code'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 font-bold text-rose-600 dark:text-rose-400">
                            <?= number_format($deduction['amount'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 rounded-md dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/30"><?= htmlspecialchars($deduction['type'] ?? '') ?></span>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 truncate max-w-[150px]" title="<?= htmlspecialchars($deduction['reason'] ?? '') ?>">
                                <?= htmlspecialchars($deduction['reason'] ?? '') ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                            <?php if (!empty($deduction['start_date']) && !empty($deduction['end_date'])): ?>
                                <?= date('Y-m-d', strtotime($deduction['start_date'])) ?> &rarr; <?= date('Y-m-d', strtotime($deduction['end_date'])) ?>
                                <?php if (!empty($deduction['total_absent_days'])): ?>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400 ml-1">(<?= floatval($deduction['total_absent_days']) ?> Days)</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= date('Y-m-d', strtotime($deduction['date'])) ?>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm border bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                <?= htmlspecialchars($deduction['status'] ?? '') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="/payrollsystem/admin/deductions" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this deduction?');">
                                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $deduction['id'] ?>">
                                <button type="submit" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-400 p-2 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if(($data['total_pages'] ?? 0) > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Page <?= $data['page'] ?> of <?= $data['total_pages'] ?>
        </div>
        <div class="flex space-x-1">
            <?php 
                $params = $_GET;
                if($data['page'] > 1): 
                    $params['page'] = $data['page'] - 1;
                    $prevUrl = '?' . http_build_query($params);
            ?>
                <a href="<?= $prevUrl ?>" class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm transition-colors">Prev</a>
            <?php endif; ?>
            
            <?php 
                if($data['page'] < $data['total_pages']): 
                    $params['page'] = $data['page'] + 1;
                    $nextUrl = '?' . http_build_query($params);
            ?>
                <a href="<?= $nextUrl ?>" class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-white text-sm transition-colors">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fa-solid fa-minus-circle mr-2 text-rose-500"></i> Add New Deduction</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/deductions" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Employee</label>
                <select name="employee_id" id="employee_id" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <option value="">Choose Employee...</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <?php if($emp['status'] === 'Active'): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= htmlspecialchars($emp['employee_code']) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (MMK)</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Applied</label>
                    <input type="date" name="date" id="date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deduction Type</label>
                <select name="type" id="type" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <option value="Half Day Absence">Half Day Absence</option>
                    <option value="Full Day Absence">Full Day Absence</option>
                    <option value="Unpaid Leave">Unpaid Leave</option>
                    <option value="Damage">Damage to Company Property</option>
                    <option value="Loan">Loan Repayment</option>
                    <option value="Manual Adjustment">Manual Adjustment</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason / Description</label>
                <input type="text" name="reason" id="reason" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700 transition-all duration-300 hover:shadow-lg hover:shadow-primary/30">Save Deduction</button>
            </div>
        </form>
    </div>
</div>
