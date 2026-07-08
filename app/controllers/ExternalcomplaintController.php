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
                'complaint_no' => 'EXT-'.time().'-'.rand(10,99),
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
                $data['person'] = NULL;

                $details = [];
                if (!empty($data['description'])) {
                    $details[] = [
                        'letter_no' => 'External',
                        'name' => $data['description'],
                        'subject' => ''
                    ];
                }
                
                if($this->complaintModel->addComplaint($data, $details)){
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
}
