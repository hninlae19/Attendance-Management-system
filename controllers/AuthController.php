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
}
