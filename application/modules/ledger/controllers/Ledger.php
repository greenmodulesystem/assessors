<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ledger extends CI_Controller
{
    // ------------------------------------ MODELS LIST ------------------------------------ //
    public function __construct()
    {
        parent::__construct();
        // unset($_SESSION['User_details_retype_password']);
        // unset($_SESSION['User_modules_retype_password']);
        $model_list = [
            'ledger/Ledger_Model' => 'lm',
            'ledger/Ledger_Services_Model' => 'lms',
        ];
        $this->load->model($model_list);
    }
    // ------------------------------------ MODELS LIST ------------------------------------ //

    // -------------------------------- APPLICANT FUNCTIONS -------------------------------- //
    public function index()
    {
        $this->data['content'] = "index";

        $this->load->view('layout', $this->data);
    }

    public function grid()
    {
        $this->data['content'] = "grid";

        $this->data['applicants'] = $this->lm->get_applicants();

        $this->load->view('layout', $this->data);
    }

    public function view_cycle()
    {
        $this->lm->ID = $this->uri->segment(3);
        $this->data['cycle_assessment'] = $this->lm->get_cycle_assessment();
        // var_dump($this->lm->get_cycle_assessment());
        $this->data['content'] = "view-ledger";
        $this->load->view('layout', $this->data);
    }

    public function view_assessment(){
        $this->lm->ID = $this->uri->segment(3);
        $this->data['cycle_details'] = $this->lm->get_cycle_assessment_details();
        // var_dump($this->lm->get_cycle_assessment());
        $this->data['content'] = "view-assessment";
        $this->load->view('layout', $this->data);
    }

    public function search()
    {
        $this->lms->search = $this->input->post('search');
        $this->data['content'] = "grid";
        $this->data['applicants'] = $this->lms->get_applicants();
        $this->load->view('layout', $this->data);
    }

    public function save()
    {
        $this->lms->bID = $this->input->post('busid');
        $this->lms->rate = $this->input->post('rate');
        $response = $this->lms->save();
        echo json_encode($response);
    }
}
