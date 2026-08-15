<?php $employee = $data['employee'] ?? []; ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">My Profile</h1>
    </div>

    <!-- Error/Success Messages -->
    <?php if (isset($_SESSION['profile_error'])): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl">
            <?= htmlspecialchars($_SESSION['profile_error']) ?>
        </div>
        <?php unset($_SESSION['profile_error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['profile_success'])): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-xl">
            <?= htmlspecialchars($_SESSION['profile_success']) ?>
        </div>
        <?php unset($_SESSION['profile_success']); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture & Basic Info -->
        <div class="lg:col-span-1">
            <div class="card-glass rounded-2xl p-6 text-center shadow-lg border border-violet-500/20">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <?php if (!empty($employee['ProfilePhoto'])): ?>
                        <img src="/payrollsystem/<?= htmlspecialchars($employee['ProfilePhoto']) ?>" alt="Profile" class="w-full h-full object-cover rounded-full border-4 border-violet-500/30">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-violet-500 to-purple-700 rounded-full flex items-center justify-center text-4xl font-bold text-white border-4 border-violet-500/30">
                            <?= htmlspecialchars(strtoupper(substr($employee['FirstName'], 0, 1))) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($employee['FirstName'] . ' ' . $employee['LastName']) ?></h3>
                <p class="text-violet-400 font-medium"><?= htmlspecialchars($employee['PositionName'] ?? 'Employee') ?></p>
                <p class="text-sm text-gray-400 mt-1"><?= htmlspecialchars($employee['DeptName'] ?? 'Department') ?></p>
                
                <div class="mt-6 border-t border-violet-500/20 pt-6">
                    <form action="/payrollsystem/employee/updatePhoto" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Update Photo</label>
                        <input type="file" name="profile_photo" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-500/20 file:text-violet-400 hover:file:bg-violet-500/30 mb-3 cursor-pointer">
                        <button type="submit" class="w-full py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-violet-500/25">
                            Upload Photo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Information & Password -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info Form -->
            <div class="card-glass rounded-2xl p-6 shadow-lg border border-violet-500/20">
                <h3 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-user-pen mr-2 text-violet-400"></i> Personal Information</h3>
                <form action="/payrollsystem/employee/updateProfile" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">First Name</label>
                            <input type="text" name="first_name" value="<?= htmlspecialchars($employee['FirstName']) ?>" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Last Name</label>
                            <input type="text" name="last_name" value="<?= htmlspecialchars($employee['LastName']) ?>" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($employee['PhoneNumber']) ?>" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                            <span id="phone-error" class="text-xs text-red-500 hidden mt-1">Invalid phone number format.</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($employee['Email']) ?>" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                            <textarea name="address" rows="3" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none"><?= htmlspecialchars($employee['Address']) ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-violet-500/25">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="card-glass rounded-2xl p-6 shadow-lg border border-violet-500/20">
                <h3 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-lock mr-2 text-violet-400"></i> Change Password</h3>
                <form action="/payrollsystem/employee/changePassword" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">New Password</label>
                            <input type="password" name="new_password" id="new_password" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                            <span id="password-error" class="text-xs text-red-500 hidden mt-1">Password must be at least 6 characters.</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" required class="w-full px-4 py-2 bg-gray-800/50 border border-violet-500/20 rounded-xl focus:ring-2 focus:ring-violet-500 text-white outline-none">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-violet-500/25">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
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
                this.classList.add('border-red-500');
            } else {
                phoneError.classList.add('hidden');
                this.classList.remove('border-red-500');
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
                this.classList.add('border-red-500');
            } else {
                passwordError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });
    }
});
</script>
