<?php $employee = $data['employee'] ?? []; ?>

<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-600 p-6 lg:p-7 mb-8 shadow-xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Account & Security</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-md font-mono">
                    EMP-<?= str_pad($employee['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT) ?>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Profile</span> & Security
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm mt-1">Manage your contact details, profile photograph, and account credentials.</p>
        </div>
    </div>
</div>

<!-- Error/Success Messages -->
<?php if (isset($_SESSION['profile_error'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-3" data-aos="fade-up">
        <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
        <span><?= htmlspecialchars($_SESSION['profile_error']) ?></span>
    </div>
    <?php unset($_SESSION['profile_error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['profile_success'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-3" data-aos="fade-up">
        <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
        <span><?= htmlspecialchars($_SESSION['profile_success']) ?></span>
    </div>
    <?php unset($_SESSION['profile_success']); ?>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="100">
    
    <!-- Profile Picture & Basic Info -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 text-center border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden flex flex-col justify-between h-full">
            <div>
                <!-- Avatar with glowing border -->
                <div class="relative w-28 h-28 mx-auto mb-4 group">
                    <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 opacity-70 blur-md group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-white dark:border-slate-800 bg-slate-100 dark:bg-slate-700 shadow-xl flex items-center justify-center">
                        <?php if (!empty($employee['ProfilePhoto'])): ?>
                            <img src="/payrollsystem/<?= htmlspecialchars($employee['ProfilePhoto']) ?>" alt="Profile" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-sky-500 flex items-center justify-center text-3xl font-extrabold text-white font-outfit">
                                <?= htmlspecialchars(strtoupper(substr($employee['FirstName'] ?? 'E', 0, 1))) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white font-outfit"><?= htmlspecialchars(($employee['FirstName'] ?? '') . ' ' . ($employee['LastName'] ?? '')) ?></h3>
                <p class="text-indigo-600 dark:text-sky-400 font-bold text-xs mt-0.5"><?= htmlspecialchars($employee['PositionName'] ?? 'Employee') ?></p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><?= htmlspecialchars($employee['DeptName'] ?? 'Department') ?></p>
                
                <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-mono">
                    <i class="fa-solid fa-fingerprint text-indigo-500 text-xs"></i>
                    <span>EMP-<?= str_pad($employee['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>
            
            <!-- Photo Upload Form -->
            <div class="mt-6 border-t border-slate-100 dark:border-slate-700 pt-5">
                <form action="/payrollsystem/employee/updatePhoto" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Update Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-indigo-50 dark:file:bg-indigo-950/50 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 mb-3 cursor-pointer bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-300 dark:border-slate-700 p-1">
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fa-solid fa-upload mr-1.5"></i> Upload Photo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Information & Password -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Info Form -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2 border-b border-slate-100 dark:border-slate-700 pb-3 font-outfit">
                <i class="fa-solid fa-user-pen text-indigo-500"></i> Personal Information
            </h3>
            <form action="/payrollsystem/employee/updateProfile" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">First Name</label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($employee['FirstName'] ?? '') ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Last Name</label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($employee['LastName'] ?? '') ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($employee['PhoneNumber'] ?? '') ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm">
                        <span id="phone-error" class="text-xs text-rose-500 hidden mt-1 font-bold">Invalid phone number format.</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($employee['Email'] ?? '') ?>" readonly class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl cursor-not-allowed text-xs shadow-inner">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Residential Address</label>
                        <textarea name="address" rows="3" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm"><?= htmlspecialchars($employee['Address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:scale-105">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2 border-b border-slate-100 dark:border-slate-700 pb-3 font-outfit">
                <i class="fa-solid fa-lock text-indigo-500"></i> Change Password
            </h3>
            <form action="/payrollsystem/employee/changePassword" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="••••••••">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                            <input type="password" name="new_password" id="new_password" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="••••••••">
                            <span id="password-error" class="text-xs text-rose-500 hidden mt-1 font-bold">Password must be at least 6 characters.</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                            <input type="password" name="confirm_password" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs shadow-sm" placeholder="••••••••">
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:scale-105">
                        <i class="fa-solid fa-key mr-1.5"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inline Validation
    const phoneInput = document.getElementById('phone_number');
    const phoneError = document.getElementById('phone-error');
    if (phoneInput && phoneError) {
        phoneInput.addEventListener('input', function() {
            const phoneVal = this.value.trim();
            const isValid = /^[0-9\-\+\s\(\)]{7,20}$/.test(phoneVal) || phoneVal === '';
            if (!isValid) {
                phoneError.classList.remove('hidden');
                this.classList.add('border-rose-500');
            } else {
                phoneError.classList.add('hidden');
                this.classList.remove('border-rose-500');
            }
        });
    }

    const passwordInput = document.getElementById('new_password');
    const passwordError = document.getElementById('password-error');
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
