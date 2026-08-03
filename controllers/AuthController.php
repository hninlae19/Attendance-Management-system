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
            $userModel = $this->model('User');
            
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $this->view('auth/login', ['title' => 'HRMS Login', 'error' => 'Please enter email and password.']);
                return;
            }

            $result = $userModel->login($email, $password);

            if ($result === true) {
                $_SESSION['user_id'] = $userModel->id;
                $_SESSION['role'] = $userModel->role;
                $_SESSION['email'] = $userModel->email;

                if ($userModel->role === 'Employee') {
                    $profile = $userModel->getEmployeeProfile();
                    if($profile) {
                        $_SESSION['employee_id'] = $profile['employee_id'];
                        $_SESSION['first_name'] = $profile['first_name'];
                        $_SESSION['last_name'] = $profile['last_name'];
                    }
                    $this->redirect('/payrollsystem/employee');
                } else {
                    $this->redirect('/payrollsystem/admin');
                }
            } else {
                $this->view('auth/login', ['title' => 'HRMS Login', 'error' => $result]);
            }
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
