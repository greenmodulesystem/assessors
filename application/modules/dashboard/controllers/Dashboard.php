<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        unset($_SESSION['User_details_retype_password']);
        unset($_SESSION['User_modules_retype_password']);
    }
    public function index()
    {
        $this->load->view('dashboard/index');
    }
}
