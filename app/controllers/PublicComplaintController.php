<?php
class PublicComplaintController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            flash('auth_error', 'You must log in before you can submit a complaint.', 'alert alert-warning');
            redirect('auth');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'applicant_name' => trim($_POST['applicant_name']),
                'nic' => trim($_POST['nic']),
                'address' => trim($_POST['address']),
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'category_id' => trim($_POST['category_id']),
                'description' => trim($_POST['description']),
                'complaint_no' => 'C-'.time().'-'.rand(10,99),
                'date' => date('Y-m-d'),
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            if(empty($data['applicant_name']) || empty($data['nic']) || empty($data['mobile']) || empty($data['subject']) || empty($data['category_id'])){
                $data['err'] = 'Please fill all required fields';
                $this->view('public/create', $data);
            } else {
                if($this->complaintModel->addComplaint($data)){
                    flash('complaint_success', 'Complaint submitted successfully. Your reference number is: <strong>' . $data['complaint_no'] . '</strong>');
                    redirect('publiccomplaint/status?ref=' . $data['complaint_no']);
                } else {
                    die('Something went wrong');
                }
            }
        } else {
            $data = [
                'applicant_name' => '',
                'nic' => '',
                'address' => '',
                'mobile' => '',
                'email' => '',
                'subject' => '',
                'category_id' => '',
                'description' => '',
                'categories' => $this->complaintModel->getCategories(),
                'err' => ''
            ];

            $this->view('public/create', $data);
        }
    }

    public function status(){
        $data = [
            'ref' => isset($_GET['ref']) ? trim($_GET['ref']) : '',
            'complaint' => null,
            'err' => ''
        ];

        if(!empty($data['ref'])){
            $complaint = $this->complaintModel->getComplaintByNo($data['ref']);
            if($complaint){
                $data['complaint'] = $complaint;
            } else {
                $data['err'] = 'No complaint found with this reference number.';
            }
        }

        $this->view('public/status', $data);
    }
}
