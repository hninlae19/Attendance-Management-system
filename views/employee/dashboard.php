<!-- Employee Dashboard Redesign -->
<div class="mb-6 flex flex-col items-center sm:items-start text-center sm:text-left">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Hello, <?= htmlspecialchars($data['employee']['first_name']) ?>! 👋</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Today is <?= date('l, F j, Y') ?></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Clock In/Out Widget (Navy/Gold Theme) -->
    <div class="lg:col-span-1">
        <div class="bg-gradient-to-br from-[#0B132B] to-[#1C2541] rounded-3xl shadow-xl overflow-hidden text-center p-8 border border-gray-800 relative">
            <!-- Subtle background decoration -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-5 rounded-full blur-xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#D4AF37] opacity-10 rounded-full blur-2xl"></div>
            
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-2">Time Clock</h2>
            
            <div class="text-5xl font-mono font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] to-[#F3E5AB] mb-8 tracking-wider drop-shadow-md" id="realTimeClock">
                <?= date('H:i:s') ?>
            </div>

            <?php if (isset($_SESSION['att_error'])): ?>
                <div class="mb-6 p-4 bg-red-900/50 border border-red-500/50 text-red-300 rounded-xl text-sm font-medium backdrop-blur-sm animate-pulse">
                    <?= htmlspecialchars($_SESSION['att_error']) ?>
                </div>
                <?php unset($_SESSION['att_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['att_success'])): ?>
                <div class="mb-6 p-4 bg-green-900/50 border border-green-500/50 text-green-300 rounded-xl text-sm font-medium backdrop-blur-sm animate-pulse">
                    <?= htmlspecialchars($_SESSION['att_success']) ?>
                </div>
                <?php unset($_SESSION['att_success']); ?>
            <?php endif; ?>

            <?php if(!$data['todayAttendance']): ?>
                <!-- Not Clocked In Yet -->
                <form action="/payrollsystem/employee" method="POST" class="relative z-10">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="clock_in">
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-[#D4AF37] to-[#C5A017] hover:from-[#C5A017] hover:to-[#B49006] text-gray-900 font-extrabold text-lg py-4 px-6 rounded-2xl shadow-[0_10px_25px_-5px_rgba(212,175,55,0.4)] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center">
                        <i class="fa-solid fa-fingerprint text-2xl mr-3 opacity-90"></i>
                        CHECK-IN
                    </button>
                </form>
            <?php elseif($data['todayAttendance'] && !$data['todayAttendance']['check_out']): ?>
                <!-- Clocked In, waiting for Clock Out -->
                <div class="mb-6 p-4 bg-[#1A2A4A] border border-[#2A3A5A] rounded-2xl relative z-10">
                    <div class="text-xs uppercase font-bold tracking-wider text-gray-400 mb-1">Active Shift Started</div>
                    <div class="text-xl font-bold text-[#D4AF37]"><?= date('h:i A', strtotime($data['todayAttendance']['check_in'])) ?></div>
                </div>
                
                <form action="/payrollsystem/employee" method="POST" class="relative z-10">
                    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="clock_out">
                    <button type="submit" class="w-full bg-gradient-to-r from-[#FF6B6B] to-[#E63946] hover:from-[#E63946] hover:to-[#D62828] text-white font-extrabold text-lg py-4 px-6 rounded-2xl shadow-[0_10px_25px_-5px_rgba(230,57,70,0.4)] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center">
                        <i class="fa-solid fa-right-from-bracket mr-3 opacity-90"></i>
                        CHECK-OUT
                    </button>
                </form>
            <?php else: ?>
                <!-- Clocked Out -->
                <div class="p-6 bg-[#1A2A4A] border border-[#2A3A5A] rounded-2xl relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500/20 text-green-400 mb-4">
                        <i class="fa-solid fa-check text-3xl"></i>
                    </div>
                    <p class="font-bold text-white text-lg mb-4">Shift Completed</p>
                    <div class="flex justify-between items-center text-sm px-4 py-2 bg-black/20 rounded-lg">
                        <span class="text-gray-400">IN: <strong class="text-white"><?= date('h:i A', strtotime($data['todayAttendance']['check_in'])) ?></strong></span>
                        <span class="text-gray-500">|</span>
                        <span class="text-gray-400">OUT: <strong class="text-white"><?= date('h:i A', strtotime($data['todayAttendance']['check_out'])) ?></strong>
                            <?php if(isset($data['todayAttendance']['is_auto_checkout']) && $data['todayAttendance']['is_auto_checkout'] == 1): ?>
                                <span class="ml-1 text-[10px] bg-red-900/50 text-red-300 px-1.5 py-0.5 rounded-full border border-red-800/50" title="System Auto Check-Out">Auto</span>
                            <?php endif; ?>
                        </span>
                    </div>
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
