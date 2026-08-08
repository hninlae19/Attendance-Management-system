<?php
$file = 'c:/wamp64/www/payrollsystem/views/admin/overtime_assignments.php';
$content = file_get_contents($file);

$jsPattern = '/<\/div>\s*<\/div>\s*$/s';
$newJs = <<<'JS'
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otForm = document.querySelector('#addModal form');
    const dateInput = document.getElementById('date');
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');
    const deptInput = document.getElementById('department_id');
    const empInput = document.getElementById('employee_ids');
    const submitBtn = otForm.querySelector('button[type="submit"]');

    // Add conflict error element
    const conflictError = document.createElement('div');
    conflictError.id = 'conflictError';
    conflictError.className = 'text-red-500 text-sm mt-2 hidden p-3 bg-red-50 rounded-lg border border-red-200';
    conflictError.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> <span id="conflictMessage"></span>';
    
    // Insert before the submit button container
    const btnContainer = submitBtn.closest('.flex.justify-end');
    btnContainer.parentNode.insertBefore(conflictError, btnContainer);

    function getSelectedEmployees() {
        const assignTypeRadios = document.querySelectorAll('input[name="assign_type"]');
        let assignType = 'department';
        assignTypeRadios.forEach(r => { if (r.checked) assignType = r.value; });

        if (assignType === 'employee') {
            const selected = Array.from(empInput.selectedOptions).map(opt => opt.value);
            return { assign_type: 'employee', employee_ids: selected };
        } else {
            return { assign_type: 'department', department_id: deptInput.value };
        }
    }

    async function checkConflicts() {
        if (!dateInput.value || !startInput.value || !endInput.value) return false;
        
        const selection = getSelectedEmployees();
        if (selection.assign_type === 'employee' && selection.employee_ids.length === 0) {
            conflictError.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            return false;
        }

        try {
            const payload = {
                date: dateInput.value,
                start_time: startInput.value,
                end_time: endInput.value,
                ...selection
            };

            const res = await fetch('/payrollsystem/api/validate_conflict', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            
            if (data.has_conflict) {
                document.getElementById('conflictMessage').innerText = data.messages.join(' | ');
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
    deptInput.addEventListener('change', checkConflicts);
    empInput.addEventListener('change', checkConflicts);
    
    document.querySelectorAll('input[name="assign_type"]').forEach(r => {
        r.addEventListener('change', checkConflicts);
    });

    otForm.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
</div>
</div>
JS;

$content = preg_replace('/<\/form>\s*<\/div>\s*<\/div>\s*$/s', $newJs, $content);
file_put_contents($file, $content);
echo "Updated overtime_assignments.php";
