<?php
$pending_requests = $data['pending_requests'] ?? [];
?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Password Resets</h1>
            <p class="text-sm text-gray-500 mt-1">Manage employee password reset requests.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['reset_success'])): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-xl">
            <?= htmlspecialchars($_SESSION['reset_success']) ?>
        </div>
        <?php unset($_SESSION['reset_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['reset_error'])): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl">
            <?= htmlspecialchars($_SESSION['reset_error']) ?>
        </div>
        <?php unset($_SESSION['reset_error']); ?>
    <?php endif; ?>

    <div class="card-glass rounded-2xl border border-violet-500/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-4 py-3">Employee</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">Department</th>
                        <th scope="col" class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pending_requests)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-check-circle text-4xl mb-3 text-emerald-500/50"></i>
                                    <p class="font-medium">No pending password reset requests.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_requests as $req): ?>
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    <?= htmlspecialchars($req['FirstName'] . ' ' . $req['LastName']) ?>
                                </td>
                                <td class="px-4 py-3"><?= htmlspecialchars($req['Email']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($req['DeptName'] ?? 'N/A') ?></td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" onclick="openResetModal(<?= $req['EmpID'] ?>, '<?= htmlspecialchars(addslashes($req['FirstName'].' '.$req['LastName'])) ?>')" class="text-violet-600 hover:text-violet-900 dark:text-violet-400 dark:hover:text-violet-300 bg-violet-50 dark:bg-violet-900/20 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                                        <i class="fa-solid fa-key mr-1"></i> Reset Password
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
<div id="resetModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 dark:bg-gray-900/80 backdrop-blur-sm overflow-y-auto w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-surface rounded-2xl shadow dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Reset Password
                </h3>
                <button type="button" onclick="closeResetModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="/payrollsystem/admin/reset_employee_password" method="POST" class="p-4 md:p-5">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="emp_id" id="reset_emp_id">
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Setting new password for: <strong id="reset_emp_name" class="text-gray-900 dark:text-white"></strong></p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 p-3 rounded-lg text-xs mb-4">
                        <i class="fa-solid fa-circle-info mr-1"></i> Please communicate the new password to the employee manually after resetting.
                    </div>
                    
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                    <div class="flex gap-2">
                        <input type="text" name="new_password" id="new_password_input" required minlength="6" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <button type="button" onclick="generatePassword()" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" title="Generate Random">
                            <i class="fa-solid fa-dice"></i>
                        </button>
                    </div>
                    <span id="reset-password-error" class="text-xs text-red-500 hidden mt-1">Password must be at least 6 characters.</span>
                    <p class="mt-2 text-xs text-violet-600 dark:text-violet-400 font-semibold bg-violet-50 dark:bg-violet-900/20 p-2 rounded-lg border border-violet-100 dark:border-violet-800/30">
                        <i class="fa-solid fa-circle-info mr-1"></i> Please copy this password and securely share it with the employee. Once they log in, they will receive a notification prompting them to change it.
                    </p>
                </div>
                
                <button type="submit" class="w-full text-white bg-violet-600 hover:bg-violet-700 focus:ring-4 focus:outline-none focus:ring-violet-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center dark:bg-violet-600 dark:hover:bg-violet-700 dark:focus:ring-violet-800 transition-all">
                    Save New Password
                </button>
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
    pwdInput.dispatchEvent(new Event('input')); // trigger validation
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new_password_input');
    const passwordError = document.getElementById('reset-password-error');
    if (passwordInput && passwordError) {
        passwordInput.addEventListener('input', function() {
            const pwdVal = this.value;
            if (pwdVal.length > 0 && pwdVal.length < 6) {
                passwordError.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                passwordError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });
    }
});
</script>
