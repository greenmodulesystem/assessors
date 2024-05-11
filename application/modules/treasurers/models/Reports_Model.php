<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
    }

    function boxes_count($get){
        $this->db->select(
            'o.Opened,'.
            'o.Opened_by,'.
            'a.ID,'.
            'a.Last_name,'.
            'a.First_name,'.
            'a.Middle_name,'.
            'a.Tax_payer,'.
            'a.Trade_name_franchise AS Tradename,'.
            'a.Business_name,'.
            'MAX(c.Date_application) AS Date_application'
        );
        $this->db->from('tbl_application_form a');
        $this->db->join('tbl_cycle c', 'c.Application_ID = a.ID', 'left');
        $this->db->join('tbl_open_applicant o', 'o.Application_ID = a.ID', 'left');
        $this->db->join('tbl_ready_to_assess r', 'r.Cycle_ID = c.ID', 'left');//01-04-2020
        $this->db->order_by('a.Business_name', 'asc');
        $this->db->group_by('a.ID');
		$this->db->where('c.Cycle_date', date('Y'));//01-04-2020
		$this->db->where('r.Status', 'Done');//01-04-2020
        $query = $this->db->get()->result();

        $filtered = [];
        $billing = $approval = $approved = $cancelled = 0;
        $cycle = $this->getcycleID(1);
        foreach($query as $key => $q){
            $cycle = $this->getcycleID($q->ID);
            $status = false;
            $i = 0;
            while(!$status){
                if(isset($cycle[$i])) {
                    $this->db->where('Cycle_ID', $cycle[$i]->ID);
                    $this->db->order_by('ID', 'desc');
                    $result = $this->db->get('tbl_assessment');
                    if($result->num_rows() > 0) {
                        $r = $result->first_row();
                        if (date('Y', strtotime($r->Expiry)) == date('Y') && 
                            date('Y-m-d', strtotime($r->Expiry)) < date('Y-m-d')) {
                            $this->db->where('Assessment_ID', $r->ID);
                            $bill_q = $this->db->get('tbl_billing_fees');
                            $bill = $bill_q->result();
                            $wait = false;
                            foreach($bill as $key => $b) {
                                if(date('Y-m-d', strtotime($r->Expiry)) >= date('Y-m-d', strtotime($b->Due_date)) 
                                    && $b->Balance != 0) {
                                    $wait = true;
                                    break;
                                }
                            }
                            if($wait) {
                                $q->Status = 'waiting_for_billing';
                                $billing++;
                            } else {
                                if($r->Status == 'Approved') {
                                    $q->Status = 'approved';
                                    $approved++;
                                } else if($r->Status == 'Cancelled') {
                                    $q->Status = 'cancelled';
                                    $cancelled++;
                                } else if($r->Status == '') {
                                    $q->Status = 'waiting_for_approval';
                                    $approval++;
                                }
                            }
                        } else {
                            if($r->Status == 'Approved') {
                                $q->Status = 'approved';
                                $approved++;
                            } else if($r->Status == 'Cancelled') {
                                $q->Status = 'cancelled';
                                $cancelled++;
                            } else if($r->Status == '') {
                                $q->Status = 'waiting_for_approval';
                                $approval++;
                            }
                        }
                        array_push($filtered,$q);
                        $status = true;
                    } else {
                        $this->db->where('Cycle_ID', $cycle[$i]->ID);
                        $ready = $this->db->get('tbl_ready_to_assess');
                        if($ready->num_rows() > 0) {
                            $q->Status = 'waiting_for_billing';
                            $billing++;
                            array_push($filtered,$q);
                            $status = true;
                        } else {
                            $i++;
                        }
                    }
                } else {
                    $status = true;
                }
            }
        }

        $data = new ArrayObject( 
            array(
                'Billing' => $billing,
                'Approval' => $approval,
                'Approved' => $approved,
                'Cancelled' => $cancelled,
            )
        );
        
        if($get == 'count') {
            return $data;
        } else {
            $array = [];
            foreach($filtered as $f) {
                if(isset($f->Status) && $f->Status == $get) {
                    array_push($array,$f);
                }
            }
            return $array;
        }
    }

    private function getcycleID($ID){
        $this->db->where('Application_ID', $ID);
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get('tbl_cycle');    
        return $query->result();
    }

    public function retirement_listings(){

        $this->db->select(
            'a.ID,'.
            'r.ID AS rID,'.
            'a.Last_name,'.
            'a.First_name,'.
            'a.Middle_name,'.
            'a.Tax_payer,'.
            'a.Trade_name_franchise AS Tradename,'.
            'a.Business_name,'.
            'r.DateAdded,'
            // 'MAX(c.Date_application) AS Date_application'
        );
        $this->db->from('tbl_retirement r');
        $this->db->join('tbl_cycle c', 'r.Cycle_ID = c.ID', 'left');
        $this->db->join('tbl_application_form a', 'r.ApplicationID = a.ID', 'left');
        // $this->db->join('tbl_ready_to_assess r', 'r.Cycle_ID = c.ID', 'left');//01-04-2020
        // $this->db->order_by('a.Business_name', 'asc');
        // $this->db->group_by('a.ID');
		$this->db->where('c.Cycle_date', date('Y'));//01-04-2020
		// $this->db->where('r.Status', 'Done');//01-04-2020
        $query = $this->db->get()->result();  
        return $query;
    }
}