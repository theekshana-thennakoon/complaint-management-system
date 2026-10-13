<?php
class DepartmentController extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('auth');
        }
        // Restrict to Department Officer (role_id 7)
        if ($_SESSION['user_role_id'] != 7) {
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
        $reply = $this->complaintModel->getComplaintReply($id);
        $replies = $this->complaintModel->getRepliesByComplaintId($id);

        $data = [
            'complaint' => $complaint,
            'details' => $details,
            'reply' => $reply,
            'replies' => $replies
        ];

        $this->view('department/show', $data);
    }

    public function reply($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('department/show/' . $id);
        }

        $department_id = $_SESSION['user_department_id'];
        $dispatched = $this->complaintModel->getDispatchedComplaintsByDepartment($department_id);
        $allowed = false;
        foreach ($dispatched as $c) {
            if ($c->id == $id) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            flash('auth_error', 'You do not have permission to reply to this complaint.', 'alert alert-danger');
            redirect('department');
        }

        $reply_text = isset($_POST['reply_text']) ? trim($_POST['reply_text']) : '';
        if (empty($reply_text)) {
            flash('reply_error', 'Please enter your reply details before submitting.', 'alert alert-danger');
            redirect('department/show/' . $id);
        }

        // Handle attachment
        $attachment = null;
        if (!empty($_FILES['attachment']['name'])) {
            $upload_dir = APPROOT . '/../public/uploads/replies/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0755, true);
            }
            if ($_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['attachment']['name'];
                $tmp_name = $_FILES['attachment']['tmp_name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_name = 'reply_' . $id . '_' . time() . '_' . uniqid() . '.' . $ext;
                $target_path = $upload_dir . $new_name;
                if (move_uploaded_file($tmp_name, $target_path)) {
                    $attachment = [
                        'name' => $file_name,
                        'path' => 'uploads/replies/' . $new_name
                    ];
                }
            }
        }

        if ($this->complaintModel->saveDepartmentReply($id, $department_id, $reply_text, $attachment, $_SESSION['user_id'])) {
            $_SESSION['sweet_success'] = 'Reply submitted successfully! The complaint status has been updated to Reply Received.';
            redirect('department/show/' . $id);
        } else {
            flash('reply_error', 'Failed to save reply. Please try again.', 'alert alert-danger');
            redirect('department/show/' . $id);
        }
    }
}
