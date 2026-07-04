<?php
class Complaint {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function addComplaint($data, $details = []){
        $this->db->query('INSERT INTO complaints (complaint_no, date, applicant_name, nic, address, mobile, email, subject, category_id, description, status, current_role_id, created_by, forward_department_id) VALUES (:complaint_no, :date, :applicant_name, :nic, :address, :mobile, :email, :subject, :category_id, :description, :status, :current_role_id, :created_by, :forward_department_id)');

        // Bind values
        $this->db->bind(':complaint_no', $data['complaint_no']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':applicant_name', $data['applicant_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':mobile', $data['mobile']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status'] ?? 'Draft');
        
        $this->db->bind(':current_role_id', $data['current_role_id'] ?? 6);
        $this->db->bind(':created_by', $data['created_by'] ?? NULL);
        $this->db->bind(':forward_department_id', $data['forward_department_id'] ?? NULL);

        if($this->db->execute()){
            $complaint_id = $this->db->lastInsertId();

            // Insert dynamic details if any
            if(!empty($details) && is_array($details)){
                foreach($details as $detail){
                    $this->db->query('INSERT INTO complaint_details (complaint_id, letter_no, name, subject) VALUES (:complaint_id, :letter_no, :name, :subject)');
                    $this->db->bind(':complaint_id', $complaint_id);
                    $this->db->bind(':letter_no', $detail['letter_no']);
                    $this->db->bind(':name', $detail['name']);
                    $this->db->bind(':subject', $detail['subject']);
                    $this->db->execute();
                }
            }

            return $complaint_id;
        } else {
            return false;
        }
    }

    public function getCategories(){
        $this->db->query('SELECT * FROM complaint_categories');
        return $this->db->resultSet();
    }

    public function getDepartments(){
        $this->db->query('SELECT * FROM departments');
        return $this->db->resultSet();
    }

    public function getComplaintByNo($complaint_no){
        $this->db->query('SELECT * FROM complaints WHERE complaint_no = :complaint_no');
        $this->db->bind(':complaint_no', $complaint_no);
        return $this->db->single();
    }

    public function getComplaints(){
        $this->db->query('
            SELECT c.*, cat.name as category_name, d.name as department_name, r.name as current_role_name 
            FROM complaints c 
            LEFT JOIN complaint_categories cat ON c.category_id = cat.id 
            LEFT JOIN departments d ON c.forward_department_id = d.id
            LEFT JOIN roles r ON c.current_role_id = r.id
            ORDER BY c.created_at DESC
        ');
        return $this->db->resultSet();
    }

    public function getComplaintsByUser($user_id){
        $this->db->query('
            SELECT c.*, cat.name as category_name, d.name as department_name, r.name as current_role_name 
            FROM complaints c 
            LEFT JOIN complaint_categories cat ON c.category_id = cat.id 
            LEFT JOIN departments d ON c.forward_department_id = d.id
            LEFT JOIN roles r ON c.current_role_id = r.id
            WHERE c.created_by = :user_id
            ORDER BY c.created_at DESC
        ');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getComplaintById($id){
        $this->db->query('
            SELECT c.*, 
                   cat.name as category_name, 
                   d.name as department_name, 
                   u.name as created_by_name
            FROM complaints c
            LEFT JOIN complaint_categories cat ON c.category_id = cat.id
            LEFT JOIN departments d ON c.forward_department_id = d.id
            LEFT JOIN users u ON c.created_by = u.id
            WHERE c.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getComplaintDetails($complaint_id){
        $this->db->query('SELECT * FROM complaint_details WHERE complaint_id = :complaint_id');
        $this->db->bind(':complaint_id', $complaint_id);
        return $this->db->resultSet();
    }

    public function updateComplaintStatus($id, $status, $current_role_id) {
        $this->db->query('UPDATE complaints SET status = :status, current_role_id = :current_role_id WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':current_role_id', $current_role_id);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getComplaintsByRoleId($role_id) {
        $this->db->query('
            SELECT c.*, cat.name as category_name, d.name as department_name, r.name as current_role_name 
            FROM complaints c 
            LEFT JOIN complaint_categories cat ON c.category_id = cat.id 
            LEFT JOIN departments d ON c.forward_department_id = d.id
            LEFT JOIN roles r ON c.current_role_id = r.id
            WHERE c.current_role_id = :role_id
            ORDER BY c.created_at DESC
        ');
        $this->db->bind(':role_id', $role_id);
        return $this->db->resultSet();
    }

    public function getComplaintsByUserId($user_id) {
        $this->db->query('
            SELECT c.*, cat.name as category_name, d.name as department_name, r.name as current_role_name 
            FROM complaints c 
            LEFT JOIN complaint_categories cat ON c.category_id = cat.id 
            LEFT JOIN departments d ON c.forward_department_id = d.id
            LEFT JOIN roles r ON c.current_role_id = r.id
            WHERE c.created_by = :user_id
            ORDER BY c.created_at DESC
        ');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function logWorkflow($complaint_id, $from_role_id, $to_role_id, $action, $remarks, $action_by) {
        $this->db->query('INSERT INTO workflow_logs (complaint_id, from_role_id, to_role_id, action, remarks, action_by) VALUES (:complaint_id, :from_role_id, :to_role_id, :action, :remarks, :action_by)');
        $this->db->bind(':complaint_id', $complaint_id);
        $this->db->bind(':from_role_id', $from_role_id);
        $this->db->bind(':to_role_id', $to_role_id);
        $this->db->bind(':action', $action);
        $this->db->bind(':remarks', $remarks);
        $this->db->bind(':action_by', $action_by);
        return $this->db->execute();
    }

    public function updateComplaint($id, $data, $details = []) {
        $this->db->query('UPDATE complaints SET applicant_name = :applicant_name, nic = :nic, address = :address, mobile = :mobile, email = :email, subject = :subject, category_id = :category_id, description = :description, forward_department_id = :forward_department_id WHERE id = :id');
        
        $this->db->bind(':applicant_name', $data['applicant_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':mobile', $data['mobile']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':forward_department_id', $data['forward_department_id']);
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            // Delete old details
            $this->db->query('DELETE FROM complaint_details WHERE complaint_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();

            // Insert dynamic details if any
            if(!empty($details) && is_array($details)){
                foreach($details as $detail){
                    $this->db->query('INSERT INTO complaint_details (complaint_id, letter_no, name, subject) VALUES (:complaint_id, :letter_no, :name, :subject)');
                    $this->db->bind(':complaint_id', $id);
                    $this->db->bind(':letter_no', $detail['letter_no']);
                    $this->db->bind(':name', $detail['name']);
                    $this->db->bind(':subject', $detail['subject']);
                    $this->db->execute();
                }
            }
            return true;
        }
        return false;
    }

    public function getLatestWorkflowLog($complaint_id, $action) {
        $this->db->query('SELECT * FROM workflow_logs WHERE complaint_id = :complaint_id AND action = :action ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':complaint_id', $complaint_id);
        $this->db->bind(':action', $action);
        return $this->db->single();
    }

    public function getRejectionLogs($complaint_id) {
        $this->db->query('SELECT * FROM workflow_logs WHERE complaint_id = :complaint_id AND action = "Reject" ORDER BY created_at DESC');
        $this->db->bind(':complaint_id', $complaint_id);
        return $this->db->resultSet();
    }
}
