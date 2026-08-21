<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-id-badge"></i>
                    <span>Role Architecture</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    <?= count($data['positions'] ?? []) ?> Job Roles
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                Position & <span class="gradient-text">Role</span> Profiles
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Configure job positions, associate department designations, and assign baseline salary standards.</p>
        </div>
        <button onclick="openAddModal()" 
                class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 hover:bg-slate-50 text-xs font-extrabold shadow-lg hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus text-indigo-600"></i>
            <span>Add Position</span>
        </button>
    </div>
</div>

<!-- ============ ERROR & SUCCESS ALERTS ============ -->
<?php if(isset($_GET['error'])): ?>
    <?php if($_GET['error'] === 'duplicate'): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm animate__animated animate__shakeX">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <div>
            <span class="font-bold">Duplicate Rule Violation:</span> A position with this name already exists. Please choose a unique position title.
        </div>
    </div>
    <?php elseif($_GET['error'] === 'in_use'): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3 shadow-sm animate__animated animate__shakeX">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <div>
            <span class="font-bold">Delete Restricted:</span> Cannot delete this position because it is currently assigned to one or more employees. Please reassign the employees first.
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
            <span class="font-bold">Success:</span> Position operation completed successfully.
        </div>
    </div>
<?php endif; ?>

<!-- ============ POSITIONS CONTROLLER & TABLE (ALPINE.JS) ============ -->
<div x-data="{
    search: '',
    deptFilter: '',
    sortField: '<?= htmlspecialchars($data['currentSort'] ?? 'PositionID') ?>',
    sortDir: '<?= htmlspecialchars($data['currentOrder'] ?? 'desc') ?>',
    positions: <?= htmlspecialchars(json_encode($data['positions'] ?? []), ENT_QUOTES, 'UTF-8') ?>,
    
    sortBy(field) {
        if (this.sortField === field) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortField = field;
            this.sortDir = 'asc';
        }
    },
    
    get sortedPositions() {
        return this.positions
            .filter(p => {
                const q = this.search.toLowerCase().trim();
                const matchSearch = !q || 
                    String(p.PositionName || '').toLowerCase().includes(q) || 
                    String(p.DeptName || '').toLowerCase().includes(q) || 
                    String(p.PositionID).includes(q) ||
                    String(p.BasicSalary || '').includes(q);
                const matchDept = !this.deptFilter || p.DeptID == this.deptFilter;
                return matchSearch && matchDept;
            })
            .sort((a, b) => {
                let valA = a[this.sortField];
                let valB = b[this.sortField];
                
                if (this.sortField === 'PositionID' || this.sortField === 'BasicSalary') {
                    valA = parseFloat(valA) || 0;
                    valB = parseFloat(valB) || 0;
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
        
        <!-- Search Input -->
        <div class="relative w-full lg:w-72">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" x-model="search" placeholder="Search position, dept, or ID..." 
                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm transition-all">
            <button x-show="search" @click="search=''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Filter and Sort Group -->
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <!-- Department Filter -->
            <div class="flex items-center gap-2 flex-1 sm:flex-initial">
                <select x-model="deptFilter" class="w-full sm:w-auto px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer font-medium">
                    <option value="">All Departments</option>
                    <?php foreach($data['departments'] as $dept): ?>
                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sort By Dropdown -->
            <div class="flex items-center gap-2 flex-1 sm:flex-initial">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap hidden sm:flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-down-short-wide text-indigo-500"></i> Sort:
                </label>
                <select @change="
                    const val = $event.target.value.split('-');
                    sortField = val[0];
                    sortDir = val[1];
                " class="w-full sm:w-auto px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer font-medium">
                    <option value="PositionID-desc" :selected="sortField === 'PositionID' && sortDir === 'desc'">ID (Descending - Newest First)</option>
                    <option value="PositionID-asc" :selected="sortField === 'PositionID' && sortDir === 'asc'">ID (Ascending - Oldest First)</option>
                    <option value="PositionName-asc" :selected="sortField === 'PositionName' && sortDir === 'asc'">Position Name (A - Z)</option>
                    <option value="PositionName-desc" :selected="sortField === 'PositionName' && sortDir === 'desc'">Position Name (Z - A)</option>
                    <option value="DeptName-asc" :selected="sortField === 'DeptName' && sortDir === 'asc'">Department (A - Z)</option>
                    <option value="DeptName-desc" :selected="sortField === 'DeptName' && sortDir === 'desc'">Department (Z - A)</option>
                    <option value="BasicSalary-desc" :selected="sortField === 'BasicSalary' && sortDir === 'desc'">Salary (High to Low)</option>
                    <option value="BasicSalary-asc" :selected="sortField === 'BasicSalary' && sortDir === 'asc'">Salary (Low to High)</option>
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
                        <!-- ID Header -->
                        <th scope="col" class="px-6 py-4 w-28 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="sortBy('PositionID')">
                            <div class="flex items-center gap-1.5">
                                <span>ID</span>
                                <span class="text-slate-400">
                                    <i x-show="sortField !== 'PositionID'" class="fa-solid fa-sort text-[10px]"></i>
                                    <i x-show="sortField === 'PositionID' && sortDir === 'asc'" class="fa-solid fa-arrow-up-1-9 text-indigo-600 dark:text-sky-400 text-xs"></i>
                                    <i x-show="sortField === 'PositionID' && sortDir === 'desc'" class="fa-solid fa-arrow-down-9-1 text-indigo-600 dark:text-sky-400 text-xs"></i>
                                </span>
                            </div>
                        </th>

                        <!-- Department Header -->
                        <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="sortBy('DeptName')">
                            <div class="flex items-center gap-1.5">
                                <span>Department</span>
                                <span class="text-slate-400">
                                    <i x-show="sortField !== 'DeptName'" class="fa-solid fa-sort text-[10px]"></i>
                                    <i x-show="sortField === 'DeptName' && sortDir === 'asc'" class="fa-solid fa-arrow-up-a-z text-indigo-600 dark:text-sky-400 text-xs"></i>
                                    <i x-show="sortField === 'DeptName' && sortDir === 'desc'" class="fa-solid fa-arrow-down-z-a text-indigo-600 dark:text-sky-400 text-xs"></i>
                                </span>
                            </div>
                        </th>

                        <!-- Position Name Header -->
                        <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="sortBy('PositionName')">
                            <div class="flex items-center gap-1.5">
                                <span>Position Name</span>
                                <span class="text-slate-400">
                                    <i x-show="sortField !== 'PositionName'" class="fa-solid fa-sort text-[10px]"></i>
                                    <i x-show="sortField === 'PositionName' && sortDir === 'asc'" class="fa-solid fa-arrow-up-a-z text-indigo-600 dark:text-sky-400 text-xs"></i>
                                    <i x-show="sortField === 'PositionName' && sortDir === 'desc'" class="fa-solid fa-arrow-down-z-a text-indigo-600 dark:text-sky-400 text-xs"></i>
                                </span>
                            </div>
                        </th>

                        <!-- Salary Header -->
                        <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="sortBy('BasicSalary')">
                            <div class="flex items-center gap-1.5">
                                <span>Baseline Salary</span>
                                <span class="text-slate-400">
                                    <i x-show="sortField !== 'BasicSalary'" class="fa-solid fa-sort text-[10px]"></i>
                                    <i x-show="sortField === 'BasicSalary' && sortDir === 'asc'" class="fa-solid fa-arrow-up-1-9 text-emerald-600 dark:text-emerald-400 text-xs"></i>
                                    <i x-show="sortField === 'BasicSalary' && sortDir === 'desc'" class="fa-solid fa-arrow-down-9-1 text-emerald-600 dark:text-emerald-400 text-xs"></i>
                                </span>
                            </div>
                        </th>

                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <template x-if="sortedPositions.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="w-12 h-12 mx-auto bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3 text-indigo-500">
                                    <i class="fa-solid fa-id-badge text-2xl"></i>
                                </div>
                                <p class="font-semibold text-slate-700 dark:text-slate-200">No positions found</p>
                                <p class="text-xs text-slate-400 mt-0.5" x-show="search || deptFilter">Try adjusting your search or filters.</p>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="pos in sortedPositions" :key="pos.PositionID">
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500 dark:text-slate-400">
                                #<span x-text="pos.PositionID"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800" x-text="pos.DeptName || 'Unassigned'">
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white" x-text="pos.PositionName">
                            </td>
                            <td class="px-6 py-4 font-extrabold text-emerald-600 dark:text-emerald-400 font-mono text-sm">
                                <span x-text="Number(pos.BasicSalary || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span> 
                                <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">MMK</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editPosition(pos.PositionID, pos.PositionName, pos.DeptID, pos.BasicSalary)" 
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:hover:bg-indigo-900/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </button>
                                    <form action="/payrollsystem/admin/positions" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this position?');">
                                        <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" :value="pos.PositionID">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold text-xs transition-all hover:scale-105 shadow-sm">
                                            <i class="fa-solid fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
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
                <i class="fa-solid fa-plus text-indigo-500"></i> Add Position
            </h3>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/positions" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">
            <div>
                <label for="department_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
                <select name="department_id" id="department_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <option value="">Select Department</option>
                    <?php foreach($data['departments'] as $dept): ?>
                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Position Name</label>
                <input type="text" name="name" id="name" required oninput="validateAddPosName(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm transition-all" placeholder="e.g. Lead Software Engineer">
                <p id="add_pos_error" class="hidden text-rose-500 text-xs font-bold mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>A position with this name already exists.</span>
                </p>
            </div>
            <div>
                <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Baseline Salary (MMK)</label>
                <input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" value="0.00" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm font-mono">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" id="add_pos_submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Save Position</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform transition-all animate__animated animate__fadeInUp">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-pen-to-square text-indigo-500"></i> Edit Position
            </h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form action="/payrollsystem/admin/positions" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label for="edit_department_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
                <select name="department_id" id="edit_department_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm cursor-pointer">
                    <?php foreach($data['departments'] as $dept): ?>
                        <option value="<?= $dept['DeptID'] ?>"><?= htmlspecialchars($dept['DeptName']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Position Name</label>
                <input type="text" name="name" id="edit_name" required oninput="validateEditPosName(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm transition-all">
                <p id="edit_pos_error" class="hidden text-rose-500 text-xs font-bold mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>A position with this name already exists.</span>
                </p>
            </div>
            <div>
                <label for="edit_basic_salary" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Baseline Salary (MMK)</label>
                <input type="number" step="0.01" min="0" name="basic_salary" id="edit_basic_salary" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm font-mono">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" id="edit_pos_submit" class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Update Position</button>
            </div>
        </form>
    </div>
</div>

<script>
    const posList = <?= json_encode($data['positions'] ?? []) ?>;

    function openAddModal() {
        document.getElementById('name').value = '';
        document.getElementById('department_id').value = '';
        document.getElementById('basic_salary').value = '0.00';
        document.getElementById('add_pos_error').classList.add('hidden');
        document.getElementById('name').classList.remove('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
        document.getElementById('add_pos_submit').disabled = false;
        document.getElementById('addModal').classList.remove('hidden');
    }

    function validateAddPosName(val) {
        const trimmed = val.trim().toLowerCase();
        const errorEl = document.getElementById('add_pos_error');
        const submitBtn = document.getElementById('add_pos_submit');
        const inputEl = document.getElementById('name');

        const exists = posList.some(p => p.PositionName.trim().toLowerCase() === trimmed);
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

    function editPosition(id, name, department_id, basic_salary) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_department_id').value = department_id;
        document.getElementById('edit_basic_salary').value = basic_salary;
        document.getElementById('edit_pos_error').classList.add('hidden');
        document.getElementById('edit_name').classList.remove('border-rose-500', 'focus:ring-rose-500/20', 'focus:border-rose-500');
        document.getElementById('edit_pos_submit').disabled = false;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function validateEditPosName(val) {
        const currentId = document.getElementById('edit_id').value;
        const trimmed = val.trim().toLowerCase();
        const errorEl = document.getElementById('edit_pos_error');
        const submitBtn = document.getElementById('edit_pos_submit');
        const inputEl = document.getElementById('edit_name');

        const exists = posList.some(p => p.PositionID != currentId && p.PositionName.trim().toLowerCase() === trimmed);
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
