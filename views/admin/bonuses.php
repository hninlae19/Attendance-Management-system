<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-gift"></i>
                    <span>Incentives & Rewards</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['bonuses'] ?? []) ?> Bonuses Awarded
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Employee <span class="gradient-text">Bonuses</span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Manage performance awards, annual disbursements, holiday rewards, and referral incentives.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus text-indigo-600"></i>
            <span>Add New Bonus</span>
        </button>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Department</th>
                    <th scope="col" class="px-6 py-4">Amount</th>
                    <th scope="col" class="px-6 py-4">Bonus Type</th>
                    <th scope="col" class="px-6 py-4">Award Date</th>
                    <th scope="col" class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if(empty($data['bonuses'])): ?>
                    <tr class="bg-white dark:bg-slate-800">
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-gift text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No bonuses recorded</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['bonuses'] as $bonus): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    <?= strtoupper(substr($bonus['FirstName'] ?? 'A',0,1) . substr($bonus['LastName'] ?? 'B',0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs"><?= htmlspecialchars(($bonus['FirstName'] ?? '') . ' ' . ($bonus['LastName'] ?? '')) ?></div>
                                    <div class="text-[11px] text-indigo-600 dark:text-sky-400 font-mono font-semibold">EMP-<?= str_pad($bonus['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-xs font-medium text-slate-700 dark:text-slate-300">
                            <?= htmlspecialchars($bonus['DeptName'] ?? 'N/A') ?>
                        </td>
                        <td class="px-6 py-3.5 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-xs">
                            <?= number_format($bonus['Amount'] ?? 0) ?> MMK
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800">
                                <?= htmlspecialchars($bonus['BonusType'] ?? 'General') ?>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-900 dark:text-white text-xs">
                            <?= date('M j, Y', strtotime($bonus['BonusDate'] ?? 'now')) ?>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <form action="/payrollsystem/admin/bonuses" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this bonus?');">
                                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $bonus['EmpBonousID'] ?>">
                                <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 inline-flex items-center justify-center transition-colors shadow-sm" title="Delete Bonus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-gift text-indigo-500"></i> Add New Bonus
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/bonuses" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="mb-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Assign To</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="assign_type" value="individual" checked class="text-indigo-600 focus:ring-indigo-500" onchange="document.getElementById('indiv_select').classList.remove('hidden'); document.getElementById('dept_select').classList.add('hidden'); document.getElementById('employee_id').required=true; document.getElementById('assign_dept_id').required=false;">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Individual Employee</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="assign_type" value="department" class="text-indigo-600 focus:ring-indigo-500" onchange="document.getElementById('indiv_select').classList.add('hidden'); document.getElementById('dept_select').classList.remove('hidden'); document.getElementById('employee_id').required=false; document.getElementById('assign_dept_id').required=true;">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Entire Department</span>
                    </label>
                </div>
            </div>
            
            <div id="indiv_select">
                <label for="employee_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Select Employee</label>
                <select name="employee_id" id="employee_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="">Choose Employee...</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <?php if($emp['Status'] === 'Active'): ?>
                            <option value="<?= $emp['EmpID'] ?>"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?> (EMP-<?= str_pad($emp['EmpID'], 4, '0', STR_PAD_LEFT) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div id="dept_select" class="hidden">
                <label for="assign_dept_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Select Department</label>
                <select name="assign_dept_id" id="assign_dept_id" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="">Choose Department...</option>
                    <?php foreach($data['departments'] as $dept): ?>
                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3.5">
                <div>
                    <label for="amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Amount (MMK)</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div>
                    <label for="date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Award Date</label>
                    <input type="date" name="date" id="date" required value="<?= date('Y-m-d') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
            </div>

            <div>
                <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Bonus Type</label>
                <select name="type" id="type" required onchange="const ct = document.getElementById('custom_type_container'); const cti = document.getElementById('custom_type'); if(this.value === 'Other') { ct.classList.remove('hidden'); cti.setAttribute('required', 'required'); } else { ct.classList.add('hidden'); cti.removeAttribute('required'); }" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="Performance">Performance Bonus</option>
                    <option value="Annual">Annual Bonus</option>
                    <option value="Referral">Referral Bonus</option>
                    <option value="Other">Other / Custom</option>
                </select>
            </div>

            <div id="custom_type_container" class="hidden">
                <label for="custom_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Custom Bonus Type Name</label>
                <input type="text" name="custom_type" id="custom_type" placeholder="e.g. Holiday Bonus" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
            </div>

            <div>
                <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Reason / Description</label>
                <input type="text" name="reason" id="reason" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="e.g. Outstanding Q3 Sales Achievement">
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">Save Bonus</button>
            </div>
        </form>
    </div>
</div>
