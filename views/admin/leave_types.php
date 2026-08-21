<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Policy Configuration</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['leaveTypes'] ?? []) ?> Leave Types
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Leave <span class="gradient-text">Types</span> & Quotas
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Define annual leave entitlements, paid status, deduction formulas, and eligibility thresholds.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus text-indigo-600"></i>
            <span>Add Leave Type</span>
        </button>
    </div>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
<div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 flex items-start gap-3 shadow-sm" data-aos="fade-up">
    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 text-base"></i>
    <div>
        <h4 class="text-xs font-bold text-rose-800 dark:text-rose-300">Save Failed</h4>
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-0.5">A leave type with this name already exists. Please choose a unique name.</p>
    </div>
</div>
<?php elseif(isset($_GET['error']) && $_GET['error'] === 'in_use'): ?>
<div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 flex items-start gap-3 shadow-sm" data-aos="fade-up">
    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 text-base"></i>
    <div>
        <h4 class="text-xs font-bold text-rose-800 dark:text-rose-300">Delete Restricted</h4>
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-0.5">Cannot delete this leave type because it is currently referenced in employee leave balances or active leave requests.</p>
    </div>
</div>
<?php endif; ?>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="50">
    <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
        <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
            <tr>
                <th scope="col" class="px-6 py-4">Id</th>
                <th scope="col" class="px-6 py-4">Leave Type</th>
                <th scope="col" class="px-6 py-4 text-center">Days Allowed</th>
                <th scope="col" class="px-6 py-4 text-center">Is Paid?</th>
                <th scope="col" class="px-6 py-4 text-center">Deduction Rate</th>
                <th scope="col" class="px-6 py-4 text-center">Req. Duration</th>
                <th scope="col" class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
            <?php if(empty($data['leaveTypes'])): ?>
                <tr class="bg-white dark:bg-slate-800">
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                        <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                            <i class="fa-solid fa-sliders text-2xl"></i>
                        </div>
                        <p class="font-bold text-slate-900 dark:text-white text-sm">No leave types configured</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($data['leaveTypes'] as $lt): ?>
                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                    <td class="px-6 py-3.5 font-mono text-xs font-semibold text-slate-500 dark:text-slate-400">
                        #<?= str_pad($lt['LeaveTypeID'], 2, '0', STR_PAD_LEFT) ?>
                    </td>
                    <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white text-xs">
                        <?= htmlspecialchars($lt['LeaveType']) ?>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold font-mono bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800">
                            <?= $lt['DaysAllowed'] ?> Days/yr
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <?php if($lt['IsPaid']): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Paid
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Unpaid
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3.5 text-center font-mono font-semibold text-xs text-slate-700 dark:text-slate-300">
                        <?= number_format($lt['DeductionRate'], 2) ?>x
                    </td>
                    <td class="px-6 py-3.5 text-center font-mono font-bold text-xs text-slate-800 dark:text-slate-200">
                        <?= $lt['DurationMonths'] ?> Months
                    </td>
                    <td class="px-6 py-3.5 text-right space-x-1.5">
                        <button onclick="editLeaveType(<?= $lt['LeaveTypeID'] ?>, '<?= htmlspecialchars(addslashes($lt['LeaveType'])) ?>', <?= $lt['DaysAllowed'] ?>, <?= $lt['IsPaid'] ? 1 : 0 ?>, <?= $lt['DeductionRate'] ?>, <?= $lt['DurationMonths'] ?>)" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 inline-flex items-center justify-center transition-colors shadow-sm" title="Edit">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>
                        <form action="/payrollsystem/admin/leave_types" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave type?');">
                            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $lt['LeaveTypeID'] ?>">
                            <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 inline-flex items-center justify-center transition-colors shadow-sm" title="Delete">
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

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-plus-circle text-indigo-500"></i> Add Leave Type
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Leave Type Name</label>
                    <input type="text" name="name" id="name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="e.g. Annual Vacation, Sick Leave">
                </div>
                <div>
                    <label for="days" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Days Allowed Per Year</label>
                    <input type="number" name="days" id="days" required min="0" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="12">
                </div>
                <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                    <input type="checkbox" name="is_paid" id="is_paid" value="1" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="is_paid" class="ml-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Compensated / Paid Leave</label>
                </div>
                <div>
                    <label for="deduction_rate" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Deduction Rate Multiplier (if unpaid/exceeded)</label>
                    <input type="number" step="0.01" name="deduction_rate" id="deduction_rate" value="0.00" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div>
                    <label for="duration_months" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Required Service Duration (Months)</label>
                    <input type="number" name="duration_months" id="duration_months" value="0" min="0" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">Save Leave Type</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-pen-to-square text-indigo-500"></i> Edit Leave Type
            </h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="space-y-4">
                <div>
                    <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Leave Type Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div>
                    <label for="edit_days" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Days Allowed</label>
                    <input type="number" name="days" id="edit_days" required min="0" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                    <input type="checkbox" name="is_paid" id="edit_is_paid" value="1" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="edit_is_paid" class="ml-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Compensated / Paid Leave</label>
                </div>
                <div>
                    <label for="edit_deduction_rate" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Deduction Rate Multiplier</label>
                    <input type="number" step="0.01" name="deduction_rate" id="edit_deduction_rate" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                <div>
                    <label for="edit_duration_months" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Required Service Duration (Months)</label>
                    <input type="number" name="duration_months" id="edit_duration_months" required min="0" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">Update Leave Type</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editLeaveType(id, name, days, isPaid, deductionRate, durationMonths) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_days').value = days;
        document.getElementById('edit_is_paid').checked = isPaid === 1;
        document.getElementById('edit_deduction_rate').value = deductionRate;
        document.getElementById('edit_duration_months').value = durationMonths;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
