<?php
class DashboardController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('auth');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index(){
        $complaints = $this->complaintModel->getComplaints();
        
        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $sent = 0;

        foreach($complaints as $c) {
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
            if($c->forward_department_id) {
                $sent++;
            }
        }

        $user_complaints = $this->complaintModel->getComplaintsByUserId($_SESSION['user_id']);

        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'sent' => $sent
            ],
            'user_complaints' => $user_complaints
        ];
        $this->view('dashboard/index', $data);
    }
}
