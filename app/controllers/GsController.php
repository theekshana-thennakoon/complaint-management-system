<?php
class GsController extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('auth');
        }
        // Restrict to Government Secretary (role_id 3)
        if ($_SESSION['user_role_id'] != 3) {
            flash('auth_error', 'You do not have permission to access this page', 'alert alert-danger');
            redirect('dashboard');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index() {
        // Fetch complaints currently pending GS approval (current_role_id = 3)
        $complaints = $this->complaintModel->getComplaintsByRoleId(3);
        $all_complaints = $this->complaintModel->getComplaints();

        $approved = 0;
        $rejected = 0;
        $approved_reports = [];
        $rejected_reports = [];

        foreach($all_complaints as $c) {
            if(strpos($c->status, 'Approved by GS') !== false) {
                $approved++;
                $approved_reports[] = $c;
            }
            if(strpos($c->status, 'Rejected by GS') !== false) {
                $rejected++;
                $rejected_reports[] = $c;
            }
        }

        $data = [
            'title' => 'Government Secretary Dashboard',
            'complaints' => $complaints,
            'approved_reports' => $approved_reports,
            'rejected_reports' => $rejected_reports,
            'stats' => [
                'pending' => count($complaints),
                'approved' => $approved,
                'rejected' => $rejected
            ]
        ];

        $this->view('gs/index', $data);
    }

    public function show($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        $can_view = false;
        if ($complaint) {
            if ($complaint->current_role_id == 3) {
                $can_view = true;
            } elseif (strpos($complaint->status, 'Approved by GS') !== false || strpos($complaint->status, 'Rejected by GS') !== false) {
                $can_view = true;
            }
        }

        if (!$can_view) {
            flash('complaint_error', 'Complaint not found or not assigned to you', 'alert alert-danger');
            redirect('gs/index');
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

        $this->view('gs/show', $data);
    }

    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $remarks = trim($_POST['remarks']);
            
            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || $complaint->current_role_id != 3) {
                redirect('gs/index');
            }

            // Approve by GS: forward to Chief Clerk (5) for dispatch.
            // Governor (role_id=2) will see this automatically in their reports dashboard.
            $new_status = 'Approved by GS';
            $new_role_id = 5;
            
            if ($this->complaintModel->updateComplaintStatus($id, $new_status, $new_role_id)) {
                $this->complaintModel->logWorkflow($id, 3, $new_role_id, 'Approve', $remarks, $_SESSION['user_id']);
                flash('complaint_success', 'Complaint approved and forwarded to Chief Clerk. Governor has been notified.');
                redirect('gs/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('gs/show/'.$id);
        }
    }

    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $remarks = trim($_POST['remarks']);
            
            if (empty($remarks)) {
                flash('complaint_error', 'Remarks are required when rejecting a complaint', 'alert alert-danger');
                redirect('gs/show/'.$id);
            }

            $complaint = $this->complaintModel->getComplaintById($id);
            if (!$complaint || $complaint->current_role_id != 3) {
                redirect('gs/index');
            }

            // Send back to Administrative Officer (Role ID 4)
            $new_status = 'Rejected by GS';
            $new_role_id = 4;
            
            if ($this->complaintModel->updateComplaintStatus($id, $new_status, $new_role_id)) {
                $this->complaintModel->logWorkflow($id, 3, $new_role_id, 'Reject', $remarks, $_SESSION['user_id']);
                flash('complaint_success', 'Complaint rejected and returned to Administrative Officer for review.');
                redirect('gs/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('gs/show/'.$id);
        }
    }
}
