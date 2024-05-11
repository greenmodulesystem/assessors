<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate extends CI_Controller 
{
    function sign_in()
    {   
        try
        {
            $this->load->model('login/Users_model', 'model');
            $username = $this->input->post('Username');
            $password = $this->input->post('Password');

            $this->model->authenticate($username, $password);
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
            $this->load->model('login/Users_model', 'model');
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
    
    function retype_password()
    {   
        try
        {
            $this->load->model('login/Users_model', 'model');

            $this->model->U_ID = $this->input->post('Credential');
            $this->model->password = $this->input->post('Password');
            $this->model->Retype_Password = $this->input->post('Retype_Password');

            $this->model->change_password();
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
}