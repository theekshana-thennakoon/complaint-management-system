<?php
class DashboardController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('auth');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index(){
        $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
        $user_complaints = $this->complaintModel->getComplaintsByUserId($_SESSION['user_id'], $month, $category_id);
        
        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $sent = 0;

        foreach($user_complaints as $c) {
            $status = strtolower($c->status);
            if(strpos($status, 'pending') !== false || $status == 'draft') {
                $pending++;
            }
            if(strpos($status, 'approved') !== false) {
                $approved++;
            }
            if(strpos($status, 'reject') !== false) {
                $rejected++;
            }
            if($this->complaintModel->isDispatched($c->id)) {
                $sent++;
            }
        }

        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'sent' => $sent
            ],
            'user_complaints' => $user_complaints,
            'departments' => $this->complaintModel->getDepartments(),
            'month' => $month,
            'category_id' => $category_id,
            'categories' => $this->complaintModel->getCategories(),
            'all_complaints' => $this->complaintModel->getComplaints($month, $category_id),
            'external_complaints' => $this->complaintModel->getExternalComplaints($month, $category_id)
        ];
        $this->view('dashboard/index', $data);
    }
}
