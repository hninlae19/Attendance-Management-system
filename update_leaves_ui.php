<?php
$file = 'c:/wamp64/www/payrollsystem/views/employee/leaves.php';
$content = file_get_contents($file);

$balanceUI = <<<'PHP'
<?php
$limit = $data['paidLeaveLimit'];
$used = $data['paidLeaveUsed'];
$pct = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 100;
$exhausted = $used >= $limit;
?>
<div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1"><i class="fa-solid fa-calendar-check text-primary mr-2"></i>Paid Leave Balance</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Total approved paid leaves taken this year.</p>
    </div>
    <div class="flex items-center gap-4 w-full md:w-auto">
        <div class="text-right">
            <span class="text-2xl font-black text-gray-900 dark:text-white"><?= $used ?></span>
            <span class="text-sm text-gray-500 dark:text-gray-400">/ <?= $limit ?> days</span>
        </div>
        <div class="w-full md:w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full <?= $exhausted ? 'bg-red-500' : 'bg-primary' ?>" style="width: <?= $pct ?>%"></div>
        </div>
    </div>
</div>
<?php if($exhausted): ?>
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg dark:bg-yellow-900/30 dark:border-yellow-600">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 dark:text-yellow-400"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                Your paid leave balance has been exhausted. Only Unpaid Leave is available.
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
PHP;

$content = str_replace('<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">', $balanceUI, $content);

$leaveOptions = <<<'PHP'
                    <option value="">Select Leave Type</option>
                    <?php foreach($data['leaveTypes'] as $lt): ?>
                        <?php 
                        $disabled = '';
                        $suffix = $lt['is_paid'] ? 'Paid' : 'Unpaid';
                        if ($exhausted && $lt['is_paid'] == 1) {
                            $disabled = 'disabled';
                            $suffix .= ' - Exhausted';
                        }
                        ?>
                        <option value="<?= $lt['id'] ?>" <?= $disabled ?>><?= htmlspecialchars($lt['name']) ?> (<?= $suffix ?>)</option>
                    <?php endforeach; ?>
PHP;

$patternOptions = '/<option value="">Select Leave Type<\/option>.*?<\?php endforeach; \?>/s';
$content = preg_replace($patternOptions, $leaveOptions, $content);


$jsPattern = '/<script>.*?<\/script>/s';
$newJs = <<<'JS'
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const dateError = document.getElementById('dateError');
    const attendanceError = document.getElementById('attendanceError');
    const durationDisplay = document.getElementById('durationDisplay');
    const durationDays = document.getElementById('durationDays');
    const leaveForm = document.getElementById('leaveForm');
    const submitBtn = leaveForm.querySelector('button[type="submit"]');
    
    // Add conflict error element
    const conflictError = document.createElement('div');
    conflictError.id = 'conflictError';
    conflictError.className = 'text-red-500 text-xs mt-1 hidden';
    conflictError.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> <span id="conflictMessage"></span>';
    dateError.parentNode.insertBefore(conflictError, dateError.nextSibling);
    
    const hasClockedInToday = <?= json_encode($data['hasClockedInToday']) ?>;
    
    // Get today's date in YYYY-MM-DD local timezone
    const now = new Date();
    const todayStr = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().split('T')[0];

    async function checkConflicts() {
        if (!startDate.value) return false;
        
        try {
            const res = await fetch('/payrollsystem/api/validate_conflict', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    start_date: startDate.value,
                    end_date: endDate.value || startDate.value
                })
            });
            const data = await res.json();
            if (data.has_conflict) {
                document.getElementById('conflictMessage').innerText = data.messages[0];
                conflictError.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return true;
            } else {
                conflictError.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return false;
            }
        } catch (e) {
            console.error(e);
            return false;
        }
    }

    async function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            let hasError = false;
            dateError.classList.add('hidden');
            attendanceError.classList.add('hidden');
            
            if (hasClockedInToday && startDate.value === todayStr) {
                attendanceError.classList.remove('hidden');
                hasError = true;
            }

            if (end < start) {
                dateError.classList.remove('hidden');
                hasError = true;
            }
            
            if (hasError) {
                durationDisplay.classList.add('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                // Calculate days inclusive of start and end
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                durationDays.innerText = diffDays;
                durationDisplay.classList.remove('hidden');
                
                // Now check conflicts
                await checkConflicts();
            }
        } else {
            dateError.classList.add('hidden');
            attendanceError.classList.add('hidden');
            conflictError.classList.add('hidden');
            durationDisplay.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    startDate.addEventListener('change', calculateDuration);
    endDate.addEventListener('change', calculateDuration);

    leaveForm.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }
        
        let hasError = false;
        
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (hasClockedInToday && startDate.value === todayStr) {
                attendanceError.classList.remove('hidden');
                hasError = true;
            }
            
            if (end < start) {
                dateError.classList.remove('hidden');
                hasError = true;
            }
        }
        
        if (hasError) {
            e.preventDefault();
            return false;
        }
        
        // Disable button to prevent double submit
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Submitting...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
    });
});
</script>
JS;

$content = preg_replace($jsPattern, $newJs, $content);
file_put_contents($file, $content);
echo "Updated leaves.php";
