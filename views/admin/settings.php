<div class="mb-6 flex justify-between items-center" data-aos="fade-down">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
        <p class="text-gray-500 text-sm mt-1">Configure global application parameters</p>
    </div>
</div>

<form action="/payrollsystem/admin/settings" method="POST" x-data="{ tab: 'general' }" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 pt-4 bg-gray-50/50 dark:bg-gray-700/30">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fa-solid fa-building mr-2"></i> General & Time
            </button>
            <button type="button" @click="tab = 'leave'" :class="tab === 'leave' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fa-solid fa-calendar-minus mr-2"></i> Leave Settings
            </button>
            <button type="button" @click="tab = 'deduction'" :class="tab === 'deduction' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fa-solid fa-minus-circle mr-2"></i> Deduction Settings
            </button>
        </nav>
    </div>

    <!-- General Tab -->
    <div x-show="tab === 'general'" class="p-6 space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-building mr-2 text-primary"></i> Company Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                    <input type="text" name="company_name" id="company_name" value="<?= htmlspecialchars($data['settings']['company_name'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-clock mr-2 text-primary"></i> Time & Attendance Rules</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="office_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Office Start Time</label>
                    <input type="time" name="office_start_time" id="office_start_time" value="<?= htmlspecialchars($data['settings']['office_start_time'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="office_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Office End Time</label>
                    <input type="time" name="office_end_time" id="office_end_time" value="<?= htmlspecialchars($data['settings']['office_end_time'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="late_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Late Time Threshold</label>
                    <input type="time" name="late_time" id="late_time" value="<?= htmlspecialchars($data['settings']['late_time'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Check-in after this time is marked Late.</p>
                </div>
                <div>
                    <label for="auto_checkout_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Auto Check-out Time</label>
                    <input type="time" name="auto_checkout_time" id="auto_checkout_time" value="<?= htmlspecialchars($data['settings']['auto_checkout_time'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="working_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Required Working Hours (Full Day)</label>
                    <input type="number" name="working_hours" id="working_hours" value="<?= htmlspecialchars($data['settings']['working_hours'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-business-time mr-2 text-primary"></i> Overtime Rules</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="weekday_ot_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Working Day Rate (Multiplier)</label>
                    <input type="number" step="0.01" name="weekday_ot_rate" id="weekday_ot_rate" value="<?= htmlspecialchars($data['settings']['weekday_ot_rate'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="weekend_ot_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weekend Rate (Multiplier)</label>
                    <input type="number" step="0.01" name="weekend_ot_rate" id="weekend_ot_rate" value="<?= htmlspecialchars($data['settings']['weekend_ot_rate'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="holiday_ot_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Holiday Rate (Multiplier)</label>
                    <input type="number" step="0.01" name="holiday_ot_rate" id="holiday_ot_rate" value="<?= htmlspecialchars($data['settings']['holiday_ot_rate'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="max_ot_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max OT Hours (Per Month)</label>
                    <input type="number" name="max_ot_hours" id="max_ot_hours" value="<?= htmlspecialchars($data['settings']['max_ot_hours'] ?? '') ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Settings Tab -->
    <div x-show="tab === 'leave'" class="p-6 space-y-6" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-umbrella-beach mr-2 text-primary"></i> Leave Limits (Per Year)</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="annual_leave_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Annual Leave Limit</label>
                    <input type="number" name="annual_leave_limit" id="annual_leave_limit" value="<?= htmlspecialchars($data['settings']['annual_leave_limit'] ?? 14) ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="casual_leave_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Casual Leave Limit</label>
                    <input type="number" name="casual_leave_limit" id="casual_leave_limit" value="<?= htmlspecialchars($data['settings']['casual_leave_limit'] ?? 7) ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="medical_leave_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medical Leave Limit</label>
                    <input type="number" name="medical_leave_limit" id="medical_leave_limit" value="<?= htmlspecialchars($data['settings']['medical_leave_limit'] ?? 14) ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="paid_leave_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Paid Leave Limit</label>
                    <input type="number" name="paid_leave_limit" id="paid_leave_limit" value="<?= htmlspecialchars($data['settings']['paid_leave_limit'] ?? 35) ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-file-contract mr-2 text-primary"></i> Leave Rules</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="unpaid_leave_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unpaid Leave Rules</label>
                    <textarea name="unpaid_leave_rules" id="unpaid_leave_rules" rows="3" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors"><?= htmlspecialchars($data['settings']['unpaid_leave_rules'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="half_day_leave_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Half-Day Leave Rules</label>
                    <textarea name="half_day_leave_rules" id="half_day_leave_rules" rows="3" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors"><?= htmlspecialchars($data['settings']['half_day_leave_rules'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Deduction Settings Tab -->
    <div x-show="tab === 'deduction'" class="p-6 space-y-6" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-coins mr-2 text-primary"></i> Deduction Rates</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="absent_deduction_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Absent Deduction Rate (Multiplier of Daily Wage)</label>
                    <input type="number" step="0.01" name="absent_deduction_rate" id="absent_deduction_rate" value="<?= htmlspecialchars($data['settings']['absent_deduction_rate'] ?? 1.00) ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
                <div>
                    <label for="half_day_deduction_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Half-Day Deduction Rate (Multiplier of Daily Wage)</label>
                    <input type="number" step="0.01" name="half_day_deduction_rate" id="half_day_deduction_rate" value="<?= htmlspecialchars($data['settings']['half_day_deduction_rate'] ?? 0.50) ?>" required class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors">
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fa-solid fa-scale-balanced mr-2 text-primary"></i> Deduction Rules</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="late_deduction_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Late Deduction Rules</label>
                    <textarea name="late_deduction_rules" id="late_deduction_rules" rows="3" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors"><?= htmlspecialchars($data['settings']['late_deduction_rules'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="excess_paid_leave_deduction_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Excess Paid Leave Deduction Rules</label>
                    <textarea name="excess_paid_leave_deduction_rules" id="excess_paid_leave_deduction_rules" rows="3" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors"><?= htmlspecialchars($data['settings']['excess_paid_leave_deduction_rules'] ?? '') ?></textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="custom_deduction_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom Deduction Rules</label>
                    <textarea name="custom_deduction_rules" id="custom_deduction_rules" rows="3" class="w-full px-4 py-2 bg-white/50 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm transition-colors"><?= htmlspecialchars($data['settings']['custom_deduction_rules'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 bg-gray-50/50 dark:bg-gray-700/30 flex justify-end border-t border-gray-100 dark:border-gray-700">
        <button type="submit" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary/30">
            <i class="fa-solid fa-save mr-2"></i> Save All Settings
        </button>
    </div>

</form>
