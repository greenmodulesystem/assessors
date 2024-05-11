<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
	}
	
	function change_password()
	{
		try
		{
			if (empty($this->password))
			{
				throw new Exception(REQUIRED_FIELD);
			}

			if (strlen($this->password) < 6)
			{
				throw new Exception(PASSWORD_LENGTH);
			}

			if($this->Retype_Password != $this->password){
				throw new Exception(NOT_MATCH);
			}

			if($this->config->item('default_password') == $this->Retype_Password){
				throw new Exception(DEFAULT_PASSWORD);
			}
			//
			// check if username exists
			$this->db->select('u.*,'.'p.Position');
			$this->db->from('tbl_users u');
			$this->db->join('tbl_position p', 'p.ID = u.Position_ID', 'left');
			$this->db->where('U_ID', $this->U_ID);
			// $this->db->join('tbl_position', 'tbl_position.ID = tbl_users.position_ID');
			$qry_username = $this->db->get();

			$get_user = $qry_username->row();
			if ($qry_username->num_rows() <= 0)
			{
				throw new Exception(NO_USERNAME_FOUND);
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
			$get_modules = $qry_modules;


			$salt = salt_generator(32);
			$password = sha1($salt ." ". $this->password ." ". $salt);

			$data = array(
				'Password_changed' => 1,
				'Password'	=>	$password,
				'Salt'		=>	$salt
			);

			$check = $this->db->get_where('tbl_users',array('U_ID'=>$this->U_ID))->row();

			if(empty($check) || is_null($check)){
				throw new Exception(ERROR_ACCESS_KEY);
			}
			//
			// assign value on session variable
			$_SESSION['User_details'] = $get_user;
			$_SESSION['User_modules'] = $get_modules;
			if(!empty($this->U_ID)){

				$this->db->where('U_ID', $this->U_ID);
				$this->db->update('tbl_users', $data);
			}
			else{
				$this->db->where('ID', $this->ID);
				$this->db->update('tbl_users', $data);
			}
			$error = array('has_error' => false);
			echo json_encode($error); 
			
		}
		catch(Exception $ex)
		{
			$error = array('error_message' => $ex->getMessage(), 'has_error' => true); 
			echo json_encode($error);
		}
	}
	
    function authenticate($username, $password)
	{
		try
		{
			if (empty($username) || empty($password))
			{
				throw new Exception(REQUIRED_FIELD);
			}

			//
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

			//
			// check if current account is enabled/disabled
			if ($get_user->Enable <= 0)
			{
				throw new Exception(ACCOUNT_DISABLED);
			}

			//
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
			$get_modules = $qry_modules;

			$_SESSION['User_details_retype_password'] = $get_user;
			$_SESSION['User_modules_retype_password'] = $get_modules;
			//
			// Retype password 
			$defaultpassword = sha1($get_user->Salt ." ". $this->config->item('default_password') ." ". $get_user->Salt);

			if($get_user->Password_changed == true){
				//
				// assign value on session variable
				$_SESSION['User_details'] = $get_user;
				$_SESSION['User_modules'] = $get_modules;
				$error = array('has_error' => false);
				echo json_encode($error); 
			}
			else if($defaultpassword==$pass){
				$response = array('password_verified' => true, 'has_error' => false);
				echo json_encode($response);
			}
		}
		catch(Exception $ex)
		{
			$error = array('error_message' => $ex->getMessage(), 'has_error' => true); 
			echo json_encode($error);
		}
    }
    
    function logout()
	{
		unset($_SESSION['User_details']);
		unset($_SESSION['User_modules']);

		if (empty($_SESSION['User_details']) && empty($_SESSION['User_modules']))
		{
			return true;
		}
	}
}