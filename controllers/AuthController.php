<?php
class AuthController extends Controller {
    public function index() {
        if(isset($_SESSION['user_id'])) {
            if($_SESSION['role'] === 'Admin') {
                $this->redirect('/payrollsystem/admin');
            } else {
                $this->redirect('/payrollsystem/employee');
            }
        }
        $this->view('auth/login', ['title' => 'HRMS Login']);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $this->view('auth/login', ['title' => 'HRMS Login', 'error' => 'Please enter email and password.']);
                return;
            }

            // Try Admin login first
            $adminModel = $this->model('Admin');
            if ($adminModel->login($email, $password)) {
                $_SESSION['user_id'] = $adminModel->AdminID;
                $_SESSION['role'] = 'Admin';
                $_SESSION['email'] = $adminModel->Email;
                $this->redirect('/payrollsystem/admin');
                return;
            }

            // Try Employee login next
            $employeeModel = $this->model('Employee');
            if ($employeeModel->login($email, $password)) {
                $_SESSION['user_id'] = $employeeModel->EmpID;
                $_SESSION['employee_id'] = $employeeModel->EmpID;
                $_SESSION['role'] = 'Employee';
                $_SESSION['email'] = $email;
                $_SESSION['first_name'] = $employeeModel->FirstName;
                $_SESSION['last_name'] = $employeeModel->LastName;
                $this->redirect('/payrollsystem/employee');
                return;
            }

            $this->view('auth/login', ['title' => 'HRMS Login', 'error' => 'Invalid email or password.']);
        } else {
            $this->index();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('/payrollsystem/auth');
    }

    public function forgot_password() {
        $this->view('auth/forgot_password', ['title' => 'Forgot Password']);
    }

    public function forgot_password_submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->validateCsrfToken($_POST['csrf_token'] ?? '');
            
            $email = trim($_POST['email'] ?? '');
            if (empty($email)) {
                $this->view('auth/forgot_password', ['title' => 'Forgot Password', 'error' => 'Please enter your email.']);
                return;
            }

            // Check if employee exists
            $db = new Database();
            $conn = $db->getConnection();
            $query = "SELECT EmpID FROM employee WHERE Email = :email AND Status = 'Active'";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                // Set PasswordResetRequest = 1
                $updateQuery = "UPDATE employee SET PasswordResetRequest = 1 WHERE Email = :email";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                // Send notification to Admin (user_id 1)
                require_once __DIR__ . '/../models/Notification.php';
                $notifModel = new Notification();
                // We fetch the employee details to make a nice message
                $empStmt = $conn->prepare("SELECT EmpID, FirstName, LastName FROM employee WHERE Email = :email");
                $empStmt->bindParam(':email', $email);
                $empStmt->execute();
                $emp = $empStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($emp) {
                    $notifMessage = $emp['FirstName'] . " " . $emp['LastName'] . " (ID: " . $emp['EmpID'] . ") requested a password reset.";
                    $notifModel->create(1, $notifMessage, 'error', '/admin/password_resets', 'Password Reset Request', $emp['EmpID']);
                }
            }

            // Always show success message for security
            $this->view('auth/forgot_password', ['title' => 'Forgot Password', 'success' => 'If an active account exists with this email, an admin will process your request.']);
        } else {
            $this->redirect('/payrollsystem/auth/forgot_password');
        }
    }
}
