<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payables_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
    }
// --------------------------------- PAYABLE FUNCTIONS --------------------------------- //
    public function add($item){
        $table = "tbl_treasurers_payables";
        $this->db->insert($table, $item);
    }

    public function add_ons($ID){
        $this->db->select(
            'p.ID,'.
            'p.Payee_ID,'.
            'p.Pay_for,'.
            'p.Quantity,'.
            'c.Fee'
        );
        $this->db->from('tbl_treasurers_payables p');
        $this->db->join('tbl_cards c', 'c.Card_type = p.Pay_for', 'left');
        $this->db->where('p.Payee_ID', $ID);
        $this->db->order_by('p.ID', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function delete_items(){
        $table = "tbl_treasurers_payables";
        $this->db->empty_table($table);
    }

    public function items(){
        $this->db->select(
            'p.ID,'.
            'p.Pay_for,'.
            'p.Quantity,'.
            'c.Fee'
        );
        $this->db->from('tbl_treasurers_payables p');
        $this->db->join('tbl_cards c', 'c.Card_type = p.Pay_for', 'left');
        $this->db->order_by('p.ID', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function remove($item){
        $table = "tbl_treasurers_payables";
        $this->db->delete($table, array('ID' => $item));
    }
// --------------------------------- PAYABLE FUNCTIONS --------------------------------- //

// --------------------------- GET PAYABLES FROM DEPARTMENTS --------------------------- //
    public function engineers_payables($ID){
        $this->db->select(
            'pt.Pay_type,'.
            'el.Rate'
        );
        $this->db->from('tbl_city_engineer e');
        $this->db->join('tbl_cycle c', 'c.ID = e.Cycle_ID', 'left');
        $this->db->join('tbl_city_engineer_line el', 'el.City_engineer_ID = e.ID', 'left');
        $this->db->join('tbl_pay_type pt', 'pt.ID = el.Pay_type_ID', 'left');
        $this->db->where('c.Application_ID', $ID);
        $this->db->where('e.Remark', 'BILLED');
        $this->db->or_where('e.Remark', 'DISCREPANCY');
        $this->db->order_by('el.ID', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function healths_payables($ID){
        $this->db->select(
            'sl.Card_qty,'.
            't.Sanitary_fee,'.
            'c.Fee'
        );
        $this->db->from('tbl_sanitary s');
        $this->db->join('tbl_sanitary_line sl', 'sl.Sanitary_ID = s.ID', 'left');
        $this->db->join('tbl_sanitary_type t', 't.ID = sl.Type_ID', 'left');
        $this->db->join('tbl_cards c', 'c.Card_type = t.Card_type', 'left');
        $this->db->where('s.Application_ID', $ID);
        $this->db->where('s.Remarks', 'BILLED');
        $query = $this->db->get();
        return $query->result();
    }
// --------------------------- GET PAYABLES FROM DEPARTMENTS --------------------------- //

// ------------------------------ UPDATE OTHER DEPARTMENT ------------------------------ //
    public function update_engineers($ID,$Date){
        $cycle = $this->getcycleID($ID);
        $this->db->set('Remark', "WAITING FOR APPROVAL");
        $this->db->where('Cycle_ID', $cycle->ID);
        $this->db->update('tbl_city_engineer');

        $engineer = $this->get_engineer_ID($cycle->ID);
        $this->db->set('Payment_date', $Date);
        $this->db->where('City_engineer_ID', $engineer->ID);
        $this->db->update('tbl_city_engineer_line');
        
    }

    private function get_engineer_ID($ID){
        $this->db->select('ID');
        $this->db->from('tbl_city_engineer');
        $this->db->where('Cycle_ID', $ID);
        $query = $this->db->get();
        return $query->first_row();
    }

    private function getcycleDate($id){
        $this->db->select('tbl_cycle.Cycle_date');
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get_where('tbl_cycle',array('Application_ID'=>$id))->first_row();        
        return $query;
    }

    private function getcycleID($id){
        $cycle = $this->getcycleDate($id);   
        $this->db->select('ID');
        $this->db->from('tbl_cycle');
        $this->db->where('Cycle_date', $cycle->Cycle_date);
        $this->db->where('Application_ID', $id);
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get()->first_row();    
        return $query;
    }

    public function update_healths($ID,$Date){
        $this->db->set('Remarks', "COMPLETED");
        $this->db->where('Application_ID', $ID);
        $this->db->update('tbl_sanitary');
        
        $sanitary = $this->get_sanitary_ID($ID);
        $data = array(
            'Status' => "APPROVED",
            'Date_paid' => $Date,
        );
        $this->db->where('Sanitary_ID', $sanitary->ID);
        $this->db->update('tbl_sanitary_line', $data);

        $this->db->set('Date_approved', $Date);
        $this->db->where('Application_ID', $ID);
        $this->db->where('Department', 'CH');
        $this->db->update('tbl_status');
    }

    private function get_sanitary_ID($ID){
        $this->db->select('ID');
        $this->db->from('tbl_sanitary');
        $this->db->where('Application_ID', $ID);
        $query = $this->db->get();
        return $query->first_row();
    }
// ------------------------------ UPDATE OTHER DEPARTMENT ------------------------------ //
}