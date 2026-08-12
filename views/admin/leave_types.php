<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Types & Policies</h1>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Add Policy
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-4 py-4">Name</th>
                <th scope="col" class="px-4 py-4 text-center">Quota</th>
                <th scope="col" class="px-4 py-4 text-center">Paid Status</th>
                <th scope="col" class="px-4 py-4 text-center">Service Reqd.</th>
                <th scope="col" class="px-4 py-4 text-center">Gender</th>
                <th scope="col" class="px-4 py-4 text-center">Status</th>
                <th scope="col" class="px-4 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data['leaveTypes'])): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="7" class="px-4 py-4 text-center">No leave policies found.</td>
                </tr>
            <?php else: ?>
                <?php foreach($data['leaveTypes'] as $lt): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 <?= $lt['is_active'] ? '' : 'opacity-50' ?>">
                    <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                        <?= htmlspecialchars($lt['name']) ?>
                        <?php if($lt['attachment_required']): ?>
                            <i class="fa-solid fa-paperclip text-gray-400 ml-1 text-xs" title="Attachment Required"></i>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <?= $lt['days_allowed'] >= 999 ? 'Unlimited' : htmlspecialchars($lt['days_allowed']) . ' Days' ?>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <?php if($lt['is_paid']): ?>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Paid</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Unpaid</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-center"><?= $lt['service_period_months'] ?> mo.</td>
                    <td class="px-4 py-4 text-center"><?= htmlspecialchars($lt['gender_restriction']) ?></td>
                    <td class="px-4 py-4 text-center">
                        <?php if($lt['is_active']): ?>
                            <span class="text-green-600 dark:text-green-400"><i class="fa-solid fa-circle-check"></i> Active</span>
                        <?php else: ?>
                            <span class="text-gray-500"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-right space-x-2">
                        <button onclick="editLeaveType(<?= htmlspecialchars(json_encode($lt)) ?>)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                        <?php if($lt['is_active']): ?>
                        <form action="/payrollsystem/admin/leave_types" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to deactivate this policy?');">
                            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $lt['id'] ?>">
                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline"><i class="fa-solid fa-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Leave Policy</h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Days Allowed (999 for Unlimited)</label>
                    <input type="number" name="days_allowed" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Period Reqd (Months)</label>
                    <input type="number" name="service_period_months" min="0" value="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender Restriction</label>
                    <select name="gender_restriction" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="All">All Genders</option>
                        <option value="Male">Male Only</option>
                        <option value="Female">Female Only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="flex items-center">
                    <input name="is_paid" type="checkbox" value="1" checked class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Paid Leave</label>
                </div>
                <div class="flex items-center">
                    <input name="attachment_required" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Requires Attachment (e.g. Medical Cert)</label>
                </div>
                <div class="flex items-center">
                    <input name="carry_forward" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Carry Forward Allowed</label>
                </div>
                <div class="flex items-center">
                    <input name="is_active" type="checkbox" value="1" checked class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Active Policy</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Save Policy</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Leave Policy</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/leave_types" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Days Allowed (999 for Unlimited)</label>
                    <input type="number" name="days_allowed" id="edit_days_allowed" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Period Reqd (Months)</label>
                    <input type="number" name="service_period_months" id="edit_service_period_months" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender Restriction</label>
                    <select name="gender_restriction" id="edit_gender_restriction" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="All">All Genders</option>
                        <option value="Male">Male Only</option>
                        <option value="Female">Female Only</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="flex items-center">
                    <input name="is_paid" id="edit_is_paid" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Paid Leave</label>
                </div>
                <div class="flex items-center">
                    <input name="attachment_required" id="edit_attachment_required" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Requires Attachment (e.g. Medical Cert)</label>
                </div>
                <div class="flex items-center">
                    <input name="carry_forward" id="edit_carry_forward" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Carry Forward Allowed</label>
                </div>
                <div class="flex items-center">
                    <input name="is_active" id="edit_is_active" type="checkbox" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Active Policy</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Update Policy</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editLeaveType(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_days_allowed').value = data.days_allowed;
        document.getElementById('edit_service_period_months').value = data.service_period_months;
        document.getElementById('edit_gender_restriction').value = data.gender_restriction;
        
        document.getElementById('edit_is_paid').checked = data.is_paid == 1;
        document.getElementById('edit_attachment_required').checked = data.attachment_required == 1;
        document.getElementById('edit_carry_forward').checked = data.carry_forward == 1;
        document.getElementById('edit_is_active').checked = data.is_active == 1;
        
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
