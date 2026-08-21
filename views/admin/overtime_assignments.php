<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-business-time"></i>
                    <span>Shift Management</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['assignments'] ?? []) ?> Assignments
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Overtime <span class="gradient-text">Assignments</span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Schedule and manage overtime work shifts for individual staff or whole departments.</p>
        </div>
        <button onclick="openModal()" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus text-indigo-600"></i>
            <span>Assign Overtime</span>
        </button>
    </div>
</div>

<?php if(isset($data['error'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm" data-aos="fade-up">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <span><?= htmlspecialchars($data['error']) ?></span>
    </div>
<?php endif; ?>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="50">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4">Employee</th>
                    <th scope="col" class="px-6 py-4">Date</th>
                    <th scope="col" class="px-6 py-4">Time</th>
                    <th scope="col" class="px-6 py-4">Hours</th>
                    <th scope="col" class="px-6 py-4">Rate/Hr</th>
                    <th scope="col" class="px-6 py-4">Total Amount</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if(empty($data['assignments'])): ?>
                    <tr class="bg-white dark:bg-slate-800">
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-14 h-14 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-business-time text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">No overtime assignments found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['assignments'] as $ot): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    <?= strtoupper(substr($ot['FirstName'],0,1) . substr($ot['LastName'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-xs"><?= htmlspecialchars($ot['FirstName'] . ' ' . $ot['LastName']) ?></div>
                                    <div class="text-[11px] text-indigo-600 dark:text-sky-400 font-mono font-semibold">EMP-<?= str_pad($ot['EmpID'], 4, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-900 dark:text-white text-xs">
                            <?= date('M j, Y', strtotime($ot['OvertimeDate'])) ?>
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs text-slate-700 dark:text-slate-300">
                            <?php if ($ot['StartTime'] && $ot['EndTime']): ?>
                                <?= date('h:i A', strtotime($ot['StartTime'])) ?> - <?= date('h:i A', strtotime($ot['EndTime'])) ?>
                            <?php else: ?>
                                <span class="text-slate-400 italic">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold font-mono bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800">
                                <?= $ot['TotalHours'] ?> Hrs
                            </span>
                        </td>
                        <td class="px-6 py-3.5 font-mono font-semibold text-xs text-slate-800 dark:text-slate-200">
                            <?= number_format($ot['RateMultiplier'], 1) ?>x
                        </td>
                        <td class="px-6 py-3.5 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-xs">
                            <?= number_format($ot['OTAmount'], 2) ?> MMK
                        </td>
                        <td class="px-6 py-3.5">
                            <?php 
                                $statusColors = [
                                    'Assigned' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/50 dark:text-sky-300 dark:border-sky-800',
                                    'Accepted' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800',
                                    'Rejected' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800',
                                    'In Progress' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
                                    'Completed' => 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-950/50 dark:text-teal-300 dark:border-teal-800',
                                    'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
                                    'No Show' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
                                    'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800'
                                ];
                                $colorClass = $statusColors[$ot['Status']] ?? $statusColors['Assigned'];
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border <?= $colorClass ?>">
                                <?= $ot['Status'] ?? 'Assigned' ?>
                            </span>
                        </td>

                        <td class="px-6 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <?php if (($ot['Status'] ?? 'Assigned') === 'Completed'): ?>
                                    <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Approve this overtime?');">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-400 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center transition-colors shadow-sm" title="Approve">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <?php if (!in_array($ot['Status'] ?? 'Assigned', ['Approved', 'Completed', 'Cancelled', 'No Show'])): ?>
                                <button onclick="editModal(<?= htmlspecialchars(json_encode($ot)) ?>)" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-400 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center transition-colors shadow-sm" title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <?php endif; ?>
                                
                                <?php if (!in_array($ot['Status'] ?? 'Assigned', ['Completed', 'Cancelled', 'No Show'])): ?>
                                <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Cancel this assignment?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="cancel">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 flex items-center justify-center transition-colors shadow-sm" title="Cancel">
                                        <i class="fa-solid fa-ban text-xs"></i>
                                    </button>
                                </form>
                                <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Mark this assignment as No Show?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="no_show">
                                    <input type="hidden" name="id" value="<?= $ot['OvertimeID'] ?>">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 border border-slate-200 dark:border-slate-600 flex items-center justify-center transition-colors shadow-sm" title="No Show">
                                        <i class="fa-solid fa-user-slash text-xs"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
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
<div id="assignmentModal" class="hidden fixed inset-0 z-[100] overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit" id="modalTitle">
                <i class="fa-solid fa-business-time text-indigo-500"></i> Assign Overtime
            </h3>
            <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="assignment_id" value="">
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-3 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="assign_type" value="individual" checked onchange="toggleAssignType()" class="form-radio text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <span class="ml-2 text-xs font-bold text-slate-700 dark:text-slate-300">Individual Employee</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="assign_type" value="department" onchange="toggleAssignType()" class="form-radio text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <span class="ml-2 text-xs font-bold text-slate-700 dark:text-slate-300">Entire Department</span>
                    </label>
                </div>

                <div id="dept_container" class="hidden">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Select Department</label>
                    <select name="assign_dept_id" id="assign_dept_id" onchange="validateOT()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                        <option value="">Select a Department</option>
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="emp_container">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Employee</label>
                    <select name="emp_id" id="emp_id" onchange="validateOT()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                        <option value="">Select Employee</option>
                        <?php foreach($data['employees'] as $emp): ?>
                            <option value="<?= $emp['EmpID'] ?>" data-dept="<?= $emp['DeptID'] ?>"><?= htmlspecialchars($emp['FirstName'] . ' ' . $emp['LastName']) ?> (EMP-<?= str_pad($emp['EmpID'], 4, '0', STR_PAD_LEFT) ?>) <?= $emp['Status'] !== 'Active' ? '[Inactive]' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Date</label>
                    <input type="date" name="overtime_date" id="overtime_date" min="<?= date('Y-m-d') ?>" required onchange="validateOT()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                </div>
                
                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Start Time</label>
                        <input type="time" name="start_time" id="start_time" required onchange="calculateHoursAndAmount(); validateOT();" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">End Time</label>
                        <input type="time" name="end_time" id="end_time" required onchange="calculateHoursAndAmount(); validateOT();" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                    </div>
                </div>
                <p id="time_error" class="text-rose-500 text-xs hidden mt-1 font-bold"></p>

                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Total Hours</label>
                        <input type="number" step="0.5" name="hours" id="hours" readonly class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl text-xs font-mono font-bold shadow-inner">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Cancel
                </button>
                <button type="submit" id="save_btn" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    Assign OT
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
        validateOT();
    }



    function calculateHoursAndAmount() {
        const startTimeStr = document.getElementById('start_time').value;
        const endTimeStr = document.getElementById('end_time').value;
        const hoursInput = document.getElementById('hours');
        
        if (startTimeStr && endTimeStr) {
            const start = new Date(`2000-01-01T${startTimeStr}`);
            let end = new Date(`2000-01-01T${endTimeStr}`);
            
            if (end < start) {
                end.setDate(end.getDate() + 1);
            }
            
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            
            hoursInput.value = diffHours > 0 ? diffHours.toFixed(2) : '';
        } else {
            hoursInput.value = '';
        }
    }

    const allAssignments = <?= json_encode($data['assignments']) ?>;

    function validateOT() {
        const errorEl = document.getElementById('time_error');
        const saveBtn = document.getElementById('save_btn');
        const dateVal = document.getElementById('overtime_date').value;
        const startVal = document.getElementById('start_time').value;
        const endVal = document.getElementById('end_time').value;
        const empId = document.getElementById('emp_id').value;
        const currentId = document.getElementById('assignment_id').value;
        const type = document.querySelector('input[name="assign_type"]:checked').value;
        
        errorEl.classList.add('hidden');
        errorEl.innerText = '';
        saveBtn.disabled = false;

        if (!dateVal || !startVal) return;



        if (!endVal) return;

        // 2. Overlap validation
        const startUnix = new Date(`1970-01-01T${startVal}`).getTime();
        let endUnix = new Date(`1970-01-01T${endVal}`).getTime();
        if (endUnix <= startUnix) endUnix += 86400000;

        const deptId = document.getElementById('assign_dept_id').value;
        let empsToCheck = [];
        if (type === 'individual' && empId) {
            empsToCheck.push(empId);
        } else if (type === 'department' && deptId) {
            const empOptions = document.querySelectorAll('#emp_id option');
            empOptions.forEach(opt => {
                if (opt.getAttribute('data-dept') === deptId) {
                    empsToCheck.push(opt.value);
                }
            });
        }

        if (empsToCheck.length > 0) {
            for (let ot of allAssignments) {
                if (empsToCheck.includes(ot.EmpID.toString()) && ot.OvertimeDate === dateVal && ot.OvertimeID != currentId && !['Cancelled', 'Rejected'].includes(ot.Status)) {
                    if (!ot.StartTime || !ot.EndTime) continue;
                    const otStartTimeStr = ot.StartTime.includes(' ') ? ot.StartTime.split(' ')[1] : ot.StartTime;
                    const otEndTimeStr = ot.EndTime.includes(' ') ? ot.EndTime.split(' ')[1] : ot.EndTime;
                    const exStart = new Date(`1970-01-01T${otStartTimeStr}`).getTime();
                    let exEnd = new Date(`1970-01-01T${otEndTimeStr}`).getTime();
                    if (exEnd <= exStart) exEnd += 86400000;

                    if (startUnix < exEnd && endUnix > exStart) {
                        if (type === 'department') {
                            errorEl.innerText = "Overtime schedule overlaps with an existing assignment for one or more employees in this department.";
                        } else {
                            errorEl.innerText = "Overtime schedule overlaps with an existing assignment for this employee.";
                        }
                        errorEl.classList.remove('hidden');
                        saveBtn.disabled = true;
                        return;
                    }
                }
            }
        }
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
        document.getElementById('hours').value = '';
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
        
        let startStr = '';
        if (data.StartTime) {
            startStr = data.StartTime.includes(' ') ? data.StartTime.split(' ')[1].substring(0, 5) : data.StartTime.substring(0, 5);
        }
        let endStr = '';
        if (data.EndTime) {
            endStr = data.EndTime.includes(' ') ? data.EndTime.split(' ')[1].substring(0, 5) : data.EndTime.substring(0, 5);
        }
        
        document.getElementById('start_time').value = startStr;
        document.getElementById('end_time').value = endStr;
        document.getElementById('hours').value = data.TotalHours;
        document.getElementById('assignmentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('assignmentModal').classList.add('hidden');
    }


</script>
