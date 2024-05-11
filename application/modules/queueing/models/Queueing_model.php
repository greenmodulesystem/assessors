<?php

defined('BASEPATH') OR exit ('No direct script to access allowed');

class Queueing_model extends CI_Model 
{ 
 
    
    protected $Table = array(
        "this" => "tbl_queueing_departments",
        "number" => "tbl_queueing_numbers",
        "app" => "tbl_application_form",
        "window" => "tbl_queueing_windows",
        "logs" => "tbl_queueing_logs"

    );


  
    public  $User;
    public  $Position;
    private $Department;
    public  $Application_ID;
    public  $User_ID;
    public  $Window;
    public  $Number_type;
    public  $Number;
    private $Date_created;
    private $Pass_count; 
    public $Task_dept;
    private $max_pass;
   
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Manila");
        $this->Department = $this->config->item('department_short');
       $this->max_pass = 0;
    }

    public function Get_details(){
        $this->db->select('n.*,a.Business_name');
        $this->db->from($this->Table['number'].' n');
        $this->db->join($this->Table['app'].' a' ,'a.ID=n.Application_ID','left');
        $this->db->where('a.ID',$this->Application_ID);
        $query = $this->db->get()->first_row();
        if($query!=null){
            $data = new \stdClass;
            $data->id = $query->ID;
            $data->application = $query->Application_ID;
            $data->business_name = $query->Business_name;
            $data->number = $query->Number;
            $data->user = $query->User_ID;
            $data->type = $query->Priority_type;
            return $data;
        }   
        return $query;
    }

    public function Get_all(){
        try{
         
            $this->db->select(
                'd.ID,'.
                'n.Number,'.
                'd.Application_ID,'.
                'd.User_ID,'.
                'n.Priority_type,'.
                'n.Priority_type,'.
                'a.Business_name'
            );
            $this->db->from($this->Table['this'].' d');
            $this->db->join($this->Table['number'].' n', 'n.Application_ID=d.Application_ID','right');
            $this->db->join($this->Table['app'].' a' ,'a.ID=n.Application_ID','right');
            $this->db->where('DATE(n.Date_created)',date('y-m-d',time()));
            $this->db->where('d.Pass_count<=',$this->max_pass);
            $this->db->where('d.Department','AS');
            $this->db->where('d.Done',0);
            $this->db->order_by('n.Priority_type','desc');
            $this->db->order_by('d.ID','asc');
            // $this->db->order_by('n.ID','desc');
            $query = $this->db->get()->result(); 
            $data = [];
            foreach ($query as $key => $result) {
                $item = new \stdClass;
                $item->id = $result->ID;
                $item->application = $result->Application_ID;
                $item->business_name = $result->Business_name;
                $item->number = $result->Number;
                $item->type = $result->Priority_type;
                $item->user = $result->User_ID;
                // $item->process_type['release'] = $assest_response['release'];
                array_push($data,$item);
            }
            $response = array('has_error' => false, 'message' => $data);   
            return $response;
    }catch(Exception $e){   
        return array('has_error' => true, 'message' => $e->getMessage());
    }
    }


    public function Done(){
        try{
            if(empty($this->Application_ID) || (int)$this->Application_ID <= 0 || empty($this->Number)){
                throw new Exception("Error Processing Request", 1);
            }
            
            $data = [
                'Done' => 1,
                'Application_ID' => $this->Application_ID,
            ];
              $existing_file = $this->db->get($this->Table['number'])->first_row();
              
            // $this->db->where('Application_ID',$this->Application_ID);
         
            $data_logs = [];
            $window_details = $this->db->get_where($this->Table['window'],array('User_ID' => $_SESSION['User_details']->ID))->first_row();
            if($this->Task_dept === 'assessor'){

                $existing_file_dept =  $this->db->get($this->Table['this'], array('Application_ID' => $this->Application_ID,'Department' => 'AS'))->first_row();
                
                $this->db->where('Application_ID',$this->Application_ID);
                $this->db->where('Department','AS');
                $this->db->where('Done',0);
                $this->db->delete($this->Table['this']);
                
                $data_logs = array(
                    'Person_in_charge' => $_SESSION['User_details']->Last_name.', '.$_SESSION['User_details']->First_name,
                    'Application_ID' => $this->Application_ID,
                    'Number' => $existing_file->Number, 
                    'Task' => $this->Task_dept,
                    'Date_created' => $existing_file_dept->Date_created,
                    'Date_finished' => date("Y-m-d H:i:s",time()),
                );
            }else{
                throw new Exception("Error Processing Request 1", 1);
                
            }
            // insert to logs
            $this->db->insert($this->Table['logs'],$data_logs);
            
            

         
            
            return array('has_error' => false, 'message' => 'Success');
        }catch(Exception $e){
            return array('has_error' => true, 'message' => $e->getMessage());
        }
    }

    public function Pass(){
        
        try{

            if(empty($this->Number)  || empty($this->User_ID) || empty($this->Task_dept)){
                
                if(empty($this->Number)){
                    throw new Exception("no number", 1);
                    
                }
                if(empty($this->User_ID)){
                    throw new Exception("no id", 1);
                    
                }
                if(empty($this->Task_dept)){
                    throw new Exception("no task", 1);
                    
                }
            }

           if($this->Task_dept == 'assessor'){
                $this->db->select(
                    't.ID,'.
                    't.Application_ID,'.
                    'n.Number,'.
                    't.Done,'.
                    't.User_ID,'.
                    't.Pass_count,'
                );
                $this->db->from($this->Table['number'].' n');
                $this->db->join($this->Table['this'].' t' ,'t.Application_ID=n.Application_ID','left');
                $this->db->join($this->Table['app'].' a' ,'a.ID=t.Application_ID','left');
                $this->db->where('t.Done' , 0);
                $this->db->where('n.Number' , $this->Number);
                $this->db->where('t.Department','AS');
                $number_details = $this->db->get()->first_row();
                if($number_details!=null){
                    $data = array(
                        'User_ID' => NULL,
                        'Pass_count' =>  ((int) $number_details->Pass_count)+1,
                        'Date_created' => date("Y-m-d H:i:s",time()),
                    );

                    // $this->db->where('ID',$number_details->ID);
                   $this->db->where('Application_ID',$number_details->Application_ID);
                   $this->db->update($this->Table['this'],$data);
                }else{
                    throw new Exception("Data not found", 1);
                }
            }else{
                throw new Exception("Error Processing Request", 1);
                
            }
            $response = array(
                'has_error' => false,
                'message' => true,
            );
            //reset
           
        }catch(Exception $e){
            $error = array(
                'has_error' => true,
                'message' => $e->getMessage(),
            );
            return $error;
        }
    }

    public function Update_window_status($status){
        try{

            $this->Remove_previous_data();
            
            if(empty($this->User_ID) || empty($this->Window) || ((int)$this->Window) <0 && ((int)$this->Window) > 2){
                throw new Exception("Error Processing Request. Missing fields", 1);
            }
            $data = array(
                'User_ID' => $this->User_ID,
                'Task' => $this->Task_dept,
                'Window' => $this->Window
            );


            $this->db->select('*');
            $this->db->from($this->Table['window'].' w');
            // $this->db->join($this->Table['number'].' n', 'n.User_ID=w.User_ID','right');
            $this->db->where('w.Window',$this->Window);
            $this->db->where('w.User_ID!=',$this->User_ID);
            $this->db->where('w.Task','assessor');

            $existing_window = $this->db->get()->first_row();
            if($existing_window!=null){
                $user = $this->db->get_where('tbl_users',array('ID'=>$existing_window->User_ID))->first_row();
                throw new Exception("(".$user->Last_name.", ".$user->First_name.") in Window ".$this->Window." is currently online", 1);
            }else{
                // $this->db->where('Window',$this->Window);
                // $this->db->where('User_ID!=',$this->User_ID);
                // $this->db->delete($this->Table['window']);
            }
         
            if( strtolower($status) == 'true'){
                $query = $this->db->get_where($this->Table['window'],array('User_ID' => $this->User_ID, 'Task' => $this->Task_dept))->first_row();
                if($query!=null){
                    // throw new Exception("Error Processing Request", 1);
                    $this->db->where('User_ID', $data['User_ID']); 
                    $this->db->delete($this->Table['window']);
                }
                $this->db->where('User_ID', $data['User_ID']); 
                $this->db->delete($this->Table['window']);
                $this->db->insert($this->Table['window'],$data);
            }else if(strtolower($status) == 'false'){
                $this->db->where('User_ID', $data['User_ID']); 
                $this->db->delete($this->Table['window']);
                $this->db->where('User_ID', $data['User_ID']);
                $this->db->update($this->Table['number'],array('User_ID'=>NULL,'Window' => NULL));

                $this->db->where('User_ID', $data['User_ID']);
                $this->db->update($this->Table['this'],array('User_ID'=>NULL));
            }
            
            $window_details = $this->db->get_where($this->Table['window'],array('User_ID'=> $this->User_ID))->first_row();
            $response = array(
                'has_error' => false,
                'message' => $window_details,
            );
            //reset
            return $response;
        }catch(Exception $e){
            $error = array(
                'has_error' => true,
                'message' => $e->getMessage(),
            );
            return $error;
        }
    }
    
    public function Get_status(){
        try{
            if(empty($this->User_ID)){
                throw new Exception("Error Processing Request. No user found", 1);
                
            }
            $window_details = $this->db->get_where($this->Table['window'],array('User_ID'=> $this->User_ID))->first_row();
            $response = array(
                'has_error' => false,
                'message' => $window_details,
            );
            //reset
            return $response;
        }catch(Exception $e){
            $error = array(
                'has_error' => true,
                'message' => $e->getMessage(),
            );
            return $error;
        }
    }
    public function Assign(){
        
        try{


            if(empty($this->Number)  || empty($this->User_ID)){
                throw new Exception("Error Processing Request", 1);
            }

            if($this->Task_dept == 'assessor'){
                $this->db->select(
                    't.ID,'.
                    'n.Number,'.
                    't.Done,'.
                    't.User_ID,'
                );
                $this->db->from($this->Table['number'].' n');
                $this->db->join($this->Table['this'].' t' ,'t.Application_ID=n.Application_ID','left');
                $this->db->join($this->Table['app'].' a' ,'a.ID=t.Application_ID','left');
                $this->db->where('t.Done' , 0);
                $this->db->where('t.User_ID', NULL);
                $this->db->where('n.Number' , $this->Number);
                $this->db->where('t.Department' , 'AS');
                $number_details = $this->db->get()->first_row();
                if($number_details!=null){
                    $data = array(
                        'User_ID' => $this->User_ID,
                        'Date_created' => date("Y-m-d H:i:s",time()),
                    );
                    
                    $this->db->where('ID',$number_details->ID);
                    $this->db->update($this->Table['this'],$data);
                }else{
                    throw new Exception("Data not found", 1);
                    
                }
            }
            else{
                throw new Exception("Error Processing Request", 1);
                
            }
            $response = array(
                'has_error' => false,
                'message' => true,
            );
            //reset
           
        }catch(Exception $e){
            $error = array(
                'has_error' => true,
                'message' => $e->getMessage(),
            );
            return $error;
        }
    }


    public function Payment_status($status){
        try{
            if(empty($this->Application_ID) || (int)$this->Application_ID <= 0){
                throw new Exception("Error Processing Request", 1);
            }
            
            $window_details = $this->db->get_where($this->Table['window'],array('User_ID' => $_SESSION['User_details']->ID))->first_row();
            if($this->Task_dept === 'assessor'){
                // var_dump((bool)$status);
                if((bool)$status == true){ 
                    $data = [
                        'Department' => 'CO',
                        'Application_ID' => $this->Application_ID,
                    ];
                    
                    $this->db->where('Department','CO');
                    $this->db->where('Application_ID!=',NULL);
                    $this->db->where('Application_ID',$this->Application_ID);
                    
                    $existing_file = $this->db->get($this->Table['this'])->first_row();
                    if($existing_file!=null){
                        throw new Exception("Duplicate record", 1);
                    }
                    $this->db->insert($this->Table['this'],$data);
                    /**update 12072019 */
                        /**remove assessment queue */
                        $this->db->where('Department','AS');
                        $this->db->where('Application_ID!=',NULL);
                        $this->db->where('Application_ID',$this->Application_ID);
                        $this->db->delete($this->Table['this']);
                    /**update 12072019 */
                    // $existing_file_dept =  $this->db->get($this->Table['this'], array('Application_ID' => $this->Application_ID,'Department' => 'AS'))->first_row();
                    
                    $data_logs = array(
                        'Person_in_charge' => $_SESSION['User_details']->Last_name.', '.$_SESSION['User_details']->First_name,
                        'Application_ID' => $this->Application_ID,
                        'Number' => 0, 
                        'Task' => $this->Task_dept,
                        // 'Date_created' => $existing_file_dept->Date_created,
                        'Date_finished' => date("Y-m-d H:i:s",time()),
                    );
                    $this->db->insert($this->Table['logs'],$data_logs);
                    
                }else{
                    $this->db->where('Department','CO');
                    $this->db->where('Application_ID' ,$this->Application_ID);
                    $this->db->delete($this->Table['this']);
                    
                }
               
            }else{
                throw new Exception("Error Processing Request 1", 1);
                
            }
            // insert to logs
            
            

         
            
            return array('has_error' => false, 'message' => 'Success');
        }catch(Exception $e){
            return array('has_error' => true, 'message' => $e->getMessage());
        }
    }

    private function Remove_previous_data(){
        $this->db->where('DATE(Date_created)!=',date('y-m-d',time()));
        $this->db->delete($this->Table['window']);
    }
}