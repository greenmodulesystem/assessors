<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API extends CI_Controller 
{  
    public function __construct(){
        parent::__construct();
        unset($_SESSION['User_details_retype_password']);
        unset($_SESSION['User_modules_retype_password']);
    }

    function sign_in()
    {   
        try
        {
            $this->load->model('API_model', 'model');

            $username = $this->input->post('Username');
            $password = $this->input->post('Password');
            $api_key  = $this->input->post('API_key');

            $this->model->authenticate($username, $password, $api_key);
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    function sign_out()
    {
        try
        {
            $this->load->model('users/Users_model', 'model');
            if ($this->model->logout() == true)
            {
                redirect(base_url());
                exit();
            }
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    function get_requirements(){
        try
        {
            $this->load->model('API_model', 'model');

            $id = $this->input->post('ID',TRUE);
            $api_key  = $this->input->post('API_key',TRUE);

            $this->model->requirements($id,$api_key);
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
}