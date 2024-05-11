<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        unset($_SESSION['User_details_retype_password']);
        unset($_SESSION['User_modules_retype_password']);
    }
    public function index()
    {
        $this->load->view('users/index');
    }

    public function grid()
    {
        $this->load->model('Users_model','model');
        $search = $this->input->post('search');
        $result['result'] = $this->model->getAll($search);
         
		$this->load->view('users/grid', $result);
    }

    public function new_user()
    {
        $this->load->model('position/Position_model', 'position');
        $this->load->model('modules/Modules_model', 'modules');
        $data['position'] = $this->position->getAll();
        $data['modules'] = $this->modules->getAll();

        $this->load->view('users/form', $data);
    }

    public function update_user($id = 0)
    {
        if ($id > 0)
		{
            $this->load->model('user_modules/User_modules_model', 'user_modules');
            $this->load->model('position/Position_model', 'position');
            $this->load->model('modules/Modules_model', 'modules');
            $this->load->model('Users_model', 'users');

            $data['position'] = $this->position->getAll();
            $data['modules'] = $this->modules->getAll();
            $data['model'] = $this->users->getSingle($id);
            $data['user_modules'] = $this->user_modules->getAll($id);
 
			$this->load->view('users/form', $data);
		}
    }

    public function profile()
    {
        $this->load->model('position/Position_model', 'position');
        $this->load->model('modules/Modules_model', 'modules');

        $data['position'] = $this->position->getAll();
        $data['modules'] = $this->modules->getAll();
        
        $this->load->view('users/profile', $data);
    }
}