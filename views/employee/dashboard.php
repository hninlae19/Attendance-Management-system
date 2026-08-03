<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, <?= htmlspecialchars($data['employee']['first_name']) ?>!</h1>
    <p class="text-gray-500 mt-1">Today is <?= date('l, F j, Y') ?></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Clock In/Out Widget -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden text-center p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Time Clock</h2>
            
            <div class="text-4xl font-mono font-bold text-gray-800 dark:text-gray-100 mb-6" id="realTimeClock">
                <?= date('H:i:s') ?>
            </div>

            <?php if(!$data['todayAttendance']): ?>
                <!-- Not Clocked In Yet -->
                <form action="/payrollsystem/employee" method="POST">
                    <input type="hidden" name="action" value="clock_in">
                    <button type="submit" class="w-full bg-primary hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all hover:-translate-y-1">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Clock In Now
                    </button>
                </form>
            <?php elseif($data['todayAttendance'] && !$data['todayAttendance']['check_out']): ?>
                <!-- Clocked In, waiting for Clock Out -->
                <div class="mb-4 p-3 bg-green-50 border border-green-100 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
                    <div class="text-xs uppercase font-bold tracking-wider mb-1">Clocked In At</div>
                    <div class="text-lg font-bold"><?= date('h:i A', strtotime($data['todayAttendance']['check_in'])) ?></div>
                </div>
                
                <form action="/payrollsystem/employee" method="POST">
                    <input type="hidden" name="action" value="clock_out">
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-orange-200 dark:shadow-none transition-all hover:-translate-y-1">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Clock Out
                    </button>
                </form>
            <?php else: ?>
                <!-- Clocked Out -->
                <div class="p-4 bg-gray-50 border border-gray-100 text-gray-700 rounded-lg dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-300">
                    <i class="fa-solid fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p class="font-medium">Shift Completed</p>
                    <p class="text-sm text-gray-500 mt-1">In: <?= date('h:i A', strtotime($data['todayAttendance']['check_in'])) ?></p>
                    <p class="text-sm text-gray-500">Out: <?= date('h:i A', strtotime($data['todayAttendance']['check_out'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="lg:col-span-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-4">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['department_name']) ?></p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-4">
                    <i class="fa-solid fa-briefcase text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($data['employee']['position_name']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('realTimeClock').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
