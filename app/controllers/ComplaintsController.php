<?php
class ComplaintsController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('auth');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index(){
        // Get complaints for logged-in user only
        $complaints = $this->complaintModel->getComplaintsByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Complaints',
            'complaints' => $complaints
        ];
        $this->view('complaints/index', $data);
    }

    public function create(){
        // Only Subject Officer (Level 10) can create official complaints usually, 
        // but we'll restrict if needed or let any logged in user see for now.
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $details = [];
            if (isset($_POST['detail_letter_no']) && isset($_POST['detail_name'])) {
                for ($i = 0; $i < count($_POST['detail_letter_no']); $i++) {
                    if (!empty(trim($_POST['detail_letter_no'][$i])) || !empty(trim($_POST['detail_name'][$i]))) {
                        $details[] = [
                            'letter_no' => trim($_POST['detail_letter_no'][$i]),
                            'name' => trim($_POST['detail_name'][$i]),
                            'subject' => ''
                        ];
                    }
                }
            }

            $data = [
                'applicant_name' => trim($_POST['applicant_name']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'category_id' => trim($_POST['category_id']),
                'forward_department_id' => trim($_POST['forward_department_id']),
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'complaint_no' => 'GOV-C-'.date('YmdHis'),
                'date' => date('Y-m-d'),
                'status' => 'Pending CC', // Submit straight to CC
                'current_role_id' => 5, // Role ID for CC is 5 based on schema
                'created_by' => $_SESSION['user_id'],
                'categories' => $this->complaintModel->getCategories(),
                'departments' => $this->complaintModel->getDepartments(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['subject']) || empty($data['category_id']) || empty($data['forward_department_id'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('complaints/create', $data);
            } else {
                $complaint_id = $this->complaintModel->addComplaint($data, $details);
                if($complaint_id){
                    $_SESSION['sweet_success'] = 'Complaint created successfully!';
                    $_SESSION['sweet_ref'] = $data['complaint_no'];
                    redirect('complaints/show/' . $complaint_id);
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
                'forward_department_id' => '',
                'description' => '',
                'categories' => $this->complaintModel->getCategories(),
                'departments' => $this->complaintModel->getDepartments(),
                'err' => ''
            ];

            $this->view('complaints/create', $data);
        }
    }

    public function show($id){
        $complaint = $this->complaintModel->getComplaintById($id);
        if(!$complaint){
            redirect('complaints');
        }

        $details = $this->complaintModel->getComplaintDetails($id);
        
        $reject_log = null;
        if ($complaint->status == 'Rejected by CC') {
            $reject_log = $this->complaintModel->getLatestWorkflowLog($id, 'Reject');
        }

        $rejections = $this->complaintModel->getRejectionLogs($id);

        $data = [
            'complaint' => $complaint,
            'details' => $details,
            'reject_log' => $reject_log,
            'rejections' => $rejections
        ];

        $this->view('complaints/show', $data);
    }

    public function generate_pdf($id){
        $complaint = $this->complaintModel->getComplaintById($id);
        if(!$complaint){
            redirect('complaints');
        }

        $details = $this->complaintModel->getComplaintDetails($id);

        // We will require composer autoloader here
        require_once APPROOT . '/../vendor/autoload.php';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        // Define writable directories for font generation
        $fontDir = APPROOT . '/../public/fonts';
        $options->set('fontDir', $fontDir);
        $options->set('fontCache', $fontDir);
        $options->set('chroot', realpath(APPROOT . '/../'));
        $options->set('defaultFont', 'Noto Sans Sinhala');

        $dompdf = new \Dompdf\Dompdf($options);

        // Fetch the HTML template (we will create a separate view file for this)
        ob_start();
        $data = ['complaint' => $complaint, 'details' => $details];
        require APPROOT . '/views/complaints/pdf_template.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('Letter_'.$complaint->complaint_no.'.pdf', array("Attachment" => true));
    }
    public function edit($id){
        $complaint = $this->complaintModel->getComplaintById($id);
        
        // Ensure complaint exists and belongs to the user
        if(!$complaint || $complaint->created_by != $_SESSION['user_id'] || $complaint->status != 'Rejected by CC'){
            flash('complaint_error', 'You cannot edit this complaint.', 'alert alert-danger');
            redirect('dashboard');
        }

        // Get rejection log
        $reject_log = $this->complaintModel->getLatestWorkflowLog($id, 'Reject');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $details = [];
            if (isset($_POST['detail_letter_no']) && isset($_POST['detail_name'])) {
                for ($i = 0; $i < count($_POST['detail_letter_no']); $i++) {
                    if (!empty(trim($_POST['detail_letter_no'][$i])) || !empty(trim($_POST['detail_name'][$i]))) {
                        $details[] = [
                            'letter_no' => trim($_POST['detail_letter_no'][$i]),
                            'name' => trim($_POST['detail_name'][$i]),
                            'subject' => ''
                        ];
                    }
                }
            }

            $data = [
                'id' => $id,
                'complaint' => $complaint,
                'reject_log' => $reject_log,
                'applicant_name' => trim($_POST['applicant_name']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'category_id' => trim($_POST['category_id']),
                'forward_department_id' => trim($_POST['forward_department_id']),
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'categories' => $this->complaintModel->getCategories(),
                'departments' => $this->complaintModel->getDepartments(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['subject']) || empty($data['category_id']) || empty($data['forward_department_id'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('complaints/edit', $data);
            } else {
                if($this->complaintModel->updateComplaint($id, $data, $details)){
                    // Update status back to pending and role to CC
                    $this->complaintModel->updateComplaintStatus($id, 'Resubmitted (Pending CC)', 5);
                    $this->complaintModel->logWorkflow($id, $complaint->current_role_id, 5, 'Resubmit', 'User corrected and resubmitted.', $_SESSION['user_id']);
                    
                    flash('complaint_success', 'Complaint successfully resubmitted to Chief Clerk.');
                    redirect('dashboard');
                } else {
                    die('Something went wrong');
                }
            }
        } else {
            $details = $this->complaintModel->getComplaintDetails($id);
            $data = [
                'id' => $id,
                'complaint' => $complaint,
                'reject_log' => $reject_log,
                'details' => $details,
                'applicant_name' => $complaint->applicant_name,
                'nic' => $complaint->nic,
                'address' => $complaint->address,
                'mobile' => $complaint->mobile,
                'email' => $complaint->email,
                'subject' => $complaint->subject,
                'category_id' => $complaint->category_id,
                'forward_department_id' => $complaint->forward_department_id,
                'description' => $complaint->description,
                'categories' => $this->complaintModel->getCategories(),
                'departments' => $this->complaintModel->getDepartments(),
                'err' => ''
            ];

            $this->view('complaints/edit', $data);
        }
    }
}
