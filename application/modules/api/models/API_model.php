<?php

defined('BASEPATH') OR exit ('No direct script to access allowed');

class API_model extends CI_Model 
{ 
	public function authenticate($username, $password, $api_key)
	{
		try
		{
			// var_dump($this->api_key());
            if (empty($username) || empty($password))
            {
                throw new Exception(REQUIRED_FIELD);
            }

			//
			// check if API key generated is equal
			// API  key changes everyday
			
			if ($api_key != $this->api_key())
			{
				throw new Exception(ERROR_API_KEY);
			}

            //
			// check if username exists
			$this->db->select('app.*,'.
					'b.Barangay,'.
					'p.Purok,'.
					'bt.Type'
				);
			$this->db->from('tbl_application_form app');
			$this->db->join('tbl_barangay b', 'app.Barangay_ID=b.ID', 'left');
			$this->db->join('tbl_purok p', 'app.Purok_ID=p.ID', 'left');
			$this->db->join('tbl_business_type bt', 'app.Business_type_ID=bt.ID', 'left');
			$this->db->where('app.Login_email_address', $username);
			$qry_username = $this->db->get();

            $get_user_account = $qry_username->row();
			if ($qry_username->num_rows() <= 0)
			{
				throw new Exception(NO_USERNAME_FOUND);
			}

			//
			// check if current account is enabled/disabled
			if ($get_user_account->Enable <= 0)
			{
				throw new Exception(ACCOUNT_DISABLED);
            }
            
            //
			// hash password
            $pass = sha1($get_user_account->Salt ." ". $password ." ". $get_user_account->Salt);
			if (($username != $get_user_account->Login_email_address) || ($pass != $get_user_account->Password))
			{
				throw new Exception(INVALID_USERNAME_PASSWORD);
			}

            if (!empty($get_user_account))
			{
				$error = array('has_error' => false, 'user_details' => $get_user_account);
				echo json_encode($error); 
			}
		}		
		catch (Exception $ex) 
		{ 
			$error = array('error_message' => $ex->getMessage(), 'has_error' => true);
			echo json_encode($error);
		}
    }
	
	public function requirements($id,$api_key){
		try
		{
			if (empty($id))
			{
				throw new Exception(REQUIRED_FIELD);
			}
			if ($api_key != $this->api_key())
			{
				throw new Exception(ERROR_API_KEY);
			}

			//authenticate user
			$this->db->select('*');
			$this->db->from('tbl_application_form');
			$this->db->where('U_ID',$id);
			$query = $this->db->get()->first_row();
			if($query==null){
				throw new Exception('No User found',1);
			}

			//get last cycle

			$this->db->select('c.*');
			$this->db->from('tbl_cycle c');
			$this->db->join('tbl_application_form app', 'c.Application_ID=app.ID', 'left');
			$this->db->where('app.ID',$query->ID);
			$this->db->order_by('c.ID','desc');
			$query2 = $this->db->get()->first_row();

			if($query2==null){
				throw new Exception('Application not approved',1);
			}

			$this->db->select('s.Department');
			$this->db->from('tbl_status s');
			$this->db->join('tbl_cycle c', 's.Cycle_ID=c.ID', 'left');
			$this->db->where('c.ID',$query2->ID);
			$query3 = $this->db->get()->result();

			$response = array('has_error' => false,'year'=> $query2->Cycle_date , 'requirements' => $query3);
			echo json_encode($response); 
			
		}		
		catch (Exception $ex) 
		{ 
			$error = array('error_message' => $ex->getMessage(), 'has_error' => true);
			echo json_encode($error);
		}
	}

    public function api_key()
    {
		date_default_timezone_set('Asia/Manila');
        $ci = & get_instance();
        $api_key = md5($ci->config->item('hash_key') . date('y-m-d') . $ci->config->item('hash_key'));

        return $api_key;
    }
}