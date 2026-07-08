<?php
class PagesController extends Controller {
    public function __construct(){
        // Do not require login here
    }

    public function index(){
        redirect('auth/login');
    }
}
