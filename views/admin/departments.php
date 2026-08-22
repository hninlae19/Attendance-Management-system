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
        <button onclick="openAddModal()" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus text-indigo-600"></i>
            <span>Add Department</span>
        </button>
    </div>
</div>

<!-- ============ ERROR & SUCCESS ALERTS ============ -->
<?php if(isset($_GET['error'])): ?>
    <?php if($_GET['error'] === 'duplicate'): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm animate__animated animate__shakeX">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <div>
            <span class="font-bold">Duplicate Rule Violation:</span> A department with this name already exists. Please choose a unique name.
        </div>
    </div>
    <?php elseif($_GET['error'] === 'in_use'): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm animate__animated animate__shakeX">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <div>
            <span class="font-bold">Delete Restricted:</span> Cannot delete this department because it is currently assigned to one or more positions or active staff. Please reassign the associated positions first.
        </div>
    </div>
    <?php else: ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm animate__animated animate__shakeX">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <div>
            <span class="font-bold">Operation Failed:</span> <?= htmlspecialchars($_GET['error']) ?>
        </div>
    </div>
    <?php endif; ?>
<?php elseif(isset($_GET['msg'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-3 shadow-sm" data-aos="fade-up">
        <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
        <div>
            <span class="font-bold">Success:</span> Department operation completed successfully.
        </div>
    </div>
<?php endif; ?>

<!-- ============ DEPARTMENT CONTROLLER & TABLE (ALPINE.JS) ============ -->
<div x-data="{
    search: '',
    sortField: '<?= htmlspecialchars($data['currentSort'] ?? 'DeptID') ?>',
    sortDir: '<?= htmlspecialchars($data['currentOrder'] ?? 'desc') ?>',
    departments: <?= htmlspecialchars(json_encode($data['departments'] ?? []), ENT_QUOTES, 'UTF-8') ?>,
    
    sortBy(field) {
        if (this.sortField === field) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortField = field;
            this.sortDir = 'asc';
        }
    },
    
    get sortedDepartments() {
        return this.departments
            .filter(d => {
                if (!this.search) return true;
                const q = this.search.toLowerCase().trim();
                return String(d.DeptName || '').toLowerCase().includes(q) || String(d.DeptID).includes(q);
            })
            .sort((a, b) => {
                let valA = a[this.sortField];
                let valB = b[this.sortField];
                
                if (this.sortField === 'DeptID') {
                    valA = parseInt(valA) || 0;
                    valB = parseInt(valB) || 0;
                } else {
                    valA = String(valA || '').toLowerCase();
                    valB = String(valB || '').toLowerCase();
                }
                
                if (valA < valB) return this.sortDir === 'asc' ? -1 : 1;
                if (valA > valB) return this.sortDir === 'asc' ? 1 : -1;
                return 0;
            });
    }
}" class="space-y-6" data-aos="fade-up" data-aos-delay="100">

    <!-- Controls Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-4">
        
        <div class="flex items-center bg-slate-100 dark:bg-slate-900/50 p-1 rounded-xl w-full lg:w-auto">
            <a href="?view=active" class="flex-1 lg:flex-none text-center px-4 py-2 rounded-lg text-xs font-bold transition-all <?= ($data['viewMode'] ?? 'active') === 'active' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-sky-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' ?>">
                <i class="fa-solid fa-check-circle mr-1"></i> Active
            </a>
            <a href="?view=inactive" class="flex-1 lg:flex-none text-center px-4 py-2 rounded-lg text-xs font-bold transition-all <?= ($data['viewMode'] ?? 'active') === 'inactive' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-sky-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' ?>">
                <i class="fa-solid fa-archive mr-1"></i> Archived
            </a>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <!-- Search Input -->
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" x-model="search" placeholder="Search department or ID..." 
                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm transition-all">
            <button x-show="search" @click="search=''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Sorting Selector -->
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-down-short-wide text-indigo-500"></i> Sort By:
            </label>
            <select @change="
                const val = $event.target.value.split('-');
                sortField = val[0];
                sortDir = val[1];
            " class="px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer font-medium">
                <option value="DeptID-desc" :selected="sortField === 'DeptID' && sortDir === 'desc'">ID (Descending - Newest First)</option>
                <option value="DeptID-asc" :selected="sortField === 'DeptID' && sortDir === 'asc'">ID (Ascending - Oldest First)</option>
                <option value="DeptName-asc" :selected="sortField === 'DeptName' && sortDir === 'asc'">Department Name (A - Z)</option>
                <option value="DeptName-desc" :selected="sortField === 'DeptName' && sortDir === 'desc'">Department Name (Z - A)</option>
            </select>
        </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider select-none">
                    <tr>
                        <!-- ID Column Header -->
                        <th scope="col" class="px-6 py-4 w-28 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="sortBy('DeptID')">
                            <div class="flex items-center gap-1.5">
                                <span>ID</span>
                                <span class="text-slate-400">
                                    <i x-show="sortField !== 'DeptID'" class="fa-solid fa-sort text-[10px]"></i>
                                    <i x-show="sortField === 'DeptID' && sortDir === 'asc'" class="fa-solid fa-arrow-up-1-9 text-indigo-600 dark:text-sky-400 text-xs"></i>
                                    <i x-show="sortField === 'DeptID' && sortDir === 'desc'" class="fa-solid fa-arrow-down-9-1 text-indigo-600 dark:text-sky-400 text-xs"></i>
                                </span>
                            </div>
                        </th>

                        <!-- Department Name Column Header -->
                        <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="sortBy('DeptName')">
                            <div class="flex items-center gap-1.5">
                                <span>Department Name</span>
                                <span class="text-slate-400">
                                    <i x-show="sortField !== 'DeptName'" class="fa-solid fa-sort text-[10px]"></i>
                                    <i x-show="sortField === 'DeptName' && sortDir === 'asc'" class="fa-solid fa-arrow-up-a-z text-indigo-600 dark:text-sky-400 text-xs"></i>
                                    <i x-show="sortField === 'DeptName' && sortDir === 'desc'" class="fa-solid fa-arrow-down-z-a text-indigo-600 dark:text-sky-400 text-xs"></i>
                                </span>
                            </div>
                        </th>

                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <template x-if="sortedDepartments.length === 0">
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                    <i class="fa-solid fa-sitemap text-2xl"></i>
                                </div>
                                <p class="font-semibold text-slate-700 dark:text-slate-200">No departments found</p>
                                <p class="text-xs text-slate-400 mt-0.5" x-show="search">No results matching "<span x-text="search"></span>"</p>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="dept in sortedDepartments" :key="dept.DeptID">
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500 dark:text-slate-400">
                                #<span x-text="dept.DeptID"></span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm shadow-sm">
                                        <i class="fa-solid fa-building-user"></i>
                                    </div>
                                    <span class="text-sm" x-text="dept.DeptName"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if(($data['viewMode'] ?? 'active') === 'active'): ?>
                                        <button @click="editDepartment(dept.DeptID, dept.DeptName)" 
                                                class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:hover:bg-indigo-900/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                        </button>
                                        <form action="/payrollsystem/admin/departments?view=active" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" :value="dept.DeptID">
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                                <i class="fa-solid fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="/payrollsystem/admin/departments?view=inactive" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to restore this department?');">
                                            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                            <input type="hidden" name="action" value="restore">
                                            <input type="hidden" name="id" :value="dept.DeptID">
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                                <i class="fa-solid fa-rotate-left mr-1"></i> Restore
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
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
                <input type="text" name="name" id="name" required oninput="validateAddDeptName(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm transition-all" placeholder="e.g. Information Technology">
                <p id="add_dept_error" class="hidden text-rose-500 text-xs font-bold mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>A department with this name already exists.</span>
                </p>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" id="add_dept_submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Save Department</button>
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
                <input type="text" name="name" id="edit_name" required oninput="validateEditDeptName(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm transition-all">
                <p id="edit_dept_error" class="hidden text-rose-500 text-xs font-bold mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>A department with this name already exists.</span>
                </p>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" id="edit_dept_submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Update Department</button>
            </div>
        </form>
    </div>
</div>

<script>
    const deptList = <?= json_encode($data['departments'] ?? []) ?>;

    function openAddModal() {
        document.getElementById('name').value = '';
        document.getElementById('add_dept_error').classList.add('hidden');
        document.getElementById('name').classList.remove('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
        document.getElementById('add_dept_submit').disabled = false;
        document.getElementById('addModal').classList.remove('hidden');
    }

    function validateAddDeptName(val) {
        const trimmed = val.trim().toLowerCase();
        const errorEl = document.getElementById('add_dept_error');
        const submitBtn = document.getElementById('add_dept_submit');
        const inputEl = document.getElementById('name');

        const exists = deptList.some(d => d.DeptName.trim().toLowerCase() === trimmed);
        if (exists && trimmed.length > 0) {
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
            submitBtn.disabled = true;
        } else {
            errorEl.classList.add('hidden');
            inputEl.classList.remove('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
            submitBtn.disabled = false;
        }
    }

    function editDepartment(id, name) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_dept_error').classList.add('hidden');
        document.getElementById('edit_name').classList.remove('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
        document.getElementById('edit_dept_submit').disabled = false;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function validateEditDeptName(val) {
        const currentId = document.getElementById('edit_id').value;
        const trimmed = val.trim().toLowerCase();
        const errorEl = document.getElementById('edit_dept_error');
        const submitBtn = document.getElementById('edit_dept_submit');
        const inputEl = document.getElementById('edit_name');

        const exists = deptList.some(d => d.DeptID != currentId && d.DeptName.trim().toLowerCase() === trimmed);
        if (exists && trimmed.length > 0) {
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
            submitBtn.disabled = true;
        } else {
            errorEl.classList.add('hidden');
            inputEl.classList.remove('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
            submitBtn.disabled = false;
        }
    }
</script>
