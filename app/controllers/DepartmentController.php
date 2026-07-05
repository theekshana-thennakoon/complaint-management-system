<?php
class DepartmentController extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('auth');
        }
        // Restrict to Subject Officer / Department user (role_id 6)
        if ($_SESSION['user_role_id'] != 6) {
            flash('auth_error', 'You do not have permission to access this page.', 'alert alert-danger');
            redirect('dashboard');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index() {
        $department_id = $_SESSION['user_department_id'];

        if (!$department_id) {
            flash('auth_error', 'Your account is not linked to a department. Please contact the administrator.', 'alert alert-danger');
            redirect('auth/logout');
        }

        $dispatched = $this->complaintModel->getDispatchedComplaintsByDepartment($department_id);

        $total = count($dispatched);
        $this_month = 0;
        foreach ($dispatched as $c) {
            if (date('Y-m') === date('Y-m', strtotime($c->dispatched_at))) {
                $this_month++;
            }
        }

        $data = [
            'title' => 'Department Letters',
            'dispatched' => $dispatched,
            'stats' => [
                'total' => $total,
                'this_month' => $this_month,
            ]
        ];

        $this->view('department/index', $data);
    }

    public function show($id) {
        $department_id = $_SESSION['user_department_id'];

        // Verify this complaint was dispatched to this department
        $dispatched = $this->complaintModel->getDispatchedComplaintsByDepartment($department_id);
        $allowed = false;
        foreach ($dispatched as $c) {
            if ($c->id == $id) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            flash('auth_error', 'You do not have permission to view this complaint.', 'alert alert-danger');
            redirect('department');
        }

        $complaint = $this->complaintModel->getComplaintById($id);
        $details = $this->complaintModel->getComplaintDetails($id);

        $data = [
            'complaint' => $complaint,
            'details' => $details,
        ];

        $this->view('department/show', $data);
    }
}
