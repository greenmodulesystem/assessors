<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_shared extends CI_Controller{

    public function __construct()
	{
		parent::__construct();
        // store all models
		$model_list = [
         'treasurers/Assessment_Model' => 'MTreasurer',
         'application_form/Application_form_model' => 'MApplication',
         'treasurers/Assessment_Model' => 'MAssessment',
         'treasurers/Billing_Model' => 'MBilling',
         'treasurers/Fees_Model' => 'MFees',
         'treasurers/Profiles_Model' => 'MProfiles',
         'treasurers/Payables_Model' => 'MPayables',
         'treasurers/Payments_Model' => 'MPayments'
         
        ];
        // load all models
		$this->load->model($model_list);
    }
    
    public function get_status(){
        $this->MTreasurer->Application_ID = $this->input->post('application_id',TRUE);
        $result = $this->MTreasurer->assessment_collection_status();
        echo json_encode($result);
    }
    
    public function assessment(){
        try{
            if($this->input->get('id',TRUE) == ''){
                throw new Exception("Error Processing Request", 1);
                
            }
           
            $application_data =  $this->MApplication->getSingle_by_uid($this->input->get('id',TRUE));
            if($application_data == null){
                throw new Exception("Error Processing Request", 1);
            }
            $ID = $application_data->ID;
            $this->data['assessment'] = $this->MAssessment->assessment($ID);
            $a_ID = @$this->data['assessment']->ID;
            if($a_ID == null){
                $this->data['content'] = "pages/assessment/error";
            }else{
               
                $this->data['content'] = "assessment";
                $this->data['profiles'] = $this->MProfiles->get_profile($ID);
                $this->data['assessment'] = $this->MAssessment->assessment($ID);
                $this->data['fees'] = $this->MAssessment->assessment_fees3($a_ID,$ID);
                $this->data['bill_fees'] = $this->MBilling->billing_fees_copy($a_ID);
                
                $Cycle_ID  = $this->MAssessment->assessment_cycle($a_ID)->Cycle_ID;
                $this->data['cycle_year'] = $this->MProfiles->getcycleYear($Cycle_ID)->Cycle_date;
            }
          
    
            $this->load->view('layout',$this->data);
        }catch(Exception $e){
            echo $e->getMessage();
        }
      
    }

    


}

?>