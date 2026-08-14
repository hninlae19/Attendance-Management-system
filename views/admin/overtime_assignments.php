<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Overtime Assignments</h1>
        <p class="text-gray-500 text-sm mt-1">Manage and assign overtime to employees.</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-xl hover:bg-indigo-600 focus:ring-4 focus:ring-primary/20 transition-all shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Assign Overtime
    </button>
</div>

<?php if(isset($data['error'])): ?>
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><?= htmlspecialchars($data['error']) ?></span>
    </div>
<?php endif; ?>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="50">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Employee</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Time</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Hours</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Rate/Hr</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Total Amount</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(empty($data['assignments'])): ?>
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-clipboard-list text-2xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <p class="font-medium text-gray-900 dark:text-white">No overtime assignments found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['assignments'] as $ot): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary/20 to-blue-500/20 text-primary dark:text-blue-400 flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-gray-800 group-hover:ring-primary/20 transition-all shadow-sm">
                                    <?= strtoupper(substr($ot['FirstName'],0,1) . substr($ot['LastName'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($ot['FirstName'] . ' ' . $ot['LastName']) ?></div>
                                    <div class="text-xs text-primary font-medium">EMP-<?= str_pad($ot['EmpID'], 5, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                            <?= date('M j, Y', strtotime($ot['OvertimeDate'])) ?>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                            <?php if ($ot['StartTime'] && $ot['EndTime']): ?>
                                <?= date('h:i A', strtotime($ot['StartTime'])) ?> - <?= date('h:i A', strtotime($ot['EndTime'])) ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/30">
                                <?= $ot['OvertimeHours'] ?> Hrs
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-300">
                            <?= number_format($ot['OTRate'], 2) ?> MMK
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                            <?= number_format($ot['OTAmount'], 2) ?> MMK
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editModal(<?= htmlspecialchars(json_encode($ot)) ?>)" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors tooltip" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-colors tooltip" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="assignmentModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center" id="modalTitle"><i class="fa-solid fa-clipboard-list text-primary mr-3"></i> Assign Overtime</h3>
            <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="assignment_id" value="">
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 mb-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="assign_type" value="individual" checked onchange="toggleAssignType()" class="form-radio text-primary focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Individual Employee</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="assign_type" value="department" onchange="toggleAssignType()" class="form-radio text-primary focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Entire Department</span>
                    </label>
                </div>

                <div id="dept_container" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Select Department</label>
                    <select name="assign_dept_id" id="assign_dept_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm">
                        <option value="">Select a Department</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="emp_container">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Employee</label>
                    <select name="emp_id" id="emp_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm">
                        <option value="">Select Employee</option>
                        <?php foreach($data['employees'] as $emp): ?>
                            <option value="<?= $emp['EmpID'] ?>" data-dept="<?= $emp['DeptID'] ?>"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?> (EMP-<?= str_pad($emp['EmpID'], 5, '0', STR_PAD_LEFT) ?>) <?= $emp['Status'] !== 'Active' ? '[Inactive]' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date" name="overtime_date" id="overtime_date" min="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                        <input type="time" name="start_time" id="start_time" required onchange="calculateHoursAndAmount()" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                        <input type="time" name="end_time" id="end_time" required onchange="calculateHoursAndAmount()" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Total Hours</label>
                        <input type="number" step="0.5" name="hours" id="hours" readonly class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300 transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Rate/Hr (MMK)</label>
                        <input type="number" step="0.01" min="0" name="rate" id="rate" required oninput="calculateAmount()" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm" placeholder="e.g. 1500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Total Amount (MMK)</label>
                    <input type="text" id="amount_display" readonly class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-gray-700 font-bold dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300 transition-all shadow-sm" placeholder="0">
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-xl hover:bg-indigo-600 hover:-translate-y-0.5 transition-all shadow-lg shadow-primary/30">
                    Save Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAssignType() {
        const type = document.querySelector('input[name="assign_type"]:checked').value;
        const empContainer = document.getElementById('emp_container');
        const deptContainer = document.getElementById('dept_container');
        const empSelect = document.getElementById('emp_id');
        const deptSelect = document.getElementById('assign_dept_id');
        
        if (type === 'department') {
            empContainer.classList.add('hidden');
            deptContainer.classList.remove('hidden');
            empSelect.removeAttribute('required');
            deptSelect.setAttribute('required', 'required');
        } else {
            empContainer.classList.remove('hidden');
            deptContainer.classList.add('hidden');
            deptSelect.removeAttribute('required');
            empSelect.setAttribute('required', 'required');
        }
    }

    function calculateHoursAndAmount() {
        const startTimeStr = document.getElementById('start_time').value;
        const endTimeStr = document.getElementById('end_time').value;
        const hoursInput = document.getElementById('hours');
        
        if (startTimeStr && endTimeStr) {
            const start = new Date(`2000-01-01T${startTimeStr}`);
            let end = new Date(`2000-01-01T${endTimeStr}`);
            
            // Handle overnight shifts if end time is earlier than start time
            if (end < start) {
                end.setDate(end.getDate() + 1);
            }
            
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            
            // Round to nearest half hour if desired, or keep exact
            hoursInput.value = diffHours > 0 ? diffHours.toFixed(2) : '';
        } else {
            hoursInput.value = '';
        }
        calculateAmount();
    }

    function openModal() {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-clipboard-list text-primary mr-3"></i> Assign Overtime';
        document.getElementById('formAction').value = 'add';
        document.getElementById('assignment_id').value = '';
        document.querySelector('input[name="assign_type"][value="individual"]').checked = true;
        
        // Disable type change for edit
        document.querySelectorAll('input[name="assign_type"]').forEach(el => el.disabled = false);
        
        toggleAssignType();
        document.getElementById('emp_id').value = '';
        document.getElementById('assign_dept_id').value = '';
        document.getElementById('overtime_date').value = '';
        document.getElementById('start_time').value = '';
        document.getElementById('end_time').value = '';
        document.getElementById('hours').value = '';
        document.getElementById('rate').value = '';
        calculateAmount();
        document.getElementById('assignmentModal').classList.remove('hidden');
    }

    function editModal(data) {
        document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-pen text-primary mr-3"></i> Edit Assignment';
        document.getElementById('formAction').value = 'edit';
        document.getElementById('assignment_id').value = data.OvertimeID;
        
        document.querySelector('input[name="assign_type"][value="individual"]').checked = true;
        // Lock to individual during edit
        document.querySelectorAll('input[name="assign_type"]').forEach(el => el.disabled = true);
        toggleAssignType();
        
        document.getElementById('emp_id').value = data.EmpID;
        document.getElementById('overtime_date').value = data.OvertimeDate;
        document.getElementById('start_time').value = data.StartTime || '';
        document.getElementById('end_time').value = data.EndTime || '';
        document.getElementById('hours').value = data.OvertimeHours;
        document.getElementById('rate').value = data.OTRate;
        calculateAmount();
        document.getElementById('assignmentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('assignmentModal').classList.add('hidden');
    }

    function calculateAmount() {
        const hours = parseFloat(document.getElementById('hours').value) || 0;
        const rate = parseFloat(document.getElementById('rate').value) || 0;
        document.getElementById('amount_display').value = (hours * rate).toLocaleString();
    }
</script>
