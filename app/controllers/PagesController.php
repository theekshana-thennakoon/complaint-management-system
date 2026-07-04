<?php
class PagesController extends Controller {
    public function __construct(){
        // Do not require login here
    }

    public function index(){
        $data = [
            'title' => 'Welcome to Mahajana Dinaya',
            'description' => 'Government Complaint/Letter Management System'
        ];

        $this->view('pages/index', $data);
    }
}
