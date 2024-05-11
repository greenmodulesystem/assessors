<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax_table extends CI_Controller
{
    // ------------------------------------ MODELS LIST ------------------------------------ //
    public function __construct()
    {
        parent::__construct();
        // unset($_SESSION['User_details_retype_password']);
        // unset($_SESSION['User_modules_retype_password']);
        $model_list = [
            'tax_table/Tax_table_Model' => 'tm',
            'tax_table/Tax_table_Services_Model' => 'tms',
        ];
        $this->load->model($model_list);
    }
    // ------------------------------------ MODELS LIST ------------------------------------ //

    public function index()
    {
        $this->data['content'] = "index";

        $this->data['m_list'] = $this->tm->get_tax_table_m();
        $this->data['d_list'] = $this->tm->get_tax_table_d();
        $this->data['s_list'] = $this->tm->get_tax_table_s();
        $this->data['retail'] = $this->tm->get_tax_table_r();
        $this->data['other'] = $this->tm->get_tax_table_other();
        $this->data['fixed'] = $this->tm->get_tax_table_fixed();
        // var_dump($this->tm->get_tax_table_fixed());
        $this->data['bus_line'] = $this->tm->get_business_line();

        $this->load->view('layout', $this->data);
    }

    public function save()
    {
        $this->tms->tbl = $this->input->post('tableClass');
        $this->tms->ID = $this->input->post('ID');
        $this->tms->tax = $this->input->post('tax');
        $this->tms->gross_from = $this->input->post('gf');
        $this->tms->gross_to = $this->input->post('gt');
        $response = $this->tms->save();
        echo json_encode($response);
    }

    public function save_retail()
    {
        $this->tms->ID = $this->input->post('ID');
        $this->tms->tax = $this->input->post('tax');
        $this->tms->tax_e = $this->input->post('tax_excess');
        $this->tms->gl = $this->input->post('gl');
        $this->tms->gm = $this->input->post('gm');
        $response = $this->tms->save_retail();
        echo json_encode($response);
    }

    public function save_other()
    {
        $this->tms->ID = $this->input->post('id');
        $this->tms->p1 = $this->input->post('percent1');
        $this->tms->p2 = $this->input->post('percent2');
        $response = $this->tms->save_other();
        echo json_encode($response);
    }

    public function save_fixed()
    {
        $this->tms->ID = $this->input->post('id');
        $this->tms->fee = $this->input->post('fee');
        $response = $this->tms->save_fixed();
        echo json_encode($response);
    }

    public function save_fixed_new()
    {
        $this->tms->description = $this->input->post('description');
        $this->tms->fee = $this->input->post('fee');
        $this->tms->category = $this->input->post('category');
        $response = $this->tms->save_fixed_new();
        echo json_encode($response);
    }
}
