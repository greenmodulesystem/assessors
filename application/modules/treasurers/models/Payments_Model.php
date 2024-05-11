<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
    }

    function generate_AR($new_AR){
        $table = "tbl_treasurers_receipts";
        $start_num = 100000;
        $query = $this->db->get($table);
        $count = $query->num_rows();
        if($count == 0){
            return $start_num;
        }else if($new_AR){
            return $start_num + $count;
        }else{
            return $start_num + $count - 1;
        }
    }
    
    public function payment_history($ID){
        $this->db->select('a.*');
        $this->db->from('tbl_assessment a');
        $this->db->join('tbl_cycle c', 'c.ID = a.Cycle_ID', 'left');
        $this->db->where('c.Application_ID', $ID);
        $this->db->order_by('a.ID', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
    

    public function receipt($AR_Num){
        if($AR_Num == 1){
            $this->db->select_max('AR_Number', 'AR_Number');
            $result = $this->db->get('tbl_treasurers_receipts')->row();
            $AR_Number = $result->AR_Number;
        } else {
            $AR_Number = $AR_Num;
        }
        $this->db->select('*');
        $this->db->from('tbl_treasurers_receipts r');
        $this->db->join('tbl_treasurers_payments p', 'p.AR_Number = r.AR_Number', 'left');
        $this->db->where('r.AR_Number', $AR_Number);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function save_payments($fields){
        $table = "tbl_treasurers_payments";
        $new_AR = false;
        $AR_Number = $this->generate_AR($new_AR);
        $Application_ID = $fields['Application_ID'];
        $Payee = $fields['Payee'];
        $Pay_fors = $fields['Pay_for'];
        $Quantity = $fields['Quantity'];
        $Amount_to_pay = $fields['Amount_to_pay'];
        $data = [];
        foreach($Pay_fors as $key => $Pay_for) {
            array_push($data, array(
                    "Application_ID" => $Application_ID,
                    "Payee" => $Payee,
                    "Pay_for" => $Pay_for,
                    "Quantity" => $Quantity[$key],
                    "Amount_to_pay" => $Amount_to_pay[$key],
                    "AR_Number" => $AR_Number
                )
            );
        }
        $this->db->insert_batch($table, $data);
    }

    public function save_receipt($data){
        $table = "tbl_treasurers_receipts";
        $new_AR = true;
        $AR_Number = $this->generate_AR($new_AR);
        $items = array(
            "AR_Number" => $AR_Number,
            "Application_ID" => $data['Application_ID'],
            "Payee" => $data['Payee'],
            "Total_amount" => $data['Total_amount'],
            "Paid_amount" => $data['Paid_amount'],
            "Change_amount" => $data['Change_amount'],
            "Received_by" => $data['Received_by'],
            "Date_paid" => $data['Date_paid']
        );
        $this->db->insert($table, $items);
    }
}