<?php
class ExternalcomplaintController extends Controller {
    public function __construct(){
        $this->complaintModel = $this->model('Complaint');
    }

    public function index(){
        redirect('externalcomplaint/create');
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'applicant_name' => trim($_POST['applicant_name']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'province' => trim($_POST['province']),
                'district' => trim($_POST['district']),
                'subject' => 'External Complaint', 
                'category_id' => trim($_POST['category_id'] ?? 1), 
                'description' => trim($_POST['reason']),
                'forward_department_id' => trim($_POST['forward_department_id']),
                'complaint_no' => $this->complaintModel->generateComplaintNo('external', trim($_POST['province'])),
                'date' => date('Y-m-d'),
                'departments' => $this->complaintModel->getDepartments(),
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['nic']) || empty($data['mobile']) || empty($data['description']) || empty($data['forward_department_id']) || empty($data['province']) || empty($data['district'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('external/create', $data);
            } else {
                $data['created_by'] = NULL;
                $data['status'] = 'Draft';
                $data['current_role_id'] = 6;
                $data['person'] = isset($_POST['person']) ? trim($_POST['person']) : NULL;

                $details = [];
                if (!empty($data['description'])) {
                    $details[] = [
                        'letter_no' => 'External',
                        'name' => $data['description'],
                        'subject' => ''
                    ];
                }
                
                if($complaint_id = $this->complaintModel->addComplaint($data, $details)){
                    // Handle file uploads
                    $uploaded_files = [];
                    if (!empty($_FILES['attachments']['name'][0])) {
                        $upload_dir = APPROOT . '/../public/uploads/complaints/';
                        if (!is_dir($upload_dir)) {
                            @mkdir($upload_dir, 0755, true);
                        }
                        foreach ($_FILES['attachments']['name'] as $key => $name) {
                            if ($_FILES['attachments']['error'][$key] == UPLOAD_ERR_OK) {
                                $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                                $ext = pathinfo($name, PATHINFO_EXTENSION);
                                $new_name = uniqid() . '_' . time() . '.' . $ext;
                                if (move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
                                    $uploaded_files[] = [
                                        'file_name' => $name,
                                        'file_path' => 'uploads/complaints/' . $new_name
                                    ];
                                }
                            }
                        }
                        $this->complaintModel->addAttachments($complaint_id, $uploaded_files);
                    }

                    $_SESSION['sweet_success'] = 'Complaint submitted successfully! Reference No: ' . $data['complaint_no'];
                    redirect('externalcomplaint/create');
                } else {
                    die('Something went wrong');
                }
            }
        } else {
            $data = [
                'applicant_name' => '',
                'nic' => '',
                'address' => '',
                'mobile' => '',
                'email' => '',
                'province' => '',
                'district' => '',
                'subject' => '',
                'reason' => '',
                'category_id' => '',
                'forward_department_id' => '',
                'departments' => $this->complaintModel->getDepartments(),
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            $this->view('external/create', $data);
        }
    }

    // AJAX: insert a new department and return its id+name as JSON (public, no login required)
    public function addDepartmentAjax() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Department name is required']);
            exit;
        }
        $id = $this->complaintModel->addDepartment($name);
        if ($id) {
            echo json_encode(['success' => true, 'id' => (int)$id, 'name' => $name]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add department']);
        }
        exit;
    }
}
