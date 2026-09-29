<?php
class DashboardController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('auth');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index(){
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        $month = isset($_GET['month']) ? trim($_GET['month']) : ($date ? '' : date('Y-m'));
        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
        $status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

        $user_complaints = $this->complaintModel->getComplaintsByUserId($_SESSION['user_id'], $month, $category_id, $date, $status_filter);
        $all_complaints = $this->complaintModel->getComplaints($month, $category_id, $date, $status_filter);
        $external_complaints = $this->complaintModel->getExternalComplaints($month, $category_id, $date, $status_filter);

        $status_summary = $this->complaintModel->getStatusSummary($_SESSION['user_id'], $month, $category_id, $date);
        $all_status_summary = $this->complaintModel->getStatusSummary(null, $month, $category_id, $date);

        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'pending'  => $status_summary['pending_total'],
                'approved' => $status_summary['approved_total'],
                'rejected' => $status_summary['rejected_total'],
                'sent'     => $status_summary['dispatched']
            ],
            'status_summary' => $status_summary,
            'all_status_summary' => $all_status_summary,
            'user_complaints' => $user_complaints,
            'departments' => $this->complaintModel->getDepartments(),
            'month' => $month,
            'date' => $date,
            'status_filter' => $status_filter,
            'category_id' => $category_id,
            'categories' => $this->complaintModel->getCategories(),
            'all_complaints' => $all_complaints,
            'external_complaints' => $external_complaints
        ];
        $this->view('dashboard/index', $data);
    }
}
