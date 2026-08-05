<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Overtime Assignments</h1>
        <p class="text-gray-500 text-sm mt-1">Assign overtime to departments or individual employees.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/30">
        <i class="fa-solid fa-plus mr-2"></i> New Assignment
    </button>
</div>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-700/50 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">Title / Reason</th>
                    <th scope="col" class="px-6 py-4">Date & Time</th>
                    <th scope="col" class="px-6 py-4">Assigned To</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['assignments'])): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                            No overtime assignments found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['assignments'] as $assign): ?>
                    <tr class="bg-white/50 border-b dark:bg-gray-800/50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($assign['title']) ?></div>
                            <div class="text-xs text-gray-500 mt-1 max-w-xs truncate" title="<?= htmlspecialchars($assign['reason']) ?>"><?= htmlspecialchars($assign['reason']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 dark:text-gray-300"><?= date('M j, Y', strtotime($assign['date'])) ?></div>
                            <div class="text-xs text-gray-500 mt-0.5"><?= date('h:i A', strtotime($assign['start_time'])) ?> - <?= date('h:i A', strtotime($assign['end_time'])) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                <?= $assign['total_assigned'] ?> Employee(s)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($assign['status'] === 'Active'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Active</span>
                            <?php elseif($assign['status'] === 'Completed'): ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300">Completed</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($assign['status'] === 'Active'): ?>
                                <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel this assignment?');">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                                    <input type="hidden" name="action" value="cancel">
                                    <input type="hidden" name="id" value="<?= $assign['id'] ?>">
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full shadow-2xl overflow-hidden transform transition-all" x-data="{ assignType: 'department' }">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fa-solid fa-user-clock mr-2 text-primary"></i> Create Overtime Assignment</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/overtime_assignments" method="POST" class="p-6 space-y-5">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

            <input type="hidden" name="action" value="add">
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assignment Title</label>
                <input type="text" name="title" id="title" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date" name="date" id="date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                    <input type="time" name="start_time" id="start_time" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                    <input type="time" name="end_time" id="end_time" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason / Task Details</label>
                <textarea name="reason" id="reason" rows="3" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors"></textarea>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3">Assign To</label>
                <div class="flex space-x-4 mb-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="assign_type" value="department" x-model="assignType" class="text-primary focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Entire Department</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="assign_type" value="employee" x-model="assignType" class="text-primary focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Specific Employees</span>
                    </label>
                </div>

                <div x-show="assignType === 'department'" x-collapse>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Department</label>
                    <select name="department_id" id="department_id" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                        <?php foreach($data['departments'] as $dept): ?>
                            <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div x-show="assignType === 'employee'" x-collapse style="display: none;">
                    <label for="employee_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Employees (Hold Ctrl/Cmd to select multiple)</label>
                    <select name="employee_ids[]" id="employee_ids" multiple class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors" style="height: 120px;">
                        <?php foreach($data['employees'] as $emp): ?>
                            <?php if($emp['status'] === 'Active'): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= htmlspecialchars($emp['employee_code']) ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700 transition-all duration-300 hover:shadow-lg hover:shadow-primary/30">Assign Overtime</button>
            </div>
        </form>
    </div>
</div>
