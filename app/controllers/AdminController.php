<?php
class AdminController extends Controller {
    public function __construct(){
        if(!isLoggedIn() || $_SESSION['user_role_id'] != 1){
            redirect('dashboard');
        }
        $this->userModel = $this->model('User');
        $this->complaintModel = $this->model('Complaint');
    }

    private function _getDashboardData(){
        $users = $this->userModel->getAdminManagedUsers();
        
        $stats = [
            'total' => count($users),
            'active' => 0,
            'inactive' => 0,
            'governor' => 0,
            'cc' => 0,
            'ao' => 0,
            'gs' => 0
        ];
        
        foreach($users as $user){
            if($user->status == 'active'){
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }
            if($user->role_id == 2) $stats['governor']++;
            if($user->role_id == 5) $stats['cc']++;
            if($user->role_id == 4) $stats['ao']++;
            if($user->role_id == 3) $stats['gs']++;
        }
        return ['users' => $users, 'stats' => $stats];
    }

    public function index(){
        $dashboardData = $this->_getDashboardData();
        $dashboardData['roles'] = $this->userModel->getManagedRoles();
        $dashboardData['departments'] = $this->complaintModel->getDepartments();
        $this->view('admin/index', $dashboardData);
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'role_id' => isset($_POST['role_id']) ? trim($_POST['role_id']) : '',
                'department_id' => isset($_POST['department_id']) ? trim($_POST['department_id']) : null,
                'roles' => $this->userModel->getManagedRoles(),
                'departments' => $this->complaintModel->getDepartments(),
                'name_err' => '',
                'username_err' => '',
                'password_err' => '',
                'role_id_err' => ''
            ];

            // Validate
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            } else {
                if($this->userModel->findUserByUsername($data['username'])){
                    $data['username_err'] = 'Username is already taken';
                }
            }
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            if(empty($data['role_id'])){
                $data['role_id_err'] = 'Please select a role';
            }

            if(empty($data['name_err']) && empty($data['username_err']) && empty($data['password_err']) && empty($data['role_id_err'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if($this->userModel->createUser($data)){
                    flash('admin_message', 'User registered successfully');
                    redirect('admin');
                } else {
                    die('Something went wrong');
                }
            } else {
                $dashboardData = $this->_getDashboardData();
                $data['users'] = $dashboardData['users'];
                $data['stats'] = $dashboardData['stats'];
                $data['show_modal'] = true;
                $this->view('admin/index', $data);
            }
        } else {
            redirect('admin');
        }
    }

    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'role_id' => isset($_POST['role_id']) ? trim($_POST['role_id']) : '',
                'department_id' => isset($_POST['department_id']) ? trim($_POST['department_id']) : null,
                'roles' => $this->userModel->getManagedRoles(),
                'departments' => $this->complaintModel->getDepartments(),
                'name_err' => '',
                'username_err' => '',
                'password_err' => '',
                'role_id_err' => ''
            ];

            // Validate
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter username';
            } else {
                $existingUser = $this->userModel->getUserById($id);
                if($data['username'] != $existingUser->username && $this->userModel->findUserByUsername($data['username'])){
                    $data['username_err'] = 'Username is already taken';
                }
            }
            if(!empty($data['password']) && strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            if(empty($data['role_id'])){
                $data['role_id_err'] = 'Please select a role';
            }

            if(empty($data['name_err']) && empty($data['username_err']) && empty($data['password_err']) && empty($data['role_id_err'])){
                if(!empty($data['password'])){
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                
                if($this->userModel->updateUser($id, $data)){
                    flash('admin_message', 'User updated successfully');
                    redirect('admin');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/edit', $data);
            }
        } else {
            $user = $this->userModel->getUserById($id);
            if(!$user){
                redirect('admin');
            }

            $data = [
                'id' => $id,
                'name' => $user->name,
                'username' => $user->username,
                'password' => '',
                'role_id' => $user->role_id,
                'department_id' => $user->department_id,
                'roles' => $this->userModel->getManagedRoles(),
                'departments' => $this->complaintModel->getDepartments(),
                'name_err' => '',
                'username_err' => '',
                'password_err' => '',
                'role_id_err' => ''
            ];
            $this->view('admin/edit', $data);
        }
    }

    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->userModel->deleteUser($id)){
                flash('admin_message', 'User removed successfully');
            } else {
                flash('admin_message', 'Cannot remove user as they are linked to existing complaints.', 'alert alert-danger');
            }
            redirect('admin');
        } else {
            redirect('admin');
        }
    }
}
