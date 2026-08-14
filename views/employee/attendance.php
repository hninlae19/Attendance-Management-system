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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php if(empty($data['myAttendance'])): ?>
                <div class="col-span-full p-8 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">No attendance records found.</p>
                </div>
            <?php else: ?>
                <?php foreach($data['myAttendance'] as $att): ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1"><?= date('D, M j, Y', strtotime($att['AttendanceDate'])) ?></p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <?php if($att['Status'] === 'Present'): ?>
                                    <span class="text-green-500"><i class="fa-solid fa-circle-check mr-2"></i> Present</span>
                                <?php elseif($att['Status'] === 'Absent'): ?>
                                    <span class="text-red-500"><i class="fa-solid fa-circle-xmark mr-2"></i> Absent</span>
                                <?php elseif($att['Status'] === 'Late'): ?>
                                    <span class="text-orange-500"><i class="fa-solid fa-clock mr-2"></i> Late</span>
                                <?php elseif($att['Status'] === 'Half Day'): ?>
                                    <span class="text-yellow-500"><i class="fa-solid fa-star-half-stroke mr-2"></i> Half Day</span>
                                <?php else: ?>
                                    <span class="text-blue-500"><i class="fa-solid fa-info-circle mr-2"></i> <?= htmlspecialchars($att['Status']) ?></span>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <button onclick="requestCorrection(<?= $att['AttendanceID'] ?>, '<?= $att['AttendanceDate'] ?>', '<?= $att['CheckInTime'] ?? '' ?>', '<?= $att['CheckOutTime'] ?? '' ?>')" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 hover:text-primary hover:bg-blue-50 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                        <div class="text-center w-1/2 border-r border-gray-200 dark:border-gray-700">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">IN</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 dark:text-gray-300"><?= $att['CheckInTime'] ? date('h:i A', strtotime($att['CheckInTime'])) : '--:--' ?></p>
                        </div>
                        <div class="text-center w-1/2">
                            <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">OUT</p>
                            <p class="font-mono text-sm font-semibold text-gray-800 dark:text-gray-300"><?= $att['CheckOutTime'] ? date('h:i A', strtotime($att['CheckOutTime'])) : '--:--' ?></p>
                        </div>
                    </div>
                    <?php if(!empty($att['working_hours']) || !empty($att['ot_hours'])): ?>
                        <div class="mt-3 text-right">
                            <?php if(!empty($att['working_hours'])): ?>
                                <span class="text-xs text-gray-500 font-medium">Logged: <strong class="text-gray-900 dark:text-white"><?= $att['working_hours'] ?>h</strong></span>
                            <?php endif; ?>
                            <?php if(!empty($att['ot_hours'])): ?>
                                <span class="text-xs text-gray-500 font-medium <?= !empty($att['working_hours']) ? 'ml-3 border-l pl-3 border-gray-300 dark:border-gray-600' : '' ?>">OT: <strong class="text-orange-600 dark:text-orange-400"><?= $att['ot_hours'] ?>h</strong></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= date('D, M j, Y', strtotime($corr['AttendanceDate'])) ?></td>
                                <td class="px-6 py-4"><?= $corr['corrected_check_in'] ? date('h:i A', strtotime($corr['corrected_check_in'])) : 'None' ?></td>
                                <td class="px-6 py-4"><?= $corr['corrected_check_out'] ? date('h:i A', strtotime($corr['corrected_check_out'])) : 'None' ?></td>
                                <td class="px-6 py-4 max-w-[200px] truncate" title="<?= htmlspecialchars($corr['Reason']) ?>"><?= htmlspecialchars($corr['Reason']) ?></td>
                                <td class="px-6 py-4">
                                    <?php if($corr['Status'] === 'Approved'): ?>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">Approved</span>
                                    <?php elseif($corr['Status'] === 'Rejected'): ?>
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
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

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
