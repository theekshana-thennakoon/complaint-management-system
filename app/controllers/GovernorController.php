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
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        $month = isset($_GET['month']) ? trim($_GET['month']) : ($date ? '' : date('Y-m'));
        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        $raw_complaints = $this->complaintModel->getComplaints($month, $category_id, $date);

        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $approved_reports = [];

        foreach($raw_complaints as $c) {
            if(strpos($c->status, 'Pending') !== false) {
                $pending++;
            } elseif(strpos($c->status, 'Approved by GS') !== false) {
                $approved_reports[] = $c;
                $approved++;
            } elseif(strpos($c->status, 'Rejected') !== false) {
                $rejected++;
            }
        }

        $all_complaints = !empty($status) ? $this->complaintModel->getComplaints($month, $category_id, $date, $status) : $raw_complaints;

        $data = [
            'title' => 'Governor Dashboard',
            'reports' => $approved_reports,
            'all_complaints' => $all_complaints,
            'stats' => [
                'total' => count($raw_complaints),
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected
            ],
            'month' => $month,
            'date' => $date,
            'status_filter' => $status,
            'category_id' => $category_id,
            'categories' => $this->complaintModel->getCategories()
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
            'attachments' => $this->complaintModel->getAttachments($id),
        ];

        $this->view('governor/show', $data);
    }
}

