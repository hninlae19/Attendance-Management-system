<?php
$pending_requests = $data['pending_requests'] ?? [];
?>
<div class="space-y-6">
    <!-- ============ HEADER BANNER ============ -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                        <i class="fa-solid fa-key"></i>
                        <span>Security Management</span>
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                        <?= count($pending_requests) ?> Pending
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                    Password <span class="gradient-text">Reset Requests</span>
                </h1>
                <p class="text-indigo-100 text-xs sm:text-sm mt-1">Review identity verification and issue temporary or permanent account access credentials.</p>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['reset_success'])): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2.5 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span><?= htmlspecialchars($_SESSION['reset_success']) ?></span>
        </div>
        <?php unset($_SESSION['reset_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['reset_error'])): ?>
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2.5 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
            <span><?= htmlspecialchars($_SESSION['reset_error']) ?></span>
        </div>
        <?php unset($_SESSION['reset_error']); ?>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 font-bold tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Employee</th>
                        <th scope="col" class="px-6 py-4">Email</th>
                        <th scope="col" class="px-6 py-4">Department</th>
                        <th scope="col" class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    <?php if (empty($pending_requests)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center mb-3 text-emerald-600 dark:text-emerald-400 text-2xl shadow-sm">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">No pending password reset requests</p>
                                    <p class="text-xs text-slate-400 mt-0.5">All employee accounts are secure and verified.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_requests as $req): ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                                <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white text-xs">
                                    <?= htmlspecialchars($req['FirstName'] . ' ' . $req['LastName']) ?>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-700 dark:text-slate-300 font-medium"><?= htmlspecialchars($req['Email']) ?></td>
                                <td class="px-6 py-3.5 text-xs text-slate-700 dark:text-slate-300"><?= htmlspecialchars($req['DeptName'] ?? 'N/A') ?></td>
                                <td class="px-6 py-3.5 text-right">
                                    <button type="button" onclick="openResetModal(<?= $req['EmpID'] ?>, '<?= htmlspecialchars(addslashes($req['FirstName'].' '.$req['LastName'])) ?>')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 transition-all shadow-sm">
                                        <i class="fa-solid fa-key text-[11px]"></i> Reset Password
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm overflow-y-auto w-full md:inset-0 h-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-md">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform transition-all animate__animated animate__fadeInUp">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2 font-outfit">
                    <i class="fa-solid fa-key text-indigo-500"></i> Reset Password
                </h3>
                <button type="button" onclick="closeResetModal()" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
            <form action="/payrollsystem/admin/reset_employee_password" method="POST" class="p-6">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="emp_id" id="reset_emp_id">
                
                <div class="mb-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Setting new password for: <strong id="reset_emp_name" class="text-slate-900 dark:text-white"></strong></p>
                    <div class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 p-3 rounded-xl text-xs mb-4 border border-indigo-200 dark:border-indigo-800">
                        <i class="fa-solid fa-circle-info mr-1"></i> Please communicate the new password to the employee manually after resetting.
                    </div>
                    
                    <label class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">New Password</label>
                    <div class="flex gap-2">
                        <input type="text" name="new_password" id="new_password_input" required minlength="6" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                        <button type="button" onclick="generatePassword()" class="px-3.5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors shadow-sm" title="Generate Random">
                            <i class="fa-solid fa-dice"></i>
                        </button>
                    </div>
                    <span id="reset-password-error" class="text-xs text-rose-500 hidden mt-1 font-bold">Password must be at least 6 characters.</span>
                    <p class="mt-3 text-[11px] text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700">
                        <i class="fa-solid fa-lock mr-1 text-indigo-500"></i> The employee will be forced to change this password on next login.
                    </p>
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-2">
                    <button type="button" onclick="closeResetModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 rounded-xl shadow-lg shadow-indigo-500/25 transition-all">
                        Save New Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResetModal(empId, empName) {
    document.getElementById('reset_emp_id').value = empId;
    document.getElementById('reset_emp_name').textContent = empName;
    document.getElementById('new_password_input').value = '';
    document.getElementById('resetModal').classList.remove('hidden');
}

function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}

function generatePassword() {
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    for (let i = 0; i < 10; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    const pwdInput = document.getElementById('new_password_input');
    pwdInput.value = password;
    pwdInput.dispatchEvent(new Event('input'));
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new_password_input');
    const passwordError = document.getElementById('reset-password-error');
    if (passwordInput && passwordError) {
        passwordInput.addEventListener('input', function() {
            const pwdVal = this.value;
            if (pwdVal.length > 0 && pwdVal.length < 6) {
                passwordError.classList.remove('hidden');
                this.classList.add('border-rose-500');
            } else {
                passwordError.classList.add('hidden');
                this.classList.remove('border-rose-500');
            }
        });
    }
});
</script>
