<?php
class PubliccomplaintController extends Controller {
    public function __construct(){
        $this->complaintModel = $this->model('Complaint');
    }

    public function create(){
        if(!isLoggedIn()){
            flash('auth_error', 'You must log in before you can submit a complaint.', 'alert alert-warning');
            redirect('auth');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'applicant_name' => trim($_POST['applicant_name']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'category_id' => trim($_POST['category_id']),
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'complaint_no' => $this->complaintModel->generateComplaintNo('external', $_SESSION['user_province'] ?? ''),
                'date' => date('Y-m-d'),
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['nic']) || empty($data['mobile']) || empty($data['subject']) || empty($data['category_id'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('public/create', $data);
            } else {
                if($complaint_id = $this->complaintModel->addComplaint($data)){
                    // Handle file uploads
                    $uploaded_files = [];
                    if (!empty($_FILES['attachments']['name'][0])) {
                        $upload_dir = APPROOT . '/../public/uploads/complaints/';
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

                    $_SESSION['sweet_success'] = 'Complaint submitted successfully!';
                    $_SESSION['sweet_ref'] = $data['complaint_no'];
                    redirect('publiccomplaint/status?ref=' . $data['complaint_no']);
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
                'subject' => '',
                'category_id' => '',
                'description' => '',
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            $this->view('public/create', $data);
        }
    }

    public function status(){
        $ref = '';
        $nic_or_mobile = '';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ref = isset($_POST['complaint_no']) ? trim($_POST['complaint_no']) : '';
            $nic_or_mobile = isset($_POST['nic_or_mobile']) ? trim($_POST['nic_or_mobile']) : '';
        } else {
            $ref = isset($_GET['ref']) ? trim($_GET['ref']) : '';
        }

        $data = [
            'ref' => $ref,
            'nic_or_mobile' => $nic_or_mobile,
            'complaint' => null,
            'attachments' => [],
            'err' => ''
        ];

        if(!empty($data['ref'])){
            $complaint = $this->complaintModel->getComplaintByNo($data['ref']);
            if($complaint){
                if(!isLoggedIn()) {
                    if(empty($data['nic_or_mobile']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
                        $data['err'] = 'NIC or Mobile number is required to verify identity.';
                    } else if(!empty($data['nic_or_mobile']) && $complaint->nic !== $data['nic_or_mobile'] && $complaint->mobile !== $data['nic_or_mobile']) {
                        $data['err'] = 'Verification failed. Incorrect NIC or Mobile number.';
                    } else if (!empty($data['nic_or_mobile'])) {
                        $data['complaint'] = $complaint;
                        $data['attachments'] = $this->complaintModel->getAttachments($complaint->id);
                    }
                } else {
                    $data['complaint'] = $complaint;
                    $data['attachments'] = $this->complaintModel->getAttachments($complaint->id);
                }
            } else {
                $data['err'] = 'No complaint found with this reference number.';
            }
        }

        $this->view('public/status', $data);
    }
}
