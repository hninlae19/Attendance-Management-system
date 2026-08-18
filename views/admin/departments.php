<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-sitemap text-secondary"></i>
                    <span>Organizational Structure</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 text-xs font-bold uppercase tracking-wider font-mono">
                    <?= count($data['departments'] ?? []) ?> Units
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Department <span class="gradient-text">Management</span>
            </h1>
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Structure and maintain business divisions, operational teams, and functional departments.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-extrabold shadow-lg shadow-violet-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Department</span>
        </button>
    </div>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
<div class="mb-6 p-4 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs font-semibold flex items-center gap-3 backdrop-blur-sm animate-pulse" data-aos="fade-up">
    <i class="fa-solid fa-circle-exclamation text-base"></i>
    <div>
        <span class="font-bold">Save Failed:</span> A department with this name already exists. Please choose a unique name.
    </div>
</div>
<?php endif; ?>

<!-- Table -->
<div class="card-glass rounded-3xl overflow-hidden border border-violet-500/20 mb-8 shadow-xl" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-surface/80 text-violet-300/80 border-b border-violet-900/40">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">#</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Department Name</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-violet-900/30">
                <?php if(empty($data['departments'])): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-12 h-12 mx-auto bg-surface rounded-2xl border border-violet-900/40 flex items-center justify-center mb-2 text-violet-400">
                                <i class="fa-solid fa-sitemap text-2xl"></i>
                            </div>
                            <p class="font-semibold text-gray-300">No departments found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach($data['departments'] as $dept): ?>
                    <tr class="hover:bg-violet-950/20 transition-colors group">
                        <td class="px-6 py-4 font-mono text-xs text-gray-500"><?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-6 py-4 font-bold text-white group-hover:text-violet-300 transition-colors flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-violet-600/20 border border-violet-500/30 text-violet-300 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-building-user"></i>
                            </div>
                            <span><?= htmlspecialchars($dept['DeptName']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editDepartment(<?= $dept['DeptID'] ?>, '<?= htmlspecialchars(addslashes($dept['DeptName'])) ?>')" 
                                        class="px-3 py-1.5 rounded-xl bg-violet-600/20 hover:bg-violet-600/40 text-violet-300 border border-violet-500/30 font-bold text-xs transition-all hover:scale-105">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </button>
                                <form action="/payrollsystem/admin/departments" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $dept['DeptID'] ?>">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 font-bold text-xs transition-all hover:scale-105">
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
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="card-glass rounded-3xl max-w-md w-full shadow-2xl border border-violet-500/30 overflow-hidden">
        <div class="px-6 py-4 border-b border-violet-900/40 flex justify-between items-center bg-surface/80">
            <h3 class="text-base font-extrabold text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-plus text-secondary"></i> Add Department
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-surface text-gray-400 hover:text-white border border-violet-900/40 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/departments" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            <div class="mb-4">
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Department Name</label>
                <input type="text" name="name" id="name" required class="w-full px-3.5 py-2.5 bg-darker/70 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 text-xs shadow-inner" placeholder="e.g. Information Technology">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-400 bg-surface border border-violet-900/40 rounded-xl hover:text-white transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl shadow-lg shadow-violet-600/30 transition-all">Save Department</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="card-glass rounded-3xl max-w-md w-full shadow-2xl border border-violet-500/30 overflow-hidden">
        <div class="px-6 py-4 border-b border-violet-900/40 flex justify-between items-center bg-surface/80">
            <h3 class="text-base font-extrabold text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-pen-to-square text-secondary"></i> Edit Department
            </h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-surface text-gray-400 hover:text-white border border-violet-900/40 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/departments" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-4">
                <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Department Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-3.5 py-2.5 bg-darker/70 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 text-xs shadow-inner">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-400 bg-surface border border-violet-900/40 rounded-xl hover:text-white transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 rounded-xl shadow-lg shadow-violet-600/30 transition-all">Update Department</button>
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
