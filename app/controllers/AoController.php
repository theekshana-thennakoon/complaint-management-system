<?php
class AoController extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('auth');
        }
        // Restrict to Administrative Officer (role_id 4)
        if ($_SESSION['user_role_id'] != 4) {
            flash('auth_error', 'You do not have permission to access this page', 'alert alert-danger');
            redirect('dashboard');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index() {
        $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
        // Fetch complaints currently pending AO approval (current_role_id = 4)
        $complaints = $this->complaintModel->getComplaintsByRoleId(4, $month);
        $all_complaints = $this->complaintModel->getComplaints($month);

        $approved = 0;
        $rejected = 0;
        $submitted_to_gs = [];
        $rejected_reports = [];

        foreach($all_complaints as $c) {
            if($this->complaintModel->hasRoleLoggedAction($c->id, 4, 'Approve')) {
                $approved++;
                $submitted_to_gs[] = $c;
            }
            if($this->complaintModel->hasRoleLoggedAction($c->id, 4, 'Reject')) {
                $rejected++;
                $rejected_reports[] = $c;
            }
        }

        $data = [
            'title' => 'Administrative Officer Dashboard',
            'complaints' => $complaints,
            'submitted_to_gs' => $submitted_to_gs,
            'rejected_reports' => $rejected_reports,
            'stats' => [
                'pending' => count($complaints),
                'approved' => $approved,
                'rejected' => $rejected
            ],
            'all_complaints' => $all_complaints,
            'month' => $month
        ];

        $this->view('ao/index', $data);
    }

    public function show($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        $can_view = false;
        if ($complaint) {
            if ($complaint->current_role_id == 4) {
                $can_view = true;
            } elseif ($this->complaintModel->hasRoleActedOnComplaint($id, 4)) {
                $can_view = true;
            }
        }

        if (!$can_view) {
            flash('complaint_error', 'Complaint not found or not assigned to you', 'alert alert-danger');
            redirect('ao/index');
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

        $this->view('ao/show', $data);
    }

    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $remarks = trim($_POST['remarks']);
            
            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || $complaint->current_role_id != 4) {
                redirect('ao/index');
            }

            // Forward to Government Secretary (Role ID 3)
            $new_status = 'Approved by AO (Pending GS)';
            $new_role_id = 3;
            
            if ($this->complaintModel->updateComplaintStatus($id, $new_status, $new_role_id)) {
                $this->complaintModel->logWorkflow($id, 4, $new_role_id, 'Approve', $remarks, $_SESSION['user_id']);
                flash('complaint_success', 'Complaint approved and forwarded to Government Secretary.');
                redirect('ao/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('ao/show/'.$id);
        }
    }

    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $remarks = trim($_POST['remarks']);
            
            if (empty($remarks)) {
                flash('complaint_error', 'Remarks are required when rejecting a complaint', 'alert alert-danger');
                redirect('ao/show/'.$id);
            }

            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || $complaint->current_role_id != 4) {
                redirect('ao/index');
            }

            // Send back to Chief Clerk (Role ID 5)
            $new_status = 'Rejected by AO';
            $new_role_id = 5;
            
            if ($this->complaintModel->updateComplaintStatus($id, $new_status, $new_role_id)) {
                $this->complaintModel->logWorkflow($id, 4, $new_role_id, 'Reject', $remarks, $_SESSION['user_id']);
                flash('complaint_success', 'Complaint rejected and returned to Chief Clerk for correction.');
                redirect('ao/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('ao/show/'.$id);
        }
    }
}
