<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queueing extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        unset($_SESSION['User_details_retype_password']);
        unset($_SESSION['User_modules_retype_password']);
    }

    // public function index()
    // {
    //     $this->data['content'] = "index";
    //     $this->load->view('layout', $this->data);
    // }
}