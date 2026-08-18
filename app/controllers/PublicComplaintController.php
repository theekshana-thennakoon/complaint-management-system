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
                'letter_type' => isset($_POST['letter_type']) ? trim($_POST['letter_type']) : '',
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'complaint_no' => $this->complaintModel->generateComplaintNo(trim($_POST['district'] ?? '')),
                'date' => date('Y-m-d'),
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['nic']) || empty($data['subject']) || empty($data['category_id'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('public/create', $data);
            } else {
                if($complaint_id = $this->complaintModel->addComplaint($data)){
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
            $nic_or_mobile = isset($_GET['nic']) ? trim($_GET['nic']) : '';
        }

        $data = [
            'ref' => $ref,
            'nic_or_mobile' => $nic_or_mobile,
            'complaints' => [],
            'err' => ''
        ];

        if($_SERVER['REQUEST_METHOD'] == 'POST' || !empty($ref) || !empty($nic_or_mobile)){
            if(!empty($ref) && !empty($nic_or_mobile)){
                // Both Reference Number and NIC/Mobile supplied
                $complaint = $this->complaintModel->getComplaintByNo($ref);
                if($complaint){
                    if($complaint->nic === $nic_or_mobile || $complaint->mobile === $nic_or_mobile){
                        $complaint->attachments = $this->complaintModel->getAttachments($complaint->id);
                        $data['complaints'][] = $complaint;
                    } else {
                        $data['err'] = 'Verification failed. Reference Number and NIC / Mobile Number do not match.';
                    }
                } else {
                    $data['err'] = 'No complaint found with this Reference Number.';
                }
            } else if(!empty($ref)){
                // Only Reference Number supplied
                $complaint = $this->complaintModel->getComplaintByNo($ref);
                if($complaint){
                    if(!isLoggedIn()){
                        $data['err'] = 'NIC or Mobile number is required to verify identity.';
                    } else {
                        $complaint->attachments = $this->complaintModel->getAttachments($complaint->id);
                        $data['complaints'][] = $complaint;
                    }
                } else {
                    $data['err'] = 'No complaint found with this Reference Number.';
                }
            } else if(!empty($nic_or_mobile)){
                // Only NIC or Mobile supplied (returns all matching complaints)
                $complaints = $this->complaintModel->getComplaintsByNicOrMobile($nic_or_mobile);
                if(!empty($complaints)){
                    foreach($complaints as $c){
                        $c->attachments = $this->complaintModel->getAttachments($c->id);
                        $data['complaints'][] = $c;
                    }
                } else {
                    $data['err'] = 'No complaints found for the provided NIC / Mobile number.';
                }
            } else {
                $data['err'] = 'Please enter a Reference Number or NIC / Mobile Number to check status.';
            }
        }

        $this->view('public/status', $data);
    }
}
