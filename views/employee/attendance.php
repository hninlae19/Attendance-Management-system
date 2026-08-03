<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Attendance</h1>
</div>

<div class="mb-8" x-data="{ tab: 'history' }">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            <li class="me-2">
                <button @click="tab = 'history'" :class="tab === 'history' ? 'text-primary border-primary dark:text-primary dark:border-primary' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Attendance History
                </button>
            </li>
            <li class="me-2">
                <button @click="tab = 'corrections'" :class="tab === 'corrections' ? 'text-primary border-primary dark:text-primary dark:border-primary' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group">
                    <i class="fa-solid fa-code-pull-request mr-2"></i> My Corrections
                </button>
            </li>
        </ul>
    </div>

    <!-- History Tab -->
    <div x-show="tab === 'history'" class="pt-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-4">Date</th>
                            <th scope="col" class="px-6 py-4">Check In</th>
                            <th scope="col" class="px-6 py-4">Check Out</th>
                            <th scope="col" class="px-6 py-4">Working Hrs</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['myAttendance'])): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="6" class="px-6 py-4 text-center">No attendance records found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['myAttendance'] as $att): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= date('D, M j, Y', strtotime($att['date'])) ?></td>
                                <td class="px-6 py-4"><?= $att['check_in'] ? date('h:i A', strtotime($att['check_in'])) : '-' ?></td>
                                <td class="px-6 py-4"><?= $att['check_out'] ? date('h:i A', strtotime($att['check_out'])) : '-' ?></td>
                                <td class="px-6 py-4"><?= $att['working_hours'] ? $att['working_hours'] . 'h' : '-' ?></td>
                                <td class="px-6 py-4">
                                    <?php if($att['status'] === 'Present'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Present</span>
                                    <?php elseif($att['status'] === 'Late'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Late</span>
                                    <?php elseif($att['status'] === 'Half Day'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full dark:bg-orange-900 dark:text-orange-300">Half Day</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Absent</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="requestCorrection(<?= $att['id'] ?>, '<?= $att['date'] ?>', '<?= $att['check_in'] ?? '' ?>', '<?= $att['check_out'] ?? '' ?>')" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen"></i> Request Correction</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Corrections Tab -->
    <div x-show="tab === 'corrections'" class="pt-6" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-4">Date</th>
                            <th scope="col" class="px-6 py-4">Requested In</th>
                            <th scope="col" class="px-6 py-4">Requested Out</th>
                            <th scope="col" class="px-6 py-4">Reason</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['myCorrections'])): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="5" class="px-6 py-4 text-center">No correction requests found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['myCorrections'] as $corr): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= date('D, M j, Y', strtotime($corr['date'])) ?></td>
                                <td class="px-6 py-4"><?= $corr['corrected_check_in'] ? date('h:i A', strtotime($corr['corrected_check_in'])) : 'None' ?></td>
                                <td class="px-6 py-4"><?= $corr['corrected_check_out'] ? date('h:i A', strtotime($corr['corrected_check_out'])) : 'None' ?></td>
                                <td class="px-6 py-4 max-w-[200px] truncate" title="<?= htmlspecialchars($corr['reason']) ?>"><?= htmlspecialchars($corr['reason']) ?></td>
                                <td class="px-6 py-4">
                                    <?php if($corr['status'] === 'Approved'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Approved</span>
                                    <?php elseif($corr['status'] === 'Rejected'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">Rejected</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Correction Modal -->
<div id="correctionModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Request Attendance Correction</h3>
            <button type="button" onclick="document.getElementById('correctionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/payrollsystem/employee/attendance" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="correction">
            <input type="hidden" name="attendance_id" id="correction_attendance_id">
            
            <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                Date: <span id="correction_date_display" class="font-bold text-gray-900 dark:text-white"></span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="corrected_check_in" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correct Check-In</label>
                    <input type="time" name="corrected_check_in" id="corrected_check_in" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div>
                    <label for="corrected_check_out" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correct Check-Out</label>
                    <input type="time" name="corrected_check_out" id="corrected_check_out" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for Correction</label>
                <textarea name="reason" id="reason" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Forgot to check out, system issue..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('correctionModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-700">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    function requestCorrection(id, date, checkIn, checkOut) {
        document.getElementById('correction_attendance_id').value = id;
        document.getElementById('correction_date_display').innerText = date;
        document.getElementById('corrected_check_in').value = checkIn;
        document.getElementById('corrected_check_out').value = checkOut;
        document.getElementById('correctionModal').classList.remove('hidden');
    }
</script>
