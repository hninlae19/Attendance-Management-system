<?php $employee = $data['employee'] ?? []; ?>

<!-- ============ HEADER BANNER ============ -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-500 to-cyan-500 border border-violet-500/25 p-6 lg:p-7 mb-8 shadow-2xl" data-aos="fade-down">
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-id-card text-secondary"></i>
                    <span>Account & Security</span>
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 text-xs font-bold uppercase tracking-wider font-mono">
                    EMP-<?= str_pad($employee['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT) ?>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-outfit">
                My <span class="gradient-text">Profile</span> & Security
            </h1>
            <p class="text-gray-300 text-xs sm:text-sm mt-1">Manage your contact details, profile photograph, and account credentials.</p>
        </div>
    </div>
</div>

<!-- Error/Success Messages -->
<?php if (isset($_SESSION['profile_error'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs font-semibold flex items-center gap-3 backdrop-blur-sm animate-pulse" data-aos="fade-up">
        <i class="fa-solid fa-circle-exclamation text-base"></i>
        <span><?= htmlspecialchars($_SESSION['profile_error']) ?></span>
    </div>
    <?php unset($_SESSION['profile_error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['profile_success'])): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs font-semibold flex items-center gap-3 backdrop-blur-sm animate-pulse" data-aos="fade-up">
        <i class="fa-solid fa-circle-check text-base"></i>
        <span><?= htmlspecialchars($_SESSION['profile_success']) ?></span>
    </div>
    <?php unset($_SESSION['profile_success']); ?>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-delay="100">
    
    <!-- Profile Picture & Basic Info -->
    <div class="lg:col-span-1">
        <div class="card-glass rounded-3xl p-6 text-center border border-violet-500/20 shadow-xl relative overflow-hidden flex flex-col justify-between h-full">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-violet-600/10 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <!-- Avatar with glowing border -->
                <div class="relative w-32 h-32 mx-auto mb-4 group">
                    <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-violet-600 via-cyan-500 to-amber-500 opacity-70 blur-md group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-violet-400/40 bg-surface shadow-2xl">
                        <?php if (!empty($employee['ProfilePhoto'])): ?>
                            <img src="/payrollsystem/<?= htmlspecialchars($employee['ProfilePhoto']) ?>" alt="Profile" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-violet-600 to-purple-800 flex items-center justify-center text-4xl font-extrabold text-white">
                                <?= htmlspecialchars(strtoupper(substr($employee['FirstName'] ?? 'E', 0, 1))) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold text-white font-outfit"><?= htmlspecialchars(($employee['FirstName'] ?? '') . ' ' . ($employee['LastName'] ?? '')) ?></h3>
                <p class="text-cyan-400 font-bold text-xs mt-0.5"><?= htmlspecialchars($employee['PositionName'] ?? 'Employee') ?></p>
                <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($employee['DeptName'] ?? 'Department') ?></p>
                
                <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-surface/90 border border-violet-900/40 text-gray-300 text-xs font-mono">
                    <i class="fa-solid fa-fingerprint text-secondary text-xs"></i>
                    <span>EMP-<?= str_pad($employee['EmpID'] ?? 0, 4, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>
            
            <!-- Photo Upload Form -->
            <div class="mt-6 border-t border-violet-900/40 pt-5">
                <form action="/payrollsystem/employee/updatePhoto" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-2">Update Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-violet-600/30 file:text-violet-300 hover:file:bg-violet-600/50 mb-3 cursor-pointer bg-darker/60 rounded-xl border border-violet-700/30 p-1">
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-violet-600/25 hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fa-solid fa-upload mr-1.5"></i> Upload Photo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Information & Password -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Info Form -->
        <div class="card-glass rounded-3xl p-6 border border-violet-500/20 shadow-xl">
            <h3 class="text-base font-extrabold text-white mb-4 flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-user-pen text-secondary"></i> Personal Information
            </h3>
            <form action="/payrollsystem/employee/updateProfile" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">First Name</label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($employee['FirstName'] ?? '') ?>" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Last Name</label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($employee['LastName'] ?? '') ?>" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($employee['PhoneNumber'] ?? '') ?>" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner">
                        <span id="phone-error" class="text-xs text-rose-400 hidden mt-1">Invalid phone number format.</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($employee['Email'] ?? '') ?>" readonly class="w-full px-3.5 py-2.5 bg-darker/80 border border-violet-900/40 text-gray-500 rounded-xl cursor-not-allowed text-xs">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Residential Address</label>
                        <textarea name="address" rows="3" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner"><?= htmlspecialchars($employee['Address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-violet-600/25 hover:scale-105">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="card-glass rounded-3xl p-6 border border-violet-500/20 shadow-xl">
            <h3 class="text-base font-extrabold text-white mb-4 flex items-center gap-2 font-outfit">
                <i class="fa-solid fa-lock text-secondary"></i> Change Password
            </h3>
            <form action="/payrollsystem/employee/changePassword" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner" placeholder="••••••••">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">New Password</label>
                            <input type="password" name="new_password" id="new_password" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner" placeholder="••••••••">
                            <span id="password-error" class="text-xs text-rose-400 hidden mt-1">Password must be at least 6 characters.</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Confirm New Password</label>
                            <input type="password" name="confirm_password" required class="w-full px-3.5 py-2.5 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 text-xs shadow-inner" placeholder="••••••••">
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-extrabold text-xs rounded-xl transition-all shadow-lg shadow-cyan-600/25 hover:scale-105">
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
