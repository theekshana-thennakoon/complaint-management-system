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

    public function sent(){
        $complaints = $this->complaintModel->getDispatchedComplaintsByUserId($_SESSION['user_id']);

        foreach ($complaints as $complaint) {
            $complaint->dispatched_departments = $this->complaintModel->getDispatchedDepartments($complaint->id);
        }

        $data = [
            'title' => 'Sent to Departments',
            'complaints' => $complaints
        ];
        $this->view('complaints/sent', $data);
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

            // Determine routing based on button clicked
            $direct_forward = isset($_POST['direct_forward']) ? trim($_POST['direct_forward']) : '';
            if ($direct_forward === 'ao') {
                $status        = 'Forwarded to AO';
                $current_role  = 4; // AO role ID
            } elseif ($direct_forward === 'gs') {
                $status        = 'Forwarded to GS';
                $current_role  = 3; // GS role ID
            } else {
                $status        = 'Pending CC';
                $current_role  = 5; // CC role ID
            }

            $data = [
                'applicant_name'       => trim($_POST['applicant_name']),
                'nic'                  => trim($_POST['nic']),
                'address'              => trim($_POST['address']),
                'mobile'               => trim($_POST['mobile']),
                'email'                => trim($_POST['email']),
                'subject'              => trim($_POST['subject']),
                'category_id'          => trim($_POST['category_id']),
                'forward_department_id'=> trim($_POST['forward_department_id']),
                'description'          => isset($_POST['description']) ? trim($_POST['description']) : '',
                'complaint_no'         => 'GOV-C-'.date('YmdHis'),
                'date'                 => date('Y-m-d'),
                'status'               => $status,
                'current_role_id'      => $current_role,
                'created_by'           => $_SESSION['user_id'],
                'categories'           => $this->complaintModel->getCategories(),
                'departments'          => $this->complaintModel->getDepartments(),
                'err'                  => ''
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
            'rejections' => $rejections,
            'departments' => $this->complaintModel->getDepartments(),
            'dispatched_departments' => $this->complaintModel->getDispatchedDepartments($id)
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
        $data = [
            'complaint' => $complaint, 
            'details' => $details,
            'dispatched_departments' => $this->complaintModel->getDispatchedDepartments($id)
        ];
        require APPROOT . '/views/complaints/pdf_template.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $is_attachment = isset($_GET['action']) && $_GET['action'] == 'print' ? false : true;
        $dompdf->stream('Letter_'.$complaint->complaint_no.'.pdf', array("Attachment" => $is_attachment));
    }

    public function dispatch($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || strpos($complaint->status, 'Approved by GS') === false) {
                flash('complaint_error', 'Invalid complaint or not eligible for dispatch.', 'alert alert-danger');
                redirect('dashboard');
            }

            $department_ids = isset($_POST['department_ids']) ? $_POST['department_ids'] : [];
            if (empty($department_ids)) {
                flash('complaint_error', 'Please select at least one department.', 'alert alert-danger');
                redirect('dashboard');
            }

            // Sanitize
            $department_ids = array_map('intval', $department_ids);

            // Get department names for alert
            $allDepartments = $this->complaintModel->getDepartments();
            $sentToNames = [];
            foreach ($allDepartments as $d) {
                if (in_array($d->id, $department_ids)) {
                    $sentToNames[] = $d->name;
                }
            }
            $deptHtml = '<div style="text-align:left; margin-top:10px;"><strong>Sent to:</strong><ul>';
            foreach($sentToNames as $name) {
                $deptHtml .= '<li>' . htmlspecialchars($name) . '</li>';
            }
            $deptHtml .= '</ul></div>';

            if ($this->complaintModel->dispatchToDepartments($id, $department_ids, $_SESSION['user_id'])) {
                $_SESSION['sweet_success'] = 'Complaint Dispatched Successfully!';
                $_SESSION['sweet_html'] = $deptHtml;
                // Redirect back to the show page if available, else dashboard
                $redirect = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/complaints/show/') !== false
                    ? 'complaints/show/' . $id
                    : 'dashboard';
                redirect($redirect);
            } else {
                flash('complaint_error', 'Something went wrong. Please try again.', 'alert alert-danger');
                redirect('dashboard');
            }
        } else {
            redirect('dashboard');
        }
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
                    $direct_forward = isset($_POST['direct_forward']) ? trim($_POST['direct_forward']) : '';
                    if ($direct_forward === 'ao') {
                        $status        = 'Forwarded to AO';
                        $current_role  = 4; // AO role ID
                        $log_msg       = 'Complaint forwarded directly to AO after correction.';
                        $flash_msg     = 'Complaint successfully forwarded to AO.';
                    } elseif ($direct_forward === 'gs') {
                        $status        = 'Forwarded to GS';
                        $current_role  = 3; // GS role ID
                        $log_msg       = 'Complaint forwarded directly to GS after correction.';
                        $flash_msg     = 'Complaint successfully forwarded to GS.';
                    } else {
                        $status        = 'Resubmitted (Pending CC)';
                        $current_role  = 5; // CC role ID
                        $log_msg       = 'User corrected and resubmitted.';
                        $flash_msg     = 'Complaint successfully resubmitted to Chief Clerk.';
                    }

                    // Update status and log workflow
                    $this->complaintModel->updateComplaintStatus($id, $status, $current_role);
                    $this->complaintModel->logWorkflow($id, $complaint->current_role_id, $current_role, 'Resubmit', $log_msg, $_SESSION['user_id']);
                    
                    flash('complaint_success', $flash_msg);
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
