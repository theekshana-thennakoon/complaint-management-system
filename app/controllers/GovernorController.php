<?php
class GovernorController extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('auth');
        }
        // Restrict to Governor (role_id 2)
        if ($_SESSION['user_role_id'] != 2) {
            flash('auth_error', 'You do not have permission to access this page', 'alert alert-danger');
            redirect('dashboard');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index() {
        $all_complaints = $this->complaintModel->getComplaints();

        $approved_reports = [];
        $total = 0;
        $this_month = 0;

        foreach($all_complaints as $c) {
            if(strpos($c->status, 'Approved by GS') !== false) {
                $approved_reports[] = $c;
                $total++;
                if(date('Y-m') === date('Y-m', strtotime($c->created_at))) {
                    $this_month++;
                }
            }
        }

        $data = [
            'title' => 'Governor Dashboard',
            'reports' => $approved_reports,
            'stats' => [
                'total' => $total,
                'this_month' => $this_month,
            ],
            'all_complaints' => $all_complaints
        ];

        $this->view('governor/index', $data);
    }

    public function show($id) {
        $complaint = $this->complaintModel->getComplaintById($id);

        // Governor can only view complaints approved by GS
        if (!$complaint || strpos($complaint->status, 'Approved by GS') === false) {
            flash('complaint_error', 'Complaint not found or not available for viewing.', 'alert alert-danger');
            redirect('governor/index');
        }

        $details = $this->complaintModel->getComplaintDetails($id);
        $rejections = $this->complaintModel->getRejectionLogs($id);

        $data = [
            'complaint' => $complaint,
            'details' => $details,
            'rejections' => $rejections,
        ];

        $this->view('governor/show', $data);
    }
}

