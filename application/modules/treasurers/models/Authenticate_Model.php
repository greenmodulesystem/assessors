<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function authenticate($username, $password) {
        $ci = & get_instance();
		try
		{
			if (empty($username) || empty($password))
			{
				throw new Exception(REQUIRED_FIELD);
			}
	
			// check if username exists
			$this->db->select('u.*,'.'p.Position');
			$this->db->from('tbl_users u');
			$this->db->join('tbl_position p', 'p.ID = u.Position_ID', 'left');
			$this->db->where('Username', $username);
			$qry_username = $this->db->get();

			$get_user = $qry_username->row();
			if ($qry_username->num_rows() <= 0)
			{
				throw new Exception(NO_USERNAME_FOUND);
			}

			// check if current account is enabled/disabled
			if ($get_user->Enable <= 0)
			{
				throw new Exception(ACCOUNT_DISABLED);
			}

			// hash password
			$pass = sha1($get_user->Salt ." ". $password ." ". $get_user->Salt);
			if (($username != $get_user->Username) || ($pass != $get_user->Password))
			{
				throw new Exception(INVALID_PASSWORD);
			}

			$this->db->select('*');
			$this->db->from('tbl_user_modules');
			$this->db->where('User_ID', $get_user->ID);
			$qry_modules = $this->db->get()->result();
			foreach ($qry_modules as $key => $value) {
				$this->db->select('*');
				$this->db->from('tbl_modules');
				$this->db->where('ID', $value->Module_ID);
				$module_data = $this->db->get()->first_row();
				$qry_modules[$key]->Module_details =  $module_data;
			}
			$modules = $qry_modules;
            
            foreach ($modules as $key => $value) {
				if($value->Module_details->Module_name == $ci->config->item('department_short')){
					if (!(bool)$value->Full_control && !(bool)$value->Approved_only){
                        throw new Exception(ACCESS_DENIED);
                	}
				}
            }
			
			$error = array('user_details' => $get_user, 'has_error' => false);
			echo json_encode($error); 
		}
		catch(Exception $ex)
		{
			$error = array('error_message' => $ex->getMessage(), 'has_error' => true); 
			echo json_encode($error);
		}
    }
}