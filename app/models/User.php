<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Find user by username
    public function findUserByUsername($username){
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if($this->db->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

    // Register user
    public function register($data){
        $this->db->query('INSERT INTO users (name, username, password, nic, role_id) VALUES(:name, :username, :password, :nic, :role_id)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':nic', $data['nic']);
        // Default role for self-registration might be 'Subject Officer' (ID 6 in the SQL schema)
        // Let's make it 6 (Subject Officer) for now
        $this->db->bind(':role_id', 6); 

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($username, $password){
        $this->db->query('SELECT * FROM users WHERE username = :username AND status = "active"');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if($row){
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Get Role Level
    public function getRoleLevel($role_id){
        $this->db->query('SELECT level FROM roles WHERE id = :id');
        $this->db->bind(':id', $role_id);
        $row = $this->db->single();
        return $row->level;
    }
    // Admin: Get Users (GS, AO, CC)
    // Admin: Get Users (GS, AO, CC, Subject Officer/Dept)
    public function getAdminManagedUsers(){
        $this->db->query('
            SELECT u.*, r.name as role_name, d.name as department_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            LEFT JOIN departments d ON u.department_id = d.id
            WHERE u.role_id IN (2, 3, 4, 5, 6) 
            ORDER BY u.created_at DESC
        ');
        return $this->db->resultSet();
    }

    // Admin: Get single user
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Admin: Create user
    public function createUser($data){
        $this->db->query('INSERT INTO users (name, username, password, role_id, department_id) VALUES(:name, :username, :password, :role_id, :department_id)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $data['role_id']);
        
        $dept_id = (!empty($data['department_id'])) ? $data['department_id'] : null;
        $this->db->bind(':department_id', $dept_id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Update user
    public function updateUser($id, $data){
        $dept_id = (!empty($data['department_id'])) ? $data['department_id'] : null;

        if(empty($data['password'])){
            $this->db->query('UPDATE users SET name = :name, username = :username, role_id = :role_id, department_id = :department_id WHERE id = :id');
        } else {
            $this->db->query('UPDATE users SET name = :name, username = :username, password = :password, role_id = :role_id, department_id = :department_id WHERE id = :id');
            $this->db->bind(':password', $data['password']);
        }
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':department_id', $dept_id);
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Delete user (Check if they have any complaints created first)
    public function deleteUser($id){
        // First check if user is linked to any complaints
        $this->db->query('SELECT id FROM complaints WHERE created_by = :id LIMIT 1');
        $this->db->bind(':id', $id);
        $this->db->single();
        if($this->db->rowCount() > 0){
            return false; // Cannot delete due to foreign key constraints/existing data
        }

        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Get managed roles
    public function getManagedRoles(){
        $this->db->query('SELECT * FROM roles WHERE id IN (2, 3, 4, 5)');
        return $this->db->resultSet();
    }
}
