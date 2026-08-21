<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-sitemap"></i>
                    <span>Organizational Units</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['departments'] ?? []) ?> Departments
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Department <span class="gradient-text">Management</span>
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Structure and maintain business divisions, operational teams, and functional departments.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus text-indigo-600"></i>
            <span>Add Department</span>
        </button>
    </div>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
<div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm" data-aos="fade-up">
    <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
    <div>
        <span class="font-bold">Save Failed:</span> A department with this name already exists. Please choose a unique name.
    </div>
</div>
<?php elseif(isset($_GET['error']) && $_GET['error'] === 'in_use'): ?>
<div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm" data-aos="fade-up">
    <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
    <div>
        <span class="font-bold">Delete Restricted:</span> Cannot delete this department because it is currently assigned to one or more positions or active staff. Please reassign the associated positions first.
    </div>
</div>
<?php endif; ?>

<!-- Data Table Card -->
<div class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 mb-8 shadow-sm" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 w-24">ID</th>
                    <th scope="col" class="px-6 py-4">Department Name</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                <?php if(empty($data['departments'])): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fa-solid fa-sitemap text-2xl"></i>
                            </div>
                            <p class="font-semibold text-slate-700 dark:text-slate-200">No departments found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['departments'] as $dept): ?>
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500 dark:text-slate-400">
                            #<?= htmlspecialchars($dept['DeptID']) ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm shadow-sm">
                                <i class="fa-solid fa-building-user"></i>
                            </div>
                            <span class="text-sm"><?= htmlspecialchars($dept['DeptName']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editDepartment(<?= $dept['DeptID'] ?>, '<?= htmlspecialchars(addslashes($dept['DeptName'])) ?>')" 
                                        class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:hover:bg-indigo-900/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </button>
                                <form action="/payrollsystem/admin/departments" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $dept['DeptID'] ?>">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                        <i class="fa-solid fa-trash mr-1"></i> Delete
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

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-plus text-indigo-500"></i> Add Department
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/departments" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            <div class="mb-4">
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department Name</label>
                <input type="text" name="name" id="name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="e.g. Information Technology">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">Save Department</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-pen-to-square text-indigo-500"></i> Edit Department
            </h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/departments" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-4">
                <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">Update Department</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editDepartment(id, name) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
