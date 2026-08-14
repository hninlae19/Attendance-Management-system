<?php
$html = file_get_contents('http://localhost/payrollsystem/test_ctrl.php');
file_put_contents('test_ctrl_output.txt', $html);
?>
