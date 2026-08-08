<?php
$file = 'c:/wamp64/www/payrollsystem/views/employee/overtime.php';
$content = file_get_contents($file);

$jsPattern = '/<script>.*?<\/script>/s';
$newJs = <<<'JS'
<script>
document.addEventListener('DOMContentLoaded', function() {
    const otForm = document.getElementById('ot-form');
    const dateInput = document.getElementById('ot_date');
    const startInput = document.getElementById('ot_start_time');
    const endInput = document.getElementById('ot_end_time');
    const submitBtn = otForm.querySelector('button[type="submit"]');
    const errorDiv = document.getElementById('ot-error-msg');

    // Add conflict error element
    const conflictError = document.createElement('div');
    conflictError.id = 'conflictError';
    conflictError.className = 'text-red-500 text-xs mt-1 hidden';
    conflictError.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> <span id="conflictMessage"></span>';
    endInput.parentNode.parentNode.parentNode.insertBefore(conflictError, endInput.parentNode.parentNode.nextSibling);

    async function checkConflicts() {
        if (!dateInput.value || !startInput.value || !endInput.value) return false;
        
        try {
            const res = await fetch('/payrollsystem/api/validate_conflict', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    date: dateInput.value,
                    start_time: startInput.value,
                    end_time: endInput.value
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

    dateInput.addEventListener('change', checkConflicts);
    startInput.addEventListener('change', checkConflicts);
    endInput.addEventListener('change', checkConflicts);

    otForm.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }

        errorDiv.classList.add('hidden');
        errorDiv.innerText = '';

        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const today = new Date();
        today.setHours(0,0,0,0);

        if (selectedDate < today) {
            e.preventDefault();
            errorDiv.innerText = 'Overtime request is only allowed for today or future dates.';
            errorDiv.classList.remove('hidden');
            return;
        }

        // Time validation
        const startTime = new Date('1970-01-01T' + startInput.value + ':00');
        const endTime = new Date('1970-01-01T' + endInput.value + ':00');

        if (endTime <= startTime) {
            e.preventDefault();
            errorDiv.innerText = 'End time must be after start time.';
            errorDiv.classList.remove('hidden');
            return;
        }

        const hours = (endTime - startTime) / (1000 * 60 * 60);
        if (hours > 4) {
            e.preventDefault();
            errorDiv.innerText = 'Overtime cannot exceed 4 hours per day.';
            errorDiv.classList.remove('hidden');
            return;
        }
    });

    // Display server-side errors
    <?php if(isset($_SESSION['ot_error'])): ?>
        document.getElementById('applyModal').classList.remove('hidden');
        errorDiv.innerText = <?= json_encode($_SESSION['ot_error']) ?>;
        errorDiv.classList.remove('hidden');
        <?php unset($_SESSION['ot_error']); ?>
    <?php endif; ?>
});
</script>
JS;

$content = preg_replace($jsPattern, $newJs, $content);
file_put_contents($file, $content);
echo "Updated overtime.php";
