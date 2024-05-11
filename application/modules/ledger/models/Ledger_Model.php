<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ledger_Model extends CI_Model
{

    public $Application_ID;

    function __construct()
    {
        parent::__construct();
        // $model_list = [ 
        //     'treasurers/Fees_Model' => 'MFees',
        //     'treasurers/Profiles_Model' => 'MProfiles',
        // ];
        // $this->load->model($model_list);

        // $libray_list = [
        //     "Logs_library" => 'logs'
        // ];
        // $this->load->library($libray_list);
    }

    private function getcycleDate($ID)
    {
        $this->db->select('Cycle_date');
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get_where('tbl_cycle', array('Application_ID' => $ID))->first_row();
        return $query;
    }

    private function getcycleID($ID)
    {
        $cycle = $this->getcycleDate($ID);
        if ($cycle != null) {
            $this->db->select('ID');
            $this->db->from('tbl_cycle');
            $this->db->where('Cycle_date', $cycle->Cycle_date);
            $this->db->where('Application_ID', $ID);
            $this->db->order_by('ID', 'desc');
            $query = $this->db->get()->first_row();
        }
        // var_dump($cycle);
        else {
            $this->db->select('ID');
            $this->db->from('tbl_cycle');
            // $this->db->where('Cycle_date', $cycle->Cycle_date);
            $this->db->where('ID', $ID);
            $this->db->order_by('ID', 'desc');
            $query = $this->db->get()->first_row();
        }
        return $query;
    }

    public function get_applicants()
    {
        $this->db->select(
            '*'
        );
        $this->db->from('tbl_application_form');
        // $this->db->join('tbl_fees_solid_waste w', 'w.ID = c.Solid_waste_ID', 'left');
        $this->db->where('Cancelled', 0);
        $this->db->where('Retired', 0);
        $query = $this->db->get();
        return $query->result();
        // var_dump($query->result());
    }

    public function penalty_fees($ID)
    {
        $this->db->where('Assessment_ID', $ID);
        $this->db->order_by('Qtr', 'asc');
        $query = $this->db->get('tbl_billing_fees');
        $result = $query->result(); 
        $total = 0;

        foreach ($result as $r) {
            $total += (floatval($r->Surcharge) + floatval($r->Interest));
        }
        return $total;
    }

    public function get_cycle_assessment()
    {
        $cycleAssessments = array();
        $this->db->select(
            'ID, Cycle_date'
        );
        $this->db->where('Application_ID', $this->ID);
        $this->db->from('tbl_cycle');
        $this->db->order_by('Cycle_date', 'asc');
        $query = $this->db->get()->result();
        foreach ($query as $val) {
            $assessment_fees = 0;
            $balance = 0;
            $col = 0;
            $assessmentID = $this->assessment(@$val->ID);
            if ($assessmentID != null) {
                $penalties = $this->penalty_fees($assessmentID->ID);
                $assessment_fees = $this->assessment_fees($assessmentID->ID, @$val->ID);
                $collect = $this->db->get_where('tbl_collection', array('Cycle_ID' => $val->ID))->result();
                if (!empty($collect) || $collect != null) {
                    foreach ($collect as $c) {
                        $col += floatval(@$c->Amount_paid);
                    }
                    $balance = $assessment_fees - $col;
                } else {
                    $balance = $assessment_fees + $penalties;
                }

                $cycleAssessment = new stdClass();
                $cycleAssessment->Cycle_ID = $val->ID;
                $cycleAssessment->Cycle_date = $val->Cycle_date;
                $cycleAssessment->total_assessed = $assessment_fees + $penalties;
                $cycleAssessment->amount_payable = $balance;

                $cycleAssessments[] = $cycleAssessment;
            }
        }
        // var_dump($cycleAssessments);
        return $cycleAssessments;
    }

    public function assessment($ID)
    {
        $this->db->where('Cycle_ID', $ID);
        // $this->db->where('Status', 'Approved');
        $this->db->order_by('ID', 'desc');
        //$this->db->where('Expiry >=', date('Y-m-d'));
        $query = $this->db->get('tbl_assessment');
        return $query->first_row();
    }

    public function assessment_fees($a_ID, $ID)
    {
        $this->db->where('Assessment_ID', $a_ID);
        $query = $this->db->get('tbl_assessment_fees');
        $result = $query->result();
        $total = 0;

        foreach ($result as $r) {
            $total += (floatval($r->Fee));
        }
        return $total;
    }
    public function assessment_other_reg_fees($a_ID, $ID)
    {
        $this->db->where('Assessment_ID', $a_ID);
        $this->db->where('Fee_category !=', 'Business Tax');
        $query = $this->db->get('tbl_assessment_fees');
        $result = $query->result();
        $total = 0;

        foreach ($result as $r) {
            $total += (floatval($r->Fee));
        }
        return $total;
    }

    public function check_collection($a_ID, $ID)
    {
        $this->db->where('Assessment_ID', $a_ID);
        $query = $this->db->get('tbl_assessment_fees');
        $result = $query->result();
        $total = 0;
        $tax = [];
        $reg = [];
        $chr = [];

        $collect = $this->db->get_where('tbl_collection', array('Cycle_ID' => $ID));
        foreach ($result as $r) {
            $collect->num_rows() > 0 ? 0 : $r->Fee;
            $total += (floatval($r->Fee));
        }
        return $total;
    }

    public function get_cycle_assessment_details()
    {
        $cycleDetailss = array();
        $cycle = $this->getcycleID($this->ID);
        $app_type = $this->get_app_type($cycle->ID);
        $bus_type = $this->bus_type($cycle->ID);
        $assessmentID = $this->assessment($cycle->ID);
        // var_dump($assessmentID);

        if ($assessmentID != null) {
            //if business line is tricycle, etrike, transloading, or if application is new
            if ($app_type->Application_status_ID == 1 || strpos($bus_type->Business_line, 'Tricycle') !== false || strpos($bus_type->Business_line, 'Trisikad/E-bike')  !== false ||   strpos($bus_type->Business_line, 'TRANSLOADING') !== false) {
                // echo 'trgd';
                $assessment_fees = $this->assessment_fees($assessmentID->ID, @$cycle->ID);
                $collection = $this->check_paid($cycle->ID);
                // var_dump($collection);
                $cycleDetails = new stdClass();
                $cycleDetails->Assessment_Date = $assessmentID->Date_assessed;
                $cycleDetails->Payment_Mode = 'NEW';
                $cycleDetails->Due = NULL;
                $cycleDetails->Amount_Payable = @$collection[0]->Amount_paid != null ? 0 : $assessment_fees;
                $cycleDetails->Amount_Paid = @$collection[0]->Amount_paid != null ? @$collection[0]->Amount_paid : 0;
                $cycleDetails->OR_Number = $collection != null ? @$collection[0]->OR_number : '-';
                $cycleDetails->Date_Paid = $collection != null ? @$collection[0]->Date_paid : '-';
                $cycleDetailss[] = $cycleDetails;
            }
            //else - if assessment is renewal
            else {
                $assessment_fees = $this->assessment_fees($assessmentID->ID, @$cycle->ID);
                $collection = $this->check_paid($cycle->ID);
                // var_dump($collection);
                $bill_fees = $this->bill_fees($assessmentID->ID);
                $ap = 0;
                foreach ($bill_fees as $k => $bval) {
                    $cycleDetails = new stdClass();
                    $cycleDetails->Assessment_Date = $assessmentID->Date_assessed;
                    $cycleDetails->Payment_Mode = $bval->Qtr;
                    $cycleDetails->Due = $bval->Due_date;
                    // $cycleDetails->Amount_Payable = $assessment_fees - ($ap + ($bval->Surcharge + $bval->Interest));
                    // $cycleDetails->Amount_Paid = 0;
                    // $cycleDetails->OR_Number = null;
                    // $cycleDetails->Date_Paid = null;
                    //assessed but--
                    if (@$collection[$k] != null || isset($collection[$k])) { //paid
                        $cycleDetails->Amount_Payable = 0;
                        // $cycleDetails->Total = $assessment_fees;
                        $cycleDetails->Amount_Paid = @$collection[$k]->Amount_paid;
                        $ap += floatval(@$collection[$k]->Amount_paid);
                        $cycleDetails->OR_Number = @$collection[$k]->OR_number;
                        $cycleDetails->Date_Paid = @$collection[$k]->Date_paid;
                    } else { //not paid
                        // $cycleDetails->Amount_Payable = $assessment_fees - ($ap + ($bval->Surcharge + $bval->Interest)); //correction
                        $cycleDetails->Amount_Payable = $bval->Balance + ($bval->Surcharge + $bval->Interest) + ($k==0? $this->assessment_other_reg_fees($assessmentID->ID , @$cycle->ID) : 0); //correction
                        // $cycleDetails->Total = $assessment_fees;
                        $cycleDetails->Amount_Paid = 0;
                        $cycleDetails->OR_Number = null;
                        $cycleDetails->Date_Paid = null;
                    }

                    $cycleDetailss[] = $cycleDetails;
                }
            }
        }
        // var_dump($cycleDetailss);
        // var_dump($assessment_fees);
        return $cycleDetailss;
    }
    public function get_mayors_permit_list()
    {
        $this->db->select(
            'mp.*, b.Description'
        );
        $this->db->order_by('ID', 'asc');
        $this->db->from('tbl_fees_mayor_permit_v2 mp');
        $this->db->join('tbl_business_line b', 'b.ID=mp.BusLine_ID', 'left');
        $result = $this->db->get()->result();
        return $result;
    }
    public function check_paid($ID)
    {
        $this->db->where('Cycle_ID', $ID);
        $this->db->order_by('Date_paid', 'asc');
        $query = $this->db->get('tbl_collection');
        $result = $query->result();
        return $result;
    }

    public function bill_fees($ID)
    {
        $this->db->where('Assessment_ID', $ID);
        $this->db->order_by('Qtr', 'asc');
        $query = $this->db->get('tbl_billing_fees');
        $result = $query->result();
        return $result;
    }

    public function get_app_type($ID)
    {
        $this->db->select('a.Application_status_ID,' . 'a.Payment_mode_ID');
        $this->db->where('c.ID', $ID);
        $this->db->join('tbl_application_form a', 'a.ID = c.Application_ID', 'left');
        $this->db->from('tbl_cycle c');
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function bus_type($ID)
    {
        $this->db->select('Business_line');
        $this->db->where('Cycle_ID', $ID);
        $this->db->from('tbl_application_business_line');
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
}
