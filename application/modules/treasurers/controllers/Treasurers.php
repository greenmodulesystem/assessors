<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Treasurers extends CI_Controller {
// ------------------------------------ MODELS LIST ------------------------------------ //
    public function __construct(){
        parent::__construct();
        unset($_SESSION['User_details_retype_password']);
        unset($_SESSION['User_modules_retype_password']);
		$model_list = [
            'treasurers/Assessment_Model' => 'MAssessment',
            'treasurers/Authenticate_Model' => 'MAuthenticate',
            'treasurers/Billing_Model' => 'MBilling',
            'treasurers/Fees_Model' => 'MFees',
            'treasurers/Listings_Model' => 'MListings',
            'treasurers/Payables_Model' => 'MPayables',
            'treasurers/Payments_Model' => 'MPayments',
            'treasurers/Profiles_Model' => 'MProfiles',
            'treasurers/Reports_Model' => 'MReports'
		];
		$this->load->model($model_list);
    }
// ------------------------------------ MODELS LIST ------------------------------------ //

// -------------------------------- APPLICANT FUNCTIONS -------------------------------- //
    public function applicant($ID){
        $this->data['content'] = "applicant";

        $user =  $_SESSION['User_details'];
        $data = array(
            "Opened" => 1,
            "Opened_by" => $user->First_name." ".$user->Last_name
        );
        $this->MProfiles->open($ID,$data);

        $this->data['asset_size'] = $this->MFees->asset_size();
        $this->data['asset_size_r'] = $this->MFees->asset_size_real(); //added 5824 alob
        $this->data['sanitary_fees'] = $this->MFees->sanitary_fees();
        $this->data['solid_fees'] = $this->MFees->solid_fees();
        $this->data['payment_mode'] = $this->MFees->payment_mode();

        $this->data['assessment'] = $this->MAssessment->assessment($ID);
        $this->data['details'] = $this->MAssessment->assessment_details($ID);
        $this->data['blines'] = $this->MAssessment->assessment_lines($ID);

        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        
        $this->data['collection'] = $this->MBilling->collection($ID);
        
        $this->data['arrears'] = $this->MBilling->get_arrears($ID); //08-04-2020
        $this->data['retirement_data'] = $this->MProfiles->get_retirement_data($ID);
        
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['profiles']);
    }

    public function applicant_history($ID){
        $this->data['content'] = "applicant_history";
        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        $this->data['history'] = $this->MPayments->payment_history($ID);
        $this->load->view('layout', $this->data);
    }
    
    public function applicant_payables($ID){
        $this->data['content'] = "applicant_payables";

        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        $Payment_mode = $this->data['profiles']->Payment_mode_ID;

        $this->data['blines'] = $this->MAssessment->assessment_lines($ID);
        $this->data['assessment'] = $this->MAssessment->assessment($ID);
        $a_ID = null;
        if ($this->data['assessment'] != null){
            $a_ID = $this->data['assessment']->ID;
            $this->data['fees'] = $this->MAssessment->assessment_fees($a_ID);
        }
        $info = $this->data['information'] = $this->MAssessment->assessment_information($ID);

        if ($info != null){
            $this->data['bill_info'] = $this->MBilling->billing_information($info,$ID,$Payment_mode);
            $this->data['bill_fees'] = $this->MBilling->billing_fees($a_ID);
        } else {
            $this->data['bill_info'] = null;
            $this->data['bill_fees'] = null;
        }
        
        $this->data['collection'] = $this->MBilling->collection($ID);

        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['information']);
    }

    public function applicant_search(){
        $sess =  $_SESSION['User_details'];
        $user = $sess->First_name." ".$sess->Last_name;
        $data = array(
            "Opened" => 0,
            "Opened_by" => null
        );
        $this->MProfiles->close($user,$data);
        $this->data['content'] = "applicant_search";
        $this->load->view('layout', $this->data);
    }

    public function applicant_search_results(){
		$search = $_POST['search'];
		if ($search != null)
		{
            $this->data['content'] = "applicant_search_results";
			$this->data['result'] = $this->MProfiles->search($search); 
			$this->load->view('layout', $this->data);
            // echo json_encode($this->data['result']);
        }
    }
    
    public function line_of_business(){
        $this->data['content'] = "listings_lines";
        $this->data['barangays'] = $this->MListings->get_barangay();
        $this->data['classes'] = $this->MListings->get_class();
        $this->load->view('layout', $this->data);
    }

    public function listings_line_default(){
        $this->data['content'] = "listings_lines_grid";
        $this->data['result'] = $this->MListings->lines();
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['result']);
    }
    
    public function listings_line_filter(){
        $this->data['content'] = "listings_lines_grid";
        
		$brgy = $_POST['brgy'];
        $class = $_POST['class'];
		$from = $_POST['from'];
        $to = $_POST['to'];
        
        $this->data['result'] = $this->MListings->lines($brgy,$class,$from,$to);
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['result']);
    }

    public function type_of_ownership(){
        $this->data['content'] = "listings_owner";
        $this->data['barangays'] = $this->MListings->get_barangay();
        $this->data['ownership'] = $this->MListings->get_ownership();
        $this->load->view('layout', $this->data);
    }
    
    public function listings_owner_default(){
        $this->data['content'] = "listings_owner_grid";
        $this->data['result'] = $this->MListings->owner();
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['result']);
    }
    
    public function listings_owner_filter(){
        $this->data['content'] = "listings_owner_grid";
        
		$brgy = $_POST['brgy'];
        $class = $_POST['class'];
		$from = $_POST['from'];
        $to = $_POST['to'];
        
        $this->data['result'] = $this->MListings->owner($brgy,$class,$from,$to);
        $this->load->view('layout', $this->data);
    }

    public function types_of_business(){
        $this->data['content'] = "listings_types";
        $this->load->view('layout', $this->data);
    }
    
    public function listings_types_all(){
        $this->data['content'] = "listings_types_all";
        $this->data['result'] = $this->MListings->types_all();
        $this->load->view('layout', $this->data);
    }
    
    public function listings_types_summary(){
        $this->data['content'] = "listings_types_summary";
        $this->data['result'] = $this->MListings->types_summary();
        $this->load->view('layout', $this->data);
    }

    public function listings_types_all_filter(){
        $this->data['content'] = "listings_types_all";

		$brgy = $_POST['brgy'];
        $class = $_POST['class'];
		$from = $_POST['from'];
        $to = $_POST['to'];

        $this->data['result'] = $this->MListings->types_all($brgy,$class,$from,$to);
        $this->load->view('layout', $this->data);
    }
    
    public function listings_types_summary_filter(){
        $this->data['content'] = "listings_types_summary";
        
		$brgy = $_POST['brgy'];
        $class = $_POST['class'];
		$from = $_POST['from'];
        $to = $_POST['to'];

        $this->data['result'] = $this->MListings->types_summary($brgy,$class,$from,$to);
        $this->load->view('layout', $this->data);
    }
    
    public function reports($type){
        $sess =  $_SESSION['User_details'];
        $user = $sess->First_name." ".$sess->Last_name;
        $data = array(
            "Opened" => 0,
            "Opened_by" => null
        );
        $this->MProfiles->close($user,$data);
        $this->data['type'] = $type;
        $this->data['r_listings'] = $this->MReports->retirement_listings();
        $this->data['content'] = "reports";
        $this->load->view('layout', $this->data);
    }

    public function reports_grid($type){
        $this->data['content'] = "reports_grid";
        $this->data['result'] = $this->MReports->boxes_count($type);
        $this->load->view('layout', $this->data);
    }

    public function retirement_grid(){
        $this->data['content'] = "retirement_grid";
        $this->data['r_listings'] = $this->MReports->retirement_listings();
        $this->load->view('layout', $this->data);
    }

    public function counter(){
        $data = $this->MReports->boxes_count('count');
        echo json_encode($data);
    }

    public function view_assessment($ID,$a_ID = ''){
        $this->data['content'] = "view_assessment";
        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        $this->data['assessment'] = $this->MAssessment->assessment($ID);
        $this->data['fees'] = $this->MAssessment->assessment_fees2($a_ID,$ID);
        $this->data['bill_fees'] = $this->MBilling->billing_fees($a_ID);
        $this->data['collection'] = $this->MBilling->collection($ID);
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['profiles']);
    } 
    
    public function view_billing($ID,$a_ID = ''){
        $this->data['content'] = "view_billing";
        
        $this->data['expiry'] = $this->MAssessment->assessment($ID) == null ? null : 
        ($this->MAssessment->assessment($ID))->Expiry;

        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        $this->data['bill_fees'] = $this->MBilling->billing_statement($a_ID);
        $this->data['blines'] = $this->MAssessment->assessment_lines($ID);
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['blines']);
    }

    public function view_history($Ass_ID){
        $this->data['content'] = "view_history";

        $this->data['solid_fees'] = $this->MFees->solid_fees();

        $Cycle_ID  = $this->MAssessment->assessment_cycle($Ass_ID)->Cycle_ID;

        $this->data['details'] = $this->MAssessment->history_details($Cycle_ID);
        $this->data['blines'] = $this->MAssessment->history_lines($Cycle_ID);

            $App_ID = $this->MProfiles->get_App_ID($Cycle_ID);
            $this->data['profiles'] = $this->MProfiles->get_profile($App_ID);

        $this->data['cycle_year'] = $this->MProfiles->getcycleYear($Cycle_ID)->Cycle_date;
        $this->data['ass_ID'] = $Ass_ID;
        
        $this->load->view('layout', $this->data);
    }   
    
    public function view_history_assessment($ID,$Ass_ID){
        $this->data['content'] = "view_history_assessment";
        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        $this->data['assessment'] = $this->MAssessment->assessment_history($Ass_ID);
        $this->data['fees'] = $this->MAssessment->assessment_fees3($Ass_ID);
        $this->data['bill_fees'] = $this->MBilling->billing_fees_copy($Ass_ID);
        
        $Cycle_ID  = $this->MAssessment->assessment_cycle($Ass_ID)->Cycle_ID;
        $this->data['cycle_year'] = $this->MProfiles->getcycleYear($Cycle_ID)->Cycle_date;
        
        $this->load->view('layout', $this->data);
        echo json_encode($this->data['bill_fees']);
    }
    
    public function view_history_billing($ID,$Ass_ID){
        $this->data['content'] = "view_history_billing";
        
        $this->data['expiry'] = $this->MAssessment->assessment($ID) == null ? null : 
        ($this->MAssessment->assessment($ID))->Expiry;

        $this->data['profiles'] = $this->MProfiles->get_profile($ID);
        $this->data['bill_fees'] = $this->MBilling->billing_statement_copy($Ass_ID);
        $this->data['blines'] = $this->MAssessment->assessment_lines($ID);
        $this->load->view('layout', $this->data);
        // echo json_encode($this->data['blines']);
    }


    public function view_history_payables($Ass_ID){
        $this->data['content'] = "view_history_payables";

        $this->data['fees'] = $this->MAssessment->assessment_fees3($Ass_ID);
        $this->data['bill_fees'] = $this->MBilling->billing_fees_copy($Ass_ID);
        
        $this->data['assessment'] = $this->MAssessment->assessment_history($Ass_ID);

        $Cycle_ID  = $this->MAssessment->assessment_cycle($Ass_ID)->Cycle_ID;
        $App_ID = $this->MProfiles->get_App_ID($Cycle_ID);
        $this->data['profiles'] = $this->MProfiles->get_profile($App_ID);
        
        $this->load->view('layout', $this->data);
    }
// -------------------------------- APPLICANT FUNCTIONS -------------------------------- //

// --------------------------------- GENERAL FUNCTIONS --------------------------------- //
    public function submit(){
        date_default_timezone_set('Asia/Manila');
        $Date = date('Y-m-d H:i:s');
        $user =  $_SESSION['User_details'];
        $data = array(
            "Date_assessed" => $Date,
            "Assessed_by" => $user->First_name." ".$user->Middle_name[0].". ".$user->Last_name,
            "Expiry" => date('Y-m-d', strtotime($_POST['Expiry']))
        );

        $array = array(
            "Fee_name" => $_POST['Fee_name'],
            "Fee_category" => $_POST['Fee_category'],
            "Fee_stat" => $_POST['Fee_stat'],
            "Fee" => $_POST['Fee']
        );

        // $fees = array(
        //     "Fee_name" => $_POST['Fee_name'],
        //     "Balance" => $_POST['Balance'],
        //     "Discount" => $_POST['Discount'],
        //     "Surcharge" => $_POST['Surcharge'],
        //     "Interest" => $_POST['Interest']
        // );
        
        if(empty($_POST['ID'])){
            $Assessment_ID = $this->MAssessment->assess($data,$_POST['Application_ID']);
            $this->MAssessment->fees($array,$Assessment_ID);
            if($_POST['bill_info'] != null) {
                $this->MBilling->fees($_POST['bill_info'],$Assessment_ID);
            }
            $this->MAssessment->update_ready($Assessment_ID);
        } else {
            $this->MAssessment->update_assessment($data,$_POST['Application_ID'],$_POST['ID']);
            $this->MAssessment->update_fees($array,$_POST['ID']);
        }
    }

    public function update_fees(){
        $data = array(
            "Assessment_ID" => $_POST['Assessment_ID'],
            "Line_of_business" => $_POST['Line'],
            "Type" => $_POST['Type'],
            "Fees_amount" => $_POST['Fees_amount']
        );
        $this->MBilling->update_fees($data);
        $this->MBilling->update_fees_copy($data);
    }
    
    public function save_details(){
        date_default_timezone_set('Asia/Manila'); //TEMP
        $Date = date('Y-m-d H:i:s'); //TEMP

        $data = array(
            // "Category_ID" => $_POST['Category_ID'],
            "DSAFee" => $_POST['DSAFee'],
            "Flammable" => $_POST['Flammable'],
            "Delivery_Permit" => $_POST['Delivery'],
            "Trucking" => $_POST['Truck'],
            "Beach_Operator" => $_POST['Beach'],
        );

        $array = array(
            "Business_line_ID" => $_POST['Business_line_ID'],
            "Capitalization" => $_POST['Capitalization'],
            "Essential" => $_POST['Essential'],
            "NonEssential" => $_POST['NonEssential'],
            "Assessment_asset_ID" => $_POST['Assessment_asset_ID'],
            "mp_ID" => $_POST['mp_ID'], //added 5824 alob
            "Exempted" => $_POST['Exempted']
        );

        if(empty($_POST['ID'])){
            $this->MAssessment->details($data,$_POST['Application_ID']);
        } else {
            $this->MAssessment->update_details($data,$_POST['Application_ID'],$_POST['ID']);
        }

        $this->MAssessment->update_lines($array,$_POST['Application_ID']);
        $this->MProfiles->update($_POST['Employees'],$_POST['Payment_mode_ID'],$_POST['Application_ID']);
        $this->MProfiles->update_cenro($_POST['Solid_waste_ID'],$_POST['Application_ID'],$Date); //TEMP FUNCTION
    }

    public function approve(){
        date_default_timezone_set('Asia/Manila');
        $Date = date('Y-m-d H:i:s');
        $data = array(
            "Status" => 'Approved',
            "Action_by" => $_POST['Action_by'],
            "Position" => $_POST['User_Position'] == '' ? null : $_POST['User_Position'],
            "Action_date" => $Date
        );
         
        $this->MAssessment->approve_assessment($data,$_POST['ID']);
    }

    public function cancel(){
        date_default_timezone_set('Asia/Manila');
        $Date = date('Y-m-d H:i:s');
        $data = array(
            "Status" => 'Cancelled',
            "Action_by" => $_POST['Action_by'],
            "Position" => $_POST['User_Position'] == '' ? null : $_POST['User_Position'],
            "Action_date" => $Date
        );
        $this->MAssessment->approval_cancel($data,$_POST['ID']);
    }
    
    public function delete(){
        $this->MAssessment->delete($_POST['ID']);
        // var_dump($_POST['ID']);
    }

    public function authenticate(){
        $Username = $_POST['Username'];
        $Password = $_POST['Password'];
 
        $test = $this->MAuthenticate->authenticate($Username,$Password);

        if($test != null){
            echo json_encode($test);
        }
    }
// --------------------------------- GENERAL FUNCTIONS --------------------------------- //
}