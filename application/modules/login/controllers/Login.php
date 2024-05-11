<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{
    public function __construct()
	{
		parent::__construct();
	}
    
    function index()
    {
        $sess_array = array(
            'applicant_form_id' => NULL,
            'application_id' => NULL,
            'service_status' => 0,
        );
        unset($_SESSION['User_details']);
        unset($_SESSION['User_modules']);
        unset($_SESSION['User_details_retype_password']);
        unset($_SESSION['User_modules_retype_password']);
        $this->session->set_userdata($sess_array);
        $this->load->view('index');
    }
    function retype_password()
    {
        $this->load->view('retype_password');
    }
}