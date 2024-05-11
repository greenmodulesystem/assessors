<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Due_date extends CI_Controller
{
    // ------------------------------------ MODELS LIST ------------------------------------ //
    public function __construct()
    {
        parent::__construct();
        // unset($_SESSION['User_details_retype_password']);
        // unset($_SESSION['User_modules_retype_password']);
        $model_list = [
            'due_date/Due_date_Model' => 'dm',
            'due_date/Due_date_Services_Model' => 'dms',
        ];
        $this->load->model($model_list);
    }
    // ------------------------------------ MODELS LIST ------------------------------------ //

    // -------------------------------- APPLICANT FUNCTIONS -------------------------------- //
    public function index()
    {
        $this->data['content'] = "index";
        $this->data['due_date'] = $this->dm->get_due_date();

        $this->load->view('layout', $this->data);
    }

    public function save()
    {
        $this->dms->ID = $this->input->post('ID');
        $this->dms->dd = $this->input->post('day');
        $this->dms->mm = $this->input->post('month');
        $response = $this->dms->save();
        echo json_encode($response);
    }
}
