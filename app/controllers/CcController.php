<?php
class CcController extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('auth');
        }
        // Restrict to Chief Clerk (role_id 5)
        if ($_SESSION['user_role_id'] != 5) {
            flash('auth_error', 'You do not have permission to access this page', 'alert alert-danger');
            redirect('dashboard');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index() {
        // Fetch complaints currently pending CC approval (current_role_id = 5)
        $complaints = $this->complaintModel->getComplaintsByRoleId(5);
        $all_complaints = $this->complaintModel->getComplaints();

        $approved = 0;
        $rejected = 0;
        $submitted_to_ao = [];
        $rejected_reports = [];

        foreach($all_complaints as $c) {
            if(strpos($c->status, 'Approved by CC') !== false) {
                $approved++;
                $submitted_to_ao[] = $c;
            }
            if(strpos($c->status, 'Rejected by CC') !== false) {
                $rejected++;
                $rejected_reports[] = $c;
            }
        }

        $data = [
            'title' => 'Chief Clerk Dashboard',
            'complaints' => $complaints,
            'submitted_to_ao' => $submitted_to_ao,
            'rejected_reports' => $rejected_reports,
            'stats' => [
                'pending' => count($complaints),
                'approved' => $approved,
                'rejected' => $rejected
            ],
            'all_complaints' => $all_complaints
        ];

        $this->view('cc/index', $data);
    }

    public function show($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        $can_view = false;
        if ($complaint) {
            if ($complaint->current_role_id == 5) {
                $can_view = true;
            } elseif (strpos($complaint->status, 'Approved by CC') !== false || strpos($complaint->status, 'Rejected by CC') !== false) {
                $can_view = true;
            }
        }

        if (!$can_view) {
            flash('complaint_error', 'Complaint not found or not assigned to you', 'alert alert-danger');
            redirect('cc/index');
        }

        $details = $this->complaintModel->getComplaintDetails($id);
        $rejections = $this->complaintModel->getRejectionLogs($id);

        $data = [
            'complaint' => $complaint,
            'details' => $details,
            'rejections' => $rejections,
            'remarks' => '',
            'remarks_err' => ''
        ];

        $this->view('cc/show', $data);
    }

    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $remarks = trim($_POST['remarks']);
            
            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || $complaint->current_role_id != 5) {
                redirect('cc/index');
            }

            // Forward to AO (Role ID 4)
            $new_status = 'Approved by CC (Pending AO)';
            $new_role_id = 4;
            
            if ($this->complaintModel->updateComplaintStatus($id, $new_status, $new_role_id)) {
                $this->complaintModel->logWorkflow($id, 5, $new_role_id, 'Approve', $remarks, $_SESSION['user_id']);
                flash('complaint_success', 'Complaint approved and forwarded to Administrative Officer.');
                redirect('cc/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('cc/show/'.$id);
        }
    }

    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $remarks = trim($_POST['remarks']);
            
            if (empty($remarks)) {
                flash('complaint_error', 'Remarks are required when rejecting a complaint', 'alert alert-danger');
                redirect('cc/show/'.$id);
            }

            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || $complaint->current_role_id != 5) {
                redirect('cc/index');
            }

            // Send back to Subject Officer (Role ID 6)
            $new_status = 'Rejected by CC';
            $new_role_id = 6;
            
            if ($this->complaintModel->updateComplaintStatus($id, $new_status, $new_role_id)) {
                $this->complaintModel->logWorkflow($id, 5, $new_role_id, 'Reject', $remarks, $_SESSION['user_id']);
                flash('complaint_success', 'Complaint rejected and returned to Subject Officer.');
                redirect('cc/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('cc/show/'.$id);
        }
    }

    public function edit($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        // Ensure complaint exists, assigned to CC, and was rejected by AO
        if(!$complaint || $complaint->current_role_id != 5 || $complaint->status != 'Rejected by AO'){
            flash('complaint_error', 'You cannot edit this complaint.', 'alert alert-danger');
            redirect('cc');
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
                'person' => isset($_POST['person']) ? trim($_POST['person']) : '',
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'categories' => $this->complaintModel->getCategories(),
                'departments' => $this->complaintModel->getDepartments(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['subject']) || empty($data['category_id']) || empty($data['forward_department_id']) || empty($data['person'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('cc/edit', $data);
            } else {
                if($this->complaintModel->updateComplaint($id, $data, $details)){
                    // Update status back to pending AO (Role ID 4)
                    $this->complaintModel->updateComplaintStatus($id, 'Approved by CC (Pending AO)', 4);
                    $this->complaintModel->logWorkflow($id, 5, 4, 'Resubmit', 'CC corrected and resubmitted to AO.', $_SESSION['user_id']);
                    
                    flash('complaint_success', 'Complaint successfully re-corrected and resubmitted to Administrative Officer.');
                    redirect('cc');
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
                'person' => $complaint->person,
                'description' => $complaint->description,
                'categories' => $this->complaintModel->getCategories(),
                'departments' => $this->complaintModel->getDepartments(),
                'err' => ''
            ];

            $this->view('cc/edit', $data);
        }
    }
}
