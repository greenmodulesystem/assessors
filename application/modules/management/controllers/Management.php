<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Management extends CI_Controller
{
    // ------------------------------------ MODELS LIST ------------------------------------ //
    public function __construct()
    {
        parent::__construct();
        // unset($_SESSION['User_details_retype_password']);
        // unset($_SESSION['User_modules_retype_password']);
        $model_list = [
            'management/Management_Model' => 'am',
            'management/Management_Services_Model' => 'ams',
        ];
        $this->load->model($model_list);
    }
    // ------------------------------------ MODELS LIST ------------------------------------ //

    // -------------------------------- APPLICANT FUNCTIONS -------------------------------- //
    public function index()
    {
        $this->data['content'] = "index";

        $this->data['fees_list'] = $this->am->get_mayors_permit_list();
        $this->data['bus_line'] = $this->am->get_business_line();
        // $this->data['sanitary_fees'] = $this->MFees->sanitary_fees();

        $this->load->view('layout', $this->data);
    }

    public function save()
    {
        $this->ams->bID = $this->input->post('busid');
        $this->ams->rate = $this->input->post('rate');
        $response = $this->ams->save();
        echo json_encode($response);
    }
}
