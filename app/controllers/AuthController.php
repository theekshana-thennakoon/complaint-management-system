<?php
class AuthController extends Controller {
    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function index(){
        if(isLoggedIn()){
            redirect('dashboard');
        }

        // Init data
        $data =[
            'username' => '',
            'password' => '',
            'username_err' => '',
            'password_err' => '',
        ];

        // Load view
        $this->view('auth/login', $data);
    }

    public function register(){
        if(isLoggedIn()){
            redirect('dashboard');
        }

        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username']),
                'nic' => trim($_POST['nic']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'username_err' => '',
                'nic_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            // Validate NIC
            if(empty($data['nic'])){
                $data['nic_err'] = 'Please enter NIC';
            }

            // Validate Username
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            } else {
                // Check username
                if($this->userModel->findUserByUsername($data['username'])){
                    $data['username_err'] = 'Username is already taken';
                }
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']){
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['username_err']) && empty($data['nic_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if($this->userModel->register($data)){
                    flash('register_success', 'You are registered and can log in');
                    redirect('auth/login');
                } else {
                    die('Something went wrong');
                }

            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }

        } else {
            // Init data
            $data = [
                'name' => '',
                'username' => '',
                'nic' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'username_err' => '',
                'nic_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }

    public function login(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data =[
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => '',
            ];

            // Validate Username
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if($this->userModel->findUserByUsername($data['username'])){
                // User found
            } else {
                // User not found
                $data['username_err'] = 'No user found';
            }

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['password_err'])){
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if($loggedInUser){
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data =[
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => '',
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_role_id'] = $user->role_id;
        $_SESSION['user_department_id'] = $user->department_id;
        $_SESSION['user_level'] = $this->userModel->getRoleLevel($user->role_id);
        
        if ($_SESSION['user_role_id'] == 1) {
            redirect('admin');
        } elseif ($_SESSION['user_role_id'] == 2) {
            redirect('governor');
        } elseif ($_SESSION['user_role_id'] == 5) {
            redirect('cc');
        } elseif ($_SESSION['user_role_id'] == 4) {
            redirect('ao');
        } elseif ($_SESSION['user_role_id'] == 3) {
            redirect('gs');
        } elseif ($_SESSION['user_role_id'] == 7) {
            redirect('department');
        } else {
            redirect('dashboard');
        }
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_role_id']);
        unset($_SESSION['user_department_id']);
        unset($_SESSION['user_level']);
        session_destroy();
        redirect('auth');
    }
}
